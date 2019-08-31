<?php
/*
BISMILLAAHIRRAHMAANIRRAHIIM - In the Name of Allah, Most Gracious, Most Merciful
================================================================================
filename	: 
purpose	: 
create	: 2006/02/24
last edit	: 060301,0317,070430,120116
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
if ($_SESSION['adzanAdmin']!=1) {
  header("Location: index.php?msg=1");
  exit();
}
$s=isset($_GET['s'])?$_GET['s']:(isset($_POST['s'])?$_POST['s']:"");
$page=isset($_GET['page'])?$_GET['page']:0;
$cmd=isset($_GET['cmd'])?$_GET['cmd']:"";
$id=isset($_GET['id'])?$_GET['id']:"";
include "../config.php";
$dataFile="../data/".$adzanCfg_country.".txt";
include "../class/class.city.php";
$City=new city($dataFile,"../");
$menu=2;
include "admin.header.php";
?>
   <div id="header-title"><?php echo _CITYMANAGEMENT;?></div>
   <div class="clr"></div>
     <div class="content-form">
        <div class="form-block">
<?php
if($cmd=="add"){
  $City->form();
}elseif($cmd=="edit"){
  $City->form($id);
}elseif($cmd=="del"){
  $City->delete($id);
}elseif($cmd=="save"){
  if($id){
    $City->edit($id);
  }else{
    $City->add();
  }
}else{
  $City->view_list($page,10,$s);
}
?>
        </div>
      </div>
<?php include "admin.footer.php"; ?>