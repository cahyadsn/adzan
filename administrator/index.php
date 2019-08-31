<?php
/*
BISMILLAAHIRRAHMAANIRRAHIIM - In the Name of Allah, Most Gracious, Most Merciful
================================================================================
filename	: 
purpose	: 
create	: 2006/02/24
last edit	: 060301,0317,0322,070430,120116
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
if (file_exists( '../installation/index.php' )) {
  define( '_INSTALL_CHECK', 1 );
  header("Location:../offline.php");
  exit();
}
include("../config.php");
$_SESSION['shortname']=$adzanCfg_title;
$_SESSION['version']=$adzanCfg_version;
include("../class/class.user.php");
$admin=new user($adzanCfg_user,$adzanCfg_secret);
if(isset($_GET['logout'])==1){
  $admin->logout();
}else{
  $msgCode=isset($_GET['msg'])?$_GET['msg']:NULL;
  if(!isset($msgCode)){
    if (!isset($_SESSION['adzanAdmin'])||((isset($_SESSION['adzanAdmin']) && $_SESSION['adzanAdmin']!=1))) {
      $auth=($admin->authentication(isset($_POST['txtAdminUser'])?$_POST['txtAdminUser']:"",isset($_POST['txtAdminPassword'])?$_POST['txtAdminPassword']:"")==1)?1:0;
      if(!$auth){
        $msgCode=2;
      }
    }
  }
}
$script=1;
include "admin.header.php";
?>
   <div id="header-title">Admin Login</div>
   <div class="clr"></div>
     <div class="content-form">
        <div class="form-block" align="center">
        <table class="content2"> 
<?php
if(!isset($_SESSION['adzanAdmin']) || (isset($_SESSION['adzanAdmin']) && $_SESSION['adzanAdmin']!=1)||(isset($_GET['logout']) && $_GET['logout']==1)){
  if(isset($msgCode)){
    session_destroy();
?>
        <tr>
          <td colspan="2" align="center" class="navigasi">
          <b class="error"><?php echo $admin->cfgMsg[$msgCode];?></b>
          </td>
        </tr>
<?php
}
?>
        <tr>
          <td align="right" width="35%"><?php echo _USERNAME;?></td>
          <td><input type="text" name="txtAdminUser" class="inputbox" /></td>
        </tr>
        <tr>
          <td align="right"><?php echo _PASSWORD;?></td>
          <td><input type="password" name="txtAdminPassword" class="inputbox" /></td>
        </tr>
        <tr>
          <td colspan="2" align="center" class="navigasi">
          <input type="submit" name="login" value="login" class="button" />
          </td>
        </tr>
<?php
}else{
   echo "<tr><td align=\"center\">"._WELCOME." ver. ".$_SESSION['version']."</td></tr>\n";
}
?>
        </table>
        <br />
        view <b><a href="../index.php" target="_blank">Adzan!</a></b>
        </div>
      </div>
<?php include "admin.footer.php"; ?>