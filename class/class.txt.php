<?php
/*
BISMILLAAHIRRAHMAANIRRAHIIM - In the Name of Allah, Most Gracious, Most Merciful
================================================================================
filename	: 
purpose	: 
create	: 2006/03/22
last edit	: 060323-24,29,120118
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
class txt extends adzan{
  var $field_terminator,$field_mark,$line_terminator,$field_name;

  function txt($dataCityFile,$id="",$pathCfg="",$pathChk=""){
    if(isset($_POST)){
      $this->set_fiqh_parameter_post();
      $this->set_ihtiyat_post();
    }
    parent::adzan($dataCityFile,$id,$pathCfg,$pathChk);
  }

  function set_parameter($field_name=""){
    $this->field_name=$field_name;
  }
  
  function generate_txt($day,$mon,$year,$txtTime,$plain=""){
  global $adzanMonth;  
    $maxdom = ($day<1)?$this->month_age($mon,$year):1;
    $d =$this->date2doy(($day<1)?1:$day,$mon,$year);
    $content="";
    if($plain!=1){
      $content=$adzanMonth[$mon-1]." ".$year."\n";
    }
    $z=$this->cfgArray['viewSecond']==1?8:5;
    for($i=0;$i<7;$i++){
      $q[$i]=0;
    }
    if($this->field_name!=""){
      $l=strlen(_DATE);
      $content.=str_pad(_DATE,($l>10?$l:10)," ",STR_PAD_LEFT)." ";
      for($i=0;$i<7;$i++){
        if((($i!=$this->limit1) AND ($i!=$this->limit2))||$plain==1){
          $q[$i]=strlen($txtTime[$i]);
          $content.=str_pad($txtTime[$i],($q[$i]>$z?$q[$i]:$z)," ",STR_PAD_LEFT).($i==6?"\n":" ");
        }  
      }
    }
    for($j=0;$j<$maxdom;$j++){
      $dd=($day<1)?$this->change_format($j+1):$this->change_format($day);
      $content.= str_pad($year." ".$this->change_format($mon)." ".$dd,(isset($l)>10?$l:10)," ",STR_PAD_LEFT)." "
                .($this->cfgArray['viewImsyak']||$plain==1? str_pad(($this->cfgArray['viewSecond']==1?$this->h2hms($this->prayTime[$d-1][0]):$this->h2hm($this->prayTime[$d-1][0])),($q[0]>$z?$q[0]:$z)," ",STR_PAD_LEFT)." ":"")
                .str_pad(($this->cfgArray['viewSecond']==1?$this->h2hms($this->prayTime[$d-1][1]):$this->h2hm($this->prayTime[$d-1][1])),($q[1]>$z?$q[1]:$z)," ",STR_PAD_LEFT)." "
                .($this->cfgArray['viewSunrise']||$plain==1?str_pad(($this->cfgArray['viewSecond']==1?$this->h2hms($this->prayTime[$d-1][2]):$this->h2hm($this->prayTime[$d-1][2])),($q[2]>$z?$q[2]:$z)," ",STR_PAD_LEFT)." ":"")
                .str_pad(($this->cfgArray['viewSecond']==1?$this->h2hms($this->prayTime[$d-1][3]):$this->h2hm($this->prayTime[$d-1][3])),($q[3]>$z?$q[3]:$z)," ",STR_PAD_LEFT)." "
                .str_pad(($this->cfgArray['viewSecond']==1?$this->h2hms($this->prayTime[$d-1][4]):$this->h2hm($this->prayTime[$d-1][4])),($q[4]>$z?$q[4]:$z)," ",STR_PAD_LEFT)." "
                .str_pad(($this->cfgArray['viewSecond']==1?$this->h2hms($this->prayTime[$d-1][5]):$this->h2hm($this->prayTime[$d-1][5])),($q[5]>$z?$q[5]:$z)," ",STR_PAD_LEFT)." "
                .str_pad(($this->cfgArray['viewSecond']==1?$this->h2hms($this->prayTime[$d-1][6]):$this->h2hm($this->prayTime[$d-1][6])),($q[6]>$z?$q[6]:$z)," ",STR_PAD_LEFT)."\n";
      $d++;
    }
    return $content."\n";
  }

  function unichr($dec) { 
 		if ($dec < 128) { 
   		$utf = chr($dec); 
 		} else if ($dec < 2048) { 
   		//$utf = chr(192 + (($dec - ($dec % 64)) / 64)); 
   		$utf = chr(128 + ($dec % 64)); 
 		} else { 
   		$utf = chr(224 + (($dec - ($dec % 4096)) / 4096)); 
   		$utf .= chr(128 + ((($dec % 4096) - ($dec % 64)) / 64)); 
   		$utf .= chr(128 + ($dec % 64)); 
 		} 
 		return $utf;
	}
	
  function generate_data($type,$day,$mon,$year,$txtTime="",$path="",$up="",$ajax="") {
    parent::generate_data($type,$day,$mon,$year,$txtTime,$path,$up,$ajax);
    $id=isset($_POST['adzanCity'])?$_POST['adzanCity']:(isset($_GET['id'])?$_GET['id']:$this->cfgArray['city']);
    list($qDirec,$qDistance)=$this->qibla($this->GEO_longitude,$this->GEO_latitude);
    $lat=$this->dtCity[$this->id][2].$this->unichr(176).$this->dtCity[$this->id][3].$this->unichr(39);
    $latsign=$this->dtCity[$this->id][4]?_S:_N;
    $long=$this->dtCity[$this->id][5].$this->unichr(176).$this->dtCity[$this->id][6].$this->unichr(39);
    $longsign=($this->dtCity[$id][7])?_E:_W;    
    $content="";
    $plain=isset($_GET['plain'])?$_GET['plain']:"";
    if($type==1){
      $content.=$this->generate_txt($day,$mon,$year,$txtTime,$plain);
    }else if($type==2){
      $content.=$this->generate_txt(0,$mon,$year,$txtTime,$plain);
    }else if($type==3){
      for($i=1; $i<=12; $i++){
        $content.=$this->generate_txt(0,$i,$year,$txtTime,$plain);
      }
    }
    $timezone = "UTC";
    if(function_exists('date_default_timezone_set')) date_default_timezone_set($timezone);
    if($plain!=1){
    	if(isset($_POST['cbxViewParam'])||isset($_GET['x'])){
    		$content.=_FORCITY." : ".ucwords(strtolower($this->dtCity[$id-1][1])).", $long$longsign  $lat$latsign \n"
    							._DIRECTION." : ".$qDirec." ".$this->unichr(176)." "._TO." "._MECCA." ,"._DISTANCE." : ".$qDistance." km "._TO." "._MECCA."\n";
    	}
      $content.="generated by ".$this->cfgArray['title']." ver ".$this->cfgArray['version']." at ".date("r")."\n";
    }  
    return $content;
  }
}
?>