<?php
/*
BISMILLAAHIRRAHMAANIRRAHIIM - In the Name of Allah, Most Gracious, Most Merciful
================================================================================
filename	: 
purpose	: 
create	: 2006/03/09
last edit	: 2006/03/15,2006/03/17
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
class gzip {
  function gzip(){
    //abstract
  }

  function compress($data, $name){
    $unc_len = strlen($data);
    $crc     = crc32($data);
    $zdata = gzdeflate($data,9);
    $c_len = strlen($zdata);
    $fr="\x1f\x8B\x08\x08\x00\x00\x00\x00\x00\x00".$name."\x00".$zdata.$this->_pack($crc, 4).$this->_pack($unc_len, 4);
    return $fr;
  }

  function _pack($val, $bytes=2){
    for($ret='', $i=0; $i<$bytes; $i++, $val=floor($val/256) )
      $ret .= chr($val % 256);
    return $ret;
  }

}
?>