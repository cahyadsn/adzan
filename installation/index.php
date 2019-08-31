<?php
/*
BISMILLAAHIRRAHMAANIRRAHIIM - In the Name of Allah, Most Gracious, Most Merciful
================================================================================
filename	: 
purpose	: 
create	: 2006/04/27
last edit	: 2006/03/17,1124,070430,1217,080810,120116
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
if (file_exists( '../config.php' ) && filesize( '../config.php' ) > 100) {
  header( "Location: ../index.php" );
  exit();
}
function get_php_setting($val) {
  $r =  (ini_get($val) == '1' ? 1 : 0);
  return $r ? 'ON' : 'OFF';
}
function writableCell( $folder ) {
  echo '<tr>';
  echo '<td class="item">' . $folder .(is_dir("../$folder")?'/':'').'</td>';
  echo '<td align="left">';
  echo is_writable( "../$folder" ) ? '<b><font color="green">Writeable</font></b>' : '<b><font color="red">Unwriteable</font></b>' . '</td>';
  echo '</tr>';
}
$f=array("install.php","index");
include "install.header.php";
?>
<div id="step">pre-installation check</div>
<div class="far-right">
  <input type="button" class="button" value="Check Again" onclick="window.location=window.location" />
  <input name="Button2" type="submit" class="button" value="Next >>" onclick="window.location='install.php';" />
</div>
<div class="clr"></div>
<h1>Pre-installation check for: Adzan <?php echo $install_ver; ?></h1>
<div class="install-text">
If any of these items are highlighted in red then please take actions to correct them. Failure to do so could lead to your Adzan installation not functioning correctly.
<div class="ctr"></div>
</div>
<div class="install-form">
<div class="form-block">
<table class="content">
<tr valign="top">
  <td class="item">
  PHP version >= 4.2.0
  </td>
  <td align="left">
  <?php echo phpversion() < '4.2' ? '<b><font color="red">No</font></b>' : '<b><font color="green">Yes</font></b>';?>
  </td>
</tr>
<tr valign="top">
  <td>
  &nbsp; - zlib compression support
  </td>
  <td align="left">
  <?php echo extension_loaded('zlib') ? '<b><font color="green">Available</font></b>' : '<b><font color="red">Unavailable</font></b>';?>
  </td>
</tr>
<tr valign="top">
  <td>
  &nbsp; - gd support
  </td>
  <td align="left">
  <?php echo extension_loaded('gd') ? '<b><font color="green">Available</font></b>' : '<b><font color="red">Unavailable</font></b>';?>
  </td>
</tr>
<tr valign="top">
  <td valign="top" class="item">
  config.php
  </td>
  <td align="left">
  <?php
  if (@file_exists('../config.php') &&  @is_writable( '../config.php' )){
    echo '<b><font color="green">Writeable</font></b>';
  } else if (is_writable( '..' )) {
    echo '<b><font color="green">Writeable</font></b>';
  } else {
    echo '<b><font color="red">Unwriteable</font></b><br /><span class="small">You can still continue the install as the configuration will be displayed at the end, just copy & paste this and upload.</span>';
  } ?>
  </td>
</tr>
<tr valign="top">
  <td class="item">
  Session save path
  </td>
  <td align="left">
  <b><?php echo (($sp=ini_get('session.save_path'))?$sp:'Not set'); ?></b><br />
  <?php echo is_writable( $sp ) ? '<b><font color="green">Writeable</font></b>' : '<b><font color="red">Unwriteable</font></b>';?>
  </td>
</tr>
</table>
</div>
</div>
<div class="clr"></div>
<h1>Recommended settings:</h1>
<div class="install-text">
These settings are recommended for PHP in order to ensure full compatibility with Adzan.
<br />
However, Adzan will still operate if your settings do not quite match the recommended
<div class="ctr"></div>
</div>
<div class="install-form">
<div class="form-block">
<table class="content">
<tr>
  <td class="toggle">
  Directive
  </td>
  <td class="toggle">
  Recommended
  </td>
  <td class="toggle">
  Actual
  </td>
</tr>
<?php
$php_recommended_settings = array(array ('Safe Mode','safe_mode','OFF'),
array ('Display Errors','display_errors','ON'),
array ('Magic Quotes GPC','magic_quotes_gpc','ON'),
array ('Magic Quotes Runtime','magic_quotes_runtime','OFF'),
array ('Register Globals','register_globals','OFF'),
array ('Output Buffering','output_buffering','OFF'),
array ('Session auto start','session.auto_start','OFF'),
);
foreach ($php_recommended_settings as $phprec) {
?>
<tr>
  <td class="item"><?php echo $phprec[0]; ?>:</td>
  <td class="toggle"><?php echo $phprec[2]; ?>:</td>
  <td>
  <?php
  if ( get_php_setting($phprec[1]) == $phprec[2] ) {
  ?>
    <font color="green"><b>
  <?php
  } else {
  ?>
    <font color="red"><b>
  <?php
  }
  echo get_php_setting($phprec[1]);
  ?>
  </b></font>
  <td>
</tr>
<?php
}
?>
</table>
</div>
</div>
<div class="clr"></div>
<h1>Directory and File Permissions:</h1>
<div class="install-text">
In order for Adzan to function correctly it needs to be able to access or write to certain files or directories. If you see "Unwriteable" you need to change the permissions on the file or directory to allow Adzan to write to it.
<div class="clr">&nbsp;&nbsp;</div>
<div class="ctr"></div>
</div>
<div class="install-form">
<div class="form-block">
<table class="content">
<?php
$dirC="../data";
$countries=array();
if(is_dir($dirC)){
  if($dhC=opendir($dirC)){
    $i=0;
    while(($country=readdir($dhC)) !== false){
      if ($country !== '.' AND $country !== '..') {
        if (preg_match("/\.txt$/i", $country)){
          $countries[$i]=substr($country,0,-4);
          $i++;
        }
      }
    }
    closedir($dhC);
  }
}
sort($countries);
writableCell( 'data' );
for($i=0;$i<count($countries);$i++){
  writableCell( 'data/'.$countries[$i].'.txt' );
}
?>
</table>
</div>
<div class="clr"></div>
</div>
<div class="clr"></div>
</div>
<?php include "install.footer.php"; ?>