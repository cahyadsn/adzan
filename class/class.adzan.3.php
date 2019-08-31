<?php
/*
BISMILLAAHIRRAHMAANIRRAHIIM - In the Name of Allah, Most Gracious, Most Merciful
================================================================================
filename	: 
purpose	: 
create	: 2006/03/15
last edit	: 060317,20,060405,070501
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
class adzan extends adzan_main{

  function adzan($dataCityFile,$id="",$pathCfg="",$pathChk="",$up=""){
    parent::adzan_main($dataCityFile,$id,$pathCfg,$pathChk,$up);
  }
  
/*
 * Schedule computation section.
 * Prayer hours are computed basically following the algorithms given in
 * Almanac for Computers, 1990
 * published by Nautical Almanac Office
 * United States Naval Observatory, Washington, DC 20392
 */

/*
 * Place sun's coaltitude at noon in coaltn,
 * and return time of noon for day no. nday of year
 */
  function noon_time($nday) {
		//convert longitude to hour value and calculate an approximate time
		$longh=rad2deg(-$this->GEO_longitude)/15;
		$t=$nday+((12-$longh)/24);
		//calculate the sun's mean anomaly
		$m=(0.9856*$t)-3.289;
		// calculate the sun's true longitude
		$slong=$m+(1.916*sin(deg2rad($m)))+(0.020*sin(deg2rad(2*$m)))+282.634;
		$slong=fmod($slong,360);
		//calculate the sun's right ascension
		//$ra=rad2deg(atan(0.91764*tan(deg2rad($slong))));
		$ra = rad2deg(atan2(0.91764*sin(deg2rad($slong)),cos(deg2rad($slong))));
		$ra=fmod($ra,360);
		//right ascension needs to be in the same quadrant as $slong (sun's true longitude)
		//$lquadrant=floor($slong/90)*90;
		//$raquadrant=floor($ra/90)*90;
		//$ra=$ra+($lquadrant-$raquadrant);
		//right ascension value needs to be converted into hours
		$ra=$ra/15;
		//calculate sun's declination
		$decl=rad2deg(asin(0.39782 * sin(deg2rad($slong))));
		//calculate local mean time of noon
		$locmt=$ra-(0.06571*$t)-6.622;
		$this->NT_t = fmod($locmt - $longh,24) + $this->GEO_timeZone;
    if ($this->NT_t<0.0) $this->NT_t += 24.0;
    if ($this->NT_t>24.0) $this->NT_t -= 24.0;
    $this->coaltn = deg2rad(abs(rad2deg($this->GEO_latitude) - $decl));
	}
	
/*
 * Returns time on day no. nday of year when sun's coaltitude is coalt.
 * If no such time, then returns a large number.
 *    time0 is approximate time of phenomenon
 */
  function time_used($nday,$coalt,$time_0){
    /*  slong =  true longitude */
    /*  ra = sun's right ascension, sindcl = sin(sun's declination) */
    /*  ha = sun's hour angle west */
    /*  locmt = local mean time of phenomenon */
		$longh=rad2deg(-$this->GEO_longitude)/15;
		$t=$nday+(($time_0+$longh)/24);
		$m=(0.9856*$t)-3.289;
		$slong=$m+(1.916*sin(deg2rad($m)))+(0.020*sin(deg2rad(2*$m)))+282.634;
		$slong=fmod($slong,360);
		$ra=rad2deg(atan(0.91764*tan(deg2rad($slong))));
		$ra=fmod($ra,360);
		$lquadrant=floor($slong/90)*90;
		$raquadrant=floor($ra/90)*90;
		$ra=$ra+($lquadrant-$raquadrant);
		$ra=$ra/15;
		$sindcl=0.39782 * sin(deg2rad($slong));
		$cosdcl=cos(asin($sindcl));
		$obs_correction=deg2rad(2.12*sqrt($this->cfgArray['observe_height'])/60);
		$cosha=(cos($coalt+$obs_correction)-($sindcl*sin($this->GEO_latitude))) / ($cosdcl*cos($this->GEO_latitude));
		if (abs($cosha)>1.0){ $tx=1.0e5;}
		else {
		  $ha=$time_0<12.0?360-rad2deg(acos($cosha)):rad2deg(acos($cosha));
		  $ha=$ha/15;
		  $locmt=$ha+$ra-(0.06571*$t)-6.622;
		  $tx=fmod($locmt - $longh,24) + $this->GEO_timeZone;
      if ($tx<0.0)  $tx += 24.0;
      if ($tx>24.0) $tx -= 24.0;
	  }
		return $tx;
  }	

}  