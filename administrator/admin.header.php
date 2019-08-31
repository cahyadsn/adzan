<?php
/*
BISMILLAAHIRRAHMAANIRRAHIIM - In the Name of Allah, Most Gracious, Most Merciful
================================================================================
filename	: 
purpose	: 
create	: 2007/12/18
last edit	: 071218,080109
author	: cahya dsn
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
echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?".">\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Adzan - Administrator</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="shortcut icon" href="../images/favicon.ico" />
<link rel="stylesheet" href="admin.css" type="text/css" />
<script type="text/javascript" language="JavaScript">
<!--
<?php if(isset($script) && $script==1){ ?>
function check() {
  var formValid = true;
  var f = document.form;
  if ( f.txtAdminUser.value == '' ) {
    alert('Please enter an username for administrator');
    f.txtAdminUser.focus();
    formValid = false;
  } else if ( f.txtAdminPassword.value == '' ) {
    alert('Please enter a password for your administrator');
    f.txtAdminPassword.focus();
    formValid = false;
  } else if (f.txtAdminPassword.value.length<=5 ) {
    alert('Please enter your password more than 5 char');
    f.txtAdminPassword.focus();
    formValid = false;
  }
  return formValid;
}
<?php }elseif(isset($script) && $script==2){?>
function check() {
  var formValid = true;
  var f = document.form;
  if (f.txtNewName.value == '' && f.txtOldPassword.value == '' && f.txtNewPassword.value == '' && f.txtConfirmPassword.value == '' ) {
      alert('No new values will be save');
      f.txtNewName.focus();
      formValid = false;
  }
  if (f.txtNewName.value != '') {
    if (f.txtNewName.value.length<5 ) {
      alert('Please enter your username more than 4 char');
      f.txtNewName.focus();
      formValid = false;
    }
  } else if ( f.txtNewPassword.value != '' ) {
    if(f.txtOldPassword.value == ''){
      alert('Please enter your old password');
      f.txtOldPassword.focus();
      formValid = false;
    }else if (f.txtNewPassword.value.length<=5 ) {
      alert('Please enter your new password more than 5 char');
      f.txtNewPassword.focus();
      formValid = false;
    }else if (f.txtNewPassword.value!=f.txtConfirmPassword.value ) {
      alert('Please enter the same value for New and Confirm password');
      f.txtNewPassword.focus();
      formValid = false;
    }
  }
  return formValid;
}
<?php }elseif(isset($script) && $script==3){ ?>
function selectData(){
  var f=document.form;
  var td=document.getElementById("d");
  var tm=document.getElementById("m");
  var trd=document.getElementById("trd");
  var trm=document.getElementById("trm");
  var nt=document.getElementById("note");
  trd.style.display="none";
  trm.style.display="none";
  if(f.period.value=="2"){
    td.disabled=true;
    tm.disabled=false;
    trm.style.display="inline";
    nt.innerHTML="<?php echo _MONTH;?> ";
  }else if(f.period.value=="3"){
    td.disabled=true;
    tm.disabled=true;
    nt.innerHTML="<?php echo _YEAR;?> ";
  }else{
    td.disabled=false;
    tm.disabled=false;
    trd.style.display="inline";
    trm.style.display="inline";
    nt.innerHTML="<?php echo _DATE;?> ";
  }
}

function hide_them_all() {
  document.getElementById("csv_options").style.display = 'none';
  document.getElementById("excel_options").style.display = 'none';
  document.getElementById("xml_options").style.display = 'none';
  document.getElementById("txt_options").style.display = 'none';
}

function show_checked_option() {
  hide_them_all();
  if (document.getElementById('radio_dump').value=="1") {
    document.getElementById('xml_options').style.display = 'block';
  } else if (document.getElementById('radio_dump').value=="2") {
    document.getElementById('csv_options').style.display = 'block';
  } else if (document.getElementById('radio_dump').value=="3") {
    document.getElementById('excel_options').style.display = 'block';
  } else if (document.getElementById('radio_dump').value=="4") {
    document.getElementById('txt_options').style.display = 'block';
  }
}
<?php }elseif(isset($script) && $script==4){ ?>
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
<?php } ?>
//-->
</script>
</head>
<body>
<div id="ctr" align="center">
  <div class="header"><?php echo $_SESSION['shortname'];?> ver. <?php echo $_SESSION['version'];?></div>
<?php if(isset($script) && $script==1){ ?>
  <form action="index.php" method="post" name="form" id="form" onsubmit="return check();">
<?php }elseif(isset($script) &&  $script==3){ ?>    
  <form action="data.php" method="post" name="form" id="form">  
<?php } ?>    
  <div class="install">
  <div id="menubar">
<?php include("menu.php");?>
  </div>
  <div id="right">