import express from 'express';
import axios from 'axios';
import cors from 'cors';
import bodyParser from 'body-parser';
import Influx from 'influx';
import passport from 'passport';
import passportLocal from 'passport-local';
import passportJwt from 'passport-jwt';
import jwt from 'jsonwebtoken';

import * as sites from './model/site.js';
import { influx } from './db.js';
import * as users from './model/user.js';

export const app = express();

app.use(passport.initialize());
app.use(cors());
app.use(bodyParser.urlencoded({ extended: true }));
app.use(bodyParser.json());
app.set('json spaces', 2);

const ping = ({ url, options }) => {
  const start = new Date();
  return axios({ url, method: 'GET', timeout: 10000, ...options })
    .then((res) => {
      const end = new Date() - start;
      return end;
    }).catch((err) => {
      return -1;
    });
}

const timed = () => {
  sites.get().forEach((site) => {
    ping(site).then((ping) => {
      const entry = {
        measurement: "ping",
        tags: {
          id: site.id,
          url: site.url
        },
        fields: {
          ping,
          online: (ping !== -1),
        }
      };
      influx.writePoints([entry]).catch((err) => { console.error('db not connected'); });
    });
  });
}

setInterval(timed, 60000);

passport.use('register', new passportLocal.Strategy({
  usernameField: 'username',
  passwordField: 'password',
}, (username, password, done) => {
  try {
    users.find(username).then((user) => {
      if (user != null) {
        return done(null, false, { message: 'Username already taken' });
      } else {
        const user = users.add({ username, password });
        return done(null, user);
      }
    });
  } catch (err) {
    return done(err);
  }
}));

passport.use('login', new passportLocal.Strategy({
  usernameField: 'username',
  passwordField: 'password',
  session: false,
}, (username, password, done) => {
  try {
    users.find(username).then((user) => {
      if (user === null) {
        return done(null, false, { message: 'bad username' });
      } else {
        if (!user.validatePassword(password)) {
          return done(null, false, { message: 'bad password' });
        }
        return done(null, user);
      }
    })
  } catch (err) {
    return done(err);
  }
}));

const secret = 'thisIsTheMonitorrApplicationSecret';

passport.use('jwt', new passportJwt.Strategy(
  {
    jwtFromRequest: passportJwt.ExtractJwt.fromAuthHeaderWithScheme('Bearer'),
    secretOrKey: secret,
  },
  (payload, done) => {
    try {
      users.findById(payload.id).then((user) => {
        if (user === null) {
          return done(null, false, { message: 'bad user' });
        }
        return done(null, user);
      });
    } catch (err) {
      return done(err);
    }
  }
));

const ensureOwner = (req, res, next) => {
  const site = sites.get(req.body.id);
  if (req.user.id !== site.owner) return res.sendStatus(401);
  return next();
}

app.get('/login', (req, res, next) => {
  passport.authenticate('login', (err, user, info) => {
    if (err) console.error(err);
    if (info) res.json(info);
    req.logIn(user, (err) => {
      users.find(user.username).then((user => {
        if (user) {
          const token = jwt.sign({ id: user.id }, secret);
          res.status(200).json({
            auth: true,
            token: token,
          });
        }
      }))
    })
  })(req, res, next);
});

app.post('/register', (req, res, next) => {
  passport.authenticate('register', (err, user, info) => {
    if (err) {
      console.log(err);
    }
    if (info != undefined) {
      res.json(info);
    } else {
      req.logIn(user, err => {
        const data = {
          username: user.username,
          password: req.body.password,
        };
        users.find(data.username).then(user => {
          if (user) res.status(200).json({ message: 'user created' });
        });
      });
    }
  })(req, res, next);
});

app.get('/', (req, res) => {
  const r = [];
  const promises = [];
  sites.get().forEach((site) => {
    promises.push(ping(site).then((time) => {
      const online = (time !== -1);
      r.push({ ...site, time, online });
    }));
  });
  Promise.all(promises).then(() => {
    res.json(r);
  });
});

app.get('/config', (req, res) => {
  res.json(sites.get());
});

app.get('/config/:id', (req, res) => {
  res.json(sites.get(req.params.id));
});

app.post('/config', passport.authenticate('jwt', { session: false }), (req, res, next) => {
  const { name, url, link, icon } = req.body;
  const owner = req.user.id;
  if (!name) return next(new Error('Name not provided'));
  if (!url) return next(new Error('URL not provided'));
  if (!link) return next(new Error('Link not provided'));
  if (!icon) return next(new Error('Icon not provided'));
  const a = sites.add({ name, url, link, icon, owner });
  res.json(a);
});

app.put('/config', passport.authenticate('jwt', { session: false }), ensureOwner, (req, res, next) => {
  let { name, url, link, icon, id } = req.body;
  if (!id) return next(new Error('ID not provided'));

  const original = sites.get(id);
  if (!original) return next(new Error('ID not found'));
  if (!name) name = original.name;
  if (!url) url = original.url;
  if (!link) link = original.link;
  if (!icon) icon = original.icon;

  const a = sites.update({ name, url, link, icon, id });
  res.json(a);
});

app.delete('/config/:id', passport.authenticate('jwt', { session: false }), ensureOwner, (req, res) => {
  const id = req.params.id;
  if (!id) return next(new Error('ID not provided'));
  sites.del({ id });
  res.json({});
});

app.get('/:id', (req, res, next) => {
  influx.query(`
  select * from ping
  where id = ${Influx.escape.stringLit(req.params.id)}
  and time >= now() - 30d
  order by time desc
`).then(result => {
    result.forEach((item, idx) => { delete result[idx].id; delete result[idx].url; })
    res.json({
      ...sites.get(req.params.id),
      last_ping: result[0].ping,
      avg_ping: result.reduce((a, b) => a + b, 0) / result.length,
      history: result,
    });
  }).catch(err => {
    return next(err);
  });
});

app.use((err, req, res, next) => {
  console.error(err);
  return res.status(500).send({ name: err.name, message: err.message });
});

app.use((req, res, next) => {
  res.status(404).json({ name: 'NotFound', message: 'Page Not Found' });
});

app.listen(process.env.PORT || 7464);
