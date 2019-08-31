<?php
/*
BISMILLAAHIRRAHMAANIRRAHIIM - In the Name of Allah, Most Gracious, Most Merciful
================================================================================
filename	: class.adzan.main.php
purpose	: 
create	: 2006/02/24
last edit	: 060301,15,17,20,23-25,29,060405,12,1123,1230,080104,0111,0311,0810,0901
            120117
author	: cahya dsn
================================================================================
This program is free software; you can redistribute it and/or modify it under the 
terms of the GNU General Public License as published by the Free Software 
Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY 
WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR 
A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

copyright (c) 2001-2012 by cahya dsn; cahyadsn@yahoo.com
================================================================================
*/
if(!function_exists ("fmod")){
  function fmod($x,$y){
    while($x>$y){
      $x-=$y;
    }
    return $x;
  }
}

class adzan_main extends config{
  public $GEO_latitude,$GEO_longitude,$GEO_timeZone;
  public $FIQH_fajrDepr,$FIQH_isFajrByInterval,$FIQH_fajrInterval;
  public $FIQH_asrShadowRatio,$FIQH_ishaDepr;
  public $FIQH_isIshaByInterval,$FIQH_ishaInterval;
  public $FIQH_imsyakDepr,$FIQH_isImsyakByInterval,$FIQH_imsyakInterval;
  public $DST_hasDST,$DST_begin,$DST_finish;
  public $bgHighlight,$bgShadowLight,$bgShadowDark;
  public $ASTRO_mlong0,$ASTRO_dmlong,$ASTRO_perigee0,$ASTRO_dperigee,$ASTRO_c1,$ASTRO_c2;
  public $ASTRO_delsid,$ASTRO_sidtm0,$ASTRO_cosobl,$ASTRO_sinobl;
  public $NT_t,$coaltn,$th,$time0,$coalt,$dayTab;
  public $dtCity,$dtCityName,$dtCityNo,$cityCount,$id;
  public $column,$limit1,$limit2;
  public $prayTime = array();
  public $ha;
  public $ihtiyat = array();

  function adzan_main($dataCityFile,$id="",$pathCfg="",$pathChk="",$up="",$ajax=""){
    //abstract
    parent::config($pathCfg);
    $id=$id?$id-1:$this->cfgArray['city']-1;
    $this->id=$id-1;
    $this->set_day_tab();
    $content = file($dataCityFile);
    $this->cityCount = count($content);
    for($i=0;$i<$this->cityCount;$i++){
      $this->dtCity[$i]=explode("!",$content[$i]);
      $this->dtCityName[$i]=ucwords(strtolower($this->dtCity[$i][1]));
      $this->dtCityNo[$i]=$i;
    }
    array_multisort($this->dtCityName,SORT_ASC, SORT_STRING,$this->dtCityNo);
    $this->id=$id;
    $this->set_fiqh_parameter();    
    $this->set_ihtiyat();
    $this->set_geo_latitude($this->dtCity[$id][2],$this->dtCity[$id][3],$this->dtCity[$id][4]);
    $this->set_geo_longitude($this->dtCity[$id][5],$this->dtCity[$id][6],$this->dtCity[$id][7]);
    $this->set_geo_time_zone($this->dtCity[$id][8],$this->dtCity[$id][9],$this->dtCity[$id][10]);
    $this->DST_hasDST=$this->dtCity[$id][11];
    $this->column=6+$this->cfgArray['viewImsyak']+$this->cfgArray['viewSunrise'];
    $this->limit1=($this->cfgArray['viewImsyak']?9:0);
    $this->limit2=($this->cfgArray['viewSunrise']?9:2);
  }

  function set_geo_latitude($deg,$min,$sign){
    $sign=($sign==1)?-1:1;
    $this->GEO_latitude=deg2rad($this->dms2deg($sign*$deg,$min));
  }

  function set_geo_longitude($deg,$min,$sign){
    $sign=($sign==1)?-1:1;
    $this->GEO_longitude=deg2rad($this->dms2deg($sign*$deg,$min));
  }

  function set_geo_time_zone($deg,$min,$sign){
    $sign=($sign==1)?-1:1;
    $this->GEO_timeZone=$this->dms2deg($sign*$deg,$min);
  }
  
  function set_parameter(){
    //abstract
  }

/*
* set fiqh parameter from configuration file
*/
  function set_fiqh_parameter(){
    $this->FIQH_isFajrByInterval=$this->cfgArray['fajr']?$this->cfgArray['fajr']:0;
    if($this->FIQH_isFajrByInterval){
      $this->FIQH_fajrInterval=$this->cfgArray['fajr_interval']?$this->cfgArray['fajr_interval']:90.0;
    }else{  
      $this->FIQH_fajrDepr=$this->cfgArray['fajr_depr']?$this->cfgArray['fajr_depr']:18.0;
    }    
    $this->FIQH_asrShadowRatio=$this->cfgArray['ashr']?($this->cfgArray['ashr']==1?2:$this->cfgArray['ashr_shadow']):1;
    $this->FIQH_isIshaByInterval=$this->cfgArray['isha']?$this->cfgArray['isha']:0;
    if($this->FIQH_isIshaByInterval){
      $this->FIQH_ishaInterval=$this->cfgArray['isha_interval']?$this->cfgArray['isha_interval']:90.0;
    }else{
      $this->FIQH_ishaDepr=$this->cfgArray['isha_depr']?$this->cfgArray['isha_depr']:18.0;
    }
    $this->FIQH_isImsyakByInterval=$this->cfgArray['imsyak']?$this->cfgArray['imsyak']:0;
    if($this->FIQH_isImsyakByInterval){
      $this->FIQH_imsyakInterval=$this->cfgArray['imsyak_interval']?$this->cfgArray['imsyak_interval']:10.0;    
    }else{
      $this->FIQH_imsyakDepr=$this->cfgArray['imsyak_depr']?$this->cfgArray['imsyak_depr']:1.5;
    }    
  }
  
/*
* set fiqh parameter from posting variables
*/
  function set_fiqh_parameter_post(){
    $this->FIQH_isFajrByInterval=isset($_POST['fajr'])?$_POST['fajr']:0;
    if($this->FIQH_isFajrByInterval){
      $this->FIQH_fajrInterval=isset($_POST['fajr_interval'])?$_POST['fajr_interval']:90.0;
    }else{  
      $this->FIQH_fajrDepr=isset($_POST['fajr_depr'])?$_POST['fajr_depr']:18.0;
    }    
    $this->FIQH_asrShadowRatio=isset($_POST['ashr'])?($_POST['ashr']==1?2:isset($_POST['ashr_shadow'])?$_POST['ashr_shadow']:2):1;
    $this->FIQH_isIshaByInterval=isset($_POST['isha'])?$_POST['isha']:0;
    if($this->FIQH_isIshaByInterval){
      $this->FIQH_ishaInterval=isset($_POST['isha_interval'])?$_POST['isha_interval']:90.0;
    }else{
      $this->FIQH_ishaDepr=isset($_POST['isha_depr'])?$_POST['isha_depr']:18.0;
    }
    $this->FIQH_isImsyakByInterval=isset($_POST['imsyak'])?$_POST['imsyak']:0;
    if($this->FIQH_isImsyakByInterval){
      $this->FIQH_imsyakInterval=isset($_POST['imsyak_interval'])?$_POST['imsyak_interval']:10.0;    
    }else{
      $this->FIQH_imsyakDepr=isset($_POST['imsyak_depr'])?$_POST['imsyak_depr']:1.5;
    }    
  }

  function set_day_tab(){
    $this->dayTab = Array(31,28,31,30,31,30,31,31,30,31,30,31);
  }

/*
 * Computes astro constants for Jan 0 of given year
 */
  function compute_astro_constants($year){
    //abstract;
  }

/*
 * Returns time on day no. nday of year when sun's coaltitude is coalt.
 */
  function time_used($nday,$coalt,$time_0){
    // abstract
  }

/*
 * Place sun's coaltitude at noon in coaltn,
 * and return time of noon for day no. nday of year
 */
  function noon_time($nday)  {
    //abstract
  }

/*
 * Computes times for range of days first..last-1.
 */
  function compute_pray_times($pday,$nday,$nyear) {
    // ---  Approximate times of fajr,shuruq,asr,maghrib,isha,imsyak
    $this->time0[1] = 4.0;
    $this->time0[2] = 6.0;
    $this->time0[4] = 15.0;
    $this->time0[5] = 18.0;
    $this->time0[6] = 20.0;
    $this->time0[0] = 4.0;
    // ---  Coaltitudes of sun at fajr,shuruq,maghrib,isha,imsyak
    $this->coalt[1] = deg2rad(90 + $this->FIQH_fajrDepr); // ---  fajr
    $this->coalt[2] = deg2rad(90.833333);         // ---  syuruq
    $this->coalt[5] = $this->coalt[2];                   // ---  maghrib
    $this->coalt[6] = deg2rad(90 + $this->FIQH_ishaDepr); // ---  isha
    $this->coalt[0] = deg2rad(90 + $this->FIQH_fajrDepr + $this->FIQH_imsyakDepr); // ---  imsyak
    // ---  Get approximate times for the pday day specified.
    // ---  Later on, each day's times used as approximate times for next day
    $this->noon_time($pday);
    $this->coalt[4] = atan($this->FIQH_asrShadowRatio + tan($this->coaltn)); // --- ashr
    $t=$this->time_used($pday,$this->coalt[2],$this->time0[2]);
    $this->time0[2] = ($t<24.0)? $t : 6.0;
    $t=$this->time_used($pday,$this->coalt[4],$this->time0[4]);
    $this->time0[4] = ($t<24.0)? $t : 15.0;
    $t=$this->time_used($pday,$this->coalt[5],$this->time0[5]);
    $this->time0[5] = ($t<24.0)? $t : 18.0;
    $t=$this->time_used($pday,$this->coalt[6],$this->time0[6]);
    $this->time0[6] = ($t<24.0)? $t : 20.0;
    if ($this->FIQH_isFajrByInterval==1){ 
      $this->time0[1] = $this->time0[2] - $this->FIQH_fajrInterval/60.0;}
    else {
      $t=$this->time_used($pday,$this->coalt[1],$this->time0[1]);
      $this->time0[1] = ($t < 24.0) ? $t: 4.0;
    }
    if ($this->FIQH_isImsyakByInterval==1){ 
      $this->time0[0] = $this->time0[1] - $this->FIQH_imsyakInterval/60.0;}
    else {
      $t=$this->time_used($pday,$this->coalt[0],$this->time0[0]);
      $this->time0[0] = ($t < 24.0) ? $t: 4.0;
    }    
    if ($this->FIQH_isIshaByInterval==1){
      $this->time0[6] = $this->time0[5] + $this->FIQH_ishaInterval/60.0;}
    else{
      $t=$this->time_used($pday,$this->coalt[6],$this->time0[6]);
      $this->time0[6] = ($t< 24.0) ? $t: 20.0;
    }
    // ---   compute times for the whole range of days
    for($l=$pday;$l<$nday;$l++){
      $k = $l;
      if (($l>59) && ($nyear==0)) $k = $l - 1; // perpetual Kalender
      $this->noon_time($k+1);
      $this->prayTime[$l][3] = $this->NT_t;
      $this->coalt[4]  = atan($this->FIQH_asrShadowRatio+ tan($this->coaltn));
      $this->prayTime[$l][2] =$this->time_used($k+1,$this->coalt[2],$this->time0[2]);
      $this->prayTime[$l][5] =$this->time_used($k+1,$this->coalt[5],$this->time0[5]);
      $this->prayTime[$l][4]= $this->time_used($k+1,$this->coalt[4],$this->time0[4]);
      $this->time0[2] = ($this->prayTime[$l][2]< 24.0)?$this->prayTime[$l][2]:6.0;
      $this->time0[4] = ($this->prayTime[$l][4]< 24.0)?$this->prayTime[$l][4]:15.0;
      $this->time0[5] = ($this->prayTime[$l][5]< 24.0)?$this->prayTime[$l][5]:18.0;
      if ($this->FIQH_isFajrByInterval==1){
        $this->time0[1] = $this->time0[2] - $this->FIQH_fajrInterval/60.0;
        $this->prayTime[$l][1] = $this->time0[1];
      }else{
        $this->prayTime[$l][1] = $this->time_used($k+1,$this->coalt[1],$this->time0[1]);
        $this->time0[1] = ($this->prayTime[$l][1]< 24.0)? $this->prayTime[$l][1] : 4.0;
      }
      if ($this->FIQH_isImsyakByInterval==1){ 
        $this->time0[0] = $this->time0[1] - $this->FIQH_imsyakInterval/60.0;
        $this->prayTime[$l][0] = $this->time0[0];
      } else {
        $this->prayTime[$l][0] = $this->time_used($k+1,$this->coalt[0],$this->time0[0]);
        $this->time0[0] = ($this->prayTime[$l][0] < 24.0) ? $this->prayTime[$l][0]: 4.0;
      }       
      if ($this->FIQH_isIshaByInterval==1){
        $this->time0[6] = $this->time0[5] + $this->FIQH_ishaInterval/60.0;
        if ($this->time0[6]<0.0) $this->time0[6] += 24.0;
        if ($this->time0[6]>24.0) $this->time0[6] -= 24.0;
        $this->prayTime[$l][6] = $this->time0[6];
      }else {
        $this->prayTime[$l][6]= $this->time_used($k+1,$this->coalt[6],$this->time0[6]);
        $this->time0[6] = ($this->prayTime[$l][6]< 24.0) ? $this->prayTime[$l][6] : 20.0;
      }
    }
    // ---- correct for daylight saving time (DST) ---
    if ($this->DST_hasDST==1){
      if(!$this->DST_begin){
        $this->day_light($nyear);
      }
      for ($i = $this->DST_begin-1; $i < $this->DST_finish; $i++)
        for ($k=0; $k<7; $k++)
          $this->prayTime[$i][$k] += 1.0;
    }
    // --- correct for ihtiyat time
    for ($i = $pday; $i < $nday; $i++)
      for ($k=0; $k<7; $k++){
        $this->prayTime[$i][$k] += $this->ihtiyat[$k]/60;  
      }
  }

  function qibla($longitude,$latitude){ // input in rad, return qDirec (deg), qDistance (Km)
    // lat0=21°25'24", long0=39°49'24" are Makkah's latitude and longitude
    // var lat0 = 0.373907703, long0 = -0.695048285, dflong
    // direction to Kiblah in rad
    $lat0 = 0.373907703;
    $long0 = -0.695048285;    
    $dflong = $longitude - $long0;
    if(abs($dflong) < 1e-8) $qDirec = 0;  // from Mecca
    else{
      $qDirec = atan2(sin($dflong),cos($latitude)*tan($lat0)-sin($latitude)*cos($dflong))*180/pi();
      if($qDirec<0) $qDirec += 360;
      $qDirec = round($qDirec,2);
    }
    // qDistance in km to Kiblah
    $sF = ($latitude +  $lat0)/2;
    $cF = cos($sF);
    $sF = sin($sF);
    $sG = ($latitude -  $lat0)/2;
    $cG = cos($sG);
    $sG = sin($sG);
    $sL = ($longitude - $long0)/2;
    $cL = cos($sL);
    $sL = sin($sL);
    $S = $sG*$sG*$cL*$cL + $cF*$cF*$sL*$sL;
    $C = $cG*$cG*$cL*$cL + $sF*$sF*$sL*$sL;
    $W = atan(sqrt($S/$C));
    $R = sqrt($S*$C)/$W;
    $S = ( (3.0*$R-1.0)*$sF*$sF*$cG*$cG/(2.0*$C) -  (3.0*$R+1.0)*$cF*$cF*$sG*$sG/(2.0*$S) )/298.257;
    $qDistance = 2.0*$W*6378.14*(1.0+$S); // in Km
    $qDistance = round($qDistance,3);
    return array($qDirec,$qDistance);
  }

  function day_light($year){
    $apr01 = mktime(0,0,0,4,1,$year);
    $apr1stSun = $apr01 +((7-date("w",$apr01))%7)*(60*60*24);
    $this->DST_begin =date("z -- w d-m-Y",mktime(2,0,0,date("m",$apr1stSun),date("d",$apr1stSun),date("Y",$apr1stSun)));
    $oct31 = mktime(0,0,0,11,1,$year);
    $octLastSat = $oct31-(date("w",$oct31))*(60*60*24)-1;
    $this->DST_finish= date("z -- w d-m-Y",mktime(2,0,0,date("m",$octLastSat),date("d",$octLastSat),date("Y",$octLastSat)));
  }
  
  function dms2deg($degree,$minute=0,$second=0){
    $sign=0;
    if($degree<0){$degree=abs($degree);$sign=1;}
    if($second){  $degs=$degree + $minute/60.0 + $second/3600.0; }
    else {$degs=$degree + $minute/60.0 + $second/3600.0;}
    if($sign==1)$degs*=(-1);
    return $degs;
  }

  function h2hms($hours){
    $sign=($hours<10)?"0":"";
    $hour1=floor(abs($hours));
    $minute=(abs($hours)-$hour1)*60;
    $second=floor(($minute-floor($minute))*60);
    $second=($second<10)?"0".$second:$second;
    $minute=floor($minute);
    $minute=($minute<10)?"0".$minute:$minute;
    return $sign.$hour1.":".$minute.":".$second;
  }

  function d2dms($deg){
    $sign=($deg<10)?"0":"";
    $deg1=floor(abs($deg));
    $minute=(abs($deg)-$deg1)*60;
    $second=floor(($minute-floor($minute))*60);
    $second=($second<10)?"0".$second:$second;
    $minute=floor($minute);
    $minute=($minute<10)?"0".$minute:$minute;
    return $sign.$deg1."&deg;".$minute."&quot;".$second."'";
  }

  function h2hm($hours){
    $sign=($hours<10)?"0":"";
    $hour1=floor(abs($hours));
    $minute=(abs($hours)-$hour1)*60;
    $minute=round($minute);
    $minute=($minute<10)?"0".$minute:$minute;
    $hour1=($minute==60)?$hour1+1:$hour1;
    $minute=($minute==60)?"00":$minute;
    return $sign.$hour1.":".$minute;
  }

  function show_hours($hours,$second){
    return $second?$this->h2hms($hours):$this->h2hm($hours);
  }
  
  function hms2h($hour,$min,$sec){
    return ($hour + $min/60.0 + $sec/3600.0);
  }

  function month_age($mon,$year){
    return date("t",mktime(0,0,0,$mon,1,$year));
  }

  function leap($year){
    return date("L",mktime(0,0,0,1,1,$year));
  }

  function tod($hour,$min){
    return ( $hour/24.0 + $min/1440.0 );
  }

  function date2doy($day,$mon,$year){
    return date("z",mktime(0,0,0,$mon,$day,$year));
  }
  
  function change_format($x){
    return ($x<10||strlen($x)<2)?"0".$x:$x;
  }

  function set_ihtiyat(){
    $this->ihtiyat[0]=0;
    $this->ihtiyat[1]=$this->cfgArray['ihtiyat_fajr'];
    $this->ihtiyat[2]=0;
    $this->ihtiyat[3]=$this->cfgArray['ihtiyat_dzuhr'];
    $this->ihtiyat[4]=$this->cfgArray['ihtiyat_ashr'];
    $this->ihtiyat[5]=$this->cfgArray['ihtiyat_maghrib'];
    $this->ihtiyat[6]=$this->cfgArray['ihtiyat_isha'];    
  }
  
  function set_ihtiyat_post(){
    $this->ihtiyat[0]=0;
    $this->ihtiyat[1]=isset($_POST['ihtiyat_fajr'])?$_POST['ihtiyat_fajr']:2;
    $this->ihtiyat[2]=0;
    $this->ihtiyat[3]=isset($_POST['ihtiyat_dzuhr'])?$_POST['ihtiyat_dzuhr']:4;
    $this->ihtiyat[4]=isset($_POST['ihtiyat_ashr'])?$_POST['ihtiyat_ashr']:2;
    $this->ihtiyat[5]=isset($_POST['ihtiyat_maghrib'])?$_POST['ihtiyat_maghrib']:2;
    $this->ihtiyat[6]=isset($_POST['ihtiyat_isha'])?$_POST['ihtiyat_isha']:2;
  }
  
  function generate_data($type,$day,$mon,$year,$text="",$path="",$up="",$ajax="") {
    $timeAdj=($this->cfgArray["time_adjust"]+$this->GEO_timeZone)*60*60;
    $dayc=date("d",mktime(0,0,0,$mon,$day,$year)+$timeAdj);
    $monc=date("m",mktime(0,0,0,$mon,$day,$year)+$timeAdj);
    $yearc=date("y",mktime(0,0,0,$mon,$day,$year)+$timeAdj);
    $this->compute_astro_constants($yearc);
    $maxdom = $this->month_age($monc, $yearc);
    if($type==1||$type>=4){   // One-day-tabel
      $last  = $this->date2doy($dayc,$monc,$yearc);
      $first = $last - 1;
    } else if($type==2){ // Month tabel
      $first = $this->date2doy(1,$monc,$yearc) - 1;
      $last  = $first + $maxdom;
    } else if($type==3){ // Year tabel
      $first = -1;
      $last  = 365 + $this->leap($yearc);
    }
    $this->compute_pray_times($first,$last,$yearc);
  }

}
?>