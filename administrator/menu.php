<?php
/*
BISMILLAAHIRRAHMAANIRRAHIIM - In the Name of Allah, Most Gracious, Most Merciful
================================================================================
filename	: 
purpose	: 
create	: 2006/02/24
last edit	: 060301,0309,0317,0322,0328,070430,080810,120116
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
 cdlink("user.php","<div class=\"".((isset($_SESSION['adzanAdmin']) && $_SESSION['adzanAdmin']==1) && (isset($_GET['logout']) && $_GET['logout']!=1)?($menu==1?"menu-on":"menu-off"):"menu-inactive")."\">".strtolower(_USERMANAGEMENT)."</div>",_USERMANAGEMENT);
 echo "\n  ";
 cdlink("layout.php","<div class=\"".((isset($_SESSION['adzanAdmin']) && $_SESSION['adzanAdmin']==1) && (isset($_GET['logout']) && $_GET['logout']!=1)?($menu==9?"menu-on":"menu-off"):"menu-inactive")."\">".strtolower(_LAYOUT)."</div>",_LAYOUT);
 echo "\n  ";
 cdlink("city.php","<div class=\"".((isset($_SESSION['adzanAdmin']) && $_SESSION['adzanAdmin']==1) && (isset($_GET['logout']) && $_GET['logout']!=1)?($menu==2?"menu-on":"menu-off"):"menu-inactive")."\">".strtolower(_CITYMANAGEMENT)."</div>",_CITYMANAGEMENT);
 echo "\n  ";
 cdlink("fiqh.php","<div class=\"".((isset($_SESSION['adzanAdmin']) && $_SESSION['adzanAdmin']==1) && (isset($_GET['logout']) && $_GET['logout']!=1)?($menu==10?"menu-on":"menu-off"):"menu-inactive")."\">".strtolower(_FIQH)."</div>",_FIQH);
 echo "\n  ";
 cdlink("export.php","<div class=\"".((isset($_SESSION['adzanAdmin']) && $_SESSION['adzanAdmin']==1) && (isset($_GET['logout']) && $_GET['logout']!=1)?($menu==8?"menu-on":"menu-off"):"menu-inactive")."\">".strtolower(_EXPORT)."</div>",_EXPORT);
 echo "\n  ";
 cdlink("license.php","<div class=\"".((isset($_SESSION['adzanAdmin']) && $_SESSION['adzanAdmin']==1) && (isset($_GET['logout']) && $_GET['logout']!=1)?($menu==4?"menu-on":"menu-off"):"menu-inactive")."\">".strtolower(_LICENSE)."</div>",_LICENSE);
 echo "\n  ";
 cdlink("about.php","<div class=\"".((isset($_SESSION['adzanAdmin']) && $_SESSION['adzanAdmin']==1) && (isset($_GET['logout']) && $_GET['logout']!=1)?($menu==5?"menu-on":"menu-off"):"menu-inactive")."\">".strtolower(_ABOUT)."</div>",_ABOUT);
 echo "\n  ";
 if ((isset($_SESSION['adzanAdmin']) && $_SESSION['adzanAdmin']==1) || (isset($_GET['logout']) && $_GET['logout']!=1)) {
  cdlink("index.php?logout=1","<div class=\"".(isset($menu) && $menu==6?"menu-on":"menu-off")."\">".strtolower(_LOGOUT)."</div>",_LOGOUT);
  echo "\n";
 }
?>