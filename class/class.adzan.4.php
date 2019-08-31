<?php
/*
BISMILLAAHIRRAHMAANIRRAHIIM - In the Name of Allah, Most Gracious, Most Merciful
================================================================================
filename	: 
purpose	: 
create	: 2007/11/24
last edit	: 071124
author	: cahya dsn
================================================================================
This program is free software; you can redistribute it and/or modify it under the 
terms of the GNU General Public License as published by the Free Software 
Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY 
WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR 
A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

copyright (c) 2007 by cahya dsn; cahyadsn@yahoo.com
================================================================================
*/
class adzan extends adzan_main{

  function adzan($dataCityFile,$id="",$pathCfg="",$pathChk="",$up="",$ajax=""){
    parent::adzan_main($dataCityFile,$id,$pathCfg,$pathChk,$up="",$ajax);
  }
/*
 * Schedule computation section.
 * Prayer hours are computed basically adopting from the algorithms given in
 * "Astronomical Algorithm" by Jean Meeus
 * Willmann Bell, Inc. 1991 ISBN 0-943396-35-2
 */

  //Julian Day (valid from 1900/3/1 to 2100/2/28) 
  //Julian day: 86400 s, Julian year: 365.25 d, Julian Century: 36525 d
  function JD($year,$month=1,$date=0,$UT=12) { 
    if ($month<=2) {$month=$month+12; $year=$year-1;}
    return (int)(365.25*$year) + (int)(30.6001*($month+1)) - 15 + 1720996.5 + $date + $UT/24.0;
  }

/*
 * Computes astro constants for Jan 0 of given year
 */
  function compute_astro_constants($year){
    /*  th = same in julian centuries (units of 36525 days) */
    /*  obl = obliquity of ecliptic */
    /*  perigee = sun's longitude at perigee  */
    /*  eccy = earth's eccentricity */
    /*  delsid = daily motion (change) in sidereal time */
    /*  sidtm0 = sidereal time, all at 0 hr, jan 0 of year year */
    /*  c1,c2 = coefficients in equation of center */
    $this->th = ((($year-1)/400 - ($year-1)/100 + ($year-1)/4) + 365.0*$year - 730485.5)/36525.0;
    $obl = deg2rad(23.4392911111-0.0130041666667*$this->th);
    $this->ASTRO_cosobl = cos($obl);
    $this->ASTRO_sinobl = sin($obl);
    $eccy = 0.016708617-0.000042037*$this->th-0.0000001236*$this->th*$this->th;
    //------//
    $M = 357.52910 + 35999.05030*$this->th - 0.0001559*$this->th*$this->th - 0.00000048*$this->th*$this->th*$this->th; // mean anomaly, degree
    $this->anomaly=deg2rad(fmod($M,360.0));
    //$L0 = 280.46645 + 36000.76983*$this->th + 0.0003032*$this->th*$this->th; // mean longitude, degree 
    //$this->ASTRO_mlong0 = deg2rad(fmod($L0,360.0));
    //DL = (1.914600 - 0.004817*T - 0.000014*T*T)*sin(k*M)+ (0.019993 - 0.000101*T)*sin(k*2*M) + 0.000290*sin(k*3*M) 
    //$L = $L0 + $DL // true longitude, degree
    $this->ASTRO_dmlong = 0.0172027916952;
    $this->ASTRO_mlong0 = deg2rad(fmod(280.466449-0.00030368*$this->th*$this->th + fmod(36000.7698231*$this->th,360.0),360.0));
    $this->ASTRO_dperigee = 8.21667514897E-007;
    $this->ASTRO_perigee0 = deg2rad(fmod(282.937348 + 1.7195269*$this->th + 0.00045962*$this->th*$this->th,360.0));
    $this->ASTRO_delsid = 0.0657098244191;
    $this->ASTRO_sidtm0 = fmod(6.69737455833+fmod(2400.05133691*$this->th,24.0),24.0);
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
    $days  = $nday+($time_0+$longh)/24.0;
    $mlong = $this->ASTRO_mlong0 + $this->ASTRO_dmlong*$days;
    $perigee = $this->ASTRO_perigee0 + $this->ASTRO_dperigee*$days;
    $anomaly = $mlong - $perigee;
    $slong = $mlong + $this->ASTRO_c1*sin($anomaly) + $this->ASTRO_c2*sin($anomaly*2);
    $sinslong = sin($slong);
    $ra = atan2($this->ASTRO_cosobl*$sinslong,cos($slong))*12/pi();
    //$ra = atan($this->ASTRO_cosobl*tan($slong))*12/pi();
    if ($ra<0.0){ $ra += 24.0;}
    $sindcl = $this->ASTRO_sinobl*$sinslong;
    $obs_correction=deg2rad(2.12*sqrt($this->cfgArray['observe_height'])/60);
    $cosha =  (cos($coalt+$obs_correction) - $sindcl*sin($this->GEO_latitude))/(sqrt(1.0 - $sindcl*$sindcl)*cos($this->GEO_latitude));
    if (abs($cosha)>1.0){ $tx=1.0e5;}
    else {
      $ha = acos($cosha)*12/pi();
      if ($time_0<12.0) $ha = 24.0 - $ha;
      $locmt = $ha + $ra - $this->ASTRO_delsid*$days - $this->ASTRO_sidtm0;
      $tx = $locmt + $longh + $this->GEO_timeZone;
      if ($tx<0.0)  $tx += 24.0;
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
    $mlong = $this->ASTRO_mlong0 + $this->ASTRO_dmlong*$days;
    $perigee = $this->ASTRO_perigee0 + $this->ASTRO_dperigee*$days;
    $anomaly = $mlong-$perigee;
    $slong = $mlong + $this->ASTRO_c1*sin($anomaly) + $this->ASTRO_c2*sin($anomaly*2);
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