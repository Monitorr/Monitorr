import axios from 'axios';

import * as sites from '../model/site.model.js';
import { influx } from './db.service.js';


export const ping = ({ url, options }) => {
  const start = new Date();
  return axios({ url, method: 'GET', timeout: 10000, ...options })
    .then((res) => {
      const end = new Date() - start;
      return end;
    }).catch((err) => {
      return -1;
    });
};

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
};

setInterval(timed, 60000);

