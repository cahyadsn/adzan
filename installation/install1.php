<?php
/*
BISMILLAAHIRRAHMAANIRRAHIIM - In the Name of Allah, Most Gracious, Most Merciful
================================================================================
filename	: install1.php
purpose	: 
create	: 2006/02/27
last edit	: 060317,060323,1124,070430,1217,080901,110621,120116,0724
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
if (file_exists( '../config.php' ) && filesize( '../config.php' ) > 10) {
  header( "Location: ../index.php" );
  exit();
}
include("../class/class.city.php");
$defCountry=isset($_GET['c'])?$_GET['c']:"indonesia";
$dataFile="../data/".$defCountry.".txt";
$city=new city($dataFile,"../");
$f=array("install2.php","1");
include "install.header.php";
?>
    <div id="step">Step 1</div>
    <div class="far-right">
    	<input class="button" type="submit" name="next" value="Next &gt;&gt;"/>
    </div>
    <div class="clr"></div>
    <h1>Adzan layout</h1>
      <div class="install-text">
        <p>
          Set default view for Adzan period, city and language user interface.<br />
          <br />
          Set to show or hide Parameters, Qibla visualization, and Fiqh options. You can also set Time format on hh:mm:dd (default time format is hh:mm)<br />
          <br />
          Set Time Adjust for adjustment Server Time to related GMT/UTC
        </p>
      </div>
      <div class="install-form">
        <div class="form-block">
          <table class="content2">
          <tr>
            <td width="100">Period</td>
            <td align="center">
              <select class="inputbox" name="adzanPeriod">
              <option value="1">Daily</option>
              <option value="2">Monthly</option>
              <option value="3">Annualy</option>
              </select>
            </td>
          </tr>
          <tr>
            <td>Default Country</td>
            <td align="center">
              <select class="inputbox" name="adzanCountry" onChange="changeCountry();">
<?php
$dirC="../data";
$country=array();
if(is_dir($dirC)){
  if($dhC=opendir($dirC)){
    $i=0;
    while(($fileC=readdir($dhC)) !== false){
      if ($fileC !== '.' AND $fileC !== '..') {
        if (preg_match("/\.txt$/i", $fileC)){
          $country[$i]=substr($fileC,0,-4);
          $i++;
        }
      }
    }
    closedir($dhC);
  }
}
sort($country);
for($j=0;$j<$i;$j++){
  echo "<option value=\"".$country[$j]."\"".($country[$j]==$defCountry?" selected":"").">".ucwords(strtolower($country[$j]))."</option>\n";
}
?>
              </select>
            </td>
          </tr>
          <tr>
            <td>Default City</td>
            <td align="center">
              <select class="inputbox" name="adzanCity">
<?php
  for($i=0;$i<$city->cityCount;$i++){
    echo "<option value=\"".($city->dtCityNo[$i]+1)."\">".ucwords(strtolower($city->dtCityName[$i]))."</option>\n";

  }
?>
              </select>
            </td>
          </tr>
          <tr>
            <td>Language</td>
            <td align="center">
              <select class="inputbox" name="language">
<?php
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
  echo "<option value=\"".$lang[$j]."\">".ucwords(strtolower($lang[$j]))."</option>\n";
}
?>
              </select>
            </td>
          </tr>
          <tr>
            <td>Style</td>
            <td align="center">
              <select class="inputbox" name="style">
<?php
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
  echo "<option value=\"".$styles[$j]."\">".ucwords(strtolower($styles[$j]))."</option>\n";
}
?>
              </select>
            </td>
          </tr>
          <tr>
            <td>Algorithm</td>
            <td align="center">
              <select class="inputbox" name="algo">
<?php
    include_once("../administrator/common.inc.php");
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
  		echo "<option value=\"".$algos[$j]."\">".ucwords(strtolower($algos[$j].". ".$algo_name[$j]))."</option>\n";
		}
?>
              </select>
            </td>
          </tr>
          <tr>
            <td colspan="2">
              <input type="checkbox" value="1" name="cbxViewParam" />Show Parameters<br />
              <input type="checkbox" value="1" name="cbxViewQibla" />Show Qibla Visualization<br />
              <input type="checkbox" value="1" name="cbxViewFiqh" />Show Fiqh Options<br />
              <input type="checkbox" value="1" name="cbxViewImsyak" />Show Imsyak Time<br />
              <input type="checkbox" value="1" name="cbxViewSunrise" />Show Sunrise Time<br />
              <input type="checkbox" value="1" name="cbxViewSecond" />Show Time Format with Second<br />
            </td>
          </tr>
          <tr>
            <td>Time Adjust</td>
            <td><input type="text" class="inputNumber" name="txtTimeAdjust" value="0" size="6" />( -12.0 .. 0.0 .. 12.0)</td>
          </tr>
          </table>
        </div>
      </div>
    <div class="clr"></div>
    <div class="clr"></div>
    <h1>Fiqh Parameters</h1>
      <div class="install-text">
          <p>Set fiqh parameters with your suitable values. These values are depend on your opinion for Imsyak, Fajr, Ashr and Isha time.
          </p>
      </div>
      <div class="install-form">
        <div class="form-block">
          <table class="content2">
          <tr>
            <td>
              Fiqh Method
            </td>
            <td>
              <select class="inputbox" name="method" onChange="javascript:change_method();">
              <?php 
                $adzanMethod=array( "Custom",
                                    "Egyptian General Authority of Survey",
                                    "University of Islamic Sciences, Karachi (Shaf'i)",
                                    "University of Islamic Sciences, Karachi (Hanafi)",
                                    "Islamic Society of North America",
                                    "Muslim World League (MWL)",
                                    "Umm Al-Qurra, Saudi Arabia",
                                    "Fixed Ishaa Interval (always 90)");
              for($i=0;$i<count($adzanMethod);$i++){
                echo "<option value=\"".$i."\">".$adzanMethod[$i]."</option>\n";
              } ?>  
              </select>
            </td>
          </tr>            
          <tr>
            <td colspan="2">
            <fieldset>
            <legend>Determination of Fajr</legend>
            <input type="Radio" name="fajr" value="0" checked />
            <input type="Text" name="fajr_depr" value="20.0" size="3" class="inputNumber" /> deg. Sun's Depression<br />
            <input type="Radio" name="fajr" value="1" />
            <input type="Text" name="fajr_interval" value="90.0" size="3" class="inputNumber" /> min. Interval from Sunrise
            </fieldset>
            </td>
          </tr>
          <tr>
            <td colspan="2">
            <fieldset>
            <legend>Determination of Ashr</legend>
            <input type="Radio" name="ashr" value="0" checked /> Shafi'i and others<br />
            <input type="Radio" name="ashr" value="1" /> Hanafi<br />
            <input type="Radio" name="ashr" value="2" /> Shadow ratio 
            <input type="Text" name="ashr_shadow" value="1.0" size="3" class="inputNumber" /><br />
            </fieldset>
            </td>
          </tr>
          <tr>
            <td colspan="2">
            <fieldset>
            <legend>Determination of Isha</legend>
            <input type="Radio" name="isha" value="0" checked />
            <input type="Text" name="isha_depr" value="18.0" size="3" class="inputNumber" /> deg. Sun's Depression<br />
            <input type="Radio" name="isha" value="1" />
            <input type="Text" name="isha_interval" value="90.0" size="3" class="inputNumber" /> min. Interval from Sunset
            </fieldset>
            </td>
          </tr>
          <tr>
            <td colspan="2">
            <fieldset>
            <legend>Determination of Imsyak</legend>
            <input type="Radio" name="imsyak" value="0"/>
            <input type="Text" name="imsyak_depr" value="1.5" size="3" class="inputNumber" /> deg. Sun's Depression to Fajr<br />
            <input type="Radio" name="imsyak" value="1"  checked />
            <input type="Text" name="imsyak_interval" value="10.0" size="3" class="inputNumber" /> min. Interval from Fajr
            </fieldset>
            </td>
          </tr>          
          <tr>
            <td colspan="2">
            <fieldset>
            <legend>Determination of Ihtiyat Time</legend>
            Fajr Ihtiyat  <input type="Text" name="ihtiyat_fajr" value="6" size="3" class="inputNumber" /> minute(s)<br />
            Dzuhr Ihtiyat  <input type="Text" name="ihtiyat_dzuhr" value="6" size="3" class="inputNumber" /> minute(s)<br />
            Asr Ihtiyat  <input type="Text" name="ihtiyat_ashr" value="6" size="3" class="inputNumber" /> minute(s)<br />
            Maghrib Ihtiyat  <input type="Text" name="ihtiyat_maghrib" value="6" size="3" class="inputNumber" /> minute(s)<br />
            Isha Ihtiyat  <input type="Text" name="ihtiyat_isha" value="6" size="3" class="inputNumber" /> minute(s)<br />
            </fieldset>
            </td>
          </tr>
          </table>
        </div>
      </div>
      <div class="clr"></div>
    </div>
<?php include "install.footer.php"; ?>