import express from 'express';
import axios from 'axios';
import cors from 'cors';
import bodyParser from 'body-parser';

import { get as settings, add as addSettings, update as updateSettings, del as deleteSettings } from './settings.js';

export const app = express();

app.use(cors());
app.use(bodyParser.urlencoded({ extended: true }));
app.use(bodyParser.json());
app.set('json spaces', 2);

const ping = ({ url, options }) => {
  const start = new Date();
  return axios({ url, method: 'GET', timeout: 2000, ...options })
    .then((res) => {
      const end = new Date() - start;
      return end;
    }).catch((err) => {
      return -1;
    });
}

app.get('/', (req, res) => {
  const r = [];
  const promises = [];
  settings().forEach((site) => {
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
  res.json(settings());
});

app.get('/config/:id', (req, res) => {
  res.json(settings(req.params.id));
});

app.post('/config', (req, res, next) => {
  const { name, url, link, icon } = req.body;
  if (!name) return next(new Error('Name not provided'));
  if (!url) return next(new Error('URL not provided'));
  if (!link) return next(new Error('Link not provided'));
  if (!icon) return next(new Error('Icon not provided'));
  const a = addSettings({ name, url, link, icon });
  res.json(a);
});

app.put('/config', (req, res) => {
  let { name, url, link, icon, id } = req.body;
  if (!id) return next(new Error('ID not provided'));

  const original = settings(id);
  if (!name) name = original.name;
  if (!url) url = original.url;
  if (!link) link = original.link;
  if (!icon) icon = original.icon;

  const a = updateSettings({ name, url, link, icon, id });
  res.json(a);
});

app.delete('/config/:id', (req, res) => {
  const id = req.params.id;
  if (!id) return next(new Error('ID not provided'));
  deleteSettings({ id });
  res.json({});
});

app.use((err, req, res, next) => {
  console.error(err);
  return res.status(500).send({ name: err.name, message: err.message });
});

app.use((req, res, next) => {
  res.status(404).json({ name: 'NotFound', message: 'Page Not Found' });
});

app.listen(3000);
