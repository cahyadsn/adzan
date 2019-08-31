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
class zip {
  function zip(){
    //abstract
  }

  function compress($data, $name) {
    $dtime    = dechex($this->updateTime());
    $hexdtime = '\x' . $dtime[6] . $dtime[7]
              . '\x' . $dtime[4] . $dtime[5]
              . '\x' . $dtime[2] . $dtime[3]
              . '\x' . $dtime[0] . $dtime[1];
    eval('$hexdtime = "' . $hexdtime . '";');
    $unc_len = strlen($data);
    $crc     = crc32($data);
    $zdata   = gzcompress($data);
    $zdata   = substr(substr($zdata, 0, strlen($zdata) - 4), 2); 
    $c_len   = strlen($zdata);
    $fdata = "\x50\x4b\x03\x04\x14\x00\x00\x00\x08\x00".$hexdtime          
          .pack('V',$crc).pack('V',$c_len).pack('V',$unc_len)
          .pack('v',strlen($name))."\x00\x00".$name.$zdata
          .pack('V',$crc).pack('V',$c_len).pack('V',$unc_len);         
    $cdrec = "\x50\x4b\x01\x02\x00\x00\x14\x00\x00\x00\x08\x00".$hexdtime          
          .pack('V',$crc).pack('V',$c_len).pack('V',$unc_len).pack('v',strlen($name))
          ."\x00\x00\x00\x00\x00\x00\x00\x00\x20\x00\x00\x00\x00\x00\x00\x00".$name;
    $fr = $fdata.$cdrec."\x50\x4b\x05\x06\x00\x00\x00\x00\x01\x00\x01\x00"
          .pack('V',strlen($cdrec)).pack('V',strlen($fdata))."\x00\x00";
    return $fr;                    
  }

  function updateTime() {
    $timearray =getdate();
    return (($timearray['year'] - 1980) << 25) | ($timearray['mon'] << 21) | ($timearray['mday'] << 16) |
           ($timearray['hours'] << 11) | ($timearray['minutes'] << 5) | ($timearray['seconds'] >> 1);
  }
}
?>