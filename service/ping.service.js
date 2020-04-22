import axios from 'axios';

import * as sites from '../model/site.model.js';
import { influx } from './db.service.js';
import { sendMail, enabled as mailEnabled } from './mail.service.js';

axios.interceptors.request.use((cfg) => {
  cfg.metadata = { startTime: new Date() };
  return cfg;
}, (err) => {
  return Promise.reject(err);
});

axios.interceptors.response.use((res) => {
  res.config.metadata.endTime = new Date();
  res.duration = res.config.metadata.endTime - res.config.metadata.startTime;
  return res;
}, (err) => {
  err.config.metadata.endTime = new Date();
  err.duration = err.config.metadata.endTime - err.config.metadata.startTime;
  return Promise.reject(err);
});

export const ping = ({ url, options }) => {
  return axios({ url, method: 'GET', timeout: 10000, ...options })
    .then((res) => {
      return res.duration;
    }).catch((err) => {
      console.log(err);
      return -1;
    });
};

const timed = () => {
  sites.get().forEach((site, idx) => {
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
      if (site.online != entry.online) {
        if (mailEnabled) {
          if (entry.online) {
            // site has gone offline, send email
            const opt = {
              subject: `${site.name} has gone offline.`,
              text: 'Site has gone offline, please check it\'s status'
            }
            sendMail(opt);
          } else {
            const opt = {
              subject: `${site.name} has come back online.`,
              text: 'Site has come back online. Have a nice day'
            }
            sendMail(opt);
            // site has come back online, send email
          }
          // Site has gone offline, time to send some notifications
        }
      }
      sites[idx].online = (ping !== -1);
    });
  });
};

setInterval(timed, 60000);
