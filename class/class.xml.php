<?php
/*
BISMILLAAHIRRAHMAANIRRAHIIM - In the Name of Allah, Most Gracious, Most Merciful
================================================================================
filename	: 
purpose	: 
create	: 2006/03/09
last edit	: 060310,17,23,29,0412,070430,120118
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
class xml extends adzan{
  function xml($dataCityFile,$id="",$pathCfg="",$pathChk=""){
    if(isset($_POST)){
      $this->set_fiqh_parameter_post();
      $this->set_ihtiyat_post();
    }    
    parent::adzan($dataCityFile,$id,$pathCfg,$pathChk);
  }

  function generate_xml($day,$mon,$year,$plain=""){
    $maxdom = ($day<1)?$this->month_age($mon,$year):1;
    $d =$this->date2doy(($day<1)?1:$day,$mon,$year);
    $second=isset($_POST['cbxViewSecond'])?$_POST['cbxViewSecond']:0;
    $content="";
    for($j=0;$j<$maxdom;$j++){
      $dd=($day<1)?$this->change_format($j+1):$this->change_format($day);
      $content.="  <data>\n"
          ."    <year>$year</year>\n"
          ."    <month>".$this->change_format($mon)."</month>\n"
          ."    <date>$dd</date>\n"
          .(($plain==1) ||($plain!=1 && isset($_POST['cbxViewImsyak']))?"    <imsyak>".$this->show_hours($this->prayTime[$d-1][0],$second)."</imsyak>\n":"")
          ."    <fajr>".$this->show_hours($this->prayTime[$d-1][1],$second)."</fajr>\n"
          .(($plain==1) ||($plain!=1 && isset($_POST['cbxViewSunrise']))?"    <syuruq>".$this->show_hours($this->prayTime[$d-1][2],$second)."</syuruq>\n":"")
          ."    <dzuhr>".$this->show_hours($this->prayTime[$d-1][3],$second)."</dzuhr>\n"
          ."    <ashr>".$this->show_hours($this->prayTime[$d-1][4],$second)."</ashr>\n"
          ."    <maghrib>".$this->show_hours($this->prayTime[$d-1][5],$second)."</maghrib>\n"
          ."    <isha>".$this->show_hours($this->prayTime[$d-1][6],$second)."</isha>\n"
          ."  </data>\n";
      $d++;
    }
    return $content;
  }

  function generate_data($type,$day,$mon,$year,$text="",$path="",$up="",$ajax="") {
    parent::generate_data($type,$day,$mon,$year,$text,$path); 
    $id=isset($_POST['adzanCity'])?$_POST['adzanCity']:(isset($_GET['id'])?$_GET['id']:$this->cfgArray['city']);
    list($qDirec,$qDistance)=$this->qibla($this->GEO_longitude,$this->GEO_latitude);
    $lat=$this->dtCity[$this->id][2]."&#0176;".$this->dtCity[$this->id][3]."&#0180;";
    $latsign=$this->dtCity[$this->id][4]?_S:_N;
    $long=$this->dtCity[$this->id][5]."&#0176;".$this->dtCity[$this->id][6]."&#0180;";
    $longsign=($this->dtCity[$this->id][7])?_E:_W;
    $content="";
    $content="<?xml version=\"1.0\" encoding=\"utf-8\"?".">\n"
        ."<!--\n"
        ."- Adzan XML Dump\n"
        ."- Generation Time: ".date('F d, Y \a\t h:i:s A')."\n"
        ."-->\n"
        ."<adzan>\n"
        ."  <version>".$this->cfgArray['version']."</version>\n"
        ."  <author>cahya dsn</author>\n"
        ."  <email>cahyadsn@yahoo.com</email>\n"
        ."  <site>www.cdsatrian.com</site>\n"
        ."  <country>".strtolower(isset($_POST['adzanCountry'])?$_POST['adzanCountry']:$this->cfgArray['country'])."</country>\n"
        ."  <city>".strtolower($this->dtCity[$id-1][1])."</city>\n";
    if(isset($_POST['cbxViewParam'])){     
      $content.="   <parameter>\n"
        ."    <longitude>$long $longsign</longitude>\n"
        ."    <latitude>$lat $latsign </latitude>\n"
        ."    <direction>".$qDirec." &#0176; "._TO." "._MECCA."</direction>\n"
        ."    <distance>".$qDistance." km "._TO." "._MECCA."</distance>\n"
        ."  </parameter>\n";
    }   
    $plain=isset($_POST['plain'])?$_POST['plain']:isset($_GET['plain'])?$_GET['plain']:0; 
    if($type==1){
      $content.=$this->generate_xml($day,$mon,$year,$plain);
    }else if($type==2){
      $content.=$this->generate_xml(0,$mon,$year,$plain);
    }else if($type==3){
      for($i=1; $i<=12; $i++){
        $content.=$this->generate_xml(0,$i,$year,$plain);
      }
    }
    $content.="</adzan>\n";
    return $content;
  }
}
?>