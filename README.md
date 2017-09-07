
## MONITORR
<br>
<b>Webfront that will live display the status of any webapp on your domain. </b>
<br>
Version:  ALPHA




## Screenshot :
 (in use as a practical application)

![preview thumb] <img src="https://i.imgur.com/95EwyGG.png[/img]">



## Configuration:
1) Clone/download repository to your webserver (Suggested a Sub DIR)
2) Replace all "YOUR SERVER NAME HERE" variables.
3) Set " setInterval(statusCheck, 3000); " to how frequent the page should check for status in milliseconds (3000 = 3 seconds).
4) If you want to have different links for if the status is up or down, update the `statusLink.href = "YOUR DOWN LINK HERE";` and `statusLink.href = "YOUR UP LINK HERE";` to where you want the link to take you dependent on the status.
6) You can also adjust image width using the `statusImg.style.width = "xxxpx"` lines
5) You can add more services. Just copy one of the existing HTML and `p.ajaxreq` blocks and edit the YOUR_xxx variables.
    eg:
    ```
    p.ajaxreq("https://YOUR_URL_HERE", function(status) {
        var statusImg = document.getElementById("YOUR_SERVICE-status-img");
        var statusLink = document.getElementById( "YOUR_SERVICE-status-link" );
        if (status === 200) {
            statusImg.src = "assets/img/online.png";
            statusImg.style.width = '150px';
            statusLink.href = "YOUR UP LINK HERE";
        } else {
            statusImg.src = "assets/img/offline.png";
            statusImg.style.width = '150px';
            statusLink.href = "YOUR DOWN LINK HERE";
        }
    }, timeout);
    ```
    and further down the page:
    ```
    <div class="col-lg-4">
        <a id="YOUR_SERVICE-status-link" href="" target="_top">
            <img src="assets/img/s03.png" width="180" alt="">
            <h4>What's New</h4>
            <p><img id="YOUR_SERVICE-status-img" src="assets/img/puff.svg"></p>
            <p>See what has been recently added to YOUR SERVER NAME HERE without having to log in.</p>
        </a>
    </div>
    ```

Considerations:

1) The .JS code will only monitor services that are hosted on the same domain.
2) Resize all HTML to fit your needs.

## About Us:
- Maintained by [seanvree](https://github.com/seanvree) (Windows Wizard),  [jonfinley](https://github.com/jonfinley) (Linux Dude) &  [wjbeckett](https://github.com/wjbeckett)

- We usually hang out here:   [![Gitter](https://img.shields.io/badge/Gitter-Organizr-ed1965.svg?style=flat-square)](https://gitter.im/Organizrr/Lobby)
