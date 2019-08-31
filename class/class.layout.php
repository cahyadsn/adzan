<?php
/*
BISMILLAAHIRRAHMAANIRRAHIIM - In the Name of Allah, Most Gracious, Most Merciful
================================================================================
filename	: class.layout.php
purpose	: 
create	: 2006/03/22
last edit	: 060322,060328,070430,110621,120116
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
class layout extends config{
  function layout($path=""){
    $this->get_config($path);
  }

  function form($msg=""){
  	include "common.inc.php";
    echo ($msg?"<div class=\"form-block\"><b class=\"error\">$msg</b></div>\n":"")
        ."<h1>"._ADZANLAYOUT."</h1>\n"
        ."<script language=\"JavaScript\">\n"
        ."function changeCountry(){\n"
        ."  var f=document.form;\n"
        ."  location.href='".basename($_SERVER['PHP_SELF'])."?c='+f.adzanCountry.value;\n"
        ."}\n"
        ."</script>\n"
        ."<form action=\"layout.php?cmd=save\" method=\"post\" name=\"form\" id=\"form\">\n"
        ."<div class=\"install-text\">\n"
        ."<p>"._ADZANLAYOUTNOTES."<br />\n"
        ."<br />\n"
        ._ADZANVIEWNOTES."</p>\n"
        ."</div>\n"
        ."<div class=\"install-form\">\n"
        ."<div class=\"form-block\">\n"
        ."<table class=\"content2\">\n"
        ."<tr>\n"
        ."<td width=\"100\">"._PERIOD."</td>\n"
        ."<td align=\"center\">\n"
        ."<select class=\"inputbox\" name=\"adzanPeriod\">\n";
    for($i=0;$i<count($this->cfgPeriod);$i++){
      echo "<option value=\"".($i+1)."\"".($this->cfgArray['period']==($i+1)?" selected":"").">".$this->cfgPeriod[$i]."</option>\n";
    }
    echo "</select>\n"
        ."</td>\n"
        ."</tr>\n"
        ."<tr>\n"
        ."<td>"._DEFAULTCOUNTRY."</td>\n"
        ."<td align=\"center\">\n"
        ."<select class=\"inputbox\" name=\"adzanCountry\" onChange=\"changeCountry();\">\n";
    $dirD="../data";
    $country=array();
    if(is_dir($dirD)){
      if($dh=opendir($dirD)){
        $i=0;
        while(($fileC=readdir($dh)) !== false){
          if ($fileC !== '.' AND $fileC !== '..') {
            if (preg_match("/\.txt$/i", $fileC)){
              $country[$i]=substr($fileC,0,-4);
              $i++;
            }
          }
        }
        closedir($dh);
      }
    }
    sort($country);
    $defCountry=isset($_GET['c'])?$_GET['c']:$this->cfgArray['country'];
    for($j=0;$j<$i;$j++){
      echo "<option value=\"".$country[$j]."\"".($country[$j]==$defCountry?" selected":"").">".ucwords(strtolower($country[$j]))."</option>\n";
    }
    echo "</select>\n"
        ."</td>\n"
        ."</tr>\n"
        ."<tr>\n"
        ."<td>"._DEFAULTCITY."</td>\n"
        ."<td align=\"center\">\n"
        ."<select class=\"inputbox\" name=\"adzanCity\">\n";
    $filecity="../data/".$defCountry.".txt";
    $content = file($filecity);
    $cityCount = count($content);
    for($i=0;$i<$cityCount;$i++){
      $dtCity[$i]=explode("!",$content[$i]);
      $dtCityName[$i]=ucwords(strtolower($dtCity[$i][1]));
      $dtCityNo[$i]=$i;
    }
    array_multisort($dtCityName,SORT_ASC, SORT_STRING,$dtCityNo);
    $defCity=isset($_GET['c'])?0:$this->cfgArray['city'];
    for($i=0;$i<$cityCount;$i++){
      echo "<option value=\"".($dtCityNo[$i]+1)."\"".(($dtCityNo[$i]+1)==$defCity?" selected":"").">".ucwords(strtolower($dtCityName[$i]))."</option>\n";
    }
    echo "</select>\n"
        ."</td>\n"
        ."</tr>\n"
        ."<tr>\n"
        ."<td>"._LANGUAGE."</td>\n"
        ."<td align=\"center\">\n"
        ."<select class=\"inputbox\" name=\"language\">\n";
    $dir="../lang";
    $lang=array();
    if(is_dir($dir)){
      if($dh=opendir($dir)){
        $i=0;
        while(($file=readdir($dh)) !== false){
          if ($file !== '.' AND $file !== '..') {
            if (preg_match("/\.lang.php$/i", $file)){
              $lang[$i]=substr($file,0,-9);
              $i++;
            }
          }
        }
        closedir($dh);
      }
    }
    sort($lang);
    for($j=0;$j<$i;$j++){
      echo "<option value=\"".$lang[$j]."\"".($lang[$j]==$this->cfgArray['lang']?" selected":"").">".ucwords(strtolower($lang[$j]))."</option>\n";
    }
    echo "</select>\n"
        ."</td>\n"
        ."</tr>\n"
        ."<tr>\n"
        ."<td>"._STYLE."</td>\n"
        ."<td align=\"center\">\n"
        ."<select class=\"inputbox\" name=\"style\">\n";
    $dirS="../style";
    $styles=array();
    if(is_dir($dirS)){
      if($dhS=opendir($dirS)){
        $i=0;
        while(($fileS=readdir($dhS)) !== false){
          if ($fileS !== '.' AND $fileS !== '..') {
            if (preg_match("/\.css$/i", $fileS)){
              $styles[$i]=substr($fileS,0,-4);
              $i++;
            }
          }
        }
        closedir($dhS);
      }
    }
    sort($styles);
    for($j=0;$j<$i;$j++){
      echo "<option value=\"".$styles[$j]."\"".($styles[$j]==$this->cfgArray['style']?" selected":"").">".ucwords(strtolower($styles[$j]))."</option>\n";
    }
    echo "</select>\n"
        ."</td>\n"
        ."</tr>\n"
        ."<tr>\n"
        ."<td>"._ALGORITHM."</td>\n"
        ."<td align=\"center\">\n"
        ."<select class=\"inputbox\" name=\"algo\">\n";
    $dirA="../class";
    $algos=array();
    if(is_dir($dirA)){
      if($dhA=opendir($dirA)){
        $i=0;
        while(($fileA=readdir($dhA)) !== false){
          if ($fileA !== '.' AND $fileA !== '..') {
            if (preg_match("/^class\.adzan\.[1-9]/i", $fileA)){
                $algos[$i]=substr($fileA,12,1);
                $i++;
            }
          }
        }
        closedir($dhA);
      }
    }
    sort($algos);
    for($j=0;$j<$i;$j++){
      echo "<option value=\"".$algos[$j]."\"".($algos[$j]==$this->cfgArray['algo']?" selected":"").">".ucwords(strtolower($algos[$j].". ".$algo_name[$j]))."</option>\n";
    }
    echo "</select>\n"
        ."</td>\n"
        ."</tr>\n"        
        ."<tr>\n"        
        ."<td>Time Adjust</td>\n"
        ."<td><input type=\"text\" value=\"".($this->cfgArray['time_adjust']==''?"0":$this->cfgArray['time_adjust'])."\" name=\"txtTimeAdjust\" class=\"inputNumber\" size=\"6\"/> ( -12.0 ... 0.0 ... 12.0)</td>\n"       
        ."</tr>\n"
        ."<tr>\n"        
        ."<td colspan=\"2\">\n"
        ."<table>\n"
        ."<tr valign=\"top\">\n"
        ."<td width=\"50%\">\n"
        ."<input type=\"checkbox\" value=\"1\" name=\"cbxViewParam\"".($this->cfgArray['viewParam']==''?"":" checked")." />"._SHOWPARAM."<br />\n"
        ."<input type=\"checkbox\" value=\"1\" name=\"cbxViewQibla\"".($this->cfgArray['viewQibla']==''?"":" checked")." />"._SHOWQIBLA."<br />\n"
        ."<input type=\"checkbox\" value=\"1\" name=\"cbxViewFiqh\"".($this->cfgArray['viewFiqh']==''?"":" checked")." />"._SHOWFIQH."<br />\n"
        ."</td>\n"
        ."<td width=\"50%\">\n"
        ."<input type=\"checkbox\" value=\"1\" name=\"cbxViewSecond\"".($this->cfgArray['viewSecond']==''?"":" checked")." />"._SHOWSECOND."<br />\n"
        ."<input type=\"checkbox\" value=\"1\" name=\"cbxViewImsyak\"".($this->cfgArray['viewImsyak']==''?"":" checked")." />"._SHOWIMSYAK."<br />\n"
        ."<input type=\"checkbox\" value=\"1\" name=\"cbxViewSunrise\"".($this->cfgArray['viewSunrise']==''?"":" checked")." />"._SHOWSUNRISE."<br />\n"
        ."</td>\n"        
        ."</tr>\n"
        ."</table>\n"
        ."</td>\n"        
        ."</tr>\n"
        ."</table>\n"
        ."</div>\n"
        ."</div>\n"
        ."<div class=\"clr\"></div>\n"
        ."<div id=\"break\"></div>\n"
        ."<div class=\"far-right\">\n"
        ."<input class=\"button\" type=\"submit\" name=\"save\" value=\""._SAVE."\"/>\n"
        ."<input class=\"button\" type=\"reset\" name=\"cancel\" value=\""._CANCEL."\"/>\n"
        ."</div>\n"
        ."</form>\n";
  }

  function save($path){
    $this->cfgArray['lang']=$_POST['language'];
    $this->cfgArray['viewParam']=$_POST['cbxViewParam'];
    $this->cfgArray['viewQibla']=$_POST['cbxViewQibla'];
    $this->cfgArray['viewFiqh']=$_POST['cbxViewFiqh'];
    $this->cfgArray['viewImsyak']=$_POST['cbxViewImsyak'];
    $this->cfgArray['viewSunrise']=$_POST['cbxViewSunrise'];
    $this->cfgArray['viewSecond']=$_POST['cbxViewSecond'];
    $this->cfgArray['period']=$_POST['adzanPeriod'];
    $this->cfgArray['style']=$_POST['style'];
    $this->cfgArray['algo']=$_POST['algo'];
    $this->cfgArray['country']=$_POST['adzanCountry'];
    $this->cfgArray['city']=$_POST['adzanCity'];
    $this->cfgArray['time_adjust']=$_POST['txtTimeAdjust'];
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