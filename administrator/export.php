<?php
/*
BISMILLAAHIRRAHMAANIRRAHIIM - In the Name of Allah, Most Gracious, Most Merciful
================================================================================
filename	: 
purpose	: 
create	: 2006/03/09
last edit	: 060317,0322,0324,070430,120116
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
if ($_SESSION['adzanAdmin']!=1) {
  header("Location: index.php?msg=1");
  exit();
}
include "../class/class.config.php";
$cfg=new config("../");
$menu=8;
$script=3;
include "admin.header.php";
?>
      <div id="header-title"><?php echo _EXPORT;?></div>
      <div class="clr"></div>
      <h1><?php echo _PERIOD;?></h1>
      <div class="install-text">
      <?php echo _EXPORTINFOPERIOD;?>
      </div>
      <div class="install-form">
        <div class="form-block">
        <table class="content2">
          <tr><td class="table-header" colspan="2"><b> <?php echo _PERIODOPTIONS;?></b></td></tr>
        </table>
        <table class="content2">
          <tr>
            <td>
            <div style="display:inline;">
            <?php echo _PERIOD;?> 
            <select name="period" onChange="selectData();" class="inputbox2">
            <?php
              for($i=0;$i<3;$i++){
                echo "<option value=\"".($i+1)."\">".$cfg->cfgPeriod[$i]."</option>\n";
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
              <?php
                for($i=0;$i<12;$i++){
                  echo "<option value=\"".($i+1)."\"".(date("n")==($i+1)?" selected":"").">".$cfg->cfgMonth[$i]."</option>\n";
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
                  <input type="radio" name="compress" id="compress" value="0" CHECKED /><?php echo _EXPORTNONE;?>
                  <input type="radio" name="compress" id="compress" value="1" /><?php echo _EXPORTGZIP;?>
                  <input type="radio" name="compress" id="compress" value="2" /><?php echo _EXPORTZIP;?>
                  <?php if(function_exists("gzencode")){ ?>
                  <input type="radio" name="compress" id="compress" value="3" /><?php echo _EXPORTTGZ;?>
                  <?php 
                  }
                  if(function_exists("bzcompress")){ ?>
                  <input type="radio" name="compress" id="compress" value="4" /><?php echo _EXPORTBZ2;?>
                  <?php } ?>
              </td>
            </tr>
          </table>
          </div>
        </div>
      </div>
      <div class="clr"></div>
      <br /><br />
      <input type="submit" class="button" name="btnGenerate" value="<?php echo _EXPORT;?>" />
      <script type="text/javascript">
      <!--
      show_checked_option();
      //-->
      </script>
<?php include "admin.footer.php"; ?>