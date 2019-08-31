<?php
/*
BISMILLAAHIRRAHMAANIRRAHIIM - In the Name of Allah, Most Gracious, Most Merciful
================================================================================
filename	: 
purpose	: 
create	: 2006/03/09
last edit	: 060317,060323-24,070430,120113
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
$algo=$algo?$algo:1;
$pathChk=$pathChk?$pathChk:"";
include $pathChk."class/class.adzan.main.php";
include $pathChk."class/class.adzan.".$algo.".php";
class html extends adzan{

  function html($dataCityFile,$id="",$pathCfg="",$pathChk="",$up=""){
    parent::adzan($dataCityFile,$id,$pathCfg,$pathChk,$up);
  }

    function print_header($type,$mont,$year){
    echo "<tr class=\"table_title\">\n"
        ."<td colspan=\"".$this->column."\" align=\"center\">\n"
        ."<b>".$this->cfgMonth[$mont-1]." $year</b><br>\n"
        ."<small>("._FORCITY." <b>".ucwords(strtolower($this->dtCity[$this->id][1]))."</b> GMT "
        .($this->dtCity[$this->id][10]?"-":"+").$this->dtCity[$this->id][8].")</small>\n"
        ."</td>\n"
        ."</tr>\n";
  }

  function print_navigation($type,$day,$mont,$year){
    $prevMon=$mont-1;
    $prevYear=$year-1;
    $nextMon=$mont+1;
    $nextYear=$year+1;
    if($type==2){
      $prev=basename($_SERVER['PHP_SELF'])."?type=2&id=".($this->id+1)."&m=".($mont>1?"$prevMon&y=$year":"12&y=".$prevYear);
      $next=basename($_SERVER['PHP_SELF'])."?type=2&id=".($this->id+1)."&m=".($mont<12?"$nextMon&y=$year":"1&y=".$nextYear);
    }else if($type==3){
      $prev=basename($_SERVER['PHP_SELF'])."?type=3&y=".$prevYear."&id=".($this->id+1);
      $next=basename($_SERVER['PHP_SELF'])."?type=3&y=".$nextYear."&id=".($this->id+1);
    }
    if($type!=1){
    echo "<script language=\"javascript\">\n"
        ."function change_page(){\n"
        ."window.location=\"".basename($_SERVER['PHP_SELF'])."?id=\"+document.pilih.kota.value;\n"
        ."}\n"
        ."</script>\n"
        ."<form name=pilih>\n"
        ."<tr class=\"table_navigasi\"><td colspan=\"".$this->column."\">\n"
        ."<table cellspacing=\"0\" cellpading=\"2\" width=\"100%\">\n"
        ."<tr class=\"table_navigasi\"><td align=\"left\">&nbsp;";
      cdlink($prev,"<img src=\"images/left.gif\" border=\"0\"> "._PREV,_PREV,"m");
      echo "</td>\n<td align=\"center\">"._SELECTCITY."&nbsp;\n"
          ."<select name=kota onChange=\"change_page()\" class=\"inputcity\">\n";
      for($i=0;$i<$this->cityCount;$i++){
        echo "<option value=\"".($this->dtCityNo[$i]+1)."\"".($this->id==$this->dtCityNo[$i]?" selected":"").">".ucwords(strtolower($this->dtCityName[$i]))."</option>\n";
      }
      echo "</select>\n"
          ."</td>\n<td align=\"right\">";
      cdlink($next,_NEXT." <img src=\"images/right.gif\" border=\"0\">",_NEXT,"m");
      echo "&nbsp;</td>\n"
          ."</tr>\n"
          ."</table>\n"
          ."</td></tr>\n"
          ."</form>\n";
    }
  }

  function print_parameter($path=""){
    list($qDirec,$qDistance)=$this->qibla($this->GEO_longitude,$this->GEO_latitude);
    $lat=$this->dtCity[$this->id][2]."&deg;".$this->dtCity[$this->id][3]."'";
    $latsign=$this->dtCity[$this->id][4]?_S:_N;
    $long=$this->dtCity[$this->id][5]."&deg;".$this->dtCity[$this->id][6]."'";
    $longsign=($this->dtCity[$this->id][7])?_E:_W;
    if($this->cfgArray['viewParam']){
      echo "<tr class=\"table_block_title\"><td colspan=\"".$this->column."\"><b>&nbsp;:: Parameter</b></td></tr>\n"
          ."<tr align=\"left\" class=\"table_block_content\"><td colspan=\"".($this->cfgArray['viewQibla']==1?($this->column-2):$this->column)."\">"._FORCITY." <b>".$this->dtCity[$this->id][1]."</b> $lat $latsign $long $longsign "
          ."&nbsp;<a id=\"maplink\" href=\"http://maps.google.com/maps?q=".rad2deg($this->GEO_latitude).",+".rad2deg(-1*$this->GEO_longitude)."+(".ucwords(strtolower($this->dtCity[$this->id][1])).")&amp;iwloc=A&amp;hl=en&amp;z=12&amp;t=h\" target=\"newtab\">map</a></td>"
          .($this->cfgArray['viewQibla']==1?"<td rowspan=\"3\" colspan=\"2\" align=\"center\"><img src=\"".$path."images/circle.php?s=".$qDirec."\" alt=\"\" /></td>":"")."</tr>\n"
          ."<tr class=\"table_block_content\"><td align=\"right\" colspan=\"2\">"._DIRECTION." :</td><td colspan=\"".($this->cfgArray['viewQibla']==1?($this->column-4):($this->column-2))."\">".$qDirec." &deg; "._TO." "._MECCA."</td></tr>\n"
          ."<tr class=\"table_block_content\"><td align=\"right\" colspan=\"2\">"._DISTANCE." :</td><td colspan=\"".($this->cfgArray['viewQibla']==1?($this->column-4):($this->column-2))."\">".$qDistance." km "._TO." "._MECCA."</td></tr>\n";
    }
  }

  function print_fiqh($txtTime){
    $this->FIQH_asrShadowRasio=$this->cfgArray['ashr']?($this->cfgArray['ashr']==1?2:$this->cfgArray['ashr_shadow']):1;
    if($this->cfgArray['viewFiqh']){
      echo "<tr class=\"table_block_title\"><td colspan=\"".$this->column."\"><b>&nbsp;:: "._FIQHOPTION."</b></td></tr>\n"
          ."<tr valign=top class=\"table_block_content\">"
          ."<td colspan=\"3\"> "._DETERMINATIONOFFAJR."</td>"
          ."<td colspan=\"".($this->column-3)."\">:&nbsp;".($this->FIQH_isFajrByInterval?$this->FIQH_fajrInterval." min. "._INTERVALFROMSUNRISE:$this->FIQH_fajrDepr." deg. "._SUNDEPRESSION)."</td>"
          ."</tr>\n"
          ."<tr valign=top class=\"table_block_content\">"
          ."<td colspan=\"3\"> "._DETERMINATIONOFASR."</td>"
          ."<td colspan=\"".($this->column-3)."\">:&nbsp;"
          ._SHADOWRATIO." ".$this->FIQH_asrShadowRatio;
      if ($this->FIQH_asrShadowRasio!=2){
        echo " (".($this->FIQH_asrShadowRasio==0?""._SHAFIIANDOTHERS."":"Hanafi").")";
      }
      echo "</td>"
          ."</tr>\n"
          ."<tr class=\"table_block_content\">"
          ."<td colspan=\"3\"> "._DETERMINATIONOFISHA."</td>"
          ."<td colspan=\"".($this->column-3)."\">:&nbsp;".($this->FIQH_isIshaByInterval?$this->FIQH_ishaInterval." min. "._INTERVALFROMSUNSET:$this->FIQH_ishaDepr." deg. "._SUNDEPRESSION)."</td>"
          ."</tr>\n"
          ."<tr class=\"table_block_content\">"
          ."<td colspan=\"3\"> "._DETERMINATIONOFIMSYAK."</td>"
          ."<td colspan=\"".($this->column-3)."\">:&nbsp;".($this->FIQH_isImsyakByInterval?$this->FIQH_imsyakInterval." min. "._INTERVALFROMFAJR:$this->FIQH_imsyakDepr." deg. "._IMSYAKDEPRESSION)."</td>"
          ."</tr>\n"
          ."<tr class=\"table_block_content\">\n"
          ."<td colspan=\"3\"> "._IHTIYATTIME."</td>"
          ."<td colspan=\"".($this->column-3)."\">:&nbsp;";
      for($i=0;$i<7;$i++){
        if($i!=0 && $i!=2){
          echo $txtTime[$i].":".$this->ihtiyat[$i]."&#39; ";
        }
      }    
      echo "</td>\n"
          ."</tr>\n";         
    }
  }
  
  function export(){
    echo "<tr class=\"table_block_title\"><td colspan=\"".$this->column."\"><b>&nbsp;:: "._EXPORT."</b></td></tr>"
        ."<tr valign=top class=\"table_block_content\">"
        ."<td colspan=\"".$this->column."\" align=\"right\">"
        ."<input type=\"button\" class=\"button\" name=\"btnExport\" value=\""._EXPORT."\" onClick=\"javascript:location.href='export.php';\" />"
        ."</td></tr>";
  }

  function print_table($type,$day,$mont,$year,$txtTime,$path) {
    if($type!=4){
      echo "<table cellspacing=\"0\" cellpadding=\"2\" class=\"table_adzan\" align=\"center\">\n";
      $this->print_header($type,$mont,$year);
      if(($type==3 && $mont==1) ||($type<3)){
        $this->print_navigation($type,$day,$mont,$year);
      }
      echo "<tr class=\"table_header\" align=\"center\">\n"
          ."<td><b>"._DATE."</b></td>";
      for($i=0;$i<7;$i++){
        if(($i!=$this->limit1) AND ($i!=$this->limit2)){
          echo "<td><b>".$txtTime[$i]."</b></td>";
        }
      }
      echo "</tr>\n";
      $maxdom = ($day<1)?$this->month_age($mont,$year):1;
      $d =$this->date2doy(($day<1)?1:$day,$mont,$year);
      $date1=date("j-n-Y");
      for($j=0;$j<$maxdom;$j++){
        $date2=($j+1)."-$mont-$year";
        $dd=($day<1)?$this->change_format($j+1):$this->change_format($day);
        $msg="<tr class=\"".($date1==$date2?"table_highlight":($j%2==0?"table_light":"table_dark"))
            ."\" align=\"center\"><td><b>".$dd."</b></td>";
        for($i=0;$i<7 ;$i++){
          if(($i!=$this->limit1) AND ($i!=$this->limit2)){
            $msg.=($this->cfgArray['viewSecond']==1)?"<td>".$this->h2hms($this->prayTime[$d-1][$i])."</td>":"<td>".$this->h2hm($this->prayTime[$d-1][$i])."</td>";
          }  
        }
        $msg.="</tr>\n";
        echo $msg;
        $d++;
      }
      if(($type==3 && $mont==12) ||($type<3)){
        $this->print_parameter($path);
        $this->print_fiqh($txtTime);
        $this->export();
        echo "<tr class=\"table_copyright\" align=\"center\"><td colspan=\"".$this->column."\">"
            ."<b><a href=\"http://www.cdsatrian.com\">Adzan!</a></b> copyright &copy 2003-".date("Y")." by <a href=\"mailto:cahyadsn@yahoo.com\">cahyadsn</a>"
            ."</td></tr>\n"
            ."</table>\n";
      }
    }else{
      for($i=0;$i<7;$i++){
        if($type==4){
          if(($i!=$this->limit1) AND ($i!=$this->limit2)){
            $msg=($this->cfgArray['viewSecond']==1)?"<td>".$this->h2hms($this->prayTime[$d-1][$i])."</td>":"<td>".$this->h2hm($this->prayTime[$d-1][$i])."</td>";
            echo "<tr class=\"".($j%2==0?"table_light":"table_dark")."\"><td align=\"left\"><b>".$txtTime[$i]."</b></td>".$msg."</tr>\n";
            $j++;
          }  
        }else {
          if(($i!=0) AND ($i!=2)){
            $msg=($this->cfgArray['viewSecond']==1)?"<td>".$this->h2hms($this->prayTime[$d-1][$i])."</td>":"<td>".$this->h2hm($this->prayTime[$d-1][$i])."</td>";
            echo "<tr class=\"".($j%2==0?"table_light":"table_dark")."\"><td align=\"left\"><b>".$txtTime[$i]."</b></td>".$msg."</tr>\n";
            $j++;            
          }    
        }
      }
    }
  }

  function generate_data($type,$day,$mon,$year,$txtTime="",$path="",$up="",$ajax="") {
    parent::generate_data($type,$day,$mon,$year,$txtTime,$path,$up,$ajax);
    if($type==1){
      $this->print_table($type,$day,$mon,$year,$txtTime,$path);
    }else if($type==2){
      $this->print_table($type,0,$mon,$year,$txtTime,$path);
    }else if($type==3){
      for($i=1; $i<=12; $i++){
        $this->print_table($type,0,$i,$year,$txtTime,$path);
      }
    }
  }

}
?>