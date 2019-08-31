<?php
/*
BISMILLAAHIRRAHMAANIRRAHIIM - In the Name of Allah, Most Gracious, Most Merciful
================================================================================
filename	: 
purpose	: 
create	: 2006/03/14
last edit	: 060317,20,29,060405,070501
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
var $ASTRO_anom0,$ASTRO_dmanom,$ASTRO_perigee;

  function adzan($dataCityFile,$id="",$pathCfg="",$pathChk="",$up="",$ajax=""){
    parent::adzan_main($dataCityFile,$id,$pathCfg,$pathChk,$up,$ajax);
  }
  
/*
 * Schedule computation section.
 * Prayer hours are computed basically following the algorithms given in
 * "Prayer Schedules for North America", American Trust Publications, 
 * Indianapolis, Indiana, 1978, Appendices A and B.
 *
 */  

/*
 * Computes astro constants for Jan 0 of given year
 */
  function compute_astro_constants($year){
    /*  ndays = time from 12 hr(noon), Jan 0, 1900 to 0 hr, Jan 0 of year */
    /*  th = same in julian centuries (units of 36525 days) */
    /*  obl = obliquity of ecliptic */
    /*  perigee = sun's longitude at perigee  */
    /*  eccy = earth's eccentricity */
    /*  dmanom,delsid = daily motion (change) in */
    /*                  sun's anomaly, sidereal time */
    /*  anom0,sidtm0 = sun's mean anomaly, */
    /*                 sidereal time, all at 0 hr, jan 0 of year year */
    /*  c1,c2 = coefficients in equation of center */
	  $ndays = (($year-1900))*365+($year-1901)/4;
	  $this->th = ($ndays-0.5)/36525.0;
	  $obl = deg2rad($this->dms2deg(23,27,8.26)-$this->dms2deg(0,0,46.845)*$this->th);
	  $this->ASTRO_cosobl = cos($obl);
	  $this->ASTRO_sinobl = sin($obl);
	  $eccy = 0.01675104-0.00004180*$this->th-0.000000126*$this->th*$this->th;
	  $this->ASTRO_perigee = deg2rad(fmod($this->dms2deg(281,13,15.0)+$this->dms2deg(1,43,9.03)*$this->th+$this->dms2deg(0,0,1.63)*$this->th*$this->th,360.0));
	  $this->ASTRO_dmanom = deg2rad($this->dms2deg(35999,2,59.10)/36525.0);
	  $this->ASTRO_anom0 = deg2rad(fmod($this->dms2deg(358,28,33.0)-$this->dms2deg(0,0,0.54)*$this->th*$this->th+fmod($this->dms2deg(35999,2,59.10)*$this->th,360.0),360.0));
	  $this->ASTRO_delsid = $this->hms2h(2400,3,4.542)/36525.0;
	  $this->ASTRO_sidtm0 = fmod($this->hms2h(6,38,45.836)+fmod($this->hms2h(2400,3,4.542)*$this->th,24.0),24.0);
	  $this->ASTRO_c1 = $eccy*(2-$eccy*$eccy/4);
	  $this->ASTRO_c2 = 5*$eccy*$eccy/4;    
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
	  $longh = $this->GEO_longitude*12/pi();
	  $days = $nday+($time_0+$longh)/24.0;
	  $anomaly = $this->ASTRO_anom0+$this->ASTRO_dmanom*$days;
	  $slong = $this->ASTRO_perigee+$anomaly+$this->ASTRO_c1*sin($anomaly)+$this->ASTRO_c2*sin($anomaly*2);
	  $sinslong = sin($slong);
	  $ra = atan2($this->ASTRO_cosobl*$sinslong,cos($slong))*12/pi();
	  //$ra = atan($this->ASTRO_cosobl*tan($slong))*12/pi();
	  if ($ra<0.0) $ra += 24.0;
	  $sindcl = $this->ASTRO_sinobl*$sinslong;
	  $obs_correction=deg2rad(2.12*sqrt($this->cfgArray['observe_height'])/60);
	  $cosha = (cos($coalt+$obs_correction)-$sindcl*sin($this->GEO_latitude))/(sqrt(1.0-$sindcl*$sindcl)*cos($this->GEO_latitude));
	  /*  if cos(ha)>1, then time cannot be evaluated */
	  if (abs($cosha)>1.0) { $tx=1.0e7;}
	  else{
	    $ha = acos($cosha)*12/pi();
	    if ($time_0<12.0) $ha = 24.0-$ha;
	    $locmt = $ha+$ra-$this->ASTRO_delsid*$days-$this->ASTRO_sidtm0;
	    $tx = $locmt+$longh+$this->GEO_timeZone;
	    if ($tx<0.0) $tx += 24.0;
  	  if ($tx>24.0) $tx -= 24.0;
    }   
    return $tx;
  }

/*
 * Place sun's coaltitude at noon in coaltn,
 * and return time of noon for day no. nday of year
 */
  function noon_time($nday)  {
    /*  slong =  sun's true longitude at noon */
    /*  ra = sun's right ascension, decl = sun's declination */
    /*  ha = sun's hour angle west */
    /*  locmt = local mean time of phenomenon */
    $longh = $this->GEO_longitude*12/pi();
    $days = $nday+(12.0+$longh)/24.0;
	  $anomaly = $this->ASTRO_anom0+$this->ASTRO_dmanom*$days;
  	$slong = $this->ASTRO_perigee+$anomaly+$this->ASTRO_c1*sin($anomaly)+$this->ASTRO_c2*sin($anomaly*2);
    $sinslong = sin($slong);
    $ra = atan2($this->ASTRO_cosobl*$sinslong,cos($slong))*12/pi();
    if ($ra<0.0) $ra += 24.0;
    $decl = asin($this->ASTRO_sinobl*$sinslong);
    $locmt = $ra - $this->ASTRO_delsid*$days - $this->ASTRO_sidtm0;
    $this->NT_t = $locmt + $longh + $this->GEO_timeZone;
    if ($this->NT_t<0.0) $this->NT_t += 24.0;
    if ($this->NT_t>24.0) $this->NT_t -= 24.0;
    $this->coaltn = abs($this->GEO_latitude - $decl);
  }

}
?>