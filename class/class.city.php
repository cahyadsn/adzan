<?php
/*
BISMILLAAHIRRAHMAANIRRAHIIM - In the Name of Allah, Most Gracious, Most Merciful
================================================================================
filename	: 
purpose	: 
create	: 2006/02/24
last edit	: 060301,060317,060325,070430,120116
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
class city extends config{
  var $dataFile,$content,$size;
  var $dtCity,$dtCityName,$dtCityNo;
  var $txtNo,$txtCityName,
      $txtLatitudeDeg,$txtLatitudeMin,$txtLatitudeSign,
      $txtLongitudeDeg,$txtLongitudeMin,$txtLongitudeSign,
      $txtTimeZoneHour,$txtTimeZoneMin,$txtTimeZoneSign,
      $txtDST;

  function city($file,$path=""){
    //$this->get_config($path);
    $this->set_data_file($file);
    $this->content = file($this->dataFile);
    $this->cityCount = count($this->content);
    for($i=0;$i<$this->cityCount;$i++){
      $this->dtCity[$i]=explode("!",$this->content[$i]);
      $this->dtCityName[$i]=ucwords(strtolower($this->dtCity[$i][1]));
      $this->dtCityNo[$i]=$i;
    }
    array_multisort($this->dtCityName,SORT_ASC, SORT_STRING,$this->dtCityNo);
  }

  function set_data_file($file){
    $this->dataFile=$file;
  }

  function get_data($id){
    $id=$id-1;
    $this->txtNo=$this->dtCity[$id][0];
    $this->txtCityName=$this->dtCity[$id][1];
    $this->txtLatitudeDeg=$this->dtCity[$id][2];
    $this->txtLatitudeMin=$this->dtCity[$id][3];
    $this->txtLatitudeSign=$this->dtCity[$id][4];
    $this->txtLongitudeDeg=$this->dtCity[$id][5];
    $this->txtLongitudeMin=$this->dtCity[$id][6];
    $this->txtLongitudeSign=$this->dtCity[$id][7];
    $this->txtTimeZoneHour=$this->dtCity[$id][8];
    $this->txtTimeZoneMin=$this->dtCity[$id][9];
    $this->txtTimeZoneSign=$this->dtCity[$id][10];
    $this->txtDST=$this->dtCity[$id][11];
  }

  function view_list($start,$count,$search=""){
    if($search){
      $j=0;
      $sorting=array();
      for($i=0;$i<$this->cityCount;$i++){
        if(stristr($this->dtCityName[$i],$search)){
          array_push($sorting,$this->dtCityNo[$i]);
          $j++;
        } 
      }
      $this->cityCount=$j;
    } else{
      $sorting=$this->dtCityNo;
    }
    $first=$start*$count;
    $last=$first+$count;
    echo "<script language=\"JavaScript\">\n"
        ."function checkDel(){\n"
        ." return confirm(\""._DELETE_MSG."\");"
        ."}\n"
        ."</script>\n"
        ."<table align=\"center\" width=\""._WIDTH."\" border=0 cellpadding=2 cellspacing=0 class=\"content2\">\n"
        ."<tr>\n"
        ."<td colspan=\"2\" class=\"navigasi\">"
        ."<b>"._COUNTRY."</b> : ".ucwords(strtolower($this->cfgArray['country']))
        ."</td>\n"
        ."<td align=\"right\" colspan=\"3\" class=\"navigasi\">\n"
        .(($start-1>-1)?"<a href=\"".basename($_SERVER['PHP_SELF'])."?page=0".($search?"&s=".$search:"")."\" title=\""._FIRST."\"><img src=\"../images/left2.png\" border=\"0\" alt=\""._FIRST."\">&nbsp;"._FIRST."</a>":"<img src=\"../images/left2.png\" border=\"0\" alt=\""._FIRST."\"/>&nbsp;"._FIRST)
        .(($start-1>-1)?"<a href=\"".basename($_SERVER['PHP_SELF'])."?page=".($start-1).($search?"&s=".$search:"")."\" title=\""._PREV."\"><img src=\"../images/left.png\" border=\"0\" alt=\""._PREV."\">&nbsp;"._PREV."</a>":"<img src=\"../images/left.png\" border=\"0\" alt=\""._PREV."\"/>&nbsp;"._PREV)
        ." ";
    $st=($start-($start%5))/5;
    for($i=$st*5;$i<$st*5+5;$i++){
      if($i<$this->cityCount/10){
        echo "<a href=\"".basename($_SERVER['PHP_SELF'])."?page=".$i.($search?"&s=".$search:"")."\">".$i."</a> ";
      }
    }    
    echo ((($start+1)*10>$this->cityCount)?"&nbsp;"._LAST."<img src=\"../images/right.png\" border=\"0\" alt=\""._NEXT."\"/>":"<a href=\"".basename($_SERVER['PHP_SELF'])."?page=".($start+1).($search?"&s=".$search:"")."\" title=\""._NEXT."\">"._NEXT."&nbsp;<img src=\"../images/right.png\" border=\"0\" alt=\""._NEXT."\"></a>")
        .((($start+1)*10>$this->cityCount)?"&nbsp;"._LAST."<img src=\"../images/right2.png\" border=\"0\" alt=\""._LAST."\"/>":"<a href=\"".basename($_SERVER['PHP_SELF'])."?page=".floor(($this->cityCount-1)/10).($search?"&s=".$search:"")."\" title=\""._LAST."\">"._LAST."&nbsp;<img src=\"../images/right2.png\" border=\"0\" alt=\""._LAST."\"></a>")
        ."</td>\n"
        ."</tr>\n"
        ."<tr>\n"
        ."<td colspan=\"5\" class=\"table-highlight\" align=\"right\">\n"
        ."<form method=\"post\" action=\"".basename($_SERVER['PHP_SELF'])."\">\n"
        ._SEARCHCITY."&nbsp;\n"
        ."<input type=\"text\" class=\"inputbox2\" name=\"s\" value=\"".$search."\"/>\n"
        ."<input type=\"submit\" class=\"button\" value=\""._SEARCH."\">\n"
        ."</form>\n"
        ."</td>\n"
        ."</tr>\n"
        ."<tr class=\"table-header\">\n"
        ."  <th>No</th><th>"._CITYNAME."</th><th>"._COORDINATE."</th><th>"._TIMEZONE."</th><th>"._ACTION."</th>\n"
        ."</tr>\n";
    for($i=$first;$i<$last;$i++){
      if($i<$this->cityCount){
        echo "<tr align=\"center\" class=\"".($i%2==0?"table-light":"table-dark")."\">"
            ."<td align=\"right\">".($i+1)."</td>"
            ."<td align=\"left\">".$this->dtCity[$sorting[$i]][1]."</td>"
            ."<td>"
            .$this->dtCity[$sorting[$i]][2]." &deg; ".$this->dtCity[$sorting[$i]][3]."' ".($this->dtCity[$sorting[$i]][4]?_S:_N)." ~ "
            .$this->dtCity[$sorting[$i]][5]." &deg; ".$this->dtCity[$sorting[$i]][6]."' ".($this->dtCity[$sorting[$i]][7]?_E:_W)
            ."</td>"
            ."<td>"
            .($this->dtCity[$sorting[$i]][10]?"-":"+").$this->dtCity[$sorting[$i]][8].":".$this->dtCity[$sorting[$i]][9]." GMT "
            ."</td>"
            ."<td>"
            ."<a href=\"?cmd=edit&id=".($sorting[$i]+1)."\">edit</a>"
            ." | "
            ."<a href=\"?cmd=del&id={$sorting[$i]}\" onClick=\"return checkDel();\">del</a>"
            ."</td></tr>\n";
      }
    }
    echo "<tr>\n"
        ."<td colspan=\"5\" align=\"right\" class=\"navigasi\"><input type=\"button\" class=\"button\" onClick=\"location.href='".basename($_SERVER['PHP_SELF'])."?cmd=add';\" value=\""._ADD."\" /></td>\n"
        ."</table>\n";
  }

  function form($id=""){
    if($id!=""){
      $this->get_data($id);
    }
    echo "<script language=\"JavaScript\">\n"
        ."function checkSave(){\n"
        ."  return confirm(\""._SAVE_MSG."\");\n"
        ."}\n"
        ."function checkCancel(){\n"
        ." if(confirm(\""._CANCEL_MSG."\")){\n"
        ."   window.location.href=\"".basename($_SERVER['PHP_SELF'])."\";\n"
        ." }\n"
        ."}\n"
        ."</script>\n"
        ."<table align=\"center\" width=\""._WIDTH."\" border=0 cellpadding=2 cellspacing=0 class=\"content2\">\n"
        ."<form method=\"post\" action=\"".basename($_SERVER['PHP_SELF'])."?cmd=save&id=$id\">\n"
        ."<tr>\n"
        ."<td colspan=\"2\" class=\"table-header\"><b>".($_GET['cmd']=="edit"?_EDIT:_ADD)."</b></td>\n"
        ."</tr>\n"
        ."<tr class=\"table-dark\"><td valign=top width=40%>"._CITYNAME."</td><td><input type=text name=\"txtCityName\" style=\"width:230px\" value=\"{$this->txtCityName}\" class=\"inputbox2\"> *</td></tr>\n"
        ."<tr class=\"table-light\">\n"
        ."  <td valign=top width=40%>"._LATITUDE." (<i>"._DEG.":"._MIN."</i>)</td>\n"
        ."  <td>\n"
        ."  <input type=hidden name=\"id\" value=\"{$id}\" />\n"
        ."  <input type=hidden name=\"txtNo\" value=\"{$this->txtNo}\" />\n"
        ."  <input type=hidden name=\"txtDST\" value=\"".trim($this->txtDST)."\" />\n"
        ."  <input type=text name=\"txtLatitudeDeg\" class=\"inputNumber\" value=\"{$this->txtLatitudeDeg}\" />\n"
        ."  <input type=text name=\"txtLatitudeMin\" class=\"inputNumber\" value=\"{$this->txtLatitudeMin}\" />\n"
        ."  <select name=\"txtLatitudeSign\" class=\"inputbox2\">\n"
        ."  <option value=\"0\"".($this->txtLatitudeSign=="0"?" selected":"").">North</option>\n"
        ."  <option value=\"1\"".($this->txtLatitudeSign=="1"?" selected":"").">South</option>\n"
        ."  </select> *\n"
        ."  </td>\n"
        ."</tr>\n"
        ."<tr class=\"table-dark\">\n"
        ."  <td valign=top width=40%>"._LONGITUDE." (<i>"._DEG.":"._MIN."</i>)</td>\n"
        ."  <td>\n"
        ."  <input type=text name=\"txtLongitudeDeg\" class=\"inputNumber\" value=\"{$this->txtLongitudeDeg}\" />\n"
        ."  <input type=text name=\"txtLongitudeMin\" class=\"inputNumber\" value=\"{$this->txtLongitudeMin}\" />\n"
        ."  <select name=\"txtLongitudeSign\" class=\"inputbox2\">\n"
        ."  <option value=\"0\"".($this->txtLongitudeSign=="0"?" selected":"").">West</option>\n"
        ."  <option value=\"1\"".($this->txtLongitudeSign=="1"?" selected":"").">East</option>\n"
        ."  </select> *\n"
        ."  </td>\n"
        ."</tr>\n"
        ."<tr class=\"table-light\">\n"
        ."  <td valign=top width=40%>"._TIMEZONE." GMT (<i>"._HOUR.":"._MIN."</i>)</td>\n"
        ."  <td>\n"
        ."  <select name=\"txtTimeZoneSign\" class=\"inputbox2\">\n"
        ."  <option value=\"0\"".($this->txtTimeZoneSign=="0"?" selected":"").">+</option>\n"
        ."  <option value=\"1\"".($this->txtTimeZoneSign=="1"?" selected":"").">-</option>\n"
        ."  </select>\n"
        ."  <input type=text name=\"txtTimeZoneHour\" class=\"inputNumber\" value=\"{$this->txtTimeZoneHour}\" />\n"
        ."  <input type=text name=\"txtTimeZoneMin\" class=\"inputNumber\" value=\"{$this->txtTimeZoneMin}\" /> *\n"
        ."  </td>\n"
        ."</tr>\n"
        ."<script language\"javascript\">\n"
        ."function changeValue(){\n"
        ."  var tds1=document.getElementById(\"txtDST1\");\n"
        ."  var tds=document.getElementById(\"txtDST\");\n"
        ."  if(tds1.checked) {\n"
        ."    tds.value=\"1\";\n"
        ."  }else{\n"
        ."    tds.value=\"0\";\n"
        ."  }\n"
        ."}\n"
        ."</script>\n"
        ."<tr class=\"table-light\">\n"
        ."  <td valign=top width=40%>"._DST."</td>\n"
        ."  <td>\n"
        ."  <input type=\"checkbox\" id=\"txtDST1\" name=\"txtDST1\" value=\"1\"".($this->txtDST==1?" CHECKED":"")." onClick=\"changeValue();\" />\n"
        ."  <input type=\"hidden\" id=\"txtDST\" name=\"txtDST\" value=\"0\" />\n"
        ."  </td>\n"
        ."</tr>\n"
        ."<tr>\n"
        ."<td align=\"right\" colspan=\"2\" class=\"navigasi\">\n"
        ."<input type=\"submit\" class=\"button\" value=\""._SAVE."\" onClick=\"return checkSave();\" />\n"
        ."<input type=\"button\" class=\"button\" value=\""._CANCEL."\" onClick=\"checkCancel();\" />\n"
        ."</td>\n"
        ."</tr>\n"
        ."</form>\n"
        ."</table>\n";
  }

  function add(){
    $no=$this->dtCity[$this->cityCount-1][0]+1;
    $text = ($no?$no:"0")."!"
           .($_POST['txtCityName']?$_POST['txtCityName']:"0")."!"
           .($_POST['txtLatitudeDeg']?$_POST['txtLatitudeDeg']:"0")."!"
           .($_POST['txtLatitudeMin']?$_POST['txtLatitudeMin']:"0")."!"
           .($_POST['txtLatitudeSign']?$_POST['txtLatitudeSign']:"0")."!"
           .($_POST['txtLongitudeDeg']?$_POST['txtLongitudeDeg']:"0")."!"
           .($_POST['txtLongitudeMin']?$_POST['txtLongitudeMin']:"0")."!"
           .($_POST['txtLongitudeSign']?$_POST['txtLongitudeSign']:"0")."!"
           .($_POST['txtTimeZoneHour']?$_POST['txtTimeZoneHour']:"0")."!"
           .($_POST['txtTimeZoneMin']?$_POST['txtTimeZoneMin']:"0")."!"
           .($_POST['txtTimeZoneSign']?$_POST['txtTimeZoneSign']:"0")."!"
           .($_POST['txtDST']?$_POST['txtDST']:"0")."\n";
    $this->content[$this->cityCount] = $text;
	  $fp=@fopen($this->dataFile, "a");
	  if($fp <= 0){
  	  $errMsg="Error opening data file.";
	  };
	  fputs($fp, $this->content[$this->cityCount]);
	  fclose($fp);
    echo "<script language=\"javascript\">\n"
        ."window.location.href=\"".basename($_SERVER['PHP_SELF'])."\";"
        ."</script>\n"; 	  
  }

  function edit($id){
    $id=$id-1;
    $text = ($_POST['txtNo']?$_POST['txtNo']:"0")."!"
           .($_POST['txtCityName']?$_POST['txtCityName']:"0")."!"
           .($_POST['txtLatitudeDeg']?$_POST['txtLatitudeDeg']:"0")."!"
           .($_POST['txtLatitudeMin']?$_POST['txtLatitudeMin']:"0")."!"
           .($_POST['txtLatitudeSign']?$_POST['txtLatitudeSign']:"0")."!"
           .($_POST['txtLongitudeDeg']?$_POST['txtLongitudeDeg']:"0")."!"
           .($_POST['txtLongitudeMin']?$_POST['txtLongitudeMin']:"0")."!"
           .($_POST['txtLongitudeSign']?$_POST['txtLongitudeSign']:"0")."!"
           .($_POST['txtTimeZoneHour']?$_POST['txtTimeZoneHour']:"0")."!"
           .($_POST['txtTimeZoneMin']?$_POST['txtTimeZoneMin']:"0")."!"
           .($_POST['txtTimeZoneSign']?$_POST['txtTimeZoneSign']:"0")."!"
           .($_POST['txtDST']?$_POST['txtDST']:"0")."\n";
    $new_content="";
    for($i=0;$i<$this->cityCount;$i++){
      if($i!=$id){
        $new_content.=$this->content[$i];
      }else{
        $new_content.=$text;
      }
    }
    $fl = @fopen($this->dataFile,"w");
    fputs($fl, $new_content);
    fclose($fl);
    echo "<script language=\"javascript\">\n"
        ."window.location.href=\"".basename($_SERVER['PHP_SELF'])."\";"
        ."</script>\n";  
  }

  function delete($id){
    $new_content="";
    for($i=0;$i<$this->cityCount;$i++){
      if($i!=$id){
        $new_content.=$this->content[$i];
      }
    }
    $fl = @fopen($this->dataFile,"w");
    fputs($fl, $new_content);
    fclose($fl);
    echo "<script language=\"javascript\">\n"
        ."window.location.href=\"".basename($_SERVER['PHP_SELF'])."\";"
        ."</script>\n";
  }
}
?>