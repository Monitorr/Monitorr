import fs from 'fs';
import nodemailer from 'nodemailer';

let mailSettings = JSON.parse(fs.readFileSync('./config/mail.json'));

export const enabled = mailSettings.enabled;

export const sendMail = ({ subject, text }) => {
    if (enabled) {
        const transporter = nodemailer.createTransport(mailSettings);
        const sendOpts = { from: mailSettings.from, to: mailSettings.to, subject, text };
        transporter.sendMail(sendOpts, (err, info) => {
            if (err) console.error(err);
            else console.log('Mail Sent %s', info.response);
        });
    } else {
        throw new Error('Mail is not enabled');
    }
};
