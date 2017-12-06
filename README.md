# <p align="center"><b> MONITORR </b></p>

<p align="center"><b>Webfront to live display the status of any webapp or service  </b></p>
<br>

<b> Version:</b> v0.10.1m [BETA]

<b> Latest major change: </b>  Added update function (including branch switching)!

## Features:

- LIVE!
- Self-hosted
- Monitor any app on any domain (NEW)
- Host system resources display (CPU, MEM, PING, Uptime)(NEW)
- Server DTG data
- Update Monitorr via web UI.
- User customizable system threshold colors

<b> Features in development: </b>
- Settings page
- Drag 'n drop tiles


## Screenshot :

<img src="https://i.imgur.com/6fn9mMc.png[/img]">

<br>

In use with [Organizr](https://github.com/causefx/Organizr)

<img src="https://i.imgur.com/VdcgPHs.png[/img]">


## Prerequisites:
1) [PHP](https://secure.php.net/downloads.php)

2) [PHP cURL](https://secure.php.net/manual/en/book.curl.php)

<br>

## Installation & Configuration:
1) Clone/download repository to your webserver (Suggested Sub DIR)

2) Make sure the user account that runs your webserver has RW access to the monitorr folder (eg. for linux it's usually www-data:www-data) - this is for updates to work properly.

3) Edit `assets/config.php`:
 - timezone
 - Site Title
 - Site URL
 - Ping address
 - Sys info color thresholds
 - Services you want to monitor
 - Branch you want to be on for updates (master/develop)

4) Enjoy! Have a Donut. Drink a Coffee.
<br>

### Docker
#### Usage
```
docker create \
  --name=monitorr \
  --restart=on-failure \
  -v <host path for config:/config \
  -v <host path for logs>:/var/log \
  -e TZ=<timezone> \
  -p 80:80 \
  tronyx/docker-monitorr
```

#### Parameters
* `--name` - The name of the container - Call it whatever you want.
* `--restart=on-failure` Container restart mode - Docker attempts to restarts the container if the container returns a non-zero exit code. More info [HERE](https://docs.docker.com/engine/admin/start-containers-automatically/ "HERE") on container restart policies.
* `-v /home/monitorr/config:/config` - Your preferred app data config path, IE: where you're storing the Monitorr config files.
* `-v /home/monitorr/config/log:/var/log` Your preferred app log path, IE: where you're storing the Monitorr, Nginx, and PHP logs.
* `-e TZ` - Your timezone, IE: `America/New_York`.

### Info
* To monitor the logs of the container in realtime `docker logs -f monitorr`

## Feature Requests:
 [![Feature Requests](https://cloud.githubusercontent.com/assets/390379/10127973/045b3a96-6560-11e5-9b20-31a2032956b2.png)](https://feathub.com/Monitorr/Monitorr)

<b> Current feature requests: </b>

[![Feature Requests](https://feathub.com/Monitorr/Monitorr?format=svg)](https://feathub.com/Monitorr/Monitorr)
<br>

## Connect:
Need live help?  Join here :   [![Discord](https://img.shields.io/discord/102860784329052160.svg)](https://discord.gg/YKbRXtt)
<br>
E-mail: monitorrapp@gmail.com
<br>
Buy us a beer! Donate:        [![Donate](https://img.shields.io/badge/Donate-PayPal-green.svg)](https://paypal.me/monitorrapp)

## About Us:
- [seanvree](https://github.com/seanvree) (Windows Wizard)
- [jonfinley](https://github.com/jonfinley) (Linux Dude)
- [wjbeckett](https://github.com/wjbeckett)
