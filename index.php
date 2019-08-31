<?php
/*
BISMILLAAHIRRAHMAANIRRAHIIM - In the Name of Allah, Most Gracious, Most Merciful
================================================================================
filename	: 
purpose	: 
create	: 080901
last edit	: 080901,120113
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
session_start();
$pathChk="";
if (!file_exists( 'config.php' ) || filesize( 'config.php' ) < 10) {
  $self = str_replace( '/index.php','', $_SERVER['PHP_SELF'] ). '/';
  header("Location: http://" . $_SERVER['HTTP_HOST'] . $self . "installation/index.php" );
  exit();
}else{
  include('config.php');
}

if (file_exists( 'installation/index.php' )) {
  define( '_INSTALL_CHECK', 1 );
  header("Location: offline.php");
  exit();
}
include "class/class.config.php";
$cfg=new config();
$cfg->get_config();
$algo=$cfg->cfgArray['algo']?$cfg->cfgArray['algo']:1;
$dtFile="data/".$cfg->cfgArray['country'].".txt";
$d=isset($_GET['d'])?$_GET['d']:date("j");
$m=isset($_GET['m'])?$_GET['m']:date("n");
$y=isset($_GET['y'])?$_GET['y']:date("Y");
echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?".">\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Adzan ver <?php echo $cfg->cfgArray['version'];?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta http-equiv="expires" content="0" />
<meta http-equiv="Pragma" content="no-cache" />
<meta name="generator" content="UltraEdit-32 Professional ver 17.00" />
<meta name="description" content="<?php echo $cfg->cfgArray['MetaDesc'];?>" />
<meta name="keywords" content="<?php echo $cfg->cfgArray['MetaKeys'];?>" />
<meta name="author" content="cahya dsn" />
<link rev="made" href="mailto:cahyadsn@yahoo.com" />
<link rev="website" href="http://www.cdsatrian.com" />
<link rel="shortcut icon" href="images/favicon.ico" />
<link rel="stylesheet" href="style/<?php echo $cfg->cfgArray['style'];?>.css" type="text/css" />
</head>
<body>
<table cellspacing="0" cellpadding="2" class="table_adzan" align="center">
  <tr>
    <td><div class="header1">Adzan v<?=$cfg->cfgArray['version']?></div></td>
  </tr>
<tr class="table_title">
<td align="center">
<a href="default.php">default</a> |
<a href="daily.php">daily</a> |
<a href="monthly.php">monthly</a> |
<a href="yearly.php">yearly</a> |
<a href="ajax.php">ajax</a> |
<a href="ajax.row.php">ajax.row</a> |
<a href="export.php">export</a> |
<a href="data.php">data</a> |
<a href="data.php?help=1">data.help</a> 
</td>
</tr>
  <tr>
    <td><div class="footer1">copyright 2003-<?=date("Y")?> by <a href="mailto:cahyadsn@yahoo.com">cahyadsn</a><br />
      <a href="source/adzan3.1.3.zip">download</a> | <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=K6YRM43CZ44UQ">donasi</a> | <a href="http://api.idhostinger.com/redir/73776">idhostinger</a></div></td>
  </tr>
</table>
</body>
</html>