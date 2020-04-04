import express from 'express';
import cors from 'cors';
import bodyParser from 'body-parser';
import passport from 'passport';
import passportLocal from 'passport-local';
import passportJwt from 'passport-jwt';
import jwt from 'jsonwebtoken';

import * as users from './model/user.model.js';
import routes from './routes/index.js';

export const app = express();

app.use(passport.initialize());
app.use(cors());
app.use(bodyParser.urlencoded({ extended: true }));
app.use(bodyParser.json());
app.set('json spaces', 2);

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

app.get('/login', (req, res, next) => {
  passport.authenticate('login', (err, user, info) => {
    if (err) console.error(err);
    if (info) res.json(info);
    req.logIn(user, (err) => {
      users.find(user.username).then((user => {
        if (user) {
          const token = jwt.sign({ id: user.id, username: user.username }, secret);
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

Object.values(routes).forEach((route) => {
  app.use(`/${route.name}`, route.router);
});

app.use((err, req, res, next) => {
  console.error(err);
  return res.status(500).send({ name: err.name, message: err.message });
});

app.use((req, res, next) => {
  res.status(404).json({ name: 'NotFound', message: 'Page Not Found' });
});

app.listen(process.env.PORT || 7464);
