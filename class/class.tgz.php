<?php
/*
BISMILLAAHIRRAHMAANIRRAHIIM - In the Name of Allah, Most Gracious, Most Merciful
================================================================================
filename	: 
purpose	: 
create	: 2006/03/24
last edit	: 060324
author	: cahya dsn
================================================================================
This program is free software; you can redistribute it and/or modify it under the 
terms of the GNU General Public License as published by the Free Software 
Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY 
WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR 
A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

copyright (c) 2006 by cahya dsn; cahyadsn@yahoo.com
================================================================================
*/
class tgz {
  function tgz(){
    //abstract
  }

  function compress($data, $name){
    $length = strlen($data);
    $header = str_pad($name,100,chr(0)).str_repeat("0",7).chr(0);
    $header .= str_repeat("0",7).chr(0).str_repeat("0",7).chr(0);
    $header .= str_pad(decoct($length),11,"0",STR_PAD_LEFT) . chr(0);
    $header .= str_repeat("0",11).chr(0).str_repeat(" ",8)."0".str_repeat(chr(0),100);
    $header .= str_pad("ustar",6,chr(32)).chr(32).str_repeat(chr(0),248);
    $checksum = str_pad(decoct($this->compute_checksum($header)),6,"0",STR_PAD_LEFT);
    for($i=0; $i<6; $i++) {
			$header[(148 + $i)] = substr($checksum,$i,1);
		}
		$header[154] = chr(0);
		$header[155] = chr(32);
    $file_contents = str_pad($data,(ceil($length / 512) * 512),chr(0));
    $filedata = $header.$file_contents.str_repeat(chr(0),512);
    $fr = gzencode($filedata);
    return $fr;
  }

  function compute_checksum($bytestring) {
    for($i=0; $i<512; $i++)
      $unsigned_chksum += ord($bytestring[$i]);
    for($i=0; $i<8; $i++)
      $unsigned_chksum -= ord($bytestring[148 + $i]);
    $unsigned_chksum += ord(" ") * 8;
    return $unsigned_chksum;
  }
}
?>