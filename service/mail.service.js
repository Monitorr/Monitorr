import fs from 'fs';
import nodemailer from 'nodemailer';

let mailSettings = JSON.parse(fs.readFileSync('./config/mail.json'));
const transporter = nodemailer.createTransport(mailSettings);

export const enabled = mailSettings.enabled;

export const sendMail = ({ subject, text }) => {
    const sendOpts = { from: mailSettings.from, to: mailSettings.to, subject, text };
    transporter.sendMail(sendOpts, (err, info) => {
        if (err) console.error(err);
        else console.log('Mail Sent %s', info.response);
    });
}