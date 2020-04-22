import fs from 'fs';
import uuid from 'uuid';

let set = JSON.parse(fs.readFileSync('./config/sites.json'));

const write = () => {
    fs.writeFileSync('./config/sites.json', JSON.stringify(set, null, 2));
};

export const add = ({ name, url, link, icon, owner }) => {
    const n = { name, enabled: true, url, link, icon, id: uuid.v4(), owner };
    set = [...set, n];
    write();
    return n;
};

export const update = ({ name, url, link, icon, id, enabled }) => {
    const idx = set.findIndex(n => n.id === id);
    set[idx] = { name, enabled, url, link, icon, id, owner: set[idx].owner };
    write();
    return set[idx];
};

export const del = ({ id }) => {
    set = set.filter(n => n.id !== id);
    write();
};

export const get = (id = null) => {
    if (id) return set.find(n => n.id === id);
    return set;
};

export default { get, add, update, del }
