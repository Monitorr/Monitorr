// Monitorr //

- When services report as OFFLINE, a *.json file with the service name is created in this directory (/assets/data/logs/). 
- This triggers the alert banner in the Monitorr UI. 
- When the service returns to an ONLINE or UNRESPONSIVE status, the .json file is renamed to “offline.json.old”. This will then trigger the UI to remove the alert banner.
- If the alert banner is erroneously persistent in the UI, and the service is ONLINE, manually remove the .json file from this directory.
