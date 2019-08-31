<?php
/*
BISMILLAAHIRRAHMAANIRRAHIIM - In the Name of Allah, Most Gracious, Most Merciful
================================================================================
filename	:
purpose	:
create	: 2006/04/11
last edit	: 060411-12,070430,120118
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
if (!file_exists( 'config.php' ) || filesize( 'config.php' ) < 10) {
  $self = str_replace( '/export.php','', $_SERVER['PHP_SELF'] ). '/';
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
if(!isset($_POST['cmd']) ||(isset($_POST['cmd']) && $_POST['cmd']!=="save")){
echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?".">\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd" />
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
  <link rev="website" href="http://www.cahyadsn.com" />
  <link rel="shortcut icon" href="images/favicon.ico" />
	<link rel="stylesheet" href="style/<?php echo $cfg->cfgArray['style'];?>.css" type="text/css" />
  <script language="JavaScript" type="text/javascript">
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
      nt.innerHTML="Bulan ";
    }else if(f.period.value=="3"){
      td.disabled=true;
      tm.disabled=true;
      nt.innerHTML="Tahun ";
    }else{
      td.disabled=false;
      tm.disabled=false;
      trd.style.display="inline";
      trm.style.display="inline";
      nt.innerHTML="Tanggal ";
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

  function change_country(){
    var f=document.form;
    location.href='<?php echo basename($_SERVER['PHP_SELF']);?>?c='+f.adzanCountry.value;
  }
  </script>
</head>
<body>
<table width="640" align="center" cellpadding="2" cellspacing="0" style="border:dotted 1px #999999;margin-top:5px;margin-bottom:5px;">
<form action="<?php echo basename($_SERVER["PHP_SELF"]);?>" method="post" name="form" id="form">
  <tr>
    <td>
    <div id="ctr" align="center">
      <div class="header1"><?php echo $cfg->cfgArray['title'];?> ver. <?php echo $cfg->cfgArray['version'];?></div>
      <h1><?php echo _PERIOD;?></h1>
      <div class="install-text">
      <?php echo _EXPORTINFOPERIOD."\n";?>
      </div>
      <div class="install-form">
        <div class="form-block">
        <table class="content2">
          <tr><td class="table-header" colspan="2"><b><?php echo _PERIODOPTIONS;?></b></td></tr>
        </table>
        <table class="content2">
          <tr>
            <td>
            <div style="display:inline;">
            <?php echo _PERIOD;?>
            <select name="period" onChange="selectData();" class="inputbox2">
<?php         for($i=0;$i<3;$i++){
                echo "              <option value=\"".($i+1)."\">".$cfg->cfgPeriod[$i]."</option>\n";
              }
?>
            </select>
            </div>
            <div style="display:inline;" name="note" id="note"><?php echo _DATE;?></div>
            <div name="trd" id="trd" style="display:inline;">
              <input type="text" name="d" id="d" value="<?php echo date("j");?>" size="2" class="inputbox2" />
            </div>
            <div name="trm" id="trm" style="display:inline;">
              <select class="inputbox2" name="m" id="m">
<?php         for($i=0;$i<12;$i++){
                  echo "                <option value=\"".($i+1)."\"".(date("n")==($i+1)?" selected":"").">".$cfg->cfgMonth[$i]."</option>\n";
                }
?>
              </select>
            </div>
            <div style="display:inline;">
              <input type="text" name="y" id="y" value="<?php echo date("Y");?>" size="4" class="inputbox2" />
            </div>
            </td>
          </tr>
        </table>
        </div>
      </div>
      <div class="clr"></div>
      <h1><?php echo _EXPORTDATAFORMAT;?></h1>
      <div class="install-text">
        <?php echo _EXPORTINFOFORMAT;?><br />
        <input type="radio" name="radio" id="radio_dump" value="1" CHECKED onclick="if (this.checked) { hide_them_all(); document.getElementById('xml_options').style.display = 'block'; }; return true" />XML<br/>
        <input type="radio" name="radio" id="radio_dump" value="2" onclick="if (this.checked) { hide_them_all(); document.getElementById('excel_options').style.display = 'block'; }; return true" /><?php echo _EXPORTCSVEXCEL;?><br/>
        <input type="radio" name="radio" id="radio_dump" value="3" onclick="if (this.checked) { hide_them_all(); document.getElementById('csv_options').style.display = 'block'; }; return true" />CSV<br/>
        <input type="radio" name="radio" id="radio_dump" value="4" onclick="if (this.checked) { hide_them_all(); document.getElementById('txt_options').style.display = 'block'; }; return true" />TXT<br/>
      </div>
      <div class="install-form">
        <div class="form-block">
          <div id="csv_options">
            <table class="content2">
              <tr><td class="table-header" colspan="2"><b> <?php echo _EXPORTCSV;?></b></td></tr>
              <tr>
                <td width="140"><?php echo _EXPORTFIELDTERMINATED;?></td>
                <td><input type="text" name="fields_terminated" value=";" size="3" class="inputbox2" /></td>
              </tr>
              <tr>
                <td><?php echo _EXPORTFIELDENClOSED;?></td>
                <td><input type="text" name="fields_enclosed" value='"' size="3" class="inputbox2" /></td>
              </tr>
              <tr>
                <td><?php echo _EXPORTLINETERMINATED;?></td>
                <td><input type="text" name="lines_terminated" value="\n\r" size="3" class="inputbox2" /></td>
              </tr>
              <tr><td colspan="2"><input type="checkbox" name="first" value="1"><?php echo _EXPORTFIRST;?></td></tr>
            </table>
          </div>
          <div id="excel_options">
            <table class="content2">
              <tr><td class="table-header" colspan="2"><b> <?php echo _EXPORTEXCEL;?></b></td></tr>
              <tr><td colspan="2"><input type="checkbox" name="first" value="1"><?php echo _EXPORTFIRST;?></td></tr>
              <tr>
                <td width="140"><?php echo _EXPORTEXCELED;?></td>
                <td>
                  <select name="edition" class="inputbox2">
                    <option value="1">Windows</option>
                    <option value="2">Excel 2003/Macintosh</option>
                  </select>
                </td>
              </tr>
            </table>
          </div>
          <div id="xml_options">
            <table class="content2">
              <tr><td class="table-header"><b> <?php echo _EXPORTXML;?></b></td></tr>
              <tr>
                <td><?php echo _EXPORTXMLINFO;?></td>
              </tr>
            </table>
          </div>
          <div id="txt_options">
            <table class="content2">
              <tr><td class="table-header"><b> <?php echo _EXPORTTXT;?></b></td></tr>
              <tr><td><input type="checkbox" name="first" value="1"><?php echo _EXPORTFIRST;?></td></tr>
            </table>
          </div>
          <div>
            <table class="content2">
              <tr><td class="table-header"><b> <?php echo _EXPORTCOMPRESS;?></b></td></tr>
              <tr>
                <td>
                  <input type="radio" name="compress" id="compress" value="0" CHECKED /><?php echo _EXPORTNONE."\n";?>
                  <input type="radio" name="compress" id="compress" value="1" /><?php echo _EXPORTGZIP."\n";?>
                  <input type="radio" name="compress" id="compress" value="2" /><?php echo _EXPORTZIP."\n";?>
<?php if(function_exists("gzencode")){ ?>
                  <input type="radio" name="compress" id="compress" value="3" /><?php echo _EXPORTTGZ."\n";?>
<?php }
      if(function_exists("bzcompress")){ ?>
                  <input type="radio" name="compress" id="compress" value="4" /><?php echo _EXPORTBZ2."\n";?>
<?php } ?>
              </td>
            </tr>
          </table>
          </div>
        </div>
      </div>
      <div class="clr"></div>
      <h1><?php echo _EXPORTPARAMETER;?></h1>
      <div class="install-text">
        <p>
           <?php echo _EXPORTPARAMETERNOTES."\n";?>
           <br /><br />
           <?php echo _EXPORTPARAMETERVIEW."\n";?>
        </p>
      </div>
      <div class="install-form">
        <div class="form-block">
          <table class="content2">
            <tr>
              <td><?php echo _DEFAULTCOUNTRY;?></td>
              <td align="center">
                <select class="inputbox" name="adzanCountry" onChange="change_country();">
<?php
    $dirD="data";
    $country=array();
    if(is_dir($dirD)){
      if($dh=opendir($dirD)){
        $i=0;
        while(($fileC=readdir($dh)) !== false){
          if ($fileC !== '.' AND $fileC !== '..') {
            if (preg_match("/\.txt$/i", $fileC)){
              $country[$i]=substr($fileC,0,-4);
              $i++;
            }
          }
        }
        closedir($dh);
      }
    }
    sort($country);
    $defCountry=isset($_GET['c'])?$_GET['c']:$cfg->cfgArray['country'];
    for($j=0;$j<$i;$j++){
      echo "                  <option value=\"".$country[$j]."\"".($country[$j]==$defCountry?" selected":"").">".ucwords(strtolower($country[$j]))."</option>\n";
    }
?>                </select>
              </td>
            </tr>
            <tr>
              <td><?php echo _DEFAULTCITY;?></td>
              <td align="center">
                <select class="inputbox" name="adzanCity">
<?php
    $filecity="data/".$defCountry.".txt";
    $content = file($filecity);
    $cityCount = count($content);
    for($i=0;$i<$cityCount;$i++){
      $dtCity[$i]=explode("!",$content[$i]);
      $dtCityName[$i]=ucwords(strtolower($dtCity[$i][1]));
      $dtCityNo[$i]=$i;
    }
    array_multisort($dtCityName,SORT_ASC, SORT_STRING,$dtCityNo);
    $defCity=isset($_GET['c'])?0:$cfg->cfgArray['city'];
    for($i=0;$i<$cityCount;$i++){
      echo "                  <option value=\"".($dtCityNo[$i]+1)."\"".(($dtCityNo[$i]+1)==$defCity?" selected":"").">".ucwords(strtolower($dtCityName[$i]))."</option>\n";
    }
?>                </select>
                </td>
              </tr>
              <tr>
                <td><?php echo _LANGUAGE;?></td>
                <td align="center">
                  <select class="inputbox" name="language">
<?php
    $dir="lang";
    $lang=array();
    if(is_dir($dir)){
      if($dh=opendir($dir)){
        $i=0;
        while(($file=readdir($dh)) !== false){
          if ($file !== '.' AND $file !== '..') {
            if (preg_match("/\.lang.php$/i", $file)){
              $lang[$i]=substr($file,0,-9);
              $i++;
            }
          }
        }
        closedir($dh);
      }
    }
    sort($lang);
    for($j=0;$j<$i;$j++){
      echo "                    <option value=\"".$lang[$j]."\"".($lang[$j]==$cfg->cfgArray['lang']?" selected":"").">".ucwords(strtolower($lang[$j]))."</option>\n";
    }
?>                </select>
                </td>
              </tr>
              <tr>
                <td><?php echo _ALGORITHM;?></td>
                <td align="center">
                  <select class="inputbox" name="algo">
<?php
    include_once("administrator/common.inc.php");
    $dirA="class";
    $algos=array();
    if(is_dir($dirA)){
      if($dhA=opendir($dirA)){
        $i=0;
        while(($fileA=readdir($dhA)) !== false){
          if ($fileA !== '.' AND $fileA !== '..') {
            if (preg_match("/^class\.adzan\.[1-9]/i", $fileA)){
                $algos[$i]=substr($fileA,12,1);
                $i++;
            }
          }
        }
        closedir($dhA);
      }
    }
    sort($algos);
    for($j=0;$j<$i;$j++){
      echo "                    <option value=\"".$algos[$j]."\"".($algos[$j]==$cfg->cfgArray['algo']?" selected":"").">".ucwords(strtolower($algos[$j]).". ".$algo_name[$j])."</option>\n";
    }
?>                  </select>
                </td>
              </tr>
              <tr>
                <td colspan="2">
                  <table width="100%">
                    <tr valign="top">
                      <td width="50%">
                        <input type="checkbox" value="1" name="cbxViewParam"<?php echo ($cfg->cfgArray['viewParam']==''?"":" checked")." />"._SHOWPARAM;?><br />
<?php /*                        <input type="checkbox" value="1" name="cbxViewFiqh"<?php echo ($cfg->cfgArray['viewFiqh']==''?"":" checked")." />"._SHOWFIQH;?><br /> */?>
                        <input type="checkbox" value="1" name="cbxViewImsyak"<?php echo ($cfg->cfgArray['viewImsyak']==''?"":" checked")." />"._SHOWIMSYAK."\n";?>
                      </td>
                      <td width="50%">
                        <input type="checkbox" value="1" name="cbxViewSecond"<?php echo ($cfg->cfgArray['viewSecond']==''?"":" checked")." />"._SHOWSECOND;?><br />
                        <input type="checkbox" value="1" name="cbxViewSunrise"<?php echo ($cfg->cfgArray['viewSunrise']==''?"":" checked")." />"._SHOWSUNRISE."\n";?>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </div>
        </div>
      <div class="clr"></div>
      <h1><?php echo _FIQHOPTION;?></h1>
      <div class="install-text">
        <p><?php echo _FIQHNOTES;?></p>
      </div>
      <div class="install-form">
        <div class="form-block">
          <table class="content2">
            <tr>
              <td>
                <?php echo _OBSERVERHEIGHT?> <input type="text" name="observer_height" size="5" value="<?php echo (isset($cfg->cfgArray['observer_height'])?$cfg->cfgArray['observer_height']:"0");?>" class="inputNumber" /> <?php echo _METERS."\n";?>
              </td>
            </tr>
            <tr>
              <td>
                <fieldset>
                <legend><?php echo _DETERMINATIONOFFAJR;?></legend>
                <input type="Radio" name="fajr" value="0"<?php echo ($cfg->cfgArray['fajr']=="0"?" checked":"");?> /><input type="Text" name="fajr_depr" value="<?php echo $cfg->cfgArray['fajr_depr'];?>" size="3" class="inputNumber" /> deg. <?php echo _SUNDEPRESSION;?><br />
                <input type="Radio" name="fajr" value="1"<?php echo ($cfg->cfgArray['fajr']=="1"?" checked":"");?> /><input type="Text" name="fajr_interval" value="<?php echo $cfg->cfgArray['fajr_interval'];?>" size="3" class="inputNumber" /> min. <?php echo _INTERVALFROMSUNRISE;?>
                </fieldset>
              </td>
            </tr>
            <tr>
              <td>
                <fieldset>
                <legend><?php echo _DETERMINATIONOFASR;?></legend>
                <input type="Radio" name="ashr" value="0"<?php echo ($cfg->cfgArray['ashr']=="0"?" checked":"");?> /> <?php echo _SHAFIIANDOTHERS;?><br />
                <input type="Radio" name="ashr" value="1"<?php echo ($cfg->cfgArray['ashr']=="1"?" checked":"");?> /> Hanafi<br />
                <input type="Radio" name="ashr" value="2"<?php echo ($cfg->cfgArray['ashr']=="2"?" checked":"");?> /> <?php echo _SHADOWRATIO;?> <input type="Text" name="ashr_shadow" value="<?php echo $cfg->cfgArray['ashr_shadow'];?>" size="3" class="inputNumber" /><br />
                </fieldset>
              </td>
            </tr>
            <tr>
              <td>
                <fieldset>
                  <legend><?php echo _DETERMINATIONOFISHA;?></legend>
                  <input type="Radio" name="isha" value="0"<?php echo ($cfg->cfgArray['isha']=="0"?" checked":"");?> /><input type="Text" name="isha_depr" value="<?php echo $cfg->cfgArray['isha_depr'];?>" size="3" class="inputNumber" /> deg. <?php echo _SUNDEPRESSION;?><br />
                  <input type="Radio" name="isha" value="1"<?php echo ($cfg->cfgArray['isha']=="1"?" checked":"");?> /><input type="Text" name="isha_interval" value="<?php echo $cfg->cfgArray['isha_interval'];?>" size="3" class="inputNumber" /> min. <?php echo _INTERVALFROMSUNSET;?>
                </fieldset>
              </td>
            </tr>
            <tr>
              <td>
                <fieldset>
                  <legend><?php echo _DETERMINATIONOFIMSYAK;?></legend>
                  <input type="Radio" name="imsyak" value="0"<?php echo ($cfg->cfgArray['imsyak']=="0"?" checked":"");?> /><input type="Text" name="imsyak_depr" value="<?php echo $cfg->cfgArray['imsyak_depr'];?>" size="3" class="inputNumber" /> deg. <?php echo _IMSYAKDEPRESSION;?><br />
                  <input type="Radio" name="imsyak" value="1"<?php echo ($cfg->cfgArray['imsyak']=="1"?" checked":"");?> /><input type="Text" name="imsyak_interval" value="<?php echo $cfg->cfgArray['imsyak_interval'];?>" size="3" class="inputNumber" /> min. <?php echo _INTERVALFROMFAJR;?>
                </fieldset>
              </td>
            </tr>
            <tr>
              <td>
                <fieldset>
                  <legend><?php echo _DETERMINATIONOFIHTIYAT;?></legend>
                  <?php echo _IHTIYATFAJR;?> <input type="Text" name="ihtiyat_fajr" value="<?php echo $cfg->cfgArray['ihtiyat_fajr'];?>" size="2" class="inputNumber" /><?php echo _MINUTES;?><br />
                  <?php echo _IHTIYATDZUHR;?> <input type="Text" name="ihtiyat_dzuhr" value="<?php echo $cfg->cfgArray['ihtiyat_dzuhr'];?>" size="2" class="inputNumber" /><?php echo _MINUTES;?><br />
                  <?php echo _IHTIYATASHR;?> <input type="Text" name="ihtiyat_ashr" value="<?php echo $cfg->cfgArray['ihtiyat_ashr'];?>" size="2" class="inputNumber" /><?php echo _MINUTES;?><br />
                  <?php echo _IHTIYATMAGHRIB;?> <input type="Text" name="ihtiyat_maghrib" value="<?php echo $cfg->cfgArray['ihtiyat_maghrib'];?>" size="2" class="inputNumber" /><?php echo _MINUTES;?><br />
                  <?php echo _IHTIYATISHA;?> <input type="Text" name="ihtiyat_isha" value="<?php echo $cfg->cfgArray['ihtiyat_isha'];?>" size="2" class="inputNumber" /><?php echo _MINUTES;?><br />
                </fieldset>
              </td>
            </tr>            
          </table>
        </div>
      </div>
      <div class="clr"></div>
      <div id="break"></div>
      <div class="far-right">
        <input type="hidden" name="cmd" value="save" />
        <input class="button" type="submit" name="save" value="<?php echo _EXPORT;?>"/>
        <input class="button" type="reset" name="cancel" value="<?php echo _CANCEL;?>" onClick="javascript:location.href='index.php';"/>
      </div>
      <div class="clr"></div>
      <br />
      <div class="footer1">
        <a href="mailto:cahyadsn@yahoo.com" target="_blank">Adzan!</a> is Free Software released under the GNU/GPL License.
      </div>      
    </div>  
    </td>
  </tr>
</form>
</table>
<script language="javascript" type="text/javascript">
<!--
 show_checked_option();
//-->
</script>
</body>
</html>
<?php
}else{
  $r=$_POST['radio']?$_POST['radio']:1;
  $id=$_POST['adzanCity']?$_POST['adzanCity']:$cfg->cfgArray['city'];
  $country=$_POST['adzanCountry']?$_POST['adzanCountry']:$cfg->cfgArray['country'];
  $algo=$_POST['algo']?$_POST['algo']:($cfg->cfgArray['algo']?$cfg->cfgArray['algo']:1);
  include "class/class.adzan.main.php";
  include "class/class.adzan.".$algo.".php";
  $dtFile="data/".$country.".txt";
  if($r==1){
    include "class/class.xml.php";
    $Adzan=new xml($dtFile,$id);
    $ext=".xml";
  }else if($r==4){
    include "class/class.txt.php";
    $Adzan=new txt($dtFile,$id);
    $Adzan->set_parameter(isset($_POST['first'])?$_POST['first']:"");
    $ext=".txt";
  }else if($r==5){
    include "class/class.pdf.php";
    $Adzan=new pdf($dtFile,$id);
    $Adzan->set_parameter(isset($_POST['first'])?$_POST['first']:"");
    $ext=".pdf"; 
  }else{
    include "class/class.csv.php";
    $Adzan=new csv($dtFile,$id);
    if($_POST['edition']==1 && $r==2){
      $p1=",";
    }else{
      $p1=$_POST['fields_terminated'];
    }
    $ext=".csv";
    $Adzan->set_parameter($p1,$_POST['fields_enclosed'],$_POST['lines_terminated'],$_POST['first']);
  }
  $p=$_POST['period']?$_POST['period']:$Adzan->cfgArray['period'];
  $d=$_POST['d']?$_POST['d']:date("j");
  $m=$_POST['m']?$_POST['m']:date("n");
  $y=$_POST['y']?$_POST['y']:date("Y");
  $Adzan->set_fiqh_parameter_post();
  $filecontent=$Adzan->generate_data($p,$d,$m,$y,$cfg->cfgTime);
  $downloadfile=isset($file)?$file.$ext:"data".$ext;
  if($_POST['compress']==1){
    include "class/class.gzip.php";
    $gz=new gzip();
    $compress=$gz->compress($filecontent,$downloadfile);
    $downloadfile.=".gz";
  }elseif($_POST['compress']==2){
    include "class/class.zip.php";
    $zip=new zip();
    $compress=$zip->compress($filecontent,$downloadfile);
    $downloadfile.=".zip";
  }elseif($_POST['compress']==3){
    include "class/class.tgz.php";
    $tgz=new tgz();
    $compress=$tgz->compress($filecontent,$downloadfile);
    $downloadfile.=".tgz";
  }elseif($_POST['compress']==4){
    include "class/class.bz2.php";
    $bzip2=new bzip2();
    $compress=$bzip2->compress($filecontent,$downloadfile);
    $downloadfile.=".bz2";    
  }
  $thefile=$_POST['compress']?$compress:$filecontent;
  header("Content-disposition: attachment; filename=$downloadfile");
  header("Content-Type: application/force-download");
  header("Content-Transfer-Encoding: binary");
  header("Content-Length: ".strlen($thefile));
  header("Pragma: no-cache");
  header("Expires: 0");
  echo $thefile;
}
?>