<?php
/*
BISMILLAAHIRRAHMAANIRRAHIIM - In the Name of Allah, Most Gracious, Most Merciful
================================================================================
filename	: 
purpose	: 
create	: 2006/03/09
last edit	: 060317,0322,070430,120116
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
session_start();
include "../class/class.config.php";
$cfg=new config("../");
$dtFile="../data/".$cfg->cfgArray['country'].".txt";
$r=isset($_POST['radio'])?$_POST['radio']:1;
$algo=$cfg->cfgArray['algo']?$cfg->cfgArray['algo']:1;
include "../class/class.adzan.main.php";
include "../class/class.adzan.".$algo.".php";
if($r==1){
  include "../class/class.xml.php";
  $Adzan=new xml($dtFile,$_POST['id'],"../","administrator");
  $ext=".xml";
}else if($r==4){
  include "../class/class.txt.php";
  $Adzan=new txt($dtFile,$_POST['id'],"../","administrator");
  $Adzan->set_parameter($_POST['first']);
  $ext=".txt";
}else{
  include "../class/class.csv.php";
  $Adzan=new csv($dtFile,$_POST['id'],"../","administrator");
  if($_POST['edition']==1 && $r==2){
    $p1=",";
  }else{
    $p1=$_POST['fields_terminated'];
  }
  $ext=".csv";
  $Adzan->set_parameter($p1,$_POST['fields_enclosed'],$_POST['lines_terminated'],$_POST['first']);
}
$p=isset($_POST['period'])?$_POST['period']:$Adzan->cfgArray['period'];
$d=isset($_POST['d'])?$_POST['d']:date("j");
$m=isset($_POST['m'])?$_POST['m']:date("n");
$y=isset($_POST['y'])?$_POST['y']:date("Y");
$filecontent=$Adzan->generate_data($p,$d,$m,$y,$Adzan->cfgTime,"administrator/");
$downloadfile=$file?$file.$ext:"data".$ext;
if(isset($_POST['compress']) && $_POST['compress']==1){
  include "../class/class.gzip.php";
  $gz=new gzip();
  $compress=$gz->compress($filecontent,$downloadfile);
  $downloadfile.=".gz";
}elseif(isset($_POST['compress']) && $_POST['compress']==2){
  include "../class/class.zip.php";
  $zip=new zip();
  $compress=$zip->compress($filecontent,$downloadfile);
  $downloadfile.=".zip";
}elseif(isset($_POST['compress']) && $_POST['compress']==3){
  include "../class/class.tgz.php";
  $tgz=new tgz();
  $compress=$tgz->compress($filecontent,$downloadfile);
  $downloadfile.=".tgz";
}elseif(isset($_POST['compress']) && $_POST['compress']==4){
  include "../class/class.bz2.php";
  $bzip2=new bzip2();
  $compress=$bzip2->compress($filecontent,$downloadfile);
  $downloadfile.=".bz2";    
}
$thefile=isset($_POST['compress'])?$compress:$filecontent;
header("Content-disposition: attachment; filename=$downloadfile");
header("Content-Type: application/force-download");
header("Content-Transfer-Encoding: binary");
header("Content-Length: ".strlen($thefile));
header("Pragma: no-cache");
header("Expires: 0");
echo $thefile;
?>