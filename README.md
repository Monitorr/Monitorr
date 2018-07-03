![monitorr](https://raw.githubusercontent.com/Monitorr/Monitorr/master/assets/img/monitorrbanner.png)


## - *Monitorr* is a webfront to live display the status of any webapp or service

[![GitHub Master-release](https://img.shields.io/github/release/monitorr/monitorr.svg?style=flat)](https://github.com/monitorr/monitorr/releases) [MASTER]

[![Docker build](https://img.shields.io/docker/build/monitorr/monitorr.svg?maxAge=2592000)](https://hub.docker.com/r/monitorr/monitorr/) [DOCKER]

[![GitHub (pre-)release](https://img.shields.io/github/release/monitorr/monitorr/all.svg)](https://github.com/monitorr/monitorr/releases) [DEVELOP]


**NOTICE (27 June 18)**: If updating to version **1.7** from ANY previous version, **BEFORE updating backup your custom.css file** located at: _`[Monitorr install path]/assets/data/css/custom.css`_ . After the update is complete, simply restore the custom.css file to the same location overwriting the custom.css file that was updated. This is due to a bug which has been resolved in version 1.7 therefore this process will no longer be needed in future updates. See the [WiKi Update Notice](https://github.com/Monitorr/Monitorr/wiki/NOTICE:-Updating-Monitorr) for more information.


## Features:

**Latest major change:** Multi HD display / Service ping response time display

- LIVE! (w/ option to pause live updating)
- Self-hosted.
- Responsive mobile display.
- Monitor any app/service on any domain (Curl as primary check, fsockopen as fallback).
- Enable/disable hot linking for each service.
- Service ping response time display. (NEW)
- Host system resources display (CPU, MEM, HD, PING, Uptime) (UPDATED)
- Integrated settings page w/ authentication.
- User customizable system threshold colors.
- Custom CSS tool. (NEW)
- Server DTG data. (UPDATED)
- Update tool via web UI / branch switching w/ update notification.
- Minimal UI for iFrame displays. (See: [WIKI](https://github.com/Monitorr/Monitorr/wiki/05-Integration:--Organizr))


**Features in development:**

- Python daemon (See: [Features 160](https://github.com/Monitorr/Monitorr/issues/160))
- Alerting (E-Mail, text, etc) (See: [Features 126](https://github.com/Monitorr/Monitorr/issues/126))


## Screenshots:

![Monitorr Screenshot](https://i.imgur.com/2wGLfLp.png)
<img src="https://i.imgur.com/UxDNcct.jpg?1" width="48%"> <img src="https://i.imgur.com/0smwRRu.jpg?1" width="50%">
<img src="https://i.imgur.com/BpBplql.png?1" width="32%">  <img src="https://i.imgur.com/PWWpDkn.jpg?1" width="32%"> <img src="https://i.imgur.com/Mnu0QJg.png?1" width="32%"> 


### Mobile:

![Monitorr Mobile SS](https://i.imgur.com/JLANkme.jpg?3)


## Prerequisites:

1) [PHP](https://secure.php.net/downloads.php) (7.1+ recommended)
2) [PHP cURL](https://secure.php.net/manual/en/book.curl.php)
3) [PHP ZipArchive](http://www.php.net/manual/en/zip.installation.php)
4) [PHP PDO](http://php.net/manual/en/book.pdo.php)
5) [SQLite](https://www.sqlite.org/index.html)
6) [GIT](https://git-scm.com/download/win) (Recommended for Windows hosts (see wiki)


## Quick Start:

- See full configuration instructions in the WiKi: https://github.com/Monitorr/Monitorr/wiki

1) Clone/download the Monitorr repository to your webserver. (Docker image available here: [![Docker build](https://img.shields.io/docker/build/monitorr/monitorr.svg?maxAge=2592000)](https://hub.docker.com/r/monitorr/monitorr/) )
2) Browse to: _`[localhost\domain]/monitorr/index.php`_
3) Establish data directory, and user database via secure registration tool.
4) Log in and configure Monitorr via secure settings UI.
5) Chill


## Feature Requests:

 [![Feature Requests](https://cloud.githubusercontent.com/assets/390379/10127973/045b3a96-6560-11e5-9b20-31a2032956b2.png)](https://feathub.com/Monitorr/Monitorr)

**Current feature requests:**

[![Feature Requests](https://feathub.com/Monitorr/Monitorr?format=svg)](https://feathub.com/Monitorr/Monitorr)


## Connect:

- Need live help?  Join us on Discord:   [![Discord](https://img.shields.io/discord/102860784329052160.svg)](https://discord.gg/YKbRXtt)

- E-mail: monitorrapp@gmail.com

- Buy us a beer! Donate:        [![Donate](https://img.shields.io/badge/Donate-PayPal-green.svg)](https://paypal.me/monitorrapp)

- Check out our sister app **Logarr**:  https://github.com/Monitorr/Logarr


## About Us:

- [seanvree](https://github.com/seanvree) (Windows Wizard)
- [jonfinley](https://github.com/jonfinley) (Linux Dude)
- [wjbeckett](https://github.com/wjbeckett)


## Credits:

[ - Causefx](https://github.com/Causefx) - [christronyxyocum](https://github.com/christronyxyocum) - [rob1998](https://github.com/rob1998) - [Roxedux](https://github.com/si0972) -


## Libraries used in this project:

- [Alpaca](https://github.com/gitana/alpaca/)
- [PHP Login](https://github.com/panique/php-login-one-file)
- [ACE](https://github.com/ajaxorg/ace)
- [PACE](https://github.com/HubSpot/pace)
- [Form Validation](https://github.com/DrRoach/FormValidation)
