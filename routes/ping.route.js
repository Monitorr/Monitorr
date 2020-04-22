import express from 'express';
import passport from 'passport';
import Influx from 'influx';

import { ping } from '../service/ping.service.js';
import * as sites from '../model/site.model.js';
import { influx } from '../service/db.service.js';

export const name = 'ping';
export const router = express.Router();

const ensureOwner = (req, res, next) => {
  const site = sites.get(req.body.id);
  if (req.user.id !== site.owner) return res.sendStatus(401);
  return next();
}

router.get('/', (req, res) => {
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

router.post('/', passport.authenticate('jwt', { session: false }), (req, res, next) => {
  const { name, url, link, icon } = req.body;
  const owner = req.user.id;
  if (!name) return next(new Error('Name not provided'));
  if (!url) return next(new Error('URL not provided'));
  if (!link) return next(new Error('Link not provided'));
  if (!icon) return next(new Error('Icon not provided'));
  const a = sites.add({ name, url, link, icon, owner });
  res.json(a);
});

router.get('/:id', (req, res) => {
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

router.post('/:id', (req, res) => {
  // Initiate check now
});

router.put('/:id', passport.authenticate('jwt', { session: false }), ensureOwner, (req, res, next) => {
  let { name, url, link, icon } = req.body;
  let id = req.params.id;
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

router.delete('/:id', passport.authenticate('jwt', { session: false }), ensureOwner, (req, res) => {
  const id = req.params.id;
  if (!id) return next(new Error('ID not provided'));
  sites.del({ id });
  res.json({});
});

export default { name, router }