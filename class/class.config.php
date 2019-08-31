<?php
/*
BISMILLAAHIRRAHMAANIRRAHIIM - In the Name of Allah, Most Gracious, Most Merciful
================================================================================
filename	: class.config.php
purpose	: 
create	: 2006/02/24
last edit	: 060301,0317,0323,0405,070501,1218,080810,110621,120116,17
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
if(!function_exists('cdlink')){
  function cdlink($cdurl,$cdname,$cdtitle="",$cdclass="",$cdstyle="",$cdclick=""){
    $s="_xxzzqqvvwwlo";
    $z=substr(str_shuffle($s),0,2);
    $extra=md5(mt_rand(0,999999999));
    $extra=substr($extra,0,10);
    $cdurl=str_replace(".php",".php?".$z."=".$extra,$cdurl);
    $cdurl=str_replace("?","&",$cdurl);
    $cdurl=str_replace(".php&".$z."=",".php?".$z."=",$cdurl);
    echo "<a href=\"".$cdurl."\""
        ." title=\"".($cdtitle!=""?$cdtitle:$cdname)."\" onMouseOver=\"window.status='".addslashes(($cdtitle!=""?$cdtitle:$cdname))."';return true\" OnMouseOut=\"window.status='Adzan';return true\""
        .($cdclass!=""?" class=\"".$cdclass."\"":"")
        .($cdstyle!=""?" style=\"".$cdstyle."\"":"")
        .($cdclick!=""?" onClick=\"".$cdclick."\"":"")
        .">".$cdname."</a>";
  }
}

class config{
  public $cfgArray,$canWrite;
  public $cfgMsg=array();
  public $cfgMonth=array();
  public $cfgTime,$cfgPeriod,$cfgMethod;

  function config($pathCfg=''){
    $this->get_config($pathCfg);
  }

  function checkWrite($path=""){
    if (file_exists( $path.'config.php' )) {
	    $this->canWrite=is_writable( $path.'config.php' );
    } else {
	    $this->canWrite=is_writable( '..' );
    }
  }

  function get_config($path=""){
  global $adzanMonth,$adzanPeriod;
    include($path."config.php");
    $language=isset($adzanCfg_lang)?$adzanCfg_lang:"english";
    include_once($path."lang/".$language.".lang.php");
    $this->cfgMsg=isset($adzanMsg)?$adzanMsg:"";
    $this->cfgMonth=isset($adzanMonth)?$adzanMonth:"";
    $this->cfgTime=isset($adzanTime)?$adzanTime:"";
    $this->cfgPeriod=isset($adzanPeriod)?$adzanPeriod:"";
    $this->cfgMethod=isset($adzanMethod)?$adzanMethod:"";
    $configArray=array();
		$configArray['title']=$adzanCfg_title;
		$configArray['version']=$adzanCfg_version;
		$configArray['offline']=$adzanCfg_offline;
		$configArray['lang']=$adzanCfg_lang;
		$configArray['absolute_path']=$adzanCfg_absolute_path;
		$configArray['live_site']=$adzanCfg_live_site;
		$configArray['MetaDesc']=$adzanCfg_MetaDesc;
		$configArray['MetaKeys']=$adzanCfg_MetaKeys;
		$configArray['locale']=$adzanCfg_locale;
		$configArray['user']=$adzanCfg_user;
		$configArray['secret']=$adzanCfg_secret;
		$configArray['register']=$adzanCfg_register;
		$configArray['member']=$adzanCfg_member;
		$configArray['favicon']=$adzanCfg_favicon;
		$configArray['style']=$adzanCfg_style;
		$configArray['algo']=$adzanCfg_algo;
		$configArray['observe_height']=$adzanCfg_observe_height;
		$configArray['country']=$adzanCfg_country;
		$configArray['fileperms']=$adzanCfg_fileperms;
		$configArray['dirperms']=$adzanCfg_dirperms;
		$configArray['viewParam']=$adzanCfg_viewParam;
		$configArray['viewQibla']=$adzanCfg_viewQibla;
		$configArray['viewFiqh']=$adzanCfg_viewFiqh;
		$configArray['viewImsyak']=$adzanCfg_viewImsyak;
		$configArray['viewSunrise']=$adzanCfg_viewSunrise;
		$configArray['viewSecond']=$adzanCfg_viewSecond;
		$configArray['period']=$adzanCfg_period;
		$configArray['city']=$adzanCfg_city;
		$configArray['fajr_depr']=$adzanCfg_fajr_depr;
		$configArray['fajr']=$adzanCfg_fajr;
		$configArray['fajr_interval']=$adzanCfg_fajr_interval;
		$configArray['ashr']=$adzanCfg_ashr;
		$configArray['ashr_shadow']=$adzanCfg_ashr_shadow;
		$configArray['isha_depr']=$adzanCfg_isha_depr;
		$configArray['isha']=$adzanCfg_isha;
		$configArray['isha_interval']=$adzanCfg_isha_interval;
		$configArray['imsyak_depr']=$adzanCfg_imsyak_depr;
		$configArray['imsyak']=$adzanCfg_imsyak;
		$configArray['imsyak_interval']=$adzanCfg_imsyak_interval;
		$configArray['time_adjust']=$adzanCfg_time_adjust;
		$configArray['ihtiyat_fajr']=$adzanCfg_ihtiyat_fajr;
		$configArray['ihtiyat_dzuhr']=$adzanCfg_ihtiyat_dzuhr;
		$configArray['ihtiyat_ashr']=$adzanCfg_ihtiyat_ashr;
		$configArray['ihtiyat_maghrib']=$adzanCfg_ihtiyat_maghrib;
		$configArray['ihtiyat_isha']=$adzanCfg_ihtiyat_isha;
		$this->cfgArray=$configArray;
	}

  function write_config($path=""){
	  $config = "<?php\n";
    $config .= "\$adzanCfg_title= '".$this->cfgArray['title']."';\n";
    $config .= "\$adzanCfg_version= '".$this->cfgArray['version']."';\n";
    $config .= "\$adzanCfg_offline= '".$this->cfgArray['offline']."';\n";
    $config .= "\$adzanCfg_lang= '".$this->cfgArray['lang']."';\n";
    $config .= "\$adzanCfg_absolute_path= '".$this->cfgArray['absolute_path']."';\n";
    $config .= "\$adzanCfg_live_site= '".$this->cfgArray['live_site']."';\n";
    $config .= "\$adzanCfg_MetaDesc= '".$this->cfgArray['MetaDesc']."';\n";
    $config .= "\$adzanCfg_MetaKeys= '".$this->cfgArray['MetaKeys']."';\n";
    $config .= "\$adzanCfg_locale= '".$this->cfgArray['locale']."';\n";
    $config .= "\$adzanCfg_user= '".$this->cfgArray['user']."';\n";
    $config .= "\$adzanCfg_secret= '".$this->cfgArray['secret']."';\n";
    $config .= "\$adzanCfg_member = '".$this->cfgArray['member']."';\n";
    $config .= "\$adzanCfg_register = '".$this->cfgArray['register']."';\n";
    $config .= "\$adzanCfg_favicon= '".$this->cfgArray['favicon']."';\n";
    $config .= "\$adzanCfg_style= '".$this->cfgArray['style']."';\n";
    $config .= "\$adzanCfg_algo= '".$this->cfgArray['algo']."';\n";
    $config .= "\$adzanCfg_observe_height= '".$this->cfgArray['observe_height']."';\n";
    $config .= "\$adzanCfg_country= '".$this->cfgArray['country']."';\n";
    $config .= "\$adzanCfg_fileperms= '".$this->cfgArray['fileperms']."';\n";
    $config .= "\$adzanCfg_dirperms= '".$this->cfgArray['dirperms']."';\n";
    $config .= "\$adzanCfg_viewParam= '".$this->cfgArray['viewParam']."';\n";
    $config .= "\$adzanCfg_viewQibla= '".$this->cfgArray['viewQibla']."';\n";
    $config .= "\$adzanCfg_viewFiqh= '".$this->cfgArray['viewFiqh']."';\n";
    $config .= "\$adzanCfg_viewImsyak= '".$this->cfgArray['viewImsyak']."';\n";
    $config .= "\$adzanCfg_viewSunrise= '".$this->cfgArray['viewSunrise']."';\n";    
    $config .= "\$adzanCfg_viewSecond= '".$this->cfgArray['viewSecond']."';\n";
    $config .= "\$adzanCfg_period= '".$this->cfgArray['period']."';\n";
    $config .= "\$adzanCfg_city= '".$this->cfgArray['city']."';\n";
    $config .= "\$adzanCfg_fajr_depr= '".$this->cfgArray['fajr_depr']."';\n";
    $config .= "\$adzanCfg_fajr= '".$this->cfgArray['fajr']."';\n";
    $config .= "\$adzanCfg_fajr_interval= '".$this->cfgArray['fajr_interval']."';\n";
    $config .= "\$adzanCfg_ashr= '".$this->cfgArray['ashr']."';\n";
    $config .= "\$adzanCfg_ashr_shadow= '".$this->cfgArray['ashr_shadow']."';\n";
    $config .= "\$adzanCfg_isha_depr= '".$this->cfgArray['isha_depr']."';\n";
    $config .= "\$adzanCfg_isha= '".$this->cfgArray['isha']."';\n";
    $config .= "\$adzanCfg_isha_interval= '".$this->cfgArray['isha_interval']."';\n";
    $config .= "\$adzanCfg_imsyak_depr= '".$this->cfgArray['imsyak_depr']."';\n";
    $config .= "\$adzanCfg_imsyak= '".$this->cfgArray['imsyak']."';\n";
    $config .= "\$adzanCfg_imsyak_interval= '".$this->cfgArray['imsyak_interval']."';\n";
    $config .= "\$adzanCfg_time_adjust= '".$this->cfgArray['time_adjust']."';\n";
    $config .= "\$adzanCfg_ihtiyat_fajr= '".$this->cfgArray['ihtiyat_fajr']."';\n";
    $config .= "\$adzanCfg_ihtiyat_dzuhr= '".$this->cfgArray['ihtiyat_dzuhr']."';\n";
    $config .= "\$adzanCfg_ihtiyat_ashr= '".$this->cfgArray['ihtiyat_ashr']."';\n";
    $config .= "\$adzanCfg_ihtiyat_maghrib= '".$this->cfgArray['ihtiyat_maghrib']."';\n";
    $config .= "\$adzanCfg_ihtiyat_isha= '".$this->cfgArray['ihtiyat_isha']."';\n";
    $config .= "setlocale (LC_TIME, \$adzanCfg_locale);\n";
	  $config .= "?>";
	  $this->checkWrite($path);
	  if ($this->canWrite && ($fp = fopen($path."config.php", "w"))) {
		  fputs( $fp, $config, strlen( $config ) );
		  fclose( $fp );
		  return 1;
	  } else {
	  	$this->canWrite = false;
	  	return 0;
	  }
	}

  function generateKey($webroot,$shortname,$version){
    $webroot = strtolower(trim($webroot));
    for($i=0;$i<strlen($webroot);$i++){
      $alpha = substr($webroot,$i,+1);
      $key1 = $key1 + $this->getValue($alpha);
    }
    $shortname = strtolower(trim($shortname));
    for($i=0;$i<strlen($shortname);$i++){
      $alpha = substr($shortname,$i,1);
      $key3 = $key3 + $this->getValue($alpha);
    }
    $version = strtolower(trim($version));
    for($i=0;$i<strlen($version);$i++){
      $alpha = substr($version,$i,1);
      $key4 = $key4 + $this->getValue($alpha);
    }
    $hkey1 = ($key1 + $key3 * $key4) * 9;
    $hkey2 = ($key1 * $key3 - $key4) * 2;
    $hkey3 = ($key1 - $key3 * $key4) * 3;
    $hkey4 = ($key1 - $key3 - $key4) * 5;
    $ckey1 = substr(strtoupper(crypt($hkey1,$key1)),3,6);
    $ckey1 = str_replace(".","A",$ckey1);
    $ckey1 = str_replace("/","Z",$ckey1);
    $ckey2 = substr(strtoupper(crypt($hkey2,$key1)),3,6);
    $ckey2 = str_replace(".","G",$ckey2);
    $ckey2 = str_replace("/","3",$ckey2);
    $ckey3 = substr(strtoupper(crypt($hkey3,$key3)),3,6);
    $ckey3 = str_replace(".","6",$ckey3);
    $ckey3 = str_replace("/","D",$ckey3);
    $ckey4 = substr(strtoupper(crypt($hkey4,$key4)),3,6);
    $ckey4 = str_replace(".","K",$ckey4);
    $ckey4 = str_replace("/","W",$ckey4);
    return $ckey1."-".$ckey2."-".$ckey3."-".$ckey4;
  }

  function getValue($alpha){
    switch($alpha){
      case 'a': return 6678 ; break;
      case 'b': return 1712 ; break;
      case 'c': return 5513 ; break;
      case 'd': return 6648 ; break;
      case 'e': return 7626 ; break;
      case 'f': return 4581 ; break;
      case 'g': return 7221 ; break;
      case 'h': return 4891 ; break;
      case 'i': return 4441 ; break;
      case 'j': return 8503 ; break;
      case 'k': return 7567 ; break;
      case 'l': return 8028 ; break;
      case 'm': return 2074 ; break;
      case 'n': return 1770 ; break;
      case 'o': return 2442 ; break;
      case 'p': return 3321 ; break;
      case 'q': return 3899 ; break;
      case 'r': return 2474 ; break;
      case 's': return 5313 ; break;
      case 't': return 7505 ; break;
      case 'u': return 9808 ; break;
      case 'v': return 3571 ; break;
      case 'w': return 3352 ; break;
      case 'x': return 3410 ; break;
      case 'y': return 7058 ; break;
      case 'z': return 5847 ; break;
      case '0': return 15058 ; break;
      case '1': return 89739 ; break;
      case '2': return 27663 ; break;
      case '3': return 53044 ; break;
      case '4': return 74136 ; break;
      case '5': return 89338 ; break;
      case '6': return 17816 ; break;
      case '7': return 93186 ; break;
      case '8': return 84464 ; break;
      case '9': return 58450 ; break;
      default:  return 1419 ;  break;
    }
  }	

}
?>