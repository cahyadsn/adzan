<?php
/*
BISMILLAAHIRRAHMAANIRRAHIIM - In the Name of Allah, Most Gracious, Most Merciful
================================================================================
filename	: install.header.php
purpose	: 
create	: 2007/12/11
last edit	: 071211,110621
author	: cahya dsn
================================================================================
This program is free software; you can redistribute it and/or modify it under the 
terms of the GNU General Public License as published by the Free Software 
Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY 
WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR 
A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

copyright (c) 2007-2011 by cahya dsn; cahyadsn@yahoo.com
================================================================================
*/
echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?".">";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Adzan - Web Installer - <?php if(($f[1]!="index")&&($f[1]!="0")){ echo "Step ".$f[1];} ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="shortcut icon" href="../images/favicon.ico" />
<link rel="stylesheet" href="install.css" type="text/css" />
<script language="JavaScript" type="text/javascript">
<?php if($f[1]=="1"){ ?>  
function changeCountry(){
  var f=document.adminForm;
  location.href="install1.php?c="+f.adzanCountry.value;
}
function change_method(){
  var f=document.adminForm;
  var m=f.method.value;
  if(m==1){
    f.fajr[0].checked=true;
    f.fajr_depr.value="20.0";
    f.ashr[0].checked=true;
    f.ashr_shadow.value="1.0";
    f.isha[0].checked=true;
    f.isha_depr.value="18.0";    
  }else if(m==2){
    f.fajr[0].checked=true;
    f.fajr_depr.value="18.0";
    f.ashr[0].checked=true;
    f.ashr_shadow.value="1.0";    
    f.isha[0].checked=true;
    f.isha_depr.value="18.0";       
  }else if(m==3){
    f.fajr[0].checked=true;
    f.fajr_depr.value="18.0";
    f.ashr[1].checked=true;
    f.ashr_shadow.value="2.0";    
    f.isha[0].checked=true;
    f.isha_depr.value="18.0";       
  }else if(m==4){
    f.fajr[0].checked=true;
    f.fajr_depr.value="15.0";
    f.ashr[0].checked=true;
    f.ashr_shadow.value="1.0";   
    f.isha[0].checked=true;
    f.isha_depr.value="15.0";        
  }else if(m==5){
    f.fajr[0].checked=true;
    f.fajr_depr.value="18.0";
    f.ashr[0].checked=true;
    f.ashr_shadow.value="1.0";
    f.isha[0].checked=true;
    f.isha_depr.value="17.0";           
  }else if(m==6){
    f.fajr[0].checked=true;
    f.fajr_depr.value="19.0";
    f.ashr[0].checked=true;
    f.ashr_shadow.value="1.0";    
    f.isha[1].checked=true;
    f.isha_interval.value="90.0";
  }else if(m==7){
    f.fajr[0].checked=true;
    f.fajr_depr.value="19.5";
    f.ashr[0].checked=true;
    f.ashr_shadow.value="1.0";    
    f.isha[1].checked=true;
    f.isha_interval.value="90.0";
  }else{
    f.fajr[0].checked=true;
    f.fajr_depr.value="20.0";
    f.ashr[0].checked=true;
    f.ashr_shadow.value="1.0";    
    f.isha[0].checked=true;
    f.isha_depr.value="18.0";
  }    
}
<?php }elseif($m==2){ ?>
function check() {
  var formValid = true;
  var f = document.form;
  if ( f.siteUrl.value == '' ) {
    alert('Please enter Site URL');
    f.siteUrl.focus();
    formValid = false;
  } else if ( f.absolutePath.value == '' ) {
    alert('Please enter the absolute path to your site');
    f.absolutePath.focus();
    formValid = false;
  } else if ( f.adminEmail.value == '' ) {
    alert('Please enter an email address to contact your administrator');
    f.adminEmail.focus();
    formValid = false;
  } else if ( f.adminUser.value == '' ) {
    alert('Please enter an username for administrator');
    f.adminUser.focus();
    formValid = false;
  } else if ( f.adminUser.value.length<5) {
    alert('Please enter an username more than 4 char');
    f.adminUser.focus();
    formValid = false;
  } else if ( f.adminPassword.value == '' ) {
    alert('Please enter a password for your administrator');
    f.adminPassword.focus();
    formValid = false;
  } else if ( f.adminPassword.value != f.adminPassword2.value ) {
    alert('Please enter the same value for your password');
    f.adminPassword.focus();
    formValid = false;
  } else if (f.adminPassword.value.length<=5 ) {
    alert('Please enter your password more than 5 char');
    f.adminPassword.focus();
    formValid = false;
  }
  return formValid;
}
function changeFilePermsMode(mode){
  if(document.getElementById) {
    switch (mode) {
      case 0:
        document.getElementById('filePermsFlags').style.display = 'none';
        break;
      default:
        document.getElementById('filePermsFlags').style.display = '';
    }
  }
}
function changeDirPermsMode(mode){
  if(document.getElementById) {
    switch (mode) {
      case 0:
        document.getElementById('dirPermsFlags').style.display = 'none';
        break;
      default:
        document.getElementById('dirPermsFlags').style.display = '';
    }
  }
}  
<?php } ?>
</script>
</head>
<body>
<div id="ctr" align="center">
<div class="header">Adzan ver. <?php echo $install_ver?></div>
<form action="<?php echo $f[0]?>" method="post" name="adminForm" id="adminForm">
<div class="install">
<div id="stepbar">
<div class="<?php echo ($f[1]=="index"?"step-on":"step-off");?>">pre-installation check</div>
<div class="<?php echo ($f[1]=="0"?"step-on":"step-off");?>">license</div>
<div class="<?php echo ($f[1]=="1"?"step-on":"step-off");?>">step 1</div>
<div class="<?php echo ($f[1]=="2"?"step-on":"step-off");?>">step 2</div>
<div class="<?php echo ($f[1]=="3"?"step-on":"step-off");?>">step 3</div>
</div>
<div id="right">