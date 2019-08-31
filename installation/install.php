<?php
/*
BISMILLAAHIRRAHMAANIRRAHIIM - In the Name of Allah, Most Gracious, Most Merciful
================================================================================
filename	: 
purpose	: 
create	: 2006/04/27
last edit	: 2006/03/17,1124,070430,1217,120724
author	: cahya dsn
================================================================================
This program is free software; you can redistribute it and/or modify it under the 
terms of the GNU General Public License as published by the Free Software 
Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY 
WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR 
A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

copyright (c) 2006-2012 by cahya dsn; cahyadsn@yahoo.com
================================================================================
*/
include_once("common.inc.php");
if (file_exists( '../config.php' ) && filesize( '../config.php' ) > 10) {
  header( "Location: ../index.php" );
  exit();
}
$f=array("install1.php","0");
include "install.header.php";
?>
    <div id="step">license</div>
    <div class="far-right">
    <input class="button" type="submit" name="next" value="Next &gt;&gt;"/>
    </div>
    <div class="clr"></div>
    <h1>GNU/GPL License:</h1>
    <div class="licensetext">
        <a href="mailto:cahyadsn@yahoo.com">Adzan </a> is Free Software released under the GNU/GPL License.
    </div>
    <div class="clr"></div>
    <div class="license-form">
      <div class="form-block" style="padding: 0px;">
        <iframe src="../docs/gpl.html" class="license" frameborder="0" scrolling="auto"></iframe>
      </div>
    </div>
    <div class="clr"></div>
    <div class="clr"></div>
    </div>
<?php include "install.footer.php"; ?>