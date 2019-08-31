<?php
/*
BISMILLAAHIRRAHMAANIRRAHIIM - In the Name of Allah, Most Gracious, Most Merciful
================================================================================
filename	: install2.php
purpose	: 
create	: 2006/02/27
last edit	: 060317,060323,1124,070430,1211,1217,080901,110621,120118
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
include_once("common.inc.php");
if (file_exists( '../config.php' ) && filesize( '../config.php' ) > 10) {
  header( "Location: ../index.php" );
  exit();
}
$f=array("install3.php","2");
include "install.header.php"; 
?>
    <div id="step">Step 2</div>
    <div class="far-right">
    <input class="button" type="button" name="prev" value="&lt;&lt; Prev" onClick="javascript:location.href='install1.php';"/>
    <input class="button" type="submit" name="next" value="Next &gt;&gt;"/>
    <input type="hidden" name="viewParam" value="<?php echo $_POST['cbxViewParam'];?>" />
    <input type="hidden" name="viewQibla" value="<?php echo $_POST['cbxViewQibla'];?>" />
    <input type="hidden" name="viewFiqh" value="<?php echo $_POST['cbxViewFiqh'];?>" />
    <input type="hidden" name="viewImsyak" value="<?php echo $_POST['cbxViewImsyak'];?>" />
    <input type="hidden" name="viewSunrise" value="<?php echo $_POST['cbxViewSunrise'];?>" />
    <input type="hidden" name="viewSecond" value="<?php echo $_POST['cbxViewSecond'];?>" />
    <input type="hidden" name="adzanPeriod" value="<?php echo $_POST['adzanPeriod'];?>" />
    <input type="hidden" name="adzanStyle" value="<?php echo $_POST['style'];?>" />
    <input type="hidden" name="adzanCountry" value="<?php echo $_POST['adzanCountry'];?>" />
    <input type="hidden" name="adzanCity" value="<?php echo $_POST['adzanCity'];?>" />
    <input type="hidden" name="adzanAlgorithm" value="<?php echo $_POST['algo'];?>" />
    <input type="hidden" name="language" value="<?php echo $_POST['language'];?>" />
    <input type="hidden" name="fajr_depr" value="<?php echo $_POST['fajr_depr'];?>" />
    <input type="hidden" name="fajr" value="<?php echo $_POST['fajr'];?>" />
    <input type="hidden" name="fajr_interval" value="<?php echo $_POST['fajr_interval'];?>" />
    <input type="hidden" name="ashr" value="<?php echo $_POST['ashr'];?>" />
    <input type="hidden" name="ashr_shadow" value="<?php echo $_POST['ashr_shadow'];?>" />
    <input type="hidden" name="isha_depr" value="<?php echo $_POST['isha_depr'];?>" />
    <input type="hidden" name="isha" value="<?php echo $_POST['isha'];?>" />
    <input type="hidden" name="isha_interval" value="<?php echo $_POST['isha_interval'];?>" />
    <input type="hidden" name="imsyak_depr" value="<?php echo $_POST['imsyak_depr'];?>" />
    <input type="hidden" name="imsyak" value="<?php echo $_POST['imsyak'];?>" />
    <input type="hidden" name="time_adjust" value="<?php echo $_POST['txtTimeAdjust'];?>" />
    <input type="hidden" name="imsyak_interval" value="<?php echo $_POST['imsyak_interval'];?>" />
    <input type="hidden" name="ihtiyat_fajr" value="<?php echo $_POST['ihtiyat_fajr'];?>" />
    <input type="hidden" name="ihtiyat_dzuhr" value="<?php echo $_POST['ihtiyat_dzuhr'];?>" />
    <input type="hidden" name="ihtiyat_ashr" value="<?php echo $_POST['ihtiyat_ashr'];?>" />
    <input type="hidden" name="ihtiyat_maghrib" value="<?php echo $_POST['ihtiyat_maghrib'];?>" />
    <input type="hidden" name="ihtiyat_isha" value="<?php echo $_POST['ihtiyat_isha'];?>" />
    </div>
    <div class="clr"></div>
    <h1>Confirm the site URL, path, e-mail, username, password and file/dir. chmods</h1>
      <div class="install-text">
          <p>If URL and Path look correct then please do not change them.
          If you are not sure then please contact your ISP or administrator. Usually
          the values displayed will work for your site.<br/>
          <br/>
          Enter your e-mail address, this will be the e-mail address of the site
          SuperAdministrator.<br />
          <br/>
          The permission settings will be used while installing Adzan itself, by
          the Adzan addon-installers and by the media manager. If you are unsure
          what flags shall be set, leave the default settings at the moment.
          You can still change these flags later in the site global configuration.</p>
      </div>
<div class="install-form">
        <div class="form-block">
          <table class="content2">
          <tr>
            <td width="100">URL</td>
<?php
  $url = "";
  if (isset($configArray['siteUrl']))
    $url = $configArray['siteUrl'];
  else {
    $port = ( $_SERVER['SERVER_PORT'] == 80 ) ? '' : ":".$_SERVER['SERVER_PORT'];
    $root = $_SERVER['SERVER_NAME'].$port.$_SERVER['PHP_SELF'];
    $root = str_replace("installation/","",$root);
    $root = str_replace("/install2.php","",$root);
    $url = "http://".$root;
  }
?>            <td align="center"><input class="inputbox" type="text" name="siteUrl" value="<?php echo $url; ?>" size="40"/></td>
          </tr>
          <tr>
            <td>Path</td>
<?php
  $abspath = "";
  if (isset($configArray['absolutePath']))
    $abspath = $configArray['absolutePath'];
  else {
    $path = getcwd();
    if (preg_match("/\/installation/i", "$path"))
      $abspath = str_replace('/installation',"",$path);
    else
      $abspath = str_replace('\installation',"",$path);
  }
?>          <td align="center">
							<input class="inputbox" type="text" name="absolutePath" value="<?php echo $abspath; ?>" size="40" />
						</td>
          </tr>
          <tr>
            <td>Your E-mail</td>
            <td align="center">
            	<input class="inputbox" type="text" name="adminEmail" value="<?php echo isset($adminEmail)?"$adminEmail":""; ?>" size="40" />
            </td>
          </tr>
          <tr>
            <td>Username</td>
            <td align="center">
            	<input class="inputbox" type="text" name="adminUser" value="" size="40" />
            </td>
          </tr>
          <tr>
            <td>Admin password</td>
            <td align="center">
            	<input class="inputbox" type="password" name="adminPassword" value="" size="40" />
            </td>
          </tr>
          <tr>
            <td>Retype password</td>
            <td align="center">
            	<input class="inputbox" type="password" name="adminPassword2" value="" size="40" />
            </td>
          </tr>
          <tr>
<?php
  $mode = 0;
  $flags = 0644;
  if (isset($filePerms) && $filePerms!='') {
    $mode = 1;
    $flags = octdec($filePerms);
  }
?>
            <td colspan="2">
                <fieldset><legend>File Permissions</legend>
                <table cellpadding="1" cellspacing="1" border="0">
                  <tr>
                    <td>
                    	<input type="radio" id="filePermsMode0" name="filePermsMode" value="0" onclick="changeFilePermsMode(0)"<?php if (!$mode) echo ' checked="checked"'; ?> />
                    </td>
                    <td><label for="filePermsMode0">Dont CHMOD files (use server defaults)</label></td>
                  </tr>
                  <tr>
                    <td>
                    	<input type="radio" id="filePermsMode1" name="filePermsMode" value="1" onclick="changeFilePermsMode(1)"<?php if ($mode) echo ' checked="checked"'; ?> />
                    </td>
                    <td><label for="filePermsMode1"> CHMOD files to:</label></td>
                  </tr>
                  <tr id="filePermsFlags"<?php if (!$mode) echo ' style="display:none"'; ?>>
                    <td>&nbsp;</td>
                    <td>
                      <table cellpadding="1" cellspacing="0" border="0">
                        <tr>
                          <td>User:</td>
                          <td><input type="checkbox" id="filePermsUserRead" name="filePermsUserRead" value="1"<?php if ($flags & 0400) echo ' checked="checked"'; ?> /></td>
                          <td><label for="filePermsUserRead">read</label></td>
                          <td><input type="checkbox" id="filePermsUserWrite" name="filePermsUserWrite" value="1"<?php if ($flags & 0200) echo ' checked="checked"'; ?> /></td>
                          <td><label for="filePermsUserWrite">write</label></td>
                          <td><input type="checkbox" id="filePermsUserExecute" name="filePermsUserExecute" value="1"<?php if ($flags & 0100) echo ' checked="checked"'; ?> /></td>
                          <td width="100%"><label for="filePermsUserExecute">execute</label></td>
                        </tr>
                        <tr>
                          <td>Group:</td>
                          <td><input type="checkbox" id="filePermsGroupRead" name="filePermsGroupRead" value="1"<?php if ($flags & 040) echo ' checked="checked"'; ?> /></td>
                          <td><label for="filePermsGroupRead">read</label></td>
                          <td><input type="checkbox" id="filePermsGroupWrite" name="filePermsGroupWrite" value="1"<?php if ($flags & 020) echo ' checked="checked"'; ?> /></td>
                          <td><label for="filePermsGroupWrite">write</label></td>
                          <td><input type="checkbox" id="filePermsGroupExecute" name="filePermsGroupExecute" value="1"<?php if ($flags & 010) echo ' checked="checked"'; ?> /></td>
                          <td width="100%"><label for="filePermsGroupExecute">execute</label></td>
                        </tr>
                        <tr>
                          <td>World:</td>
                          <td><input type="checkbox" id="filePermsWorldRead" name="filePermsWorldRead" value="1"<?php if ($flags & 04) echo ' checked="checked"'; ?> /></td>
                          <td><label for="filePermsWorldRead">read</label></td>
                          <td><input type="checkbox" id="filePermsWorldWrite" name="filePermsWorldWrite" value="1"<?php if ($flags & 02) echo ' checked="checked"'; ?> /></td>
                          <td><label for="filePermsWorldWrite">write</label></td>
                          <td><input type="checkbox" id="filePermsWorldExecute" name="filePermsWorldExecute" value="1"<?php if ($flags & 01) echo ' checked="checked"'; ?> /></td>
                          <td width="100%"><label for="filePermsWorldExecute">execute</label></td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
              </fieldset>
            </td>
          </tr>
          <tr>
<?php
  $mode = 0;
  $flags = 0755;
  if (isset($dirPerms) && $dirPerms!='') {
    $mode = 1;
    $flags = octdec($dirPerms);
  }
?>
            <td colspan="2">
                <fieldset><legend>Directory Permissions</legend>
                <table cellpadding="1" cellspacing="1" border="0">
                  <tr>
                    <td><input type="radio" id="dirPermsMode0" name="dirPermsMode" value="0" onclick="changeDirPermsMode(0)"<?php if (!$mode) echo ' checked="checked"'; ?> /></td>
                    <td><label for="dirPermsMode0">Dont CHMOD directories (use server defaults)</label></td>
                  </tr>
                  <tr>
                    <td><input type="radio" id="dirPermsMode1" name="dirPermsMode" value="1" onclick="changeDirPermsMode(1)"<?php if ($mode) echo ' checked="checked"'; ?> /></td>
                    <td><label for="dirPermsMode1"> CHMOD directories to:</label></td>
                  </tr>
                  <tr id="dirPermsFlags"<?php if (!$mode) echo ' style="display:none"'; ?>>
                    <td>&nbsp;</td>
                    <td>
                      <table cellpadding="1" cellspacing="0" border="0">
                        <tr>
                          <td>User:</td>
                          <td><input type="checkbox" id="dirPermsUserRead" name="dirPermsUserRead" value="1"<?php if ($flags & 0400) echo ' checked="checked"'; ?> /></td>
                          <td><label for="dirPermsUserRead">read</label></td>
                          <td><input type="checkbox" id="dirPermsUserWrite" name="dirPermsUserWrite" value="1"<?php if ($flags & 0200) echo ' checked="checked"'; ?> /></td>
                          <td><label for="dirPermsUserWrite">write</label></td>
                          <td><input type="checkbox" id="dirPermsUserSearch" name="dirPermsUserSearch" value="1"<?php if ($flags & 0100) echo ' checked="checked"'; ?> /></td>
                          <td width="100%"><label for="dirPermsUserSearch">search</label></td>
                        </tr>
                        <tr>
                          <td>Group:</td>
                          <td><input type="checkbox" id="dirPermsGroupRead" name="dirPermsGroupRead" value="1"<?php if ($flags & 040) echo ' checked="checked"'; ?> /></td>
                          <td><label for="dirPermsGroupRead">read</label></td>
                          <td><input type="checkbox" id="dirPermsGroupWrite" name="dirPermsGroupWrite" value="1"<?php if ($flags & 020) echo ' checked="checked"'; ?> /></td>
                          <td><label for="dirPermsGroupWrite">write</label></td>
                          <td><input type="checkbox" id="dirPermsGroupSearch" name="dirPermsGroupSearch" value="1"<?php if ($flags & 010) echo ' checked="checked"'; ?> /></td>
                          <td width="100%"><label for="dirPermsGroupSearch">search</label></td>
                        </tr>
                        <tr>
                          <td>World:</td>
                          <td><input type="checkbox" id="dirPermsWorldRead" name="dirPermsWorldRead" value="1"<?php if ($flags & 04) echo ' checked="checked"'; ?> /></td>
                          <td><label for="dirPermsWorldRead">read</label></td>
                          <td><input type="checkbox" id="dirPermsWorldWrite" name="dirPermsWorldWrite" value="1"<?php if ($flags & 02) echo ' checked="checked"'; ?> /></td>
                          <td><label for="dirPermsWorldWrite">write</label></td>
                          <td><input type="checkbox" id="dirPermsWorldSearch" name="dirPermsWorldSearch" value="1"<?php if ($flags & 01) echo ' checked="checked"'; ?> /></td>
                          <td width="100%"><label for="dirPermsWorldSearch">search</label></td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
              </fieldset>
            </td>
          </tr>
          </table>
        </div>
      </div>
    <div class="clr"></div>
    <div class="clr"></div>
    </div>
<?php include "install.footer.php"; ?>