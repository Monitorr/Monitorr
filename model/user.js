import crypto from 'crypto';
import uuid from 'uuid';
import fs from 'fs';

const users = JSON.parse(fs.readFileSync('./users.json')) || [];

const write = () => {
  fs.writeFileSync('./users.json', JSON.stringify(users, null, 2));
};

const setPassword = (user) => {
  return (password) => {
    user.salt = crypto.randomBytes(16).toString('hex');
    user.hash = crypto.pbkdf2Sync(password, user.salt, 10000, 512, 'sha512').toString('hex');
    write();
  };
};

const validatePassword = (user) => {
  return (password) => {
    const hash = crypto.pbkdf2Sync(password, user.salt, 10000, 512, 'sha512').toString('hex');
    return user.hash === hash;
  };
};

export const add = ({ username, password }) => {
  const user = {
    id: uuid.v4(),
    username,
    hash: null,
    salt: null
  };
  user.setPassword = setPassword(user);
  user.validatePassword = validatePassword(user);
  user.setPassword(password);
  users.push(user);
  write();
  return user;
}

export const find = (username) => {
  return new Promise((resolve, reject) => {
    const user = users.find(u => u.username === username);
    if (!user) resolve(null);
    user.setPassword = setPassword(user);
    user.validatePassword = validatePassword(user);
    resolve(user);
  });
};

export const findById = (id) => {
  return new Promise((resolve, reject) => {
    const user = users.find(u => u.id === id);
    if (!user) resolve(null);
    user.setPassword = setPassword(user);
    user.validatePassword = validatePassword(user);
    resolve(user);
  });
};

if (users.length === 0) add({ username: 'admin', password: 'admin' });
