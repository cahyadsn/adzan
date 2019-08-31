<?php
/*
BISMILLAAHIRRAHMAANIRRAHIIM - In the Name of Allah, Most Gracious, Most Merciful
================================================================================
filename	: install3.php
purpose	: 
create	: 2006/02/27
last edit	: 060317,060323,1124,070430,1211,1217,110621
author	: cahya dsn
================================================================================
This program is free software; you can redistribute it and/or modify it under the 
terms of the GNU General Public License as published by the Free Software 
Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY 
WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR 
A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

copyright (c) 2006-2011 by cahya dsn; cahyadsn@yahoo.com
================================================================================
*/
include_once("common.inc.php");
if (file_exists( '../config.php' ) && filesize( '../config.php' ) > 10) {
  header( "Location: ../index.php" );
  exit();
}
$siteUrl    = $_POST['siteUrl'];
$absolutePath = $_POST['absolutePath'];
$adminEmail = $_POST['adminEmail'];
$adminUser = $_POST['adminUser'];
$adminPassword = $_POST['adminPassword'];
$language = $_POST['language'];
$filePerms = '';
if ($_POST['filePermsMode'])
  $filePerms = '0'.
    (($_POST['filePermsUserRead']?$_POST['filePermsUserRead']:0)* 4 +
     ($_POST['filePermsUserWrite']?$_POST['filePermsUserWrite']:0) * 2 +
     ($_POST['filePermsUserExecute']?$_POST['filePermsUserExecute']:0)).
    (($_POST['filePermsGroupRead']?$_POST['filePermsGroupRead']:0) * 4 +
     ($_POST['filePermsGroupWrite']?$_POST['filePermsGroupWrite']:0) * 2 +
     ($_POST['filePermsGroupExecute']?$_POST['filePermsGroupExecute']:0)).
    (($_POST['filePermsWorldRead']?$_POST['filePermsWorldRead']:0) * 4 +
     ($_POST['filePermsWorldWrite']?$_POST['filePermsWorldWrite']:0) * 2 +
     ($_POST['filePermsWorldExecute']?$_POST['filePermsWorldExecute']:0));

$dirPerms = '';
if ($_POST['dirPermsMode'])
  $dirPerms = '0'.
    (($_POST['dirPermsUserRead']?$_POST['dirPermsUserRead']:0) * 4 +
     ($_POST['dirPermsUserWrite']?$_POST['dirPermsUserWrite']:0) * 2 +
     ($_POST['dirPermsUserSearch']?$_POST['dirPermsUserSearch']:0)).
    (($_POST['dirPermsGroupRead']?$_POST['dirPermsGroupRead']:0) * 4 +
     ($_POST['dirPermsGroupWrite']?$_POST['dirPermsGroupWrite']:0) * 2 +
     ($_POST['dirPermsGroupSearch']?$_POST['dirPermsGroupSearch']:0)).
    (($_POST['dirPermsWorldRead']?$_POST['dirPermsWorldRead']:0) * 4 +
     ($_POST['dirPermsWorldWrite']?$_POST['dirPermsWorldWrite']:0) * 2 +
     ($_POST['dirPermsWorldSearch']?$_POST['dirPermsWorldSearch']:0));
if ((trim($adminEmail== "")) || (preg_match("/[\w\.\-]+@\w+[\w\.\-]*?\.\w{1,4}/", $adminEmail )==false)) {
  echo "<html>\n"
      ."<body>\n"
      ."<form name=\"stepBack\" id=\"stepBack\" method=\"post\" action=\"install2.php\">\n"
      ."<input type=\"hidden\" name=\"siteUrl\" value=\"$siteUrl\" />\n"
      ."<input type=\"hidden\" name=\"absolutePath\" value=\"$absolutePath\" />\n"
      ."<input type=\"hidden\" name=\"adminEmail\" value=\"$adminEmail\" />\n"
      ."<input type=\"hidden\" name=\"adminUser\" value=\"$adminUser\" />\n"
      ."<input type=\"hidden\" name=\"filePerms\" value=\"$filePerms\" />\n"
      ."<input type=\"hidden\" name=\"dirPerms\" value=\"$dirPerms\" />\n"
      ."</form>\n"
      ."<script language=\"JavaScript\" type=\"text/javascript\">\n"
      ."var frm=document.getElementById('stepBack');\n"
      ."alert('You must provide a valid admin email address.');\n"
      ."frm.submit();\n"
      ."</script>\n"
      ."</body>\n"
      ."</html>";
  return;
}
if (file_exists( '../config.php' )) {
  $canWrite = is_writable( '../config.php' );
} else {
  $canWrite = is_writable( '..' );
}
function chmodRecursive($path, $filemode=NULL, $dirmode=NULL) {
  $ret = TRUE;
  if (is_dir($path)) {
    $dh = opendir($path);
    while ($file = readdir($dh)) {
      if ($file != '.' && $file != '..') {
        $fullpath = $path.'/'.$file;
        if (is_dir($fullpath)) {
          if (!chmodRecursive($fullpath, $filemode, $dirmode))
            $ret = FALSE;
        } else {
          if (isset($filemode))
            if (!@chmod($fullpath, $filemode))
              $ret = FALSE;
        }
      }
    }
    closedir($dh);
    if (isset($dirmode))
      if (!@chmod($path, $dirmode))
        $ret = FALSE;
  } else {
    if (isset($filemode))
      $ret = @chmod($path, $filemode);
  }
  return $ret;
}
if ($siteUrl) {
  $configArray['siteUrl']=$siteUrl;
  $absolutePath= str_replace("\\","/", $absolutePath);
  $absolutePath= str_replace("//","/", $absolutePath);
  $configArray['absolutePath']=$absolutePath;
  $configArray['filePerms']=$filePerms;
  $configArray['dirPerms']=$dirPerms;
  $configArray['secret']=substr($adminUser,0,3).$adminPassword.substr($adminUser,3);
  $config = "<?php\n";
  $config .= "\$adzanCfg_title = 'Adzan';\n";
  $config .= "\$adzanCfg_version = '".$install_ver."';\n";
  $config .= "\$adzanCfg_offline = '0';\n";
  $config .= "\$adzanCfg_lang = '".$language."';\n";
  $config .= "\$adzanCfg_absolute_path = '{$configArray['absolutePath']}';\n";
  $config .= "\$adzanCfg_live_site = '{$configArray['siteUrl']}';\n";
  $config .= "\$adzanCfg_MetaDesc = 'Adzan - islamic prayer time schedule ';\n";
  $config .= "\$adzanCfg_MetaKeys = 'Adzan, adzan';\n";
  $config .= "\$adzanCfg_locale = 'en_GB';\n";
  $config .= "\$adzanCfg_user = '".$adminUser."';\n";
  $config .= "\$adzanCfg_secret = '" . md5($configArray['secret']) . "';\n";
  $config .= "\$adzanCfg_member = '';\n";
  $config .= "\$adzanCfg_register = '';\n";
  $config .= "\$adzanCfg_favicon = 'favicon.ico';\n";
  $config .= "\$adzanCfg_style = '".$_POST['adzanStyle']."';\n";
  $config .= "\$adzanCfg_algo = '".$_POST['adzanAlgorithm']."';\n";
  $config .= "\$adzanCfg_observe_height = '0';\n";
  $config .= "\$adzanCfg_country = '".$_POST['adzanCountry']."';\n";
  $config .= "\$adzanCfg_fileperms = '".$configArray['filePerms']."';\n";
  $config .= "\$adzanCfg_dirperms = '".$configArray['dirPerms']."';\n";
  $config .= "\$adzanCfg_viewParam= '".$_POST['viewParam']."';\n";
  $config .= "\$adzanCfg_viewQibla= '".$_POST['viewQibla']."';\n";
  $config .= "\$adzanCfg_viewFiqh= '".$_POST['viewFiqh']."';\n";
  $config .= "\$adzanCfg_viewImsyak= '".$_POST['viewImsyak']."';\n";
  $config .= "\$adzanCfg_viewSunrise= '".$_POST['viewSunrise']."';\n";
  $config .= "\$adzanCfg_viewSecond= '".$_POST['viewSecond']."';\n";
  $config .= "\$adzanCfg_period= '".$_POST['adzanPeriod']."';\n";
  $config .= "\$adzanCfg_city= '".$_POST['adzanCity']."';\n";
  $config .= "\$adzanCfg_fajr_depr= '".$_POST['fajr_depr']."';\n";
  $config .= "\$adzanCfg_fajr = '".$_POST['fajr']."';\n";
  $config .= "\$adzanCfg_fajr_interval= '".$_POST['fajr_interval']."';\n";
  $config .= "\$adzanCfg_ashr= '".$_POST['ashr']."';\n";
  $config .= "\$adzanCfg_ashr_shadow= '".$_POST['ashr_shadow']."';\n";
  $config .= "\$adzanCfg_isha_depr= '".$_POST['isha_depr']."';\n";
  $config .= "\$adzanCfg_isha= '".$_POST['isha']."';\n";
  $config .= "\$adzanCfg_isha_interval= '".$_POST['isha_interval']."';\n";
  $config .= "\$adzanCfg_imsyak_depr= '".$_POST['imsyak_depr']."';\n";
  $config .= "\$adzanCfg_imsyak= '".$_POST['imsyak']."';\n";
  $config .= "\$adzanCfg_imsyak_interval= '".$_POST['imsyak_interval']."';\n";
  $config .= "\$adzanCfg_time_adjust= '".$_POST['time_adjust']."';\n";
  $config .= "\$adzanCfg_ihtiyat_fajr= '".$_POST['ihtiyat_fajr']."';\n";
  $config .= "\$adzanCfg_ihtiyat_dzuhr= '".$_POST['ihtiyat_dzuhr']."';\n";
  $config .= "\$adzanCfg_ihtiyat_ashr= '".$_POST['ihtiyat_ashr']."';\n";
  $config .= "\$adzanCfg_ihtiyat_maghrib= '".$_POST['ihtiyat_maghrib']."';\n";
  $config .= "\$adzanCfg_ihtiyat_isha= '".$_POST['ihtiyat_isha']."';\n";
  $config .= "setlocale (LC_TIME, \$adzanCfg_locale);\n";
  $config .= "?>";
  if ($canWrite && ($fp = fopen("../config.php", "w"))) {
    fputs( $fp, $config, strlen( $config ) );
    fclose( $fp );
  } else {
    $canWrite = false;
  }
  $chmod_report = "Directory and file permissions left unchanged.";
  if ($filePerms != '' || $dirPerms != '') {
    $rootfiles = array(
      'data',
      'config.php'
    );
    $filemode = NULL;
    if ($filePerms != '') $filemode = octdec($filePerms);
    $dirmode = NULL;
    if ($dirPerms != '') $dirmode = octdec($dirPerms);
    $chmodOk = TRUE;
    foreach ($rootfiles as $file) {
      if (!chmodRecursive($absolutePath.'/'.$file, $filemode, $dirmode)) {
        $chmodOk = FALSE;
      }
    }
    if ($chmodOk) {
      $chmod_report = 'File and directory permissions successfully changed.';
    } else {
      $chmod_report = 'File and directory permissions could not be changed.<br />'.
              'Please CHMOD Adzan files and directories manually.';
    }
  }
} else {
?>
<html>
<body>
  <form action="install2.php" method="post" name="stepBack1" id="stepBack1">
    <input type="hidden" name="siteUrl" value="<?php echo $siteUrl;?>" />
    <input type="hidden" name="absolutePath" value="<?php echo $absolutePath;?>" />
    <input type="hidden" name="adminEmail" value="<?php echo $adminEmail;?>" />
    <input type="hidden" name="adminUser" value="<?php echo $adminUser;?>" />
    <input type="hidden" name="filePerms" value="<?php echo $filePerms;?>" />
    <input type="hidden" name="dirPerms" value="<?php echo $dirPerms;?>" />
  </form>
  <script language="JavaScript" type="text/javascript">
  var frm=document.getElementById('stepBack1');
  alert('The site url has not been provided');
  frm.submit();
  </script>
</body>
</html>
<?php
}
$f=array("#","3");
include "install.header.php";
?>
      <div id="step">step 3</div>
      <div class="far-right">
        <input class="button" type="button" name="runSite" value="View Site"
<?php
        if ($siteUrl) {
          echo "onClick='window.location.href=\"$siteUrl"."/index.php\" '";
        } else {
          echo "onClick='window.location.href=\"{$configArray['siteURL']}"."/index.php\" '";
        }
?>/>
        <input class="button" type="button" name="Admin" value="Administration"
<?php
        if ($siteUrl) {
          echo "onClick='window.location.href=\"$siteUrl"."/administrator/index.php\" '";
        } else {
          echo "onClick='window.location.href=\"{$configArray['siteURL']}"."/administrator/index.php\" '";
        }
?>/>
      </div>
      <div class="clr"></div>
      <h1>Congratulations! Adzan is installed</h1>
      <div class="install-text">
        <p>Click the "View Site" button to start Adzan site or "Administration"
          to take you to administrator login.</p>
        <p>Don't forget to register your Adzan! site and get full functionallity for this program</p>
      </div>
      <div class="install-form">
        <div class="form-block">
          <table width="100%">
            <tr><td class="error" align="center">PLEASE REMEMBER TO COMPLETELY<br/>REMOVE THE INSTALLATION DIRECTORY</td></tr>
            <tr><td align="center"><h5>Administration Login Details</h5></td></tr>
            <tr><td align="center" class="notice"><b>Username : admin</b></td></tr>
            <tr><td align="center" class="notice"><b>Password : <?php echo $adminPassword; ?></b></td></tr>
            <tr><td>&nbsp;</td></tr>
            <tr><td align="right">&nbsp;</td></tr>
<?php            if (!$canWrite) { ?>
            <tr>
              <td class="small">
                Your configuration file or directory is not writeable,
                or there was a problem creating the configuration file. You'll have to
                upload the following code by hand. Click in the textarea to highlight
                all of the code.
              </td>
            </tr>
            <tr>
              <td align="center">
                <textarea rows="5" cols="60" name="configcode" onclick="javascript:this.form.configcode.focus();this.form.configcode.select();" ><?php echo htmlspecialchars( $config );?></textarea>
              </td>
            </tr>
<?php            } ?>
            <tr><td class="small"><?php /*echo $chmod_report*/; ?></td></tr>
          </table>
        </div>
      </div>
      <div id="break"></div>
    </div>
<?php include "install.footer.php"; ?>