<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="shortcut icon" type="image/x-icon" href="plexlanding.ico" />`
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <style>
        body.offline #link-bar {
            display: none;
        }

        body.online #link-bar {
            display: block;
        }

        .auto-style1 {
            float: left;
            text-align: center;
        }
    </style>
    <?php include ('assets/php/check.php') ;?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="assets/js/ajax.js"></script>
    <script type='text/javascript'>
        function statusCheck() {
            var p = new AReq();
            var timeout = 20000; //Milliseconds
            var body = document.getElementsByTagName("body")[0];
            p.ajaxreq("https://plex.beckeflix.com/", function(status) {
                var serviceImg = document.getElementById("plex-service-img");
                var statusImg = document.getElementById("plex-status-img");
                var statusLink = document.getElementById( "plex-status-link" );
                serviceImg.src = "assets/img/plex.png";
                serviceImg.style.width = "55px";
                if (status === 200) {
                    statusImg.src = "assets/img/online.png";
                    statusImg.style.width = '115px';
                    statusLink.href = "http://app.plex.tv/web/app";
                } else if (status === 401) {
                    statusImg.src = "assets/img/online.png";
                    statusImg.style.width = '115px';
                    statusLink.href = "http://app.plex.tv/web/app";
                } else {
                    statusImg.src = "assets/img/offline.png";
                    statusImg.style.width = '115px';
                    statusLink.href = "YOUR DOWN LINK HERE";

                }
            }, timeout);

            p.ajaxreq("https://requests.beckeflix.com/", function(status) {
                var serviceImg = document.getElementById("requests-service-img");
                var statusImg = document.getElementById("requests-status-img");
                var statusLink = document.getElementById( "requests-status-link" );
                serviceImg.src = "assets/img/ombi.png";
                serviceImg.style.width = "55px";
                if (status === 200) {
                    statusImg.src = "assets/img/online.png";
                    statusImg.style.width = '115px';
                    statusLink.href = "YOUR UP LINK HERE";
                } else {
                    statusImg.src = "assets/img/offline.png";
                    statusImg.style.width = '115px';
                    statusLink.href = "YOUR DOWN LINK HERE";
                }
            }, timeout);

            p.ajaxreq("https://jackett.beckeflix.com/UI/Dashboard", function(status) {
                var serviceImg = document.getElementById("jackett-service-img");
                var statusImg = document.getElementById("jackett-status-img");
                var statusLink = document.getElementById( "jackett-status-link" );
                serviceImg.src = "assets/img/jackett.png";
                serviceImg.style.width = "55px";
                if (status == 502) {
                    statusImg.src = "assets/img/online.png";
                    statusImg.style.width = '115px';
                    statusLink.href = "YOUR UP LINK HERE";
                } else {
                    statusImg.src = "assets/img/offline.png";
                    statusImg.style.width = '115px';
                    statusLink.href = "YOUR DOWN LINK HERE";
                }
            }, timeout);

            p.ajaxreq("https://deluge.beckeflix.com/", function(status) {
                var serviceImg = document.getElementById("deluge-service-img");
                var statusImg = document.getElementById("deluge-status-img");
                var statusLink = document.getElementById( "deluge-status-link" );
                serviceImg.src = "assets/img/deluge.png";
                serviceImg.style.width = "55px";
                if (status === 200) {
                    statusImg.src = "assets/img/online.png";
                    statusImg.style.width = '115px';
                    statusLink.href = "YOUR UP LINK HERE";
                } else {
                    statusImg.src = "assets/img/online.png";
                    statusImg.style.width = '115px';
                    statusLink.href = "YOUR DOWN LINK HERE";
                }
            }, timeout);

            p.ajaxreq("https://sonarr.beckeflix.com", function(status) {
                var serviceImg = document.getElementById("sonarr-service-img");
                var statusImg = document.getElementById("sonarr-status-img");
                var statusLink = document.getElementById( "sonarr-status-link" );
                serviceImg.src = "assets/img/sonarr.png";
                serviceImg.style.width = "55px";
                if (status === 401) {
                    statusImg.src = "assets/img/online.png";
                    statusImg.style.width = '115px';
                    statusLink.href = "YOUR UP LINK HERE";
                } else {
                    statusImg.src = "assets/img/offline.png";
                    statusImg.style.width = '115px';
                    statusLink.href = "YOUR DOWN LINK HERE";
                }
            }, timeout);

            p.ajaxreq("https://radarr.beckeflix.com", function(status) {
                var serviceImg = document.getElementById("radarr-service-img");
                var statusImg = document.getElementById("radarr-status-img");
                var statusLink = document.getElementById( "radarr-status-link" );
                serviceImg.src = "assets/img/radarr.png";
                serviceImg.style.width = "55px";
                if (status === 401) {
                    statusImg.src = "assets/img/online.png";
                    statusImg.style.width = '115px';
                    statusLink.href = "YOUR UP LINK HERE";
                } else {
                    statusImg.src = "assets/img/offline.png";
                    statusImg.style.width = '115px';
                    statusLink.href = "YOUR DOWN LINK HERE";
                }
            }, timeout);
        }
        setInterval(statusCheck, 3000);
    </script>


    <title>YOUR SERVER NAME HERE</title>


    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">

    <!-- Custom styles -->
    <link href="assets/css/main.css" rel="stylesheet">

    <!-- Fonts from Google Fonts -->
    <link href='//fonts.googleapis.com/css?family=Lato:300,400,900' rel='stylesheet' type='text/css'>


</head>

<body onload="statusCheck()">

    <!-- Fixed navbar -->
    <br>
    <a class="navbar-brand" href="#" style="width: 100%">
        <div class="text-center">
            <b>YOUR SERVER NAME HERE</b>
        </div>
    </a>
    <br>
    <br>
    <div class="container">
        <div class="auto-style1">
            <a href="http://YOURSERVERURLHERE.com">
            </a>
        </div>
        <!-- /row -->

        <div class="row mt centered">
            <div class="col-lg-4">
                <a id="plex-status-link" href="" target="_top">
                    <img id="plex-service-img" src="" alt="">
                    <h4>PLEX</h4>
                    <p><img id="plex-status-img" src="assets/img/puff.svg"></p>
                </a>
            </div>
            <!--/col-lg-4 -->

            <div class="col-lg-4">
                <a id="requests-status-link" href="" target="_top">
                    <img id="requests-service-img" src="" alt="">
                    <h4>Request</h4>
                    <p><img id="requests-status-img" src="assets/img/puff.svg"></p>
                </a>
            </div>
            <!--/col-lg-4 -->

            <div class="col-lg-4">
                <a id="jackett-status-link" href="" target="_top">
                    <img id="jackett-service-img" src="" alt="">
                    <h4>Jackett</h4>
                    <p><img id="jackett-status-img" src="assets/img/puff.svg"></p>
                </a>
            </div>
            <!--/col-lg-4 -->
        </div>
        <!-- /row -->

        <div class="row mt centered">
            <div class="col-lg-4">
                <a id="deluge-status-link" href="" target="_top">
                    <img id="deluge-service-img" src="" alt="">
                    <h4>Deluge</h4>
                    <p><img id="deluge-status-img" src="assets/img/puff.svg"></p>
                </a>
            </div>
            <!--/col-lg-4 -->

            <div class="col-lg-4">
                <a id="sonarr-status-link" href="" target="_top">
                    <img id="sonarr-service-img" src="" alt="">
                    <h4>Sonarr</h4>
                    <p><img id="sonarr-status-img" src="assets/img/puff.svg"></p>
                    <p>Status: <?php $domain = 'https://requests.beckeflix.com';
                        if( !url_test( $domain ) ) {
                          echo $domain ." is down!";
                        }
                        else { echo $domain ." functions correctly."; }
                        ?></p>
                </a>
            </div>
            <!--/col-lg-4 -->

            <div class="col-lg-4">
                <a id="radarr-status-link" href="" target="_top">
                    <img id="radarr-service-img" src="" alt="">
                    <h4>Radarr</h4>
                    <p><img id="radarr-status-img" src="assets/img/puff.svg"></p>
                </a>
            </div>
            <!--/col-lg-4 -->
        </div>
    </div>
    <!-- /container -->
    <p>

</body>

</html>

