<?php
/*
BISMILLAAHIRRAHMAANIRRAHIIM - In the Name of Allah, Most Gracious, Most Merciful
================================================================================
filename	: offline.php
purpose	: 
create	: 2006/02/01
last edit	: 060301;0317,070430,120113
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
if (!file_exists( 'installation/index.php' )) {
  define( '_INSTALL_CHECK', 1 );
  header("Location: index.php");
  exit();
}
define('_ISO','UTF-8=ISO-8859-1');
if (!defined( '_ADMIN_OFFLINE' ) || defined( '_INSTALL_CHECK' )) {
  include("config.php");
  include("lang/".$adzanCfg_lang.".lang.php");
  $iso = preg_split( '/=/', _ISO );
  echo '<?xml version="1.0" encoding="'. $iso[1] .'"?' .'>'."\n";
  ?>
  <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
  <html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title><?php echo $adzanCfg_title." ver ".$adzanCfg_version; ?> - Offline</title>
    <link rel="stylesheet" href="<?php echo $adzanCfg_live_site; ?>/templates/css/offline.css" type="text/css" />
    <link rel="shortcut icon" href="<?php echo $adzanCfg_live_site; ?>/images/favicon.ico" />
    <meta http-equiv="Content-Type" content="text/html; <?php echo _ISO; ?>" />
  </head>
  <body>
    <p>&nbsp;</p>
    <table width="550" align="center" class="outline">
    <tr>
      <td align="center">
        <h1>
          <?php echo $adzanCfg_title." ver ".$adzanCfg_version; ?>
        </h1>
      </td>
    </tr>
    <?php
    if ( $adzanCfg_offline == 1 ) {
      ?>
      <tr>
        <td width="39%" align="center">
          <h2>
            <?php echo _OFFLINE_MESSAGE; ?>
          </h2>
        </td>
      </tr>
      <?php
    } else if (@$systemError) {
      ?>
      <tr>
        <td width="39%" align="center">
          <h2>
            <?php echo _ERROR_MESSAGE; ?>
          </h2>
          <?php echo $systemError; ?>
        </td>
      </tr>
      <?php
    } else {
      ?>
      <tr>
        <td width="39%" align="center">
        <h2>
          <?php echo _INSTALL_WARN; ?>
        </h2>
        </td>
      </tr>
      <?php
    }
    ?>
    </table>
  </body>
  </html>
  <?php
  exit( 0 );
}
?>