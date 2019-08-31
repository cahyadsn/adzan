<?php
/*
BISMILLAAHIRRAHMAANIRRAHIIM - In the Name of Allah, Most Gracious, Most Merciful
================================================================================
filename	: 
purpose	: 
create	: 2006/03/24
last edit	: 060324,060328,060405,070430,080901,120116
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
if(isset($_GET['help'])){
?>
<pre>
usage :
  data.php?help=1
  data.php[?d=day[&m=month[&y=year[&p=period[&id=city[&t=type[&algo=algo]]]]]]] 
  
notes :  
  help=1 -> show this help
  day = day number [1..31], current date is default
  month = month number [1-12], current month is default
  year = four digit year (yyyy), current year is default
  period = period type : 1 daily (default), 2 monthly, 3 yearly
  city = city number [1..308], default 83 (Jakarta), see list of cities below)**
  type = file format type ('txt' or 'xml'),default 'txt'
  algo = algorithm for calculation praytime schedule [1..3]
         Prayer hours are computed basically adopting from the algorithms given in
          1. Islamic Prayer Time Schedule by Imam Abdul Mujib
             Taken from "Sterne im Computer" by Dr. Klaus Hempe 
          2. Prayer Schedules for North America", American Trust Publications, 
             Indianapolis, Indiana, 1978, Appendices A and B.
          3. Almanac for Computers, 1990 published by Nautical Almanac Office
             United States Naval Observatory, Washington, DC 20392
             
  results is in the following format:
  
     yyyy mm dd imsyaq shubuh syuruq dhuhur ashr maghrib isya\n
  
  yyyy    = four digit year
  mm      = two digit month
  dd      = two digit day
  ismyaq  = 8 digit time format)* for imsyaq time
  shubuh  = 8 digit time format)* for imsyaq time
  syuruq  = 8 digit time format)* for imsyaq time
  dhuhur  = 8 digit time format)* for imsyaq time
  ashr    = 8 digit time format)* for imsyaq time
  maghrib = 8 digit time format)* for imsyaq time
  isya    = 8 digit time format)* for imsyaq time
  each fields separated by 1 space, each record separated by "\n"       
  
  )* 8 digit time format hh:mm:ss, where :
          hh=2 digit hour (00-24), 
          mm=2 digit minutes(00-59),
          ss=2 digit seconds (00-59)
          
  )** list of available cities :
<?
$adata = file("./data/indonesia.txt");
for($i=0; $i<count($adata); $i++){
  $dt[$i] = explode("!",$adata[$i]);
  echo "           ".str_pad($dt[$i][1],20)."   ".str_pad($dt[$i][0],3," ",STR_PAD_LEFT)."\n";
}; 
?>  
  
eg.:
  current date : data.php
  show this help : data.php?help=1
  prayertime for March 30,2006 : data.php?d=30&m=3&y=2006
  prayertime for April 2006 : data.php?p=2&m=4&y=2006
  prayertime for year 2007 : data.php?p=3&y=2007
  current date for Yogyakarta : data.php?id=308
</pre>
<?  
}else{
session_start();
include "class/class.config.php";
$cfg=new config();
$dtFile="data/".$cfg->cfgArray['country'].".txt";
$algo=isset($_GET['algo'])?$_GET['algo']:($cfg->cfgArray['algo']?$cfg->cfgArray['algo']:1);
include "class/class.adzan.main.php";
include "class/class.adzan.".$algo.".php";
$t=isset($_GET['t'])?$_GET['t']:"txt";
if($t=="xml"){
  include "class/class.xml.php";
  $Adzan=new xml($dtFile,isset($_GET['id'])?$_GET['id']:83);
}else{
  include "class/class.txt.php";
  $Adzan=new txt($dtFile,isset($_GET['id'])?$_GET['id']:83);
  $Adzan->set_parameter(isset($_GET['first'])?$_GET['first']:"");
}
$p=isset($_GET['p'])?$_GET['p']:1;
$d=isset($_GET['d'])?$_GET['d']:date("j");
$m=isset($_GET['m'])?$_GET['m']:date("n");
$y=isset($_GET['y'])?$_GET['y']:date("Y");
$filecontent=$Adzan->generate_data($p,$d,$m,$y,$cfg->cfgTime,"",1);
echo $filecontent;
}
?>













































































