<?php
/*
BISMILLAAHIRRAHMAANIRRAHIIM - In the Name of Allah, Most Gracious, Most Merciful
================================================================================
filename|: class.html.ajax.php
purpose|: html_ajax class functions
create|: 2006/11/24
last edit|: 061127,070430,0501,0525,0611,080113,0311,0810,0814,0901,120119
author|: cahya dsn
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
include $pathChk."class/class.adzan.main.php";
include $pathChk."class/class.adzan.".$algo.".php";
class html_ajax extends adzan{
  public $pos;
  
  function html_ajax($dataCityFile,$id="",$pathCfg="",$pathChk="",$up="",$ajax="",$pos=1){
    parent::adzan($dataCityFile,$id,$pathCfg,$pathChk,$up,$ajax);
    $this->pos=$pos;
  }

  function print_header($type,$mont,$year){
    echo "<div class=\"table_title\" align=\"center\">\n"
        ."<b>".$this->cfgMonth[$mont-1]." $year</b><br>\n"
        ."<small>("._FORCITY." <b>".ucwords(strtolower($this->dtCity[$this->id][1]))."</b> GMT "
        .($this->dtCity[$this->id][10]?"-":"+").$this->dtCity[$this->id][8].")</small>\n"
        ."</div>\n";
  }

  function print_navigation($type,$day,$mont,$year){
    $prevMon=$mont-1;
    $prevYear=$year-1;
    $nextMon=$mont+1;
    $nextYear=$year+1;
    if($type==2){
      $prev="type=2&id=".($this->id+1)."&m=".($mont>1?"$prevMon&y=$year":"12&y=".$prevYear);
      $next="type=2&id=".($this->id+1)."&m=".($mont<12?"$nextMon&y=$year":"1&y=".$nextYear);
    }else if($type==3){
      $prev="type=3&y=".$prevYear."&id=".($this->id+1);
      $next="type=3&y=".$nextYear."&id=".($this->id+1);
    }
    $prev="javascript:ajax_loadContent('content','ajax/ajax.content.php?id=".$prev."');";
    $next="javascript:ajax_loadContent('content','ajax/ajax.content.php?id=".$next."');";
    if($type!=1){
    echo "<div class=\"table_navigasi\" align=\"center\">\n"
        ."<table cellspacing=\"0\" cellpadding=\"2\" width=\"100%\">\n"
        ."<tr class=\"table_navigasi\"><td align=\"left\">&nbsp;";
      cdlink($prev,"<img src=\"images/left.gif\" border=\"0\"> "._PREV,_PREV,"m");
      echo "</td>\n<td align=\"center\">"._SELECTCITY."&nbsp;\n"
          ."<select name=\"city\" id=\"city\" onChange=\"change_page()\" class=\"inputcity\">\n";
      for($i=0;$i<$this->cityCount;$i++){
        echo "<option value=\"".($this->dtCityNo[$i]+1)."\"".($this->id==$this->dtCityNo[$i]?" selected":"").">".ucwords(strtolower($this->dtCityName[$i]))."</option>\n";
      }
      echo "</select>\n"
          ."</td>\n<td align=\"right\">";         
      cdlink($next,_NEXT." <img src=\"images/right.gif\" border=\"0\">",_NEXT,"m");
      echo "&nbsp;</td>\n"
          ."</tr>\n"
          ."</table>\n"
          ."</div>\n";
    }
  }

  function print_city_selection($type,$mont){
   if(($type==3 && $mont==1) ||($type<3)||($type==4)){
    if($type!=1){
      echo ($type<4?" "._SELECTCITY."&nbsp;\n":"")
            ."<select name=\"city\" id=\"city\" onChange=\"change_page()\" class=\"inputcity\">\n";
      for($i=0;$i<$this->cityCount;$i++){
        echo "<option value=\"".($this->dtCityNo[$i]+1)."\"".($this->id==$this->dtCityNo[$i]?" selected":"").">".ucwords(strtolower($this->dtCityName[$i]))."</option>\n";
      }
       echo "</select>\n";
     }
    }
  }
  
  function print_nav_left($type,$day,$mont,$year){
    $prevMon=$mont-1;
    $prevYear=$year-1;
    if($type==2){
      $prevMon=($mont>1?$prevMon:12);
      $prevYear=($mont>1?$year:$prevYear);
    }    
    $prev="javascript:change_nav(".($this->id+1).",".$type.",".$day.",".$prevMon.",".$prevYear.");";
    if($type!=1){
     cdlink($prev,"<img src=\"images/left.gif\" border=\"0\"> "._PREV,_PREV,"m");
    }
  }

  function print_nav_right($type,$day,$mont,$year){
    $nextMon=$mont+1;
    $nextYear=$year+1;
    if($type==2){
      $nextMon=($mont<12?$nextMon:1);
      $nextYear=($mont<12?$year:$nextYear);
    }
    $next="javascript:change_nav(".($this->id+1).",".$type.",".$day.",".$nextMon.",".$nextYear.");";
    if($type!=1){
     cdlink($next,_NEXT." <img src=\"images/right.gif\" border=\"0\">",_NEXT,"m");
    }
  }

  function print_parameter($type="",$path=""){
    list($qDirec,$qDistance)=$this->qibla($this->GEO_longitude,$this->GEO_latitude);
    $lat=$this->dtCity[$this->id][2]."&deg;".$this->dtCity[$this->id][3]."'";
    $latsign=$this->dtCity[$this->id][4]?_S:_N;
    $long=$this->dtCity[$this->id][5]."&deg;".$this->dtCity[$this->id][6]."'";
    $longsign=($this->dtCity[$this->id][7])?_E:_W;
    if($type!=4){
      if($this->cfgArray['viewParam']){
        echo "<table width=\"100%\">\n" 
            ."<tr align=\"left\" class=\"table_block_content\"><td colspan=\"".($this->cfgArray['viewQibla']==1?($this->column-2):$this->column)."\">"._FORCITY." <b>".$this->dtCity[$this->id][1]."</b> $lat $latsign $long $longsign</td>"
            .($this->cfgArray['viewQibla']==1?"<td rowspan=\"3\" colspan=\"2\" align=\"center\"><img src=\"".$path."images/circle.php?s=".$qDirec."\" alt=\"\" /></td>":"")."</tr>\n"
            ."<tr class=\"table_block_content\"><td align=\"right\" colspan=\"2\">"._DIRECTION." :</td><td colspan=\"".($this->cfgArray['viewQibla']==1?($this->column-4):($this->column-2))."\">".$qDirec." &deg; "._TO." "._MECCA."</td></tr>\n"
            ."<tr class=\"table_block_content\"><td align=\"right\" colspan=\"2\">"._DISTANCE." :</td><td colspan=\"".($this->cfgArray['viewQibla']==1?($this->column-4):($this->column-2))."\">".$qDistance." km "._TO." "._MECCA."</td></tr>\n"
            ."</table>\n";
      }
    }else{
      echo "<table class=\"table_adzan2\">"
          ."<tr><td align=\"center\"><img src=\"".$path."images/circle.php?s=".$qDirec."\" alt=\"\" /></td></tr>"
          ."<tr><td class=\"table_dark\">"._DIRECTION."</td>\n"
          ."<tr><td style=\"font-size:6pt;\">".$qDirec." &deg; "._TO." "._MECCA."</td></tr>\n"
          ."<tr class=\"table_dark\"><td>"._DISTANCE."</td></tr>"
          ."<tr><td style=\"font-size:6pt;\">".$qDistance." km "._TO." "._MECCA."</td></tr>\n"
          ."</tr>\n"
          ."</table>\n";
    }  
  }

  function print_qibla($type="",$path=""){
    list($qDirec,$qDistance)=$this->qibla($this->GEO_longitude,$this->GEO_latitude);
    $lat=$this->dtCity[$this->id][2]."&deg;".$this->dtCity[$this->id][3]."'";
    $latsign=$this->dtCity[$this->id][4]?_S:_N;
    $long=$this->dtCity[$this->id][5]."&deg;".$this->dtCity[$this->id][6]."'";
    $longsign=($this->dtCity[$this->id][7])?_E:_W;
    if($type!=4){
      if($this->cfgArray['viewParam']){
        echo "<table width=\"100%\">\n" 
            ."<tr align=\"left\" class=\"table_block_content\"><td colspan=\"".($this->cfgArray['viewQibla']==1?($this->column-2):$this->column)."\">"._FORCITY." <b>".$this->dtCity[$this->id][1]."</b> $lat $latsign $long $longsign</td>"
            .($this->cfgArray['viewQibla']==1?"<td rowspan=\"3\" colspan=\"2\" align=\"center\"><img src=\"".$path."images/circle.php?s=".$qDirec."\" alt=\"\" /></td>":"")."</tr>\n"
            ."<tr class=\"table_block_content\"><td align=\"right\" colspan=\"2\">"._DIRECTION." :</td><td colspan=\"".($this->cfgArray['viewQibla']==1?($this->column-4):($this->column-2))."\">".$qDirec." &deg; "._TO." "._MECCA."</td></tr>\n"
            ."<tr class=\"table_block_content\"><td align=\"right\" colspan=\"2\">"._DISTANCE." :</td><td colspan=\"".($this->cfgArray['viewQibla']==1?($this->column-4):($this->column-2))."\">".$qDistance." km "._TO." "._MECCA."</td></tr>\n"
            ."</table>\n";
      }
    }else{
      echo "<table id=\"adzan\">"
          ."<tr class=\"light\"><td align=\"center\" colspan=\"2\"><img src=\"".$path."images/circle.php?s=".$qDirec."\" alt=\"\" /></td></tr>"
          ."<tr class=\"dark\"><td>Latitude </td><td>:".$lat.$latsign."</td></tr>"
          ."<tr class=\"light\"><td>Longitude </td><td>:".$long.$longsign."</td></tr>"
          ."<tr class=\"dark\"><td>"._DIRECTION."</td><td>".$qDirec." &deg;</td></tr>\n"
          ."<tr class=\"light\"><td>"._DISTANCE."</td><td>".$qDistance." km</td></tr>\n"
          ."<tr class=\"light\"><td colspan=\"2\"><a id=\"maplink\" href=\"http://maps.google.com/maps?q=".rad2deg($this->GEO_latitude).",+".rad2deg(-1*$this->GEO_longitude)."+(".ucwords(strtolower($this->dtCity[$this->id][1])).")&amp;iwloc=A&amp;hl=en&amp;z=12&amp;t=h\" target=\"newtab\">".(defined('_MAP')?_MAP:"Map")."</a></td></tr>\n"
          ."</table>\n";
    }  
  }

  function print_fiqh($type=1){
    if($this->cfgArray['viewFiqh']){
      if($type<4){
        echo "<table>\n"
            ."<tr valign=top class=\"table_block_content\">"
            ."<td> "._DETERMINATIONOFFAJR."</td>"
            ."<td>:&nbsp;".($this->FIQH_isFajrByInterval?$this->FIQH_fajrInterval." min. "._INTERVALFROMSUNRISE:$this->FIQH_fajrDepr." deg. "._SUNDEPRESSION)."</td>"
            ."</tr>\n"
            ."<tr valign=top class=\"table_block_content\">"
            ."<td> "._DETERMINATIONOFASR."</td>"
            ."<td>:&nbsp;"
            ._SHADOWRATIO." ".$this->FIQH_asrShadowRatio;
        if ($this->FIQH_asrShadowRatio!=2){
          echo " (".($this->FIQH_asrShadowRatio==0?""._SHAFIIANDOTHERS."":"Hanafi").")";
        }
        echo "</td>"
            ."</tr>\n"
            ."<tr class=\"table_block_content\">"
            ."<td> "._DETERMINATIONOFISHA."</td>"
            ."<td>:&nbsp;".($this->FIQH_isIshaByInterval?$this->FIQH_ishaInterval." min. "._INTERVALFROMSUNSET:$this->FIQH_ishaDepr." deg. "._SUNDEPRESSION)."</td>"
            ."</tr>\n"
            ."<tr class=\"table_block_content\">"
            ."<td> "._DETERMINATIONOFIMSYAK."</td>"
            ."<td>:&nbsp;".($this->FIQH_isImsyakByInterval?$this->FIQH_imsyakInterval." min. "._INTERVALFROMFAJR:$this->FIQH_imsyakDepr." deg. "._IMSYAKDEPRESSION)."</td>"
            ."</tr>\n"    
            ."</table>\n";
      }else{
        echo "<table id=\"adzan\">\n"
            ."<tr valign=top class=\"dark\">"
            ."<td> "._DETERMINATIONOFFAJR."</td></tr>"
            ."<tr class=\"light\"><td>&nbsp;".($this->FIQH_isFajrByInterval?$this->FIQH_fajrInterval." min. "._INTERVALFROMSUNRISE:$this->FIQH_fajrDepr." &deg;. "._SUNDEPRESSION)."</td>"
            ."</tr>\n"
            ."<tr valign=top class=\"dark\">"
            ."<td> "._DETERMINATIONOFASR."</td>"
            ."</tr><tr class=\"light\">"
            ."<td>&nbsp;"
            ._SHADOWRATIO." ".$this->FIQH_asrShadowRatio;
        if ($this->FIQH_asrShadowRatio!=2){
          echo " (".($this->FIQH_asrShadowRatio==0?"Shafi`i":"Hanafi").")";
        }
        echo "</td>"
            ."</tr>\n"
            ."<tr class=\"dark\">"
            ."<td> "._DETERMINATIONOFISHA."</td>"
            ."</tr><tr class=\"light\">"
            ."<td>&nbsp;".($this->FIQH_isIshaByInterval?$this->FIQH_ishaInterval." min. "._INTERVALFROMSUNSET:$this->FIQH_ishaDepr." &deg;. "._SUNDEPRESSION)."</td>"
            ."</tr>\n"
            ."<tr class=\"dark\">"
            ."<td> "._DETERMINATIONOFIMSYAK."</td>"
            ."</tr><tr class=\"light\">"
            ."<td>&nbsp;".($this->FIQH_isImsyakByInterval?$this->FIQH_imsyakInterval." min. "._INTERVALFROMFAJR:$this->FIQH_imsyakDepr." &deg;. "._IMSYAKDEPRESSION)."</td>"
            ."</tr>\n"    
            ."</table>\n";   
      }    
    }
  }
  
  function export(){
    echo "<div class=\"dhtml_question\"><b>&nbsp;:: "._EXPORT."</b></div>\n"
        ."<div class=\"dhtml_answer\">\n"
        ."<div>\n"
        ."<input type=\"button\" class=\"button\" name=\"btnExport\" value=\""._EXPORT."\" onClick=\"javascript:location.href='export.php';\" />\n"
        ."</div>\n</div>\n";
  }

  function print_table($type,$day,$mont,$year,$txtTime,$path,$z=0) {
    global $adzanPeriod;
    echo "<input type=\"hidden\" name=\"yr\" id=\"yr\" value=\"".$year."\" />\n"
        ."<input type=\"hidden\" name=\"mn\" id=\"mn\" value=\"".$mont."\" />\n"
        ."<input type=\"hidden\" name=\"dt\" id=\"dt\" value=\"".$day."\" />\n"
        ."<input type=\"hidden\" name=\"tp\" id=\"tp\" value=\"".$type."\" />\n";
    $maxdom = ($day<1)?$this->month_age($mont,$year):1;
    $d =$this->date2doy(($day<1)?1:$day,$mont,$year);
    $date1=date("j-n-Y");
    if($type<4){
      echo "<table cellspacing=\"0\" cellpadding=\"2\" width=\"100%\" align=\"center\">\n"
          ."<tr class=\"table_header\" align=\"center\">\n"
          ."<td><b>"._DATE."</b></td>";
      for($i=0;$i<7;$i++){
        if(($i!=$this->limit1) AND ($i!=$this->limit2)){
          echo "<td><b>".$txtTime[$i]."</b></td>";
        }
      }
      echo "</tr>\n";
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
    }else{
      $j=0;
      echo "<table class=\"table_adzan2\">\n";
      if($type>=4){
        $yest=time()-(24*60*60);
        $tomo=time()+(24*60*60);
        $prev_date="javascript:callAHAH('ajax/ajax.daily1.php?id=".($this->id+1)."&amp;d=".date("j",$yest)."&amp;m=".date("n",$yest)."&amp;y=".date("Y",$yest)."&amp;z=1', 'content','getting content for<br />Adzan tab. Wait...', 'Error');";
        $next_date="javascript:callAHAH('ajax/ajax.daily1.php?id=".($this->id+1)."&amp;d=".date("j",$tomo)."&amp;m=".date("n",$tomo)."&amp;y=".date("Y",$tomo)."&amp;z=2', 'content','getting content for<br />Adzan tab. Wait...', 'Error');";
        if($z==1){
          echo "<tr class=\"\"><td align=\"center\" colspan=\"2\">"._YESTERDAY."&nbsp;<b>".date("d-m-Y",$yest)."</b></td></tr>\n"
              ."<tr class=\"\">\n"
              ."<td align=\"left\">&nbsp</td>\n"
              ."<td align=\"right\"><a href=\""."?p=1\">"._TODAY."</a></td>\n"
              ."</tr>\n";
        }elseif($z==2){
          echo "<tr class=\"\"><td align=\"center\" colspan=\"2\">"._TOMORROW."&nbsp;<b>".date("d-m-Y",$tomo)."</b></td></tr>\n"
              ."<tr class=\"\"><td align=\"left\"><a href=\"\">"._TODAY."</a></td><td align=\"right\">&nbsp;</td></tr>\n";               
        }else{
          echo "<tr class=\"\"><td align=\"center\" colspan=\"2\">"._TODAY."&nbsp;<b>".date("j F Y")."</b></td></tr>\n"
              ."<tr class=\"\"><td align=\"left\"><a href=\"".$prev_date."\">"._YESTERDAY."</a></td><td align=\"right\"><a href=\"".$next_date."\">"._TOMORROW."</a></td></tr>\n";
        }    
      }      
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
      echo "<tr><td align\"left\">[&nbsp;<a href=\"monthly.php\" style=\"font-size:7pt;color:#000099;\">".$adzanPeriod[1]."</a>&nbsp;]</td>"
          ."<td align=\"right\"><a href=\"mailto:cahyadsn@yahoo.com\" style=\"font-size:7pt;color:#000099;\">adzan ".$this->cfgArray['version']."</a></td></tr>\n"
          ."</table>\n";
    }  
  } 
  function print_footer($type,$mont){
    if(($type==3 && $mont==12) ||($type<3)){
      echo "<div class=\"table_copyright\" align=\"center\"><b>";
      cdlink("mailto:cahyadsn@yahoo.com","Adzan!");
      echo "</b> copyright &copy; 2003-".date("Y")." by ";
      cdlink("mailto:cahyadsn@yahoo.com","cahyadsn","send email to author");
      echo "</div>\n";
    }
  }

  function generate_data($type,$day,$mon,$year,$txtTime='',$path="",$up="",$ajax="1",$z="") {
    parent::generate_data($type,$day,$mon,$year,$txtTime,$path,$up,1);
    if($type==1){
      $this->print_table($type,$day,$mon,$year,$txtTime,$path,$z);
    }else if($type==2){
      $this->print_table($type,0,$mon,$year,$txtTime,$path,$z);
    }else if($type==3){
      for($i=1; $i<=12; $i++){
        $this->print_table($type,0,$i,$year,$txtTime,$path,$z);
      }
    }else if(($type==4) OR ($type==5)){
      $this->print_table($type,$day,$mon,$year,$txtTime,$path,$z);
    }
  }

}
?>