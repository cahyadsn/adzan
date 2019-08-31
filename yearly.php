<?php
/*
BISMILLAAHIRRAHMAANIRRAHIIM - In the Name of Allah, Most Gracious, Most Merciful
================================================================================
filename	: 
purpose	: 
create	: 2006/02/01
last edit	: 2006/03/01;2006/03/17,070430,120113
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
}

if (file_exists( 'installation/index.php' )) {
  define( '_INSTALL_CHECK', 1 );
  header("Location: offline.php");
  exit();
}
include "class/class.config.php";
$cfg=new config();
$algo=$cfg->cfgArray['algo']?$cfg->cfgArray['algo']:1;
include "class/class.html.php";
$dtFile="data/".$cfg->cfgArray['country'].".txt";
$Adzan=new html($dtFile,isset($_GET['id'])?$_GET['id']:0);
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
<meta name="generator" content="UltraEdit-32 Professional ver 14.20" />
<meta name="description" content="<?php echo $cfg->cfgArray['MetaDesc'];?>" />
<meta name="keywords" content="<?php echo $cfg->cfgArray['MetaKeys'];?>" />
<meta name="author" content="cahya dsn" />
<link rev="made" href="mailto:cahyadsn@yahoo.com" />
<link rev="website" href="http://www.cdsatrian.com" />
<link rel="shortcut icon" href="images/favicon.ico" />
<link rel="stylesheet" href="style/<?php echo $Adzan->cfgArray['style'];?>.css" type="text/css" />
</head>
<body>
<?php 
$Adzan->generate_data(3,$d,$m,$y,$cfg->cfgTime);
?>
</body>
</html>