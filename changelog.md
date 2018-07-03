<div id="about">

*   <div class="github-item">

    ## Develop: 1.7.7d

    <time class="github-item-time" datetime="2018-07-03T07:12:52Z"><span class="releasetime">Released on: 2018-07-03 at 07:12:52</span></time>

    <div class="releaseBody">

    ### - Summary:

    a. This update is a MAJOR update to Monitorr with numerous fixes and enhancements.  
    b. Docker image available here: [https://hub.docker.com/r/monitorr/monitorr/](https://hub.docker.com/r/monitorr/monitorr/)  

    ### - Update ALERT:

    If updating to version _1.7_* from ANY previous version, *_BEFORE updating backup your custom.css file_ located at: _(Monitorr install path)/assets/data/css/custom.css_ . After the update is complete, simply restore the custom.css file to the same location overwriting the custom.css file that was updated. This is due to a bug which has been resolved in version 1.7, therefore, this process will no longer be needed in future updates. See the [WiKi Update Notice](https://github.com/Monitorr/Monitorr/wiki/NOTICE:-Updating-Monitorr "WiKi Update Notice") for more information.  

    ### - Configuration changes:

    The following configuration changes need to be made AFTER updating to version 1.7.0 from ANY previous version. See the [WiKi Update Notice](https://github.com/Monitorr/Monitorr/wiki/NOTICE:-Updating-Monitorr "WiKi Update Notice") for more information.  

    a. _User Preferences:_  

    - Registration: Enable or disable access to the Registration tool. _NOTE: _This value should be changed to "Disable"_ AFTER updating._ [screenshot](https://user-images.githubusercontent.com/8906637/41943907-573ca978-795a-11e8-8f01-019c8b9eb9a4.PNG "screenshot")  

    b. _Monitorr Settings:_ [screenshot](https://user-images.githubusercontent.com/8906637/41945529-8df0cbd6-7962-11e8-87cf-2a92ca7e3374.PNG "screenshot")  

    - Time sync interval: Specifies how frequently (in milliseconds) the UI clock will synchronize time with the hosting webserver.  
    - HD display: Monitorr now has the much-anticipated feature of disabling or displaying multiple volumes (up to 3) in the system resources area of the UI.  
    - Ping color values: These values will determine the color of the ping indicators in the system resources are of the UI as well as individual service response times when enabled in "Services Configuration".  

    c. _Services Configuration:_  

    - Ping RT: Enables PING RT time output in the UI for each service. [screenshot](https://user-images.githubusercontent.com/8906637/41945612-dda1df8a-7962-11e8-8a59-82f31f0dc2b8.PNG "screenshot")  

    ### - CHANGE LOG:

    1.  ADD: Ping response time for services. See: [#113](https://github.com/Monitorr/Monitorr/issues/113 "GitHub Issue")
    2.  ADD: User defined HD volume / multiple volumes: see: [#148](https://github.com/Monitorr/Monitorr/issues/148 "GitHub Issue")
    3.  ADD: Monitorr release changelog. See: [#123](https://github.com/Monitorr/Monitorr/issues/123 "GitHub Issue")
    4.  ADD: Disable browser output error if service is down. See: [#174](https://github.com/Monitorr/Monitorr/issues/174 "GitHub Issue")
    5.  ADD: execute single service check on settings.php load and when submit changes on Services Config settings page
    6.  ADD: Offline service img and title will fade when offline.
    7.  ADD: Form validation to user input on _register form.
    8.  ADD: CSS classes added to service images.
    9.  ADD: refresh marquee on settings page load.
    10.  ADD: MAIN PING indicator to respect color values.
    11.  ADD: HD badge color RED if HD stats are null/error

    13.  FIX: Offline time does not coincide with the set timezone. See: [#165](https://github.com/Monitorr/Monitorr/issues/165 "GitHub Issue")
    14.  FIX: Remove offline.json log files on service config settings page submit to prevent rouge offline.json files.
    15.  FIX: Prevent null data posting to json settings files
    16.  FIX: Uptime format on Windows
    17.  FIX: Update via UI will override custom CSS.
    18.  FIX: Form submit post data
    19.  FIX: Version check script errors if any functions error in functions.php
    20.  FIX: Timeout indicators were persistent if ajax fail.
    21.  FIX: Wrong PHP TZ for Greenland
    22.  FIX: Analog clock browser compatibility

    24.  CHANGE: New JS clock
    25.  CHANGE: Move UI icons from /img dir
    26.  CHANGE: correct scrollbars on settings pages
    27.  CHANGE: Change custom css script to local from CDN.
    28.  CHANGE: date display on min site: to NOT show year.
    29.  CHANGE: img upload error message / img upload modal color
    30.  CHANGE: Clock will not sync when auto-update toggle is disabled.
    31.  CHANGE: Form schema / Default json settings file format
    32.  CHANGE: Update font-awesome update from 4.7.0 to 5.1.0

    </div>

    </div>

    * * *

*   <div class="github-item">

    ## Master: 1.7.6m

    <time class="github-item-time" datetime="2018-07-03T07:10:57Z"><span class="releasetime">Released on: 2018-07-03 at 07:10:57</span></time>

    <div class="releaseBody">

    ### - Summary:

    a. This update is a MAJOR update to Monitorr with numerous fixes and enhancements.  
    b. Docker image available here: [https://hub.docker.com/r/monitorr/monitorr/](https://hub.docker.com/r/monitorr/monitorr/)  

    ### - Update ALERT:

    If updating to version _1.7_* from ANY previous version, *_BEFORE updating backup your custom.css file_ located at: _(Monitorr install path)/assets/data/css/custom.css_ . After the update is complete, simply restore the custom.css file to the same location overwriting the custom.css file that was updated. This is due to a bug which has been resolved in version 1.7, therefore, this process will no longer be needed in future updates. See the [WiKi Update Notice](https://github.com/Monitorr/Monitorr/wiki/NOTICE:-Updating-Monitorr "WiKi Update Notice") for more information.  

    ### - Configuration changes:

    The following configuration changes need to be made AFTER updating to version 1.7.0 from ANY previous version. See the [WiKi Update Notice](https://github.com/Monitorr/Monitorr/wiki/NOTICE:-Updating-Monitorr "WiKi Update Notice") for more information.  

    a. _User Preferences:_  

    - Registration: Enable or disable access to the Registration tool. _NOTE: _This value should be changed to "Disable"_ AFTER updating._ [screenshot](https://user-images.githubusercontent.com/8906637/41943907-573ca978-795a-11e8-8f01-019c8b9eb9a4.PNG "screenshot")  

    b. _Monitorr Settings:_ [screenshot](https://user-images.githubusercontent.com/8906637/41945529-8df0cbd6-7962-11e8-87cf-2a92ca7e3374.PNG "screenshot")  

    - Time sync interval: Specifies how frequently (in milliseconds) the UI clock will synchronize time with the hosting webserver.  
    - HD display: Monitorr now has the much-anticipated feature of disabling or displaying multiple volumes (up to 3) in the system resources area of the UI.  
    - Ping color values: These values will determine the color of the ping indicators in the system resources are of the UI as well as individual service response times when enabled in "Services Configuration".  

    c. _Services Configuration:_  

    - Ping RT: Enables PING RT time output in the UI for each service. [screenshot](https://user-images.githubusercontent.com/8906637/41945612-dda1df8a-7962-11e8-8a59-82f31f0dc2b8.PNG "screenshot")  

    ### - CHANGE LOG:

    1.  ADD: Ping response time for services. See: [#113](https://github.com/Monitorr/Monitorr/issues/113 "GitHub Issue")
    2.  ADD: User defined HD volume / multiple volumes: see: [#148](https://github.com/Monitorr/Monitorr/issues/148 "GitHub Issue")
    3.  ADD: Monitorr release changelog. See: [#123](https://github.com/Monitorr/Monitorr/issues/123 "GitHub Issue")
    4.  ADD: Disable browser output error if service is down. See: [#174](https://github.com/Monitorr/Monitorr/issues/174 "GitHub Issue")
    5.  ADD: execute single service check on settings.php load and when submit changes on Services Config settings page
    6.  ADD: Offline service img and title will fade when offline.
    7.  ADD: Form validation to user input on _register form.
    8.  ADD: CSS classes added to service images.
    9.  ADD: refresh marquee on settings page load.
    10.  ADD: MAIN PING indicator to respect color values.
    11.  ADD: HD badge color RED if HD stats are null/error

    13.  FIX: Offline time does not coincide with the set timezone. See: [#165](https://github.com/Monitorr/Monitorr/issues/165 "GitHub Issue")
    14.  FIX: Remove offline.json log files on service config settings page submit to prevent rouge offline.json files.
    15.  FIX: Prevent null data posting to json settings files
    16.  FIX: Uptime format on Windows
    17.  FIX: Update via UI will override custom CSS.
    18.  FIX: Form submit post data
    19.  FIX: Version check script errors if any functions error in functions.php
    20.  FIX: Timeout indicators were persistent if ajax fail.
    21.  FIX: Wrong PHP TZ for Greenland
    22.  FIX: Analog clock browser compatibility

    24.  CHANGE: New JS clock
    25.  CHANGE: Move UI icons from /img dir
    26.  CHANGE: correct scrollbars on settings pages
    27.  CHANGE: Change custom css script to local from CDN.
    28.  CHANGE: date display on min site: to NOT show year.
    29.  CHANGE: img upload error message / img upload modal color
    30.  CHANGE: Clock will not sync when auto-update toggle is disabled.
    31.  CHANGE: Form schema / Default json settings file format
    32.  CHANGE: Update font-awesome update from 4.7.0 to 5.1.0

    </div>

    </div>

    * * *

*   <div class="github-item">

    ## Develop: 1.6.4d

    <time class="github-item-time" datetime="2018-06-07T17:42:37Z"><span class="releasetime">Released on: 2018-06-07 at 17:42:37</span></time>

    <div class="releaseBody">This release is a _HOTFIX_ for a bug was discovered in the Monitorr settings page form.  

    ISSUE: If a user clicks inside a field on any of the settings pages and fires ENTER keystroke, the ajax call will write blank data to the user's .json files in their data directory thus completely breaking Monitorr if the UI is refreshed and the blank data is loaded into the DOM.  

    See [#167](https://github.com/Monitorr/Monitorr/issues/167 "GitHub Issue") for further details.</div>

    </div>

    * * *

*   <div class="github-item">

    ## Master: 1.6.3m

    <time class="github-item-time" datetime="2018-06-07T17:16:40Z"><span class="releasetime">Released on: 2018-06-07 at 17:16:40</span></time>

    <div class="releaseBody">- This release is a HOTFIX for a bug was discovered in the Monitorr settings page form.  

    - ISSUE: If a user clicks inside a field on any of the settings pages and fires ENTER keystroke, the ajax call will write blank data to the user's .json files in their data directory thus completely breaking Monitorr if the UI is refreshed and the blank data is loaded into the DOM.  

    - See [#167](https://github.com/Monitorr/Monitorr/issues/167 "GitHub Issue") for further details.</div>

    </div>

    * * *

*   <div class="github-item">

    ## Master: 1.6.1m

    <time class="github-item-time" datetime="2018-05-14T08:45:33Z"><span class="releasetime">Released on: 2018-05-14 at 08:45:33</span></time>

    <div class="releaseBody">Changelog:  
    - Add: Custom CSS option in "user preferences". Huge shout out to @rob1998 for this! see: [#141](https://github.com/Monitorr/Monitorr/issues/141 "GitHub Issue")  
    See Wiki for further instructions: [https://github.com/Monitorr/Monitorr/wiki/03-Monitorr-Settings#2-user-preferences](https://github.com/Monitorr/Monitorr/wiki/03-Monitorr-Settings#2-user-preferences)  

    ![image](https://user-images.githubusercontent.com/8906637/39987048-6fa22858-5718-11e8-8a36-93578611b819.png "image")  

    - Add: Image upload option in services configuration. See : [#152](https://github.com/Monitorr/Monitorr/issues/152 "GitHub Issue")  

    NOTE: There are a lot of CSS changes. Please ensure to clear your browser's cache after updating.  

    Docker image is available via Dockerhub with "latest" or "develop" tags:[https://hub.docker.com/r/monitorr/monitorr/tags/](https://hub.docker.com/r/monitorr/monitorr/tags/)</div>

    </div>

    * * *

*   <div class="github-item">

    ## Develop: 1.5.6d

    <time class="github-item-time" datetime="2018-04-20T09:56:56Z"><span class="releasetime">Released on: 2018-04-20 at 09:56:56</span></time>

    <div class="releaseBody">Alert: Configuration change:  

    See Wiki for further details: [https://github.com/Monitorr/Monitorr/wiki/NOTICE:-Updating-Monitorr](https://github.com/Monitorr/Monitorr/wiki/NOTICE:-Updating-Monitorr)  

    Starting with version 1.5.2 we added the feature to enable hot-links on the main Monitorr page for both "Standard" and "Ping only" check types:  

    If you are updating from any version prior to version 1.5.2, you will need to make a small change in your Monitorr settings. After updating proceed to the Monitorr Settings page "Services Configuration", you will notice that the new "Link Enabled" drop-down menu will be enabled for all your services. However, there will be no input field for your link to the right. Simply change the "Link Enabled" option to "No", then immediately to "Yes" again. If you previously had a link associated with that services it will appear in the input field. Do this with ALL your services FIRST before clicking "submit". IF you do NOT want a link associated with that service, change the option to NO.  

    ![image](https://user-images.githubusercontent.com/8906637/38525686-157393b0-3c08-11e8-9e12-1c0505a61e1f.png "image")  

    Note2:  

    Users can/should place their custom images in the /assets/data/usrimg directory. If using docker: /app/images. This is especially important for Docker users as that directory will NOT be erased when re-building the container.  

    Changelog:  

    - ADD: Option to enable/disable hotlink for both "standard" and "ping only" check types.  
    - ADD: User custom images directory (see [#138](https://github.com/Monitorr/Monitorr/issues/138 "GitHub Issue"))  
    - ADD: PHP extension checks (see [#140](https://github.com/Monitorr/Monitorr/issues/140 "GitHub Issue"))  
    - CHANGE: Curl check timeout from 30 seconds to 15 seconds.  
    - FIX: Services settings page reformat  
    - FIX: script reordering.  

    Docker image is available via Dockerhub with the "develop" tag: [https://hub.docker.com/r/monitorr/monitorr/tags/](https://hub.docker.com/r/monitorr/monitorr/tags/)  
    </div>

    </div>

    * * *

*   <div class="github-item">

    ## Master: 1.5.5m

    <time class="github-item-time" datetime="2018-04-20T09:54:48Z"><span class="releasetime">Released on: 2018-04-20 at 09:54:48</span></time>

    <div class="releaseBody">Alert: Configuration change:  

    See Wiki for further details: [https://github.com/Monitorr/Monitorr/wiki/NOTICE:-Updating-Monitorr](https://github.com/Monitorr/Monitorr/wiki/NOTICE:-Updating-Monitorr)  

    Starting with version 1.5.2 we added the feature to enable hot-links on the main Monitorr page for both "Standard" and "Ping only" check types:  

    If you are updating from any version prior to version 1.5.2, you will need to make a small change in your Monitorr settings. After updating proceed to the Monitorr Settings page "Services Configuration", you will notice that the new "Link Enabled" drop-down menu will be enabled for all your services. However, there will be no input field for your link to the right. Simply change the "Link Enabled" option to "No", then immediately to "Yes" again. If you previously had a link associated with that services it will appear in the input field. Do this with ALL your services FIRST before clicking "submit". IF you do NOT want a link associated with that service, change the option to NO.  

    ![image](https://user-images.githubusercontent.com/8906637/38525686-157393b0-3c08-11e8-9e12-1c0505a61e1f.png "image")  

    Note2:  

    Users can/should place their custom images in the /assets/data/usrimg directory. If using docker: /app/images. This is especially important for Docker users as that directory will NOT be erased when re-building the container.  

    Changelog:  

    - ADD: Option to enable/disable hotlink for both "standard" and "ping only" check types.  
    - ADD: User custom images directory (see [#138](https://github.com/Monitorr/Monitorr/issues/138 "GitHub Issue"))  
    - ADD: PHP extension checks (see [#140](https://github.com/Monitorr/Monitorr/issues/140 "GitHub Issue"))  
    - CHANGE: Curl check timeout from 30 seconds to 15 seconds.  
    - FIX: Services settings page reformat  
    - FIX: script reordering.  

    Docker image is available via Dockerhub with the "latest" tag: [https://hub.docker.com/r/monitorr/monitorr/tags/](https://hub.docker.com/r/monitorr/monitorr/tags/)  
    </div>

    </div>

    * * *

*   <div class="github-item">

    ## Master: 1.5.0m

    <time class="github-item-time" datetime="2018-04-04T16:22:09Z"><span class="releasetime">Released on: 2018-04-04 at 16:22:09</span></time>

    <div class="releaseBody">Monitorr 1.0!!!  

    Docker can be downloaded here:[https://hub.docker.com/r/monitorr/monitorr/](https://hub.docker.com/r/monitorr/monitorr/)  

    _NOTE1:_ If you are updating to any version prior to 1.0 updating via the UI by clicking on “check for update” in the footer MAY FAIL. It is recommended to clone a new copy of this repo starting with version 1.0\. Please See [WIKI](https://github.com/Monitorr/Monitorr/wiki/NOTICE:-Updating-Monitorr "WIKI")  

    _NOTE2:_ After updating PLEASE clear your browser cache.  

    We thank you for helping us develop.  

    Cheers to the future.  

    Change log:  

    - Integrated Settings page /w authentication.  
    - Migrated to data/user database for settings files.  
    - Service offline display banner.  
    - Option to choose “ping only” for services that don’t serve a webpage.  
    - Option to disable individual services.  
    - Alternate hot-links for UI.  
    </div>

    </div>

    * * *

*   <div class="github-item">

    ## Master: v0.14.1m

    <time class="github-item-time" datetime="2018-03-16T09:22:18Z"><span class="releasetime">Released on: 2018-03-16 at 09:22:18</span></time>

    <div class="releaseBody">Added notice to footer:  

    _NOTICE_ (16 MAR 2018): Monitorr 1.0 will be released ~18 March 2018\. When this release is published, updating via the UI by clicking on “check for update” in the footer MAY FAIL. Please See [WIKI](https://github.com/Monitorr/Monitorr/wiki/NOTICE:-Updating-Monitorr "WIKI") for further explanation.</div>

    </div>

    * * *

*   <div class="github-item">

    ## Develop: v0.14.1d

    <time class="github-item-time" datetime="2018-03-16T08:57:02Z"><span class="releasetime">Released on: 2018-03-16 at 08:57:02</span></time>

    <div class="releaseBody">Added notice to footer:  

    _NOTICE_ (16 MAR 2018): Monitorr 1.0 will be released ~18 March 2018\. When this release is published, updating via the UI by clicking on “check for update” in the footer MAY FAIL. Please See [WIKI](https://github.com/Monitorr/Monitorr/wiki/NOTICE:-Updating-Monitorr "WIKI") for further explanation.</div>

    </div>

    * * *

*   <div class="github-item">

    ## Master: v0.14.0m

    <time class="github-item-time" datetime="2018-02-16T01:55:14Z"><span class="releasetime">Released on: 2018-02-16 at 01:55:14</span></time>

    <div class="releaseBody">  
    After updating CLEAR YOUR BROWSER CACHE!!!  

    Changes:  

    - Complete bootstrap overhaul. Works/looks great on mobile.  
    - Updated jquery and bootstrap.css  
    - Fixed analog clock not displaying server side time.  

    NOTE:  

    If you are using Organizr integration, Update your HTML code within the following new measurements in Org settings ->"edit homepage" -> HTML  

    ```  

    <div style="overflow:hidden;  height:255px">  
    <embed style="height:100%" width="100%" src="https://yourdomain.com/monitorr/index.min.php">  
    </div>

    ```  

    ![36181875-9eda088a-10db-11e8-84d5-1e7117efe263](https://user-images.githubusercontent.com/8906637/36290178-e3ded26e-1278-11e8-99be-19f90e9f4c1f.png "36181875-9eda088a-10db-11e8-84d5-1e7117efe263")  
    </div>

    </div>

    * * *

*   <div class="github-item">

    ## Develop: v0.14.0d

    <time class="github-item-time" datetime="2018-02-14T00:19:55Z"><span class="releasetime">Released on: 2018-02-14 at 00:19:55</span></time>

    <div class="releaseBody">_After updating CLEAR YOUR BROWSER CACHE!!!_  

    Changes:  

    - Complete bootstrap overhaul. Works/looks great on mobile.  
    - Updated jquery and bootstrap.css  
    - Fixed analogue clock not displaying server side time.  

    NOTE:  

    If you are using Organizr integration, Update your HTML code within the following new measurements in Org settings ->"edit homepage" -> HTML  

    ```  

    <div style="overflow:hidden;  height:255px">  
    <embed style="height:100%" width="100%" src="https://yourdomain.com/monitorr/index.min.php">  
    </div>

    ```  

    ![image](https://user-images.githubusercontent.com/8906637/36181875-9eda088a-10db-11e8-84d5-1e7117efe263.png "image")  

    </div>

    </div>

    * * *

*   <div class="github-item">

    ## Master: v0.13.3m

    <time class="github-item-time" datetime="2018-02-06T01:08:56Z"><span class="releasetime">Released on: 2018-02-06 at 01:08:56</span></time>

    <div class="releaseBody">Master branch changes:  

    - Added update notification. NOTE: if an update is available the link above ("Check for update") will trigger the the update and update the application.  
    - Fixed curl_close errors  
    - Added CURL/PING check manual tool to /assets/php/checkmanual.php. See wiki:[https://github.com/Monitorr/Monitorr/wiki/05-Troubleshooting](https://github.com/Monitorr/Monitorr/wiki/05-Troubleshooting)  

    ![monitorr_update](https://user-images.githubusercontent.com/8906637/35836810-3779cfc6-0a97-11e8-8892-9e25bdaaba95.PNG "monitorr_update")  

    </div>

    </div>

    * * *

*   <div class="github-item">

    ## Develop: v0.13.3d

    <time class="github-item-time" datetime="2018-02-05T23:51:52Z"><span class="releasetime">Released on: 2018-02-05 at 23:51:52</span></time>

    <div class="releaseBody">- minor maintenance release</div>

    </div>

    * * *

*   <div class="github-item">

    ## Develop: v0.13.1d

    <time class="github-item-time" datetime="2018-02-03T21:54:11Z"><span class="releasetime">Released on: 2018-02-03 at 21:54:11</span></time>

    <div class="releaseBody">Fixing curl_close errors</div>

    </div>

    * * *

*   <div class="github-item">

    ## Develop: v0.13.0d

    <time class="github-item-time" datetime="2018-02-02T15:58:19Z"><span class="releasetime">Released on: 2018-02-02 at 15:58:19</span></time>

    <div class="releaseBody">Changes:  

    - improved check manual script  
    - added update notification:  

    ![image](https://user-images.githubusercontent.com/8906637/35742211-9d02dca8-07ee-11e8-88e4-67e822c6aca2.png "image")  
    </div>

    </div>

    * * *

*   <div class="github-item">

    ## Master: v0.12.8m

    <time class="github-item-time" datetime="2018-01-28T00:10:14Z"><span class="releasetime">Released on: 2018-01-28 at 00:10:14</span></time>

    <div class="releaseBody">- Changed fallback check to NOT use persistent connections.  
    - Added 5 second timeout for fallback check  
    - Added error message in UI if invalid host URL is entered in config.php  
    - Added CURL/PING manual check script. See /assets/php/checkmanual.php for usage and wiki doc here:[https://github.com/Monitorr/Monitorr/wiki/05-Troubleshooting](https://github.com/Monitorr/Monitorr/wiki/05-Troubleshooting)</div>

    </div>

    * * *

*   <div class="github-item">

    ## Develop: v0.12.8d

    <time class="github-item-time" datetime="2018-01-27T11:36:51Z"><span class="releasetime">Released on: 2018-01-27 at 11:36:51</span></time>

    <div class="releaseBody">- Changed fallback check to NOT use persistent connections.  
    - Added 5 second timeout for fallback check  
    - Added error message in UI if invalid host URL is entered in config.php  
    </div>

    </div>

    * * *

*   <div class="github-item">

    ## Master: v0.12.7m

    <time class="github-item-time" datetime="2018-01-25T18:40:14Z"><span class="releasetime">Released on: 2018-01-25 at 18:40:14</span></time>

    <div class="releaseBody">Updates:  

    NOTE: If you are upgrading from any version prior to 0.12.5\. You MUST add a port to all of your monitorred URLs (i.e.[https://mydomain.com:443/application](https://mydomain.com:443/application)). Please See WIKI for further explanation.  

    - Added ping check fallback method (Curl as primary check, sockopen as fallback)  
    - Removed Curl SSL cert hostname match requirement  
    - added system disk used percentage  
    - Minor CSS updates</div>

    </div>

    * * *

*   <div class="github-item">

    ## Develop: v0.12.6d

    <time class="github-item-time" datetime="2018-01-24T05:49:39Z"><span class="releasetime">Released on: 2018-01-24 at 05:49:39</span></time>

    <div class="releaseBody">- added system disk used percentage  
    - CSS mods  

    ![image](https://user-images.githubusercontent.com/8906637/35316567-3b4f0860-0087-11e8-8fe1-59f57ddf7da5.png "image")  
    </div>

    </div>

    * * *

*   <div class="github-item">

    ## Develop: v0.12.5d

    <time class="github-item-time" datetime="2018-01-23T00:49:18Z"><span class="releasetime">Released on: 2018-01-23 at 00:49:18</span></time>

    <div class="releaseBody">Updates:  

    NOTE: If you are upgrading from any version prior to 0.12.5\. You MUST add a port to all of your monitorred URLs (i.e.[https://mydomain.com:443/application](https://mydomain.com:443/application)). Please See WIKI for further explanation.  

    1. Added ping check fallback method (Curl as primary check, sockopen as fallback)  
    2. Removed Curl SSL cert hostname match requirement  
    3. Minor CSS updates  
    </div>

    </div>

    * * *

*   <div class="github-item">

    ## Master: v0.12.4m

    <time class="github-item-time" datetime="2018-01-08T07:44:21Z"><span class="releasetime">Released on: 2018-01-08 at 07:44:21</span></time>

    <div class="releaseBody">changed offline service link behavior. See [#88](https://github.com/Monitorr/Monitorr/issues/88 "GitHub Issue")  

    a. OFFLINE service link will now be disabled EXCEPT for the service title which the cursor will show "not-allowed". The decision to make the service title still "clickable" is in the case of a webserver misconfig in which the service UI might still be UP but Monitorr is reporting it as down.</div>

    </div>

    * * *

*   <div class="github-item">

    ## Develop: v0.12.3d

    <time class="github-item-time" datetime="2018-01-08T07:42:33Z"><span class="releasetime">Released on: 2018-01-08 at 07:42:33</span></time>

    <div class="releaseBody">changed offline service link behavior. See [#88](https://github.com/Monitorr/Monitorr/issues/88 "GitHub Issue")  

    a. OFFLINE service link will now be disabled EXCEPT for the service title which the cursor will show "not-allowed". The decision to make the service title still "clickable" is in the case of a webserver misconfig in which the service UI might still be UP but Monitorr is reporting it as down.</div>

    </div>

    * * *

*   <div class="github-item">

    ## Develop: v0.12.2d

    <time class="github-item-time" datetime="2018-01-07T12:06:52Z"><span class="releasetime">Released on: 2018-01-07 at 12:06:52</span></time>

    <div class="releaseBody">- changed offline service link behavior. See [#88](https://github.com/Monitorr/Monitorr/issues/88 "GitHub Issue")  
    a. OFFLINE service link will now be disabled EXCEPT for the service title which the cursor will show "not-allowed". The decision to make the service title still "clickable" is in the case of a webserver misconfig in which the service UI might still be UP but Monitorr is reporting it as down.</div>

    </div>

    * * *

*   <div class="github-item">

    ## Develop: v0.12.1d

    <time class="github-item-time" datetime="2018-01-05T00:03:02Z"><span class="releasetime">Released on: 2018-01-05 at 00:03:02</span></time></div>

    * * *

*   <div class="github-item">

    ## Master: v0.12.0m

    <time class="github-item-time" datetime="2018-01-04T23:53:12Z"><span class="releasetime">Released on: 2018-01-04 at 23:53:12</span></time>

    <div class="releaseBody">- Added auto-refresh toggle switch  
    - Fix for RAM percentage showing incorrect value. See [#67](https://github.com/Monitorr/Monitorr/issues/67 "GitHub Issue")  
    - Fixed spacing when tile service title text is two lines  
    - See Wiki for correct values when using w/ Organizr:[https://github.com/Monitorr/Monitorr/wiki/Integration:--Organizr](https://github.com/Monitorr/Monitorr/wiki/Integration:--Organizr)  

    _After updating always ensure to clear browser cache_</div>

    </div>

    * * *

*   <div class="github-item">

    ## Develop: v0.11.9d

    <time class="github-item-time" datetime="2018-01-04T22:27:49Z"><span class="releasetime">Released on: 2018-01-04 at 22:27:49</span></time>

    <div class="releaseBody">- Fix for RAM percentage showing incorrect value. See [#67](https://github.com/Monitorr/Monitorr/issues/67 "GitHub Issue")  
    - Adjusted service img display size.</div>

    </div>

    * * *

*   <div class="github-item">

    ## Develop: v0.11.7d

    <time class="github-item-time" datetime="2018-01-03T18:44:35Z"><span class="releasetime">Released on: 2018-01-03 at 18:44:35</span></time>

    <div class="releaseBody">- Fixed spacing when tile service title text is two lines</div>

    </div>

    * * *

*   <div class="github-item">

    ## Develop: v0.11.6d

    <time class="github-item-time" datetime="2018-01-02T10:45:38Z"><span class="releasetime">Released on: 2018-01-02 at 10:45:38</span></time>

    <div class="releaseBody">- changed services tile spacing</div>

    </div>

    * * *

*   <div class="github-item">

    ## Develop: v0.11.5d

    <time class="github-item-time" datetime="2018-01-01T14:55:49Z"><span class="releasetime">Released on: 2018-01-01 at 14:55:49</span></time>

    <div class="releaseBody">- Changed BODY tag to fix scroll bars for IE  
    - Changed classes to alter margin values  
    - Changed margin values for min site  
    </div>

    </div>

    * * *

</div>
