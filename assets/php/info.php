<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <link type="text/css" href="../css/bootstrap.min.css" rel="stylesheet">
        <!-- <link type="text/css" href="../css/alpaca.min.css" rel="stylesheet"> -->
        <link type="text/css" href="../css/main.css" rel="stylesheet">

        <meta name="theme-color" content="#464646" />
        <meta name="theme_color" content="#464646" />

        <script type="text/javascript" src="../js/jquery.min.js"></script>
        <script type="text/javascript" src="../js/pace.js" async></script>
        <!-- <script type="text/javascript" src="../js/handlebars.js"></script> -->
        <!-- <script type="text/javascript" src="../js/alpaca.min.js"></script> -->
        <script type="text/javascript" src="../js/bootstrap.min.js"></script>

            <style>

                body {
                    margin: 2vw !important;
                    overflow-y: auto;
                    overflow-x: hidden;
                    /* color: white !important; */
                }

                legend {
                    color: white;
                    }

                body::-webkit-scrollbar {
                    width: 10px;
                    background-color: #252525;
                }

                body::-webkit-scrollbar-track {
                    -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
                    box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
                    border-radius: 10px;
                    background-color: #252525;
                }

                body::-webkit-scrollbar-thumb {
                    border-radius: 10px;
                    -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, .3);
                    box-shadow: inset 0 0 6px rgba(0, 0, 0, .3);
                    background-color: #8E8B8B;
                }

                body.offline #link-bar {
                    display: none;
                }

                body.online #link-bar {
                    display: block;
                }

                .auto-style1 {
                    text-align: center;
                }
                
                #centertext {
                    padding-bottom: 2rem !important;
                }


                #includedContent {
                    /* position: static; */
                    /* margin-top: -5rem; */
                    float: right;
                    width: 95% !important;
                }

                tbody {
                    cursor: default !important;
                }

                .links {
                    color: yellow !important;
                    font-size: 1rem !important;
                    font-weight: 500 !important;
                }

                select, input {
                    -webkit-appearance: none;
                    -moz-appearance: none;
                    appearance: none;
                }


            </style>

        <title>
            <?php
                $str = file_get_contents('../data/user_preferences-data.json');
                $json = json_decode($str, true);
                $title = $json['sitetitle'];
                echo $title . PHP_EOL;
            ?>
            | Info
        </title>

        <?php include ('../config.php'); ?>
        <!-- <?php include ('../php/check.php') ;?> -->
        <?php include ('gitinfo.php'); ?>

    </head>

<body>

        <script>
            document.body.className += ' fade-out';
            $(function() {
                $('body').removeClass('fade-out');
            });
        </script>


        <div id="centertext">
            <div class="navbar-brand">
                Information
            </div>
        </div>


        <div id="infodata">
          <!-- <p> test table </p> -->
            <table class="table">
              <thead> <div id="blank"> . </div> </thead>
              <tbody>
                <tr>
                    <td><strong>Monitorr Installed Version:</strong></td>
                    <td><?php echo file_get_contents( "../js/version/version.txt" );?> <p id="version_check_auto"></p> </td>
                    <td><strong>OS / Version:</strong></td>
                    <td><?php echo php_uname(); ?></td>
                </tr>
                <tr>
                    <td><strong>Monitorr Latest Version:</strong></td>
                    <td><a href="https://github.com/monitorr/monitorr/releases" target="_blank" title="Monitorr Releases">
                            <img src="https://img.shields.io/github/release/monitorr/monitorr.svg?style=flat" label="Monitorr Release" alt="Monitorr Release" style="width:6rem;height:1.1rem;" >
                        </a>
                    </td>
                    <td><strong>PHP Version:</strong></td>
                    <td><?php echo phpversion('tidy'); ?></td>
                   
                </tr>
                <tr>
                    <td><strong>Check & Execute Update:</strong></td>
                    <td><a id="version_check" style="cursor: pointer">Check for Update</a> </td>
                     <td><strong>Install Path: </strong></td>
                     <td>
                        <?php
                            $vnum_loc = "../../";
                            echo realpath($vnum_loc), PHP_EOL;
                        ?>
                    </td>
                </tr>

                <tr>
                    <td><strong>Resources:</strong></td>
                    <td><a href="https://github.com/monitorr/Monitorr" target="_blank" title="Monitorr GitHub Repo"> <img src="https://img.shields.io/badge/GitHub-repo-green.svg" style="width:4rem;height:1rem;" alt="Monitorr GitHub Repo"></a> | <a href="https://hub.docker.com/r/monitorr/monitorr/" target="_blank" title="Monitorr Docker Repo"> <img src="https://img.shields.io/docker/build/monitorr/monitorr.svg?maxAge=2592000" style="width:6rem;height:1rem;" alt="Monitorr Docker Repo"></a> | <a href="https://feathub.com/Monitorr/Monitorr" target="_blank" title="Monitorr Feature Request"> <img src="https://img.shields.io/badge/FeatHub-suggest-blue.svg" style="width:5rem;height:1rem;" alt="Monitorr Feature Request"></a> | <a href="https://discord.gg/j2XGCtH" target="_blank" title="Monitorr Discord Channel"> <img src="https://img.shields.io/discord/102860784329052160.svg" style="width:5rem;height:1rem;" alt="Monitorr on Discord" ></a> | <a href="https://paypal.me/monitorrapp" target="_blank" title="Buy us a beer!"> <img src="https://img.shields.io/badge/Donate-PayPal-green.svg" style="width:4rem;height:1rem;" alt="PayPal" ></a> </td>
                    <td><strong>Manual Check Tool:</strong></td>
                    <td><a href="checkmanual.php" target="_blank">A generated output of why a service may be reporting incorrectly.</a></td>          

                </tr>

              </tbody>
                    <tr>
                        <!-- <div id="blank"> . </div> -->
                    </tr>
            </table>

        </div>

       

         <!-- <div id ="phpContent"> </div> -->

        <div class="slide">
            <input class="expandtoggle" type="checkbox" name="slidebox"  checked>
            <!-- <label for="php" ></label> -->
                <div id="expand" class="expand">

                     <div id ="phpContent"> </div>

                </div>
            </input>
        </div>


        <script>document.getElementById("phpContent").innerHTML='<object type="text/html" class="phpobject" data="phpinfo.php" ></object>'</script>

            <script src="../js/update.js" async></script>
            <script src="../js/update_auto-settings.js" async></script>

        <div id="footer">

            <p> <a class="footer a" href="https://github.com/monitorr/Monitorr" target="_blank"> Monitorr </a> | <a class="footer a" href="https://github.com/Monitorr/Monitorr/releases" target="_blank"> <?php echo file_get_contents( "../js/version/version.txt" );?> </a> </p>

            <!-- <a class="footer a" id="version_check" style="cursor: pointer">Check for Update</a> -->

            <!-- <div id="version_check_auto"></div> -->

        </div>

</body>

</html>
