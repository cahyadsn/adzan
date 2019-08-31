<?php
/*
BISMILLAAHIRRAHMAANIRRAHIIM - In the Name of Allah, Most Gracious, Most Merciful
================================================================================
filename	: 
purpose	: 
create	: 2006/03/22
last edit	: 060323,070430,1218,080810,120118
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
include "../class/class.config.php";
class fiqh extends config{
  function fiqh($path=""){
    $this->get_config($path);
  }

  function form($msg=""){
    echo ($msg?"<div class=\"form-block\"><b class=\"error\">$msg</b></div>\n":"")
        ."<h1>"._FIQHOPTION."</h1>\n"
        ."<form action=\"fiqh.php?cmd=save\" method=\"post\" name=\"adminForm\" id=\"adminForm\">\n"        
        ."<div class=\"install-text\">\n"
        ."<p>"._FIQHNOTES."</p>\n"
        ."</div>\n"
        ."<div class=\"install-form\">\n"
        ."<div class=\"form-block\">\n"
        ."<table class=\"content2\">\n"
        ."<tr>\n"
        ."<td>\n"
        ."<div style=\"display:inline\">\n"
        ._FIQHMETHOD
        ."</div>\n"
        ."<div style=\"display:inline\">\n"
        ."<select class=\"inputbox\" name=\"method\" onChange=\"javascript:change_method();\">\n";
    for($i=0;$i<count($this->cfgMethod);$i++){
      echo "<option value=\"".$i."\">".$this->cfgMethod[$i]."</option>\n";
    }
    echo "</select>\n"
        ."</div>\n"
        ."</td>\n"
        ."</tr>\n"         
        ."<tr>\n"
        ."<td>\n"
        ._OBSERVERHEIGHT." <input type=\"text\" name=\"observe_height\" size=\"5\" value=\"".($this->cfgArray['observe_height']?$this->cfgArray['observe_height']:"0")."\" class=\"inputNumber\" /> "._METERS
        ."</td>\n"
        ."</tr>\n"
        ."<tr>\n"
        ."<td>\n"
        ."<fieldset>\n"
        ."<legend>"._DETERMINATIONOFFAJR."</legend>\n"
        ."<input type=\"Radio\" name=\"fajr\" value=\"0\"".($this->cfgArray['fajr']=="0"?" checked":"")." /><input type=\"Text\" name=\"fajr_depr\" value=\"".$this->cfgArray['fajr_depr']."\" size=\"3\" class=\"inputNumber\" /> deg. "._SUNDEPRESSION."<br />\n"
        ."<input type=\"Radio\" name=\"fajr\" value=\"1\"".($this->cfgArray['fajr']=="1"?" checked":"")." /><input type=\"Text\" name=\"fajr_interval\" value=\"".$this->cfgArray['fajr_interval']."\" size=\"3\" class=\"inputNumber\" /> min. "._INTERVALFROMSUNRISE."\n"
        ."</fieldset>\n"
        ."</td>\n"
        ."</tr>\n"
        ."<tr>\n"
        ."<td>\n"
        ."<fieldset>\n"
        ."<legend>"._DETERMINATIONOFASR."</legend>\n"
        ."<input type=\"Radio\" name=\"ashr\" value=\"0\"".($this->cfgArray['ashr']=="0"?" checked":"")." /> "._SHAFIIANDOTHERS."<br />\n"
        ."<input type=\"Radio\" name=\"ashr\" value=\"1\"".($this->cfgArray['ashr']=="1"?" checked":"")." /> Hanafi<br />\n"
        ."<input type=\"Radio\" name=\"ashr\" value=\"2\"".($this->cfgArray['ashr']=="2"?" checked":"")." /> "._SHADOWRATIO." <input type=\"Text\" name=\"ashr_shadow\" value=\"".$this->cfgArray['ashr_shadow']."\" size=\"3\" class=\"inputNumber\" /><br />\n"
        ."</fieldset>\n"
        ."</td>\n"
        ."</tr>\n"
        ."<tr>\n"
        ."<td>\n"
        ."<fieldset>\n"
        ."<legend>"._DETERMINATIONOFISHA."</legend>\n"
        ."<input type=\"Radio\" name=\"isha\" value=\"0\"".($this->cfgArray['isha']=="0"?" checked":"")." /><input type=\"Text\" name=\"isha_depr\" value=\"".$this->cfgArray['isha_depr']."\" size=\"3\" class=\"inputNumber\" /> deg. "._SUNDEPRESSION."<br />\n"
        ."<input type=\"Radio\" name=\"isha\" value=\"1\"".($this->cfgArray['isha']=="1"?" checked":"")." /><input type=\"Text\" name=\"isha_interval\" value=\"".$this->cfgArray['isha_interval']."\" size=\"3\" class=\"inputNumber\" /> min. "._INTERVALFROMSUNSET."\n"
        ."</fieldset>\n"
        ."</td>\n"
        ."</tr>\n"
        ."<tr>\n"
        ."<td>\n"
        ."<fieldset>\n"
        ."<legend>"._DETERMINATIONOFIMSYAK."</legend>\n"
        ."<input type=\"Radio\" name=\"imsyak\" value=\"0\"".($this->cfgArray['imsyak']=="0"?" checked":"")." /><input type=\"Text\" name=\"imsyak_depr\" value=\"".$this->cfgArray['imsyak_depr']."\" size=\"3\" class=\"inputNumber\" /> deg. "._IMSYAKDEPRESSION."<br />\n"
        ."<input type=\"Radio\" name=\"imsyak\" value=\"1\"".($this->cfgArray['imsyak']=="1"?" checked":"")." /><input type=\"Text\" name=\"imsyak_interval\" value=\"".$this->cfgArray['imsyak_interval']."\" size=\"3\" class=\"inputNumber\" /> min. "._INTERVALFROMFAJR."\n"
        ."</fieldset>\n"
        ."</td>\n"
        ."</tr>\n"
        ."<tr>\n"
        ."<td>\n"
        ."<fieldset>\n"
        ."<legend>"._DETERMINATIONOFIHTIYAT."</legend>\n"
        ._IHTIYATFAJR." <input type=\"Text\" name=\"ihtiyat_fajr\" value=\"".$this->cfgArray['ihtiyat_fajr']."\" size=\"2\" class=\"inputNumber\" /> "._MINUTES."<br />"
        ._IHTIYATDZUHR." <input type=\"Text\" name=\"ihtiyat_dzuhr\" value=\"".$this->cfgArray['ihtiyat_dzuhr']."\" size=\"2\" class=\"inputNumber\" /> "._MINUTES."<br />"
        ._IHTIYATASHR." <input type=\"Text\" name=\"ihtiyat_ashr\" value=\"".$this->cfgArray['ihtiyat_ashr']."\" size=\"2\" class=\"inputNumber\" /> "._MINUTES."<br />"
        ._IHTIYATMAGHRIB." <input type=\"Text\" name=\"ihtiyat_maghrib\" value=\"".$this->cfgArray['ihtiyat_maghrib']."\" size=\"2\" class=\"inputNumber\" /> "._MINUTES."<br />"
        ._IHTIYATISHA." <input type=\"Text\" name=\"ihtiyat_isha\" value=\"".$this->cfgArray['ihtiyat_isha']."\" size=\"2\" class=\"inputNumber\" /> "._MINUTES."<br />"
        ."</fieldset>\n"
        ."</td>\n"
        ."</tr>\n"
        ."</table>\n"
        ."</div>\n"
        ."</div>\n"
        ."<div class=\"clr\"></div>\n"
        ."<div id=\"break2\"></div>\n"
        ."<div class=\"far-right\">\n"
        ."<input class=\"button\" type=\"submit\" name=\"save\" value=\""._SAVE."\"/>\n"
        ."<input class=\"button\" type=\"reset\" name=\"cancel\" value=\""._CANCEL."\"/>\n"
        ."</div>\n"
        ."</form>\n";   
  }

  function save($path){
    $this->cfgArray['observe_height']=$_POST['observe_height'];
    $this->cfgArray['fajr_depr']=$_POST['fajr_depr'];
    $this->cfgArray['fajr']=$_POST['fajr'];
    $this->cfgArray['fajr_interval']=$_POST['fajr_interval'];
    $this->cfgArray['ashr']=$_POST['ashr'];
    $this->cfgArray['ashr_shadow']=$_POST['ashr_shadow'];
    $this->cfgArray['isha_depr']=$_POST['isha_depr'];
    $this->cfgArray['isha']=$_POST['isha'];
    $this->cfgArray['isha_interval']=$_POST['isha_interval'];
    $this->cfgArray['imsyak_depr']=$_POST['imsyak_depr'];
    $this->cfgArray['ihtiyat_fajr']=$_POST['ihtiyat_fajr'];
    $this->cfgArray['ihtiyat_dzuhr']=$_POST['ihtiyat_dzuhr'];
    $this->cfgArray['ihtiyat_ashr']=$_POST['ihtiyat_ashr'];
    $this->cfgArray['ihtiyat_maghrib']=$_POST['ihtiyat_maghrib'];
    $this->cfgArray['ihtiyat_isha']=$_POST['ihtiyat_isha'];
    $this->cfgArray['imsyak']=$_POST['imsyak'];
    $this->cfgArray['imsyak_interval']=$_POST['imsyak_interval'];
    if($this->write_config($path)){
      $msg=8;
    }else{
      $msg=9;
    }
    $msg=13;
    return $this->cfgMsg[$msg];
  }
}
?>