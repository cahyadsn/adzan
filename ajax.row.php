<?php
/*
BISMILLAAHIRRAHMAANIRRAHIIM - In the Name of Allah, Most Gracious, Most Merciful
================================================================================
filename  : 
purpose  : 
create  : 2007/05/01
last edit  : 070501,0525,0611,080810,120116
author  : cahya dsn
================================================================================
This program is free software; you can redistribute it and/or modify it under the 
terms of the GNU General Public License as published by the Free Software 
Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY 
WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR 
A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

copyright (c) 2007 by cahya dsn; cahyadsn@yahoo.com
================================================================================
*/
session_start();
$pathChk="";
if (!file_exists( 'config.php' ) || filesize( 'config.php' ) < 10) {
  $self = str_replace( '/index.php','', $_SERVER['PHP_SELF'] ). '/';
  header("Location: http://" . $_SERVER['HTTP_HOST'] . $self . "installation/index.php" );
  exit();
}

if (file_exists( 'installation/index.php' )) {
  define( '_INSTALL_CHECK', 1 );
  header("Location: offline.php");
  exit();
}
include "class/class.config.php";
$cfg=new config();
echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?".">\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Adzan ver <?php echo $cfg->cfgArray['version'];?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta http-equiv="expires" content="0" />
<meta http-equiv="Pragma" content="no-cache" />
<meta name="generator" content="UltraEdit-32 Professional ver 11.20" />
<meta name="description" content="<?php echo $cfg->cfgArray['MetaDesc'];?>" />
<meta name="keywords" content="<?php echo $cfg->cfgArray['MetaKeys'];?>" />
<meta name="author" content="cahya dsn" />
<link rev="made" href="mailto:cahyadsn@yahoo.com" />
<link rev="website" href="http://www.cdsatrian.com" />
<link rel="shortcut icon" href="images/favicon.ico" />
<link rel="stylesheet" href="style/<?php echo $cfg->cfgArray['style'];?>.css" type="text/css" />
<style>
pre {text-indent: 30px} 

#tabmenu { 
color: #000; 
border-bottom: 1px solid #cccccc; 
margin: 12px 0px 0px 0px; 
padding: 0px; 
z-index: 1; 
padding-left: 10px } 

#tabmenu li { 
display: inline; 
overflow: hidden; 
list-style-type: none; } 

#tabmenu a, a.active { 
color: #cccccc; 
background: #525292;
font: normal 1em verdana, Arial, sans-serif; 
border: 1px solid #cccccc; 
padding: 2px 5px 0px 5px; 
margin: 0px; 
text-decoration: none;
cursor:hand; } 

#tabmenu a.active { 
color: #000099; 
background: #ffffff; 
border-bottom: 3px solid #ffffff; } 

#tabmenu a:hover { 
color: #ffffff; 
background: #9FC0AD; } 

#tabmenu a:visited { 
color: #E8E9BE; } 

#tabmenu a.active:hover { 
background: #ffffff; 
color: #DEDECF; } 

#content {
height:140px;
font: 0.9em/1.3em verdana, sans-serif; 
text-align: justify; 
background: #ffffff; 
padding: 2px; 
border: 1px solid #cccccc; 
border-top: none; 
z-index: 2; } 

#content a { 
text-decoration: none; 
color: #E8E9BE; } 

#content a:hover { background: #aaaaaa; }
</style>
</head>
<body>
<div id="mainContainer" align="center">
<?
$algo=$cfg->cfgArray['algo']?$cfg->cfgArray['algo']:1;
$d=isset($_GET['d'])?$_GET['d']:date("j");
$m=isset($_GET['m'])?$_GET['m']:date("n");
$y=isset($_GET['y'])?$_GET['y']:date("Y");
include "class/class.html.ajax.php";
$dtFile="data/".$cfg->cfgArray['country'].".txt";
$Adzan=new html_ajax($dtFile,isset($_GET['id'])?$_GET['id']:83);
$city_no=isset($_GET['id'])?$_GET['id']:83;
?>
<script type="text/javascript" language="JavaScript">
function callAHAH(url, pageElement, callMessage, errorMessage) {
     document.getElementById(pageElement).innerHTML = callMessage;
     try {
     req = new XMLHttpRequest(); /* e.g. Firefox */
     } catch(e) {
       try {
       req = new ActiveXObject("Msxml2.XMLHTTP");  /* some versions IE */
       } catch (e) {
         try {
         req = new ActiveXObject("Microsoft.XMLHTTP");  /* some versions IE */
         } catch (E) {
          req = false;
         } 
       } 
     }
     req.onreadystatechange = function() {responseAHAH(pageElement, errorMessage);};
     req.open("GET",url,true);
     req.send(null);
  }

function responseAHAH(pageElement, errorMessage) {
   var output = '';
   if(req.readyState == 4) {
      if(req.status == 200) {
         output = req.responseText;
         document.getElementById(pageElement).innerHTML = output;
         } else {
         document.getElementById(pageElement).innerHTML = errorMessage+"\n"+output;
         }
      }
  }

</script>
<table cellspacing="0" cellpadding="0" align="center">
<tr>
  <td>
    <form name="frmNav" style="margin:0px; padding:1px; border: solid 1px #999999;background:#ccccff;">
    <div class="dhtml_title2"><? $Adzan->print_city_selection(4,$m);?></div>
    <input type=hidden id="ta" value="1" />
<ul id="tabmenu" > 
<li onclick="makeactive(1)"><a class="" id="tab1">Adzan</a></li> 
<li onclick="makeactive(2)"><a class="" id="tab2">Qibla</a></li>
<li onclick="makeactive(3)"><a class="" id="tab3">Fiqh</a></li> 
</ul> 
<div id="content"></div>
</form>
</td>
</tr>
</table>
</div>
<script type="text/javascript" language="JavaScript">
  function makeactive(tab) { 
    var x=document.getElementById("city").value;
    document.getElementById("tab1").className = ""; 
    document.getElementById("tab2").className = ""; 
    document.getElementById("tab3").className = "";     
    document.getElementById("tab"+tab).className = "active";
    document.getElementById("ta").value=tab;
    if(tab==1){
      callAHAH('ajax/ajax.daily1.php?id='+x, 'content','getting content for<br />Adzan tab. Wait...', 'Error'); 
    }else if(tab==2){
      callAHAH('ajax/ajax.qibla.php?id='+x, 'content','getting content for<br />Qibla tab. Wait...', 'Error'); 
    }else{
      callAHAH('ajax/ajax.param.php', 'content','getting content for<br/>About tab. Wait...', 'Error'); 
    }
  } 
  makeactive(1);
  function change_page(){
    makeactive(document.getElementById("ta").value);
  }

</script>
</body>
</html>