<select name="image1">
      <option value="" selected="selected"></option>
  <?php 
    $dir = "assets/img/";//your path
    $dh  = opendir($dir);
    while (false !== ($filename = readdir($dh))) {
        $files[] = htmlentities($filename);
    }

    sort($files);

    foreach($files as $filename) {
       echo "<option value='" . $filename . "'>".$filename."</option>";
    }

?>
</select> 

// Load a config file written in the return syntax
function loadConfig($path = 'assets/config.php') {
    // Adapted from http://stackoverflow.com/a/14173339/6810513
    if (!is_file($path)) {
        return null;
    } else {
        return (array) call_user_func(function() use($path) {
            return include($path);
        });
    }
}

// Commit new values to the configuration
function updateConfig($new, $current = false) {
    // Get config if not supplied
    if ($current === false) {
        $current = loadConfig();
    } else if (is_string($current) && is_file($current)) {
        $current = loadConfig($current);
    }

    // Inject Parts
    foreach ($new as $k => $v) {
        $current[$k] = $v;
    }

    // Return Create
    return createConfig($current);
}


// Build output
    $output = (!$nest?"<?php\nreturn ":'')."array(\n".implode(",\n",$output)."\n".str_repeat("\t",$nest).')'.(!$nest?';':'');

    if (!$nest && $path) {
        $pathDigest = pathinfo($path);

        @mkdir($pathDigest['dirname'], 0770, true);

        if (file_exists($path)) {
            rename($path, $pathDigest['dirname'].'/'.$pathDigest['filename'].'.bak.php');
        }

        $file = fopen($path, 'w');
        fwrite($file, $output);
        fclose($file);
        if (file_exists($path)) {
            return true;
        }
        writeLog("error", "config was unable to write");
        return false;
    } else {
          writeLog("success", "config was updated with new values");
        return $output;
    }


    <?php
    $dirname = "assets/img/";
    $images = scandir($dirname);
    $ignore = Array(".", "..", "favicon", "settings", "cache", "platforms", "._.DS_Store", ".DS_Store", "confused.png", "sowwy.png", "sort-btns", "loading.png", "titlelogo.png", "default.svg", "login.png", "no-np.png", "no-list.png", "no-np.psd", "no-list.psd", "themes", "nadaplaying.jpg", "organizr-logo-h-d.png", "organizr-logo-h.png");
    foreach($images as $curimg){
        if(!in_array($curimg, $ignore)) { ?>
            <div class="col-xs-2" style="width: 75px; height: 75px; padding-right: 0px;">    
                <a data-toggle="tooltip" data-placement="bottom" title="<?=$dirname.$curimg;?>" class="thumbnail" style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
                    <img style="width: 50px; height: 50px;" src="<?=$dirname.$curimg;?>" alt="thumbnail" class="allIcons">
                </a>
            </div>
    <?php 
        }
    }
    ?>
                                                </div>
                                            </div>
                                            <div class="panel">
                                                <div class="panel-body todo">
                                                    <ul class="list-group ui-sortable">
    <?php
    foreach($file_db->query('SELECT * FROM tabs ORDER BY `order` asc') as $key => $row) {
        if (!isset($row['id'])) { $row['id'] = $key + 1; }
        echo printTabRow($row);
    }
    ?>
                                                    </ul>
                                                </div>
                                            </div>
                                        </form>
    <?php echo printTabRow(false); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="email-content color-box white-bg">   

                    <div class="email-content tab-box white-bg">
                    <div class="email-body">
                        <div class="email-header gray-bg">
                            <button type="button" class="btn btn-danger btn-sm waves close-button"><i class="fa fa-close"></i></button>
                            <button type="button" class="btn waves btn-labeled btn-success btn btn-sm text-uppercase waves-effect waves-float save-btn-form submitTabBtn">
												<span class="btn-label"><i class="fa fa-floppy-o"></i></span><?php echo translate('SAVE_TABS'); ?>
											</button>
                            <h1>Edit Tabs</h1>
                        </div>
                        <div class="email-inner small-box">
                            <div class="email-inner-section">
                                <div class="small-box todo-list fade in" id="tab-tabs">
									<form id="submitTabs" onsubmit="submitTabs(this); return false;">
										<div class="sort-todo">
											<button id="newtab" type="button" class="btn waves btn-labeled btn-success btn-sm text-uppercase waves-effect waves-float" onclick="addTab()">
												<span class="btn-label"><i class="fa fa-plus"></i></span><?php echo translate("NEW_TAB");?>
											</button>
											<button id="iconHide" type="button" class="btn waves btn-labeled btn-warning btn-sm text-uppercase waves-effect waves-float">
												<span class="btn-label"><i class="fa fa-upload"></i></span><?php echo $language->translate("UPLOAD_ICONS");?>
											</button>
											<button id="iconAll" type="button" class="btn waves btn-labeled btn-info btn-sm text-uppercase waves-effect waves-float">
												<span class="btn-label"><i class="fa fa-picture-o"></i></span><?php echo $language->translate("VIEW_ICONS");?>
											</button>
           									<button id="checkFrame" data-toggle="modal" data-target=".checkFrame" type="button" class="btn waves btn-labeled btn-gray btn-sm text-uppercase waves-effect waves-float">
												<span class="btn-label"><i class="fa fa-check"></i></span><?php echo $language->translate("CHECK_FRAME");?>
											</button>
                                            <button id="toggleAllExtra" type="button" class="btn waves btn-labeled btn-info btn-sm text-uppercase waves-effect waves-float indigo-bg">
												<span class="btn-label"><i class="fa fa-toggle-off"></i></span><span class="btn-text"><?php echo $language->translate("TOGGLE_ALL");?></span>
											</button>
										</div>
										<input type="file" name="files[]" id="uploadIcons" multiple="multiple">
										<div id="viewAllIcons" style="display: none;">
											<h4><strong><?php echo $language->translate("ALL_ICONS");?></strong> [<?php echo $language->translate("CLICK_ICON");?>]</h4>
											<div class="row">
												<textarea id="copyTarget" class="hideCopy" style="left: -9999px; top: 0; position: absolute;"></textarea>                                           
<?php
$dirname = "images/";
$images = scandir($dirname);
$ignore = Array(".", "..", "favicon", "settings", "cache", "platforms", "._.DS_Store", ".DS_Store", "confused.png", "sowwy.png", "sort-btns", "loading.png", "titlelogo.png", "default.svg", "login.png", "no-np.png", "no-list.png", "no-np.psd", "no-list.psd", "themes", "nadaplaying.jpg", "organizr-logo-h-d.png", "organizr-logo-h.png");
foreach($images as $curimg){
	if(!in_array($curimg, $ignore)) { ?>
												<div class="col-xs-2" style="width: 75px; height: 75px; padding-right: 0px;">    
													<a data-toggle="tooltip" data-placement="bottom" title="<?=$dirname.$curimg;?>" class="thumbnail" style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
														<img style="width: 50px; height: 50px;" src="<?=$dirname.$curimg;?>" alt="thumbnail" class="allIcons">
													</a>
												</div>
<?php 
	}
}
?>
											</div>
										</div>
										<div class="panel">
                                            <div class="panel-body todo">
                                                <ul class="list-group ui-sortable">
<?php
foreach($file_db->query('SELECT * FROM tabs ORDER BY `order` asc') as $key => $row) {
	if (!isset($row['id'])) { $row['id'] = $key + 1; }
	echo printTabRow($row);
}
?>
                                                </ul>
                                            </div>
										</div>
									</form>
<?php echo printTabRow(false); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="email-content color-box white-bg">\



                <div class="container">
                <!-- /row -->
                
        <?php /*from ja v a 2s.c  o  m*/
           $myBooks = array( 
             array( 
               "title" => "Learn PHP from java2s.com", 
               "author" => "java2s.com", 
               "pubYear" => 2000 
             ), 
             array( 
               "title" => "Learn Java from java2s.com", 
               "author" => "JavaAuthor", 
               "pubYear" => 2001 
             ), 
             array( 
               "title" => "Learn HTML from java2s.com", 
               "author" => "HTMLAuthor", 
               "pubYear" => 2002 
             ), 
             array( 
               "title" => "Learn CSS from java2s.com", 
               "author" => "CSSAuthor", 
               "pubYear" => 2003 
             ), 
            ); 
        
            $bookNum = 0; 
        
            foreach ( $myBooks as $book ) { 
             $bookNum++; 
             //echo "Book $bookNum:"; 
             foreach ( $book as $key => $value ) { 
               echo "$key :$value \n"; 
             } 
            } 
         ?> 
        </div>