<?php
/*
BISMILLAAHIRRAHMAANIRRAHIIM - In the Name of Allah, Most Gracious, Most Merciful
================================================================================
filename	: 
purpose	: 
create	: 2006/02/27
last edit	: 060301,0317
author	: cahya dsn
================================================================================
This program is free software; you can redistribute it and/or modify it under the 
terms of the GNU General Public License as published by the Free Software 
Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY 
WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR 
A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

copyright (c) 2006-2007 by cahya dsn; cahyadsn@yahoo.com
================================================================================
*/
session_start();
if ($_SESSION['adzanAdmin']!=1) {
  header("Location: index.php?msg=1");
  exit();
}
$menu=7;
include "../class/class.registration.php";
$reg= new registration();
if($_POST['cmd']=='save'){
  $msg=$reg->save("/administrator");
}
include "admin.header.php";
?>
   <div id="header-title"><?php echo _REGISTRATION;?></div>
   <div class="clr"></div>
     <div class="content-form">
        <div class="form-block">
<?php
if(!$reg->check_key("/administrator")){
?>
        <fieldset>
        <legend><?php echo _REGISTERNOW;?></legend>
        <?php echo _REGISTERCAN;?> :
        <ul>
        <li><?php echo _REGISTERCITY;?></li>
        <li><?php echo _REGISTERMONTHLY;?></li>
        <li><?php echo _REGISTERSUPPORT;?></li>
        </ul>
        </fieldset>
<?php
  $register_to="-";
  if($reg->cfgArray['register']){
    if(!$reg->check_key("/administrator")){
      $register_status=_REGISTERKEYINVALID;
    }
  }else{
    $register_status=_REGISTERNO;
  }
}else{
  $register_to=$reg->cfgArray['member'];
  $register_status=_REGISTERED;
}
?>
        <fieldset>
        <legend><?php echo _REGISTERINFO;?></legend>
        <table class="form_block_table">
<?php
if($_POST['cmd']=='save'){
      echo "<tr>\n"
        ."<td colspan=\"2\">\n"
        ."<b class=\"error\">".$reg->cfgMsg[$msg]."</b>\n"
        ."</td>\n"
        ."</tr>\n";
}
?>
        <tr>
          <td><?php echo _REGISTERTO;?></td>
          <td>:
          <input type="text" name="registeredTo" class="inputreadonly" value="<? echo $register_to;?>" READONLY />
          </td>
        </tr>
        <tr>
          <td><?php echo _REGISTERSTATUS;?></td>
          <td>:
          <input type="text" name="registrationStatus" value="<?php echo $register_status;?>" class="inputreadonly" READONLY />
          </td>
        </tr>
<?php
if(!$reg->check_key("/administrator")){
?>
        <form action="../misc/register.php" method="post">
        <tr>
          <td colspan="2">
          <script language="JavaScript" type="text/javascript">
          function SerialKey(id){
            if (!document.getElementById){return;}
            objectID = document.getElementById('serialkey');
            if(id==1){
              objectID.style.display='block';
            }else{
              objectID.style.display='none';
            }
          }
          </script>
          <input type="hidden" name="ver" value="<?php echo $reg->cfgArray['version'];?>" />
          <input type="hidden" name="pro" value="<?php echo $reg->cfgArray['title'];?>" />
          <input type="hidden" name="web" value="<?php echo $reg->cfgArray['live_site'];?>" />
          <input type="submit" class="button" value="<?php echo _REGISTERONLINE;?>"/>
          <input type="button" class="button" value="<?php echo _REGISTERENTER;?>" onClick="SerialKey(1);" />
        </tr>
        </form>
        </table>
        </fieldset>
        <div id="serialkey" style="display:none">
        <fieldset>
        <legend><?php echo _REGISTERENTERKEY;?></legend>
        <table class="form_block_table">
        <form method="post" id="form" name="form">
        <tr>
          <td><?php echo _REGISTERNAME;?></td>
          <td><input type="text" name="reg_name" class="inputbox" value="" /></td>
        </tr>
        <tr>
          <td><?php echo _REGISTERKEY;?></td>
          <td><input type="text" name="reg_key" class="inputbox" value="" /></td>
        </tr>
        <tr>
          <td colspan="2">
          <input type="hidden" name="cmd" value="save" />
          <input type="submit" name="submit" value="<?php echo _SAVE;?>" class="button" />
          <input type="reset" value="<?php echo _CANCEL;?>" class="button" onclick="SerialKey(2)" />
          </td>
        </tr>
        </form>
        </table>
        </fieldset>
        </div>
<?php
}else{
?>

        </table>
        </fieldset>
<?php
}
?>
        </div>
      </div>
<? include "admin.footer.php"; ?>