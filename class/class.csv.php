<?php
/*
BISMILLAAHIRRAHMAANIRRAHIIM - In the Name of Allah, Most Gracious, Most Merciful
================================================================================
filename	: 
purpose	: 
create	: 2006/03/09
last edit	: 060310,060317,060323,120118
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
class csv extends adzan{
  var $field_terminator,$field_mark,$line_terminator,$field_name;

  function xml($dataCityFile,$id="",$pathCfg="",$pathChk){
    if(isset($_POST)){
      $this->set_fiqh_parameter_post();
      $this->set_ihtiyat_post();
    }   
    parent::adzan($dataCityFile,$id,$pathCfg,$pathChk);
  }

  function set_parameter($field_terminator,$field_mark,$line_terminator,$field_name=""){
    $this->field_terminator=stripslashes($field_terminator);
    $this->field_mark=stripslashes($field_mark);
    $s=stripslashes($line_terminator);
    $s=str_replace("\\n","\n",$s);
    $s=str_replace("\\r","\r",$s);
    $this->line_terminator=$s;
    $this->field_name=stripslashes($field_name);
  }

  function generate_csv($day,$mon,$year){
    $maxdom = ($day<1)?$this->month_age($mon,$year):1;
    $d =$this->date2doy(($day<1)?1:$day,$mon,$year);
    $content="";
    for($j=0;$j<$maxdom;$j++){
      $dd=($day<1)?$this->change_format($j+1):$this->change_format($day);
      $content.=$this->field_mark.$year.$this->field_mark.$this->field_terminator
          .$this->field_mark.$this->change_format($mon).$this->field_mark.$this->field_terminator
          .$this->field_mark.$dd.$this->field_mark.$this->field_terminator
          .($this->cfgArray['viewImsyak']?$this->field_mark.$this->h2hms($this->prayTime[$d-1][0]).$this->field_mark.$this->field_terminator:"")
          .$this->field_mark.$this->h2hms($this->prayTime[$d-1][1]).$this->field_mark.$this->field_terminator
          .($this->cfgArray['viewSunrise']?$this->field_mark.$this->h2hms($this->prayTime[$d-1][2]).$this->field_mark.$this->field_terminator:"")
          .$this->field_mark.$this->h2hms($this->prayTime[$d-1][3]).$this->field_mark.$this->field_terminator
          .$this->field_mark.$this->h2hms($this->prayTime[$d-1][4]).$this->field_mark.$this->field_terminator
          .$this->field_mark.$this->h2hms($this->prayTime[$d-1][5]).$this->field_mark.$this->field_terminator
          .$this->field_mark.$this->h2hms($this->prayTime[$d-1][6]).$this->field_mark.$this->line_terminator;
      $d++;
    }
    return $content;
  }

  function generate_data($type,$day,$mon,$year,$txtTime="",$path="") {
    parent::generate_data($type,$day,$mon,$year,$txtTime,$path);
    $content="";
    if($this->field_name){
      $content.=$this->field_mark."year".$this->field_mark.$this->field_terminator
               .$this->field_mark."month".$this->field_mark.$this->field_terminator
               .$this->field_mark."date".$this->field_mark.$this->field_terminator;
      for($i=0;$i<7;$i++){
        if(($i!=$this->limit1) AND ($i!=$this->limit2)){
          $content.=$this->field_mark.$txtTime[$i].$this->field_mark.($i<6?$this->field_terminator:$this->line_terminator);
        }  
      }
    }
    if($type==1){
      $content.=$this->generate_csv($day,$mon,$year);
    }else if($type==2){
      $content.=$this->generate_csv(0,$mon,$year);
    }else if($type==3){
      for($i=1; $i<=12; $i++){
        $content.=$this->generate_csv(0,$i,$year);
      }
    }
    return $content;
  }
}
?>