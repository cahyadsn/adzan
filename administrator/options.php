<?php
/*
BISMILLAAHIRRAHMAANIRRAHIIM - In the Name of Allah, Most Gracious, Most Merciful
================================================================================
filename	: 
purpose	: 
create	: 2006/04/24
last edit	: 060301,0317,070430
author	: cahya dsn
================================================================================
This program is free software; you can redistribute it and/or modify it under the 
terms of the GNU General Public License as published by the Free Software 
Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY 
WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR 
A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

copyright (c) 2006-2007 by cahya dsn; cahyadsn@yahoo.com
================================================================================
*/
session_start();
if ($_SESSION['adzanAdmin']!=1) {
  header("Location: index.php?msg=1");
  exit();
}
$page=$_GET['page']?$_GET['page']:0;
$cmd=$_GET['cmd']?$_GET['cmd']:"";
$id=$_GET['id']?$_GET['id']:"";
include "../inc/class.options.php";
$admin=new options("../");
$menu=3;
echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?".">\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Adzan - Administrator</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="shortcut icon" href="../images/favicon.ico" />
<link rel="stylesheet" href="admin.css" type="text/css" />
</head>
<body>
<div id="ctr" align="center">
  <div class="header"><?php echo $_SESSION['shortname'];?> ver. <?php echo $_SESSION['version'];?></div>
  <div class="install">
  <div id="menubar">
  <?php include("menu.php");?>
  </div>
  <div id="right">
    <div id="header-title"><?php echo _OPTIONS;?></div>
    <div class="clr"></div>
<?php
if($cmd=="save"){
  $msg=$admin->save("../");
}
$admin->form($msg);
?>
    </div>
    </div>
    <div id="break"></div>
    <div class="clr"></div>
  </div>
  <div class="footer">
  <a href="http://www.cdsatrian.com" target="_blank">Adzan!</a> is Free Software released under the GNU/GPL License.
  </div>
</div>
</body>
</html>