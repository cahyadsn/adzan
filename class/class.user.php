<?php
/*
BISMILLAAHIRRAHMAANIRRAHIIM - In the Name of Allah, Most Gracious, Most Merciful
================================================================================
filename	: 
purpose	: 
create	: 2006/02/24
last edit	: 2006/03/01,2006/03/17
author	: cahya dsn
================================================================================
This program is free software; you can redistribute it and/or modify it under the 
terms of the GNU General Public License as published by the Free Software 
Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY 
WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR 
A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

copyright (c) 2006 by cahya dsn; cahyadsn@yahoo.com
================================================================================
*/
include "../class/class.config.php";
class user extends config{

  function user($user="",$password=""){
    $this->get_config("../");
    if($user){
      if($password){
        $this->set_user($user,$password);
      }
    }
  }

  function set_user($user,$password){
    if(!isset($_SESSION['adzanUser'])){
      $_SESSION['adzanUser']=$user;
    }
    if(!isset($_SESSION['adzanPassword'])){
      $_SESSION['adzanPassword']=$password;
    }
  }

  function authentication($user,$password){
    if(($user==$_SESSION['adzanUser'])&&(md5(substr($user,0,3).$password.substr($user,3))==$_SESSION['adzanPassword'])){
      $_SESSION['adzanAdmin']=1;
      return 1;
    }else{
      $_SESSION['adzanAdmin']=0;
      return 0;
    }
  }

  function form($msg=""){
    echo ($msg?"<div class=\"form-block\"><b class=\"error\">$msg</b></div>\n":"")
        .($msg?"<div class=\"clr\">&nbsp;</div>\n":"")
        ."<script language=\"JavaScript\">\n"
        ."function checkCancel(){\n"
        ." if(confirm(\""._CANCEL_MSG."\")){\n"
        ."   window.location.href=\"".basename($_SERVER['PHP_SELF'])."\";\n"
        ." }\n"
        ."}\n"
        ."</script>\n"
        ."<div class=\"content-form\">\n"
        ."<div class=\"form-block\">\n"
        ."<table align=\"center\" width=\""._WIDTH."\" border=0 cellpadding=2 cellspacing=0 class=\"content2\">\n"
        ."<form method=\"post\" action=\"".basename($_SERVER['PHP_SELF'])."?cmd=save\" name=\"form\" id=\"form\" onsubmit=\"return check();\">\n"
        ."<tr>\n"
        ."<td colspan=\"2\" class=\"table-header\"><b>"._EDITNAME."</b></td>\n"
        ."</tr>\n"
        ."<tr class=\"table-dark\"><td valign=top width=40%>"._CURRENTNAME."</td><td><b>{$_SESSION['adzanUser']}</b></td></tr>\n"
        ."<tr class=\"table-light\"><td valign=top width=40%>"._NEWNAME."</td><td><input type=text name=\"txtNewName\" class=\"inputbox\" value=\"\"></td></tr>\n"
        ."<tr>\n"
        ."<td colspan=\"2\" class=\"table-header\"><b>"._EDITPASSWORD."</b></td>\n"
        ."</tr>\n"
        ."<tr class=\"table-dark\"><td valign=top width=40%>"._OLDPASSWORD."</td><td><input type=password name=\"txtOldPassword\" class=\"inputbox\" value=\"\"></td></tr>\n"
        ."<tr class=\"table-light\"><td valign=top width=40%>"._NEWPASSWORD."</td><td><input type=password name=\"txtNewPassword\" class=\"inputbox\" value=\"\"></td></tr>\n"
        ."<tr class=\"table-dark\"><td valign=top width=40%>"._CONFIRMPASSWORD."</td><td><input type=password name=\"txtConfirmPassword\" class=\"inputbox\" value=\"\"></td></tr>\n"
        ."<tr>\n"
        ."<td align=\"right\" colspan=\"2\" class=\"navigasi\">\n"
        ."<input type=\"submit\" class=\"button\" value=\""._SAVE."\" />\n"
        ."<input type=\"button\" class=\"button\" value=\""._CANCEL."\" onClick=\"checkCancel();\" />\n"
        ."</td>\n"
        ."</tr>\n"
        ."</form>\n"
        ."</table>\n"
        ."</div>\n"
        ."</div>\n";
  }

  function save(){
    $newuser=$_POST['txtNewName'];
    $oldpsw=$_POST['txtOldPassword'];
    $newpsw=$_POST['txtNewPassword'];
    $cfmpsw=$_POST['txtConfirmPassword'];
    $msg=13;
    if($newuser || $newpsw){
      if($newpsw){
        if($this->cfgArray['secret']!=md5(substr($this->cfgArray['user'],0,3).$oldpsw.substr($this->cfgArray['user'],3))){
          $msg=3;
        }else{
          if($newuser){
            $this->cfgArray['user']=$newuser;
            $this->cfgArray['secret']!=md5(substr($newuser,0,3).$newpsw.substr($newuser,3));
            $msg=4;
          }else{
            $this->cfgArray['secret']!=md5(substr($this->cfgArray['user'],0,3).$newpsw.substr($this->cfgArray['user'],3));
            $msg=5;
          }
          if($this->write_config()){
            $_SESSION['adzanUser']=$this->cfgArray['user'];
            $_SESSION['adzanPassword']=$this->cfgArray['secret'];
          }else{
            $msg=7;
          }
        }
      }else{
        $this->cfgArray['user']=$newuser;
        $this->cfgArray['secret']!=md5(substr($newuser,0,3).$oldpsw.substr($newuser,3));
        if($this->write_config()){
          $_SESSION['adzanUser']=$this->cfgArray['user'];
          $_SESSION['adzanPassword']=$this->cfgArray['secret'];
          $msg=6;
        }else{
          $msg=7;
        }
      }
    }
    return $this->cfgMsg[$msg];
  }

  function logout(){
    session_destroy();
  }
}
?>