![](https://raw.githubusercontent.com/Monitorr/Monitorr/master/assets/img/monitorrbanner.png)

## - *Monitorr* is a webfront to live display the status of any webapp or service

**Version:** v0.12.2d [DEVELOP]

[![](https://img.shields.io/github/release/monitorr/monitorr.svg?style=flat)](https://github.com/monitorr/monitorr/releases) [MASTER]

[![Docker build](https://img.shields.io/docker/build/monitorr/monitorr.svg?maxAge=2592000)](https://hub.docker.com/r/monitorr/monitorr/)

**Latest major change:** added refresh disable toggle switch

## Features:

- LIVE!
- Self-hosted
- Monitor any app on any domain
- Host system resources display (CPU, MEM, PING, Uptime)
- Server DTG data
- Update Monitorr via web UI / branch switching
- Minimal UI for iFrame displays (See [WIKI](https://github.com/Monitorr/Monitorr/wiki/Integration:--Organizr))
- User customizable system threshold colors

**Features in development:**
- UL/DL times via Speedtest
- Settings page


## Screenshot :

![]( https://i.imgur.com/ILm2UZL.png)

In use with [Organizr](https://github.com/causefx/Organizr)

![]( https://i.imgur.com/t5tzuR4.png)


## Prerequisites:
1) [PHP](https://secure.php.net/downloads.php)

2) [PHP cURL](https://secure.php.net/manual/en/book.curl.php)


## Configuration:
1) Clone/download repository to your webserver (Suggested Sub DIR)

2) Make sure the user account that runs your webserver has RW access to the monitorr folder (eg. for linux it's usually www-data:www-data) - this is for updates to work properly.

3) Browse to <localhost\domain>/monitorr/index.php (config.php will be auto populated in /assets/config.php)

4) Edit `assets/config.php`:
 - timezone
 - Site Title
 - Site URL
 - Ping address
 - Sys info color thresholds
 - Services you want to monitor
 - Branch you want to be on for updates (master/develop)

5) Enjoy! Have a Donut. Drink a Coffee.


## Feature Requests:
 [![Feature Requests](https://cloud.githubusercontent.com/assets/390379/10127973/045b3a96-6560-11e5-9b20-31a2032956b2.png)](https://feathub.com/Monitorr/Monitorr)

**Current feature requests:**

[![Feature Requests](https://feathub.com/Monitorr/Monitorr?format=svg)](https://feathub.com/Monitorr/Monitorr)


## Connect:
- Need live help?  Join here :   [![Discord](https://img.shields.io/discord/102860784329052160.svg)](https://discord.gg/YKbRXtt)

- E-mail: monitorrapp@gmail.com

- Buy us a beer! Donate:        [![Donate](https://img.shields.io/badge/Donate-PayPal-green.svg)](https://paypal.me/monitorrapp)

## About Us:
- [seanvree](https://github.com/seanvree) (Windows Wizard)
- [jonfinley](https://github.com/jonfinley) (Linux Dude)
- [wjbeckett](https://github.com/wjbeckett)
