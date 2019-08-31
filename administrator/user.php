<?php
/*
BISMILLAAHIRRAHMAANIRRAHIIM - In the Name of Allah, Most Gracious, Most Merciful
================================================================================
filename	: 
purpose	: 
create	: 2006/04/24
last edit	: 060301,0317,070430,1218,120116
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
if (!$_SESSION['adzanAdmin']) {
  header("Location: index.php?msg=1");
  exit();
}
$page=isset($_GET['page'])?$_GET['page']:0;
$cmd=isset($_GET['cmd'])?$_GET['cmd']:"";
$id=isset($_GET['id'])?$_GET['id']:"";
include "../class/class.user.php";
$admin=new user();
$menu=1;
$script=2;
include "admin.header.php";
?>
   <div id="header-title"><?php echo _USERMANAGEMENT;?></div>
   <div class="clr"></div>
<?php
if($cmd=="save"){
  $msg=$admin->save();
}
$msg=isset($msg)?$msg:"";
$admin->form($msg);
include "admin.footer.php"; 
?>