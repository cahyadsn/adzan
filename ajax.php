<?
/*
BISMILLAAHIRRAHMAANIRRAHIIM - In the Name of Allah, Most Gracious, Most Merciful
================================================================================
filename  : 
purpose  : 
create  : 2007/05/01
last edit  : 070501,0525,0611,080901,120117
author  : cahya dsn
================================================================================
This program is free software; you can redistribute it and/or modify it under the 
terms of the GNU General Public License as published by the Free Software 
Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY 
WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR 
A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

copyright (c) 2007-2012 by cahya dsn; cahyadsn@yahoo.com
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
<script type="text/javascript" src="ajax/ajax.js"></script>
</head>
<body>
<div id="mainContainer" align="center">
<?
$algo=$cfg->cfgArray['algo']?$cfg->cfgArray['algo']:1;
$d=isset($_GET['d'])?$_GET['d']:date("j");
$m=isset($_GET['m'])?$_GET['m']:date("n");
$y=isset($_GET['y'])?$_GET['y']:date("Y");
$type=$cfg->cfgArray['period'];
include "class/class.html.ajax.php";
$dtFile="data/".$cfg->cfgArray['country'].".txt";
$Adzan=new html_ajax($dtFile,isset($_GET['id'])?$_GET['id']:83);
$city_no=isset($_GET['id'])?$_GET['id']:83;
?>
<table cellspacing="0" cellpadding="0" class="table_adzan" align="center">
<tr>
  <td>
    <div id="header">loading content ...</div></td>
</tr>
<tr>
  <td>
    <form name="frmNav" style="margin:0px; padding:0px;">
      <div class="table_navigasi" align="center">
        <table cellspacing="0" cellpadding="2" width="100%">
          <tr class="table_navigasi">
            <td align="left">
              <div id="nav_left">loading content ...</div>
            </td>
            <td align="center">
              <? $Adzan->print_city_selection($type,$m);?>
            </td>
            <td align="right">
              <div id="nav_right">loading content ...</div>
            </td>
          </tr>
        </table>
      </div>
    </form>
  </td>
</tr>
<tr>
  <td>
    <div class="dhtml_title"><b>&nbsp;:: Prayer Time</b></div>
    <div class="dhtml_content">
      <div>
    <form name="frmContent" style="margin:0px; padding:0px;">
      <div id="content">loading content ...</div>
    </form>  
  </div>
  </div>
</td>
</tr>
<tr>
  <td>
    <div class="dhtml_title"><b>&nbsp;:: Parameter</b></div>
    <div class="dhtml_content">
	    <div>    
    <form name="frmParameter" style="margin:0px; padding:0px;">
      <div id="parameter">loading parameter ...</div>
    </form>  
  </div>
  </div>
</td>
</tr>
<tr>
  <td>
    <div class="dhtml_title"><b>&nbsp;:: Fiqh</b></div>
    <div class="dhtml_content">
	    <div>    
    <form name="frmFiqh" style="margin:0px; padding:0px;">
      <div id="fiqh">loading parameter ...</div>
    </form>  
  </div>
  </div>
</td>
</tr>
<tr>
  <td>
    <? $Adzan->print_footer($type,$m);?>
  </td>
</tr>
</table>
</div>
<script type="text/javascript" language="JavaScript">
  ajax_loadContent('header','ajax/ajax.header.php?id=<?=$city_no?>');
  ajax_loadContent('nav_left','ajax/ajax.nav.left.php?id=<?=$city_no?>&t=<?=$type?>&d=<?=$d?>&m=<?=$m?>&y=<?=$y?>');
  ajax_loadContent('nav_right','ajax/ajax.nav.right.php?id=<?=$city_no?>&t=<?=$type?>&d=<?=$d?>&m=<?=$m?>&y=<?=$y?>');
  ajax_loadContent('parameter','ajax/ajax.parameter.php?id=<?=$city_no?>');
  ajax_loadContent('fiqh','ajax/ajax.fiqh.php?id=<?=$city_no?>');
  ajax_loadContent('content','ajax/ajax.content.php?id=<?=$city_no?>');
  initShowHideDivs();
  showHideContent(false,1);
  
  function change_page(){
    var x=document.getElementById("city").value;
    var y=document.getElementById("yr").value;
    var m=document.getElementById("mn").value;
    var d=document.getElementById("dt").value;
    var t=document.getElementById("tp").value;
    ajax_loadContent('content','ajax/ajax.content.php?id='+x+'&d='+d+'&m='+m+'&y='+y);
    ajax_loadContent('header','ajax/ajax.header.php?id='+x+'&d='+d+'&m='+m+'&y='+y);
    ajax_loadContent('nav_left','ajax/ajax.nav.left.php?id='+x+'&t='+t+'&d='+d+'&m='+m+'&y='+y);
    ajax_loadContent('nav_right','ajax/ajax.nav.right.php?id='+x+'&t='+t+'&d='+d+'&m='+m+'&y='+y);
    ajax_loadContent('parameter','ajax/ajax.parameter.php?id='+x);
    ajax_loadContent('fiqh','ajax/ajax.fiqh.php?id='+x);
  }
  
  function change_nav(x,t,d,m,y){
    ajax_loadContent('header','ajax/ajax.header.php?id='+x+'&d='+d+'&m='+m+'&y='+y);
    ajax_loadContent('content','ajax/ajax.content.php?id='+x+'&d='+d+'&m='+m+'&y='+y);
    ajax_loadContent('nav_left','ajax/ajax.nav.left.php?id='+x+'&t='+t+'&d='+d+'&m='+m+'&y='+y);
    ajax_loadContent('nav_right','ajax/ajax.nav.right.php?id='+x+'&t='+t+'&d='+d+'&m='+m+'&y='+y);
    ajax_loadContent('parameter','ajax/ajax.parameter.php?id='+x);
    ajax_loadContent('fiqh','ajax/ajax.fiqh.php?id='+x);
  }
</script>
</body>
</html>