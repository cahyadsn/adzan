<?php
/*
BISMILLAAHIRRAHMAANIRRAHIIM - In the Name of Allah, Most Gracious, Most Merciful
================================================================================
filename	: 
purpose	: 
create	: 
last edit	: 120118
author	: cahya dsn
================================================================================
This program is free software; you can redistribute it and/or modify it under the 
terms of the GNU General Public License as published by the Free Software 
Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY 
WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR 
A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

copyright (c) 2008-2012 by cahya dsn; cahyadsn@yahoo.com
================================================================================
*/
define('CPDF_VERSION','1.0');

class pdf extends adzan{
  //Private properties
  var $page;               //current page number
  var $n;                  //current object number
  var $offsets;            //array of object offsets
  var $buffer;             //buffer holding in-memory PDF
  var $pages;              //array containing pages
  var $state;              //current document state
  var $compress;           //compression flag
  var $DefOrientation;     //default orientation
  var $CurOrientation;     //current orientation
  var $OrientationChanges; //array indicating orientation changes
  var $fwPt,$fhPt;         //dimensions of page format in points
  var $fw,$fh;             //dimensions of page format in user unit
  var $wPt,$hPt;           //current dimensions of page in points
  var $k;                  //scale factor (number of points in user unit)
  var $w,$h;               //current dimensions of page in user unit
  var $lMargin;            //left margin
  var $tMargin;            //top margin
  var $rMargin;            //right margin
  var $bMargin;            //page break margin
  var $cMargin;            //cell margin
  var $x,$y;               //current position in user unit for cell positionning
  var $lasth;              //height of last cell printed
  var $LineWidth;          //line width in user unit
  var $CoreFonts;          //array of core font names
  var $fonts;              //array of used fonts
  var $FontFiles;          //array of font files
  var $diffs;              //array of encoding differences
  var $images;             //array of used images
  var $PageLinks;          //array of links in pages
  var $links;              //array of internal links
  var $FontFamily;         //current font family
  var $FontStyle;          //current font style
  var $underline;          //underlining flag
  var $CurrentFont;        //current font info
  var $FontSizePt;         //current font size in points
  var $FontSize;           //current font size in user unit
  var $DrawColor;          //commands for drawing color
  var $FillColor;          //commands for filling color
  var $TextColor;          //commands for text color
  var $ColorFlag;          //indicates whether fill and text colors are different
  var $ws;                 //word spacing
  var $AutoPageBreak;      //automatic page breaking
  var $PageBreakTrigger;   //threshold used to trigger page breaks
  var $InFooter;           //flag set when processing footer
  var $ZoomMode;           //zoom display mode
  var $LayoutMode;         //layout display mode
  var $title;              //title
  var $subject;            //subject
  var $author;             //author
  var $keywords;           //keywords
  var $creator;            //creator
  var $AliasNbPages;       //alias for total number of pages

/****************************************************************************
*                                                                           *
*                              Public methods                               *
*                                                                           *
****************************************************************************/

  function pdf($dataCityFile,$id="",$pathCfg="",$pathChk="",$orientation='P',$unit='mm',$format='A4'){
    if(isset($_POST)){
      $this->set_fiqh_parameter_post();
      $this->set_ihtiyat_post();
    }    
    parent::adzan($dataCityFile,$id,$pathCfg,$pathChk);
	  //Initialization of properties
	  $this->page=0;
	  $this->n=2;
	  $this->buffer='';
	  $this->pages=array();
	  $this->OrientationChanges=array();
	  $this->state=0;
	  $this->fonts=array();
	  $this->FontFiles=array();
	  $this->diffs=array();
	  $this->images=array();
	  $this->InFooter=false;
	  $this->FontFamily='';
	  $this->FontStyle='';
	  $this->FontSizePt=12;
	  $this->underline=false;
	  $this->DrawColor='0 G';
	  $this->FillColor='0 g';
	  $this->TextColor='0 g';
	  $this->ColorFlag=false;
	  $this->ws=0;
	  //Font names
	  $this->CoreFonts['courier']='Courier';
	  $this->CoreFonts['courierB']='Courier-Bold';
	  $this->CoreFonts['courierI']='Courier-Oblique';
	  $this->CoreFonts['courierBI']='Courier-BoldOblique';
	  $this->CoreFonts['helvetica']='Helvetica';
	  $this->CoreFonts['helveticaB']='Helvetica-Bold';
	  $this->CoreFonts['helveticaI']='Helvetica-Oblique';
	  $this->CoreFonts['helveticaBI']='Helvetica-BoldOblique';
	  $this->CoreFonts['times']='Times-Roman';
	  $this->CoreFonts['timesB']='Times-Bold';
	  $this->CoreFonts['timesI']='Times-Italic';
	  $this->CoreFonts['timesBI']='Times-BoldItalic';
	  $this->CoreFonts['symbol']='Symbol';
	  $this->CoreFonts['zapfdingbats']='ZapfDingbats';
	  //Scale factor
	  if($unit=='pt')
		  $this->k=1;
	  elseif($unit=='mm')
		  $this->k=72/25.4;
	  elseif($unit=='cm')
		  $this->k=72/2.54;
	  elseif($unit=='in')
		  $this->k=72;
	  else
		  $this->Error('Incorrect unit: '.$unit);
	  //Page format
	  if(is_string($format)){
		  $format=strtolower($format);
		  if($format=='a3')
		  	$format=array(841.89,1190.55);
		  elseif($format=='a4')
			  $format=array(595.28,841.89);
		  elseif($format=='a5')
			  $format=array(420.94,595.28);
		  elseif($format=='letter')
			  $format=array(612,792);
		  elseif($format=='legal')
			  $format=array(612,1008);
		  else
			  $this->Error('Unknown page format: '.$format);
		  $this->fwPt=$format[0];
		  $this->fhPt=$format[1];
	  } else {
		  $this->fwPt=round($format[0]*$this->k,2);
		  $this->fhPt=round($format[1]*$this->k,2);
	  }
	  $this->fw=round($this->fwPt/$this->k,2);
	  $this->fh=round($this->fhPt/$this->k,2);
	  //Page orientation
	  $orientation=strtolower($orientation);
	  if($orientation=='p' or $orientation=='portrait'){
		  $this->DefOrientation='P';
		  $this->wPt=$this->fwPt;
		  $this->hPt=$this->fhPt;
  	}elseif($orientation=='l' or $orientation=='landscape'){
		  $this->DefOrientation='L';
		  $this->wPt=$this->fhPt;
		  $this->hPt=$this->fwPt;
	  }else
		  $this->Error('Incorrect orientation: '.$orientation);
	  $this->CurOrientation=$this->DefOrientation;
	  $this->w=round($this->wPt/$this->k,2);
	  $this->h=round($this->hPt/$this->k,2);
	  //Page margins (1 cm)
	  $margin=round(28.35/$this->k,2);
	  $this->SetMargins($margin,$margin);
	  //Interior cell margin (1 mm)
	  $this->cMargin=$margin/10;
	  //Line width (0.2 mm)
	  $this->LineWidth=round(.567/$this->k,3);
	  //Automatic page break
	  $this->SetAutoPageBreak(true,2*$margin);
	  //Full width display mode
	  $this->SetDisplayMode('fullwidth');
	  //Compression
	  $this->SetCompression(true);
	  //Links
	  $this->links=array();
  }
  
  function SetMargins($left,$top,$right=-1){
  	//Set left, top and right margins
  	$this->lMargin=$left;
  	$this->tMargin=$top;
  	if($right==-1)
  		$right=$left;
  	$this->rMargin=$right;
  }
  
  function SetLeftMargin($margin){
  	//Set left margin
  	$this->lMargin=$margin;
  	if($this->page>0 and $this->x<$margin)
  		$this->x=$margin;
  }
  
  function SetTopMargin($margin){
  	//Set top margin
  	$this->tMargin=$margin;
  }
  
  function SetRightMargin($margin){
  	//Set right margin
  	$this->rMargin=$margin;
  }
  
  function SetAutoPageBreak($auto,$margin=0){
  	//Set auto page break mode and triggering margin
  	$this->AutoPageBreak=$auto;
  	$this->bMargin=$margin;
  	$this->PageBreakTrigger=$this->h-$margin;
  }
  
  function SetDisplayMode($zoom,$layout='continuous'){
  	//Set display mode in viewer
  	if($zoom=='fullpage' or $zoom=='fullwidth' or $zoom=='real' or $zoom=='default' or !is_string($zoom))
  		$this->ZoomMode=$zoom;
  	elseif($zoom=='zoom')
  		$this->ZoomMode=$layout;
  	else
  		$this->Error('Incorrect zoom display mode: '.$zoom);
  	if($layout=='single' or $layout=='continuous' or $layout=='two' or $layout=='default')
  		$this->LayoutMode=$layout;
  	elseif($zoom!='zoom')
  		$this->Error('Incorrect layout display mode: '.$layout);
  }
  
  function SetCompression($compress){
  	//Set page compression
  	if(function_exists('gzcompress'))
  		$this->compress=$compress;
  	else
  		$this->compress=false;
  }
  
  function SetTitle($title){
  	//Title of document
  	$this->title=$title;
  }
  
  function SetSubject($subject){
  	//Subject of document
  	$this->subject=$subject;
  }
  
  function SetAuthor($author){
  	//Author of document
  	$this->author=$author;
  }
  
  function SetKeywords($keywords){
  	//Keywords of document
  	$this->keywords=$keywords;
  }
  
  function SetCreator($creator){
  	//Creator of document
  	$this->creator=$creator;
  }
  
  function AliasNbPages($alias='{nb}'){
  	//Define an alias for total number of pages
  	$this->AliasNbPages=$alias;
  }
  
  function Error($msg){
  	//Fatal error
  	die('<B>FPDF error: </B>'.$msg);
  }
  
  function Open(){
  	//Begin document
  	$this->_begindoc();
  }
  
  function Close(){
  	//Terminate document
  	if($this->page==0)
  		$this->AddPage();
  	//Page footer
  	$this->InFooter=true;
  	$this->Footer();
  	$this->InFooter=false;
  	//Close page
  	$this->_endpage();
  	//Close document
  	$this->_enddoc();
  }
  
  function AddPage($orientation=''){
  	//Start a new page
  	$family=$this->FontFamily;
  	$style=$this->FontStyle.($this->underline ? 'U' : '');
  	$size=$this->FontSizePt;
  	$lw=$this->LineWidth;
  	$dc=$this->DrawColor;
  	$fc=$this->FillColor;
  	$tc=$this->TextColor;
  	$cf=$this->ColorFlag;
  	if($this->page>0)	{
  		//Page footer
  		$this->InFooter=true;
  		$this->Footer();
  		$this->InFooter=false;
  		//Close page
  		$this->_endpage();
  	}
  	//Start new page
  	$this->_beginpage($orientation);
  	//Set line cap style to square
  	$this->_out('2 J');
  	//Set line width
  	$this->LineWidth=$lw;
  	$this->_out($lw.' w');
  	//Set font
  	if($family)
  		$this->SetFont($family,$style,$size);
  	//Set colors
  	$this->DrawColor=$dc;
  	if($dc!='0 G')
  		$this->_out($dc);
  	$this->FillColor=$fc;
  	if($fc!='0 g')
  		$this->_out($fc);
  	$this->TextColor=$tc;
  	$this->ColorFlag=$cf;
  	//Page header
  	$this->Header();
  	//Restore line width
  	if($this->LineWidth!=$lw)	{
  		$this->LineWidth=$lw;
  		$this->_out($lw.' w');
  	}
  	//Restore font
  	if($family)
  		$this->SetFont($family,$style,$size);
  	//Restore colors
  	if($this->DrawColor!=$dc)	{
  		$this->DrawColor=$dc;
  		$this->_out($dc);
  	}
  	if($this->FillColor!=$fc)	{
  		$this->FillColor=$fc;
  		$this->_out($fc);
  	}
  	$this->TextColor=$tc;
  	$this->ColorFlag=$cf;
  }
  
  function Header(){
  	//To be implemented in your own inherited class
  }
  
  function Footer(){
  	//To be implemented in your own inherited class
  }
  
  function PageNo(){
  	//Get current page number
  	return $this->page;
  }
  
  function SetDrawColor($r,$g=-1,$b=-1){
  	//Set color for all stroking operations
  	if(($r==0 and $g==0 and $b==0) or $g==-1)
  		$this->DrawColor=substr($r/255,0,5).' G';
  	else
  		$this->DrawColor=substr($r/255,0,5).' '.substr($g/255,0,5).' '.substr($b/255,0,5).' RG';
  	if($this->page>0)
  		$this->_out($this->DrawColor);
  }
  
  function SetFillColor($r,$g=-1,$b=-1){
  	//Set color for all filling operations
  	if(($r==0 and $g==0 and $b==0) or $g==-1)
  		$this->FillColor=substr($r/255,0,5).' g';
  	else
  		$this->FillColor=substr($r/255,0,5).' '.substr($g/255,0,5).' '.substr($b/255,0,5).' rg';
  	$this->ColorFlag=($this->FillColor!=$this->TextColor);
  	if($this->page>0)
  		$this->_out($this->FillColor);
  }
  
  function SetTextColor($r,$g=-1,$b=-1){
  	//Set color for text
  	if(($r==0 and $g==0 and $b==0) or $g==-1)
  		$this->TextColor=substr($r/255,0,5).' g';
  	else
  		$this->TextColor=substr($r/255,0,5).' '.substr($g/255,0,5).' '.substr($b/255,0,5).' rg';
  	$this->ColorFlag=($this->FillColor!=$this->TextColor);
  }
  
  function GetStringWidth($s){
  	//Get width of a string in the current font
  	$s=(string)$s;
  	$cw=&$this->CurrentFont['cw'];
  	$w=0;
  	$l=strlen($s);
  	for($i=0;$i<$l;$i++)
  		$w+=$cw[$s{$i}];
  	return $w*$this->FontSize/1000;
  }
  
  function SetLineWidth($width){
  	//Set line width
  	$this->LineWidth=$width;
  	if($this->page>0)
  		$this->_out($width.' w');
  }
  
  function Line($x1,$y1,$x2,$y2){
  	//Draw a line
  	$this->_out($x1.' -'.$y1.' m '.$x2.' -'.$y2.' l S');
  }
  
  function Rect($x,$y,$w,$h,$style=''){
  	//Draw a rectangle
  	if($style=='F')
  		$op='f';
  	elseif($style=='FD' or $style=='DF')
  		$op='B';
  	else
  		$op='S';
  	$this->_out($x.' -'.$y.' '.$w.' -'.$h.' re '.$op);
  }
  
  function AddFont($family,$style='',$file=''){
  	//Add a TrueType font
  	$family=strtolower($family);
  	$style=strtoupper($style);
  	if($style=='IB')
  		$style='BI';
  	if(isset($this->fonts[$family.$style]))
  		$this->Error('Font already added: '.$family.' '.$style);
  	if($file=='')
  		$file=str_replace(' ','',$family).strtolower($style).'.php';
  	if(defined('FPDF_FONTPATH'))
  		$file=FPDF_FONTPATH.$file;
  	include($file);
  	if(!isset($name))
  		$this->Error('Could not include font definition file');
  	$i=count($this->fonts)+1;
  	$this->fonts[$family.$style]=array('i'=>$i,'type'=>$type,'name'=>$name,'desc'=>$desc,'up'=>$up,'ut'=>$ut,'cw'=>$cw,'enc'=>$enc,'file'=>$file);
  	if($diff)	{
  		//Search existing encodings
  		$d=0;
  		$nb=count($this->diffs);
  		for($i=1;$i<=$nb;$i++)
  			if($this->diffs[$i]==$diff){
  				$d=$i;
  				break;
  			}
  		if($d==0){
  			$d=$nb+1;
  			$this->diffs[$d]=$diff;
  		}
  		$this->fonts[$family.$style]['diff']=$d;
  	}
  	if($file)
  		$this->FontFiles[$file]=array('originalsize'=>$originalsize);
  }
  
  function SetFont($family,$style='',$size=0){
  	//Select a font; size given in points
  	global $fpdf_charwidths;
  
  	$family=strtolower($family);
  	if($family=='')
  		$family=$this->FontFamily;
  	if($family=='arial')
  		$family='helvetica';
  	if($family=='symbol' or $family=='zapfdingbats')
  		$style='';
  	$style=strtoupper($style);
  	if(is_int(strpos($style,'U')))
  	{
  		$this->underline=true;
  		$style=str_replace('U','',$style);
  	}
  	else
  		$this->underline=false;
  	if($style=='IB')
  		$style='BI';
  	if($size==0)
  		$size=$this->FontSizePt;
  	//Test if font is already selected
  	if($this->FontFamily==$family and $this->FontStyle==$style and $this->FontSizePt==$size)
  		return;
  	//Test if used for the first time
  	$fontkey=$family.$style;
  	if(!isset($this->fonts[$fontkey])){
  		//Check if one of the core fonts
  		if(isset($this->CoreFonts[$fontkey])){
  			if(!isset($fpdf_charwidths[$fontkey])){
  				//Load metric file
  				$file=$family;
  				if($family=='times' or $family=='helvetica')
  					$file.=strtolower($style);
  				$file.='.php';
  				if(defined('FPDF_FONTPATH'))
  					$file=FPDF_FONTPATH.$file;
  				include($file);
  				if(!isset($fpdf_charwidths[$fontkey]))
  					$this->Error('Could not include font metric file');
  			}
  			$i=count($this->fonts)+1;
  			$this->fonts[$fontkey]=array('i'=>$i,'type'=>'core','name'=>$this->CoreFonts[$fontkey],'up'=>-100,'ut'=>50,'cw'=>$fpdf_charwidths[$fontkey]);
  		}
  		else
  			$this->Error('Undefined font: '.$family.' '.$style);
  	}
  	//Select it
  	$this->FontFamily=$family;
  	$this->FontStyle=$style;
  	$this->FontSizePt=$size;
  	$this->FontSize=round($size/$this->k,2);
  	$this->CurrentFont=&$this->fonts[$fontkey];
  	if($this->page>0)
  		$this->_out('BT /F'.$this->CurrentFont['i'].' '.$this->FontSize.' Tf ET');
  }
  
  function SetFontSize($size){
  	//Set font size in points
  	if($this->FontSizePt==$size)
  		return;
  	$this->FontSizePt=$size;
  	$this->FontSize=round($size/$this->k,2);
  	if($this->page>0)
  		$this->_out('BT /F'.$this->CurrentFont['i'].' '.$this->FontSize.' Tf ET');
  }
  
  function AddLink(){
  	//Create a new internal link
  	$n=count($this->links)+1;
  	$this->links[$n]=array(0,0);
  	return $n;
  }
  
  function SetLink($link,$y=0,$page=-1){
  	//Set destination of internal link
  	if($y==-1)
  		$y=$this->y;
  	if($page==-1)
  		$page=$this->page;
  	$this->links[$link]=array($page,$this->hPt-$y*$this->k);
  }
  
  function Link($x,$y,$w,$h,$link){
  	//Put a link on the page
  	$this->PageLinks[$this->page][]=array($x*$this->k,$this->hPt-$y*$this->k,$w*$this->k,$h*$this->k,$link);
  }
  
  function Text($x,$y,$txt){
  	//Output a string
  	$txt=str_replace(')','\\)',str_replace('(','\\(',str_replace('\\','\\\\',$txt)));
  	$s='BT '.$x.' -'.$y.' Td ('.$txt.') Tj ET';
  	if($this->underline and $txt!='')
  		$s.=' '.$this->_dounderline($x,$y,$txt);
  	if($this->ColorFlag)
  		$s='q '.$this->TextColor.' '.$s.' Q';
  	$this->_out($s);
  }
  
  function AcceptPageBreak(){
  	//Accept automatic page break or not
  	return $this->AutoPageBreak;
  }
  
  function Cell($w,$h=0,$txt='',$border=0,$ln=0,$align='',$fill=0,$link=''){
  	//Output a cell
  	if($this->y+$h>$this->PageBreakTrigger and !$this->InFooter and $this->AcceptPageBreak())	{
  		$x=$this->x;
  		$ws=$this->ws;
  		if($ws>0)		{
  			$this->ws=0;
  			$this->_out('0 Tw');
  		}
  		$this->AddPage($this->CurOrientation);
  		$this->x=$x;
  		if($ws>0)		{
  			$this->ws=$ws;
  			$this->_out($ws.' Tw');
  		}
  	}
  	if($w==0)
  		$w=$this->w-$this->rMargin-$this->x;
  	$s='';
  	if($fill==1 or $border==1)	{
  		$s.=$this->x.' -'.$this->y.' '.$w.' -'.$h.' re ';
  		if($fill==1)
  			$s.=($border==1) ? 'B ' : 'f ';
  		else
  			$s.='S ';
  	}
  	if(is_string($border)){
  		$x=$this->x;
  		$y=$this->y;
  		if(is_int(strpos($border,'L')))
  			$s.=$x.' -'.$y.' m '.$x.' -'.($y+$h).' l S ';
  		if(is_int(strpos($border,'T')))
  			$s.=$x.' -'.$y.' m '.($x+$w).' -'.$y.' l S ';
  		if(is_int(strpos($border,'R')))
  			$s.=($x+$w).' -'.$y.' m '.($x+$w).' -'.($y+$h).' l S ';
  		if(is_int(strpos($border,'B')))
  			$s.=$x.' -'.($y+$h).' m '.($x+$w).' -'.($y+$h).' l S ';
  	}
  	if($txt!=''){
  		if($align=='R')
  			$dx=$w-$this->cMargin-$this->GetStringWidth($txt);
  		elseif($align=='C')
  			$dx=($w-$this->GetStringWidth($txt))/2;
  		else
  			$dx=$this->cMargin;
  		$txt=str_replace(')','\\)',str_replace('(','\\(',str_replace('\\','\\\\',$txt)));
  		if($this->ColorFlag)
  			$s.='q '.$this->TextColor.' ';
  		$s.='BT '.($this->x+$dx).' -'.($this->y+.5*$h+.3*$this->FontSize).' Td ('.$txt.') Tj ET';
  		if($this->underline)
  			$s.=' '.$this->_dounderline($this->x+$dx,$this->y+.5*$h+.3*$this->FontSize,$txt);
  		if($this->ColorFlag)
  			$s.=' Q';
  		if($link)
  			$this->Link($this->x+$this->cMargin,$this->y+.5*$h-.5*$this->FontSize,$this->GetStringWidth($txt),$this->FontSize,$link);
  	}
  	if($s)
  		$this->_out($s);
  	$this->lasth=$h;
  	if($ln>0){
  		//Go to next line
  		$this->y+=$h;
  		if($ln==1)
  			$this->x=$this->lMargin;
  	}
  	else
  		$this->x+=$w;
  }
  
  function MultiCell($w,$h,$txt,$border=0,$align='J',$fill=0){
  	//Output text with automatic or explicit line breaks
  	$cw=&$this->CurrentFont['cw'];
  	if($w==0)
  		$w=$this->w-$this->rMargin-$this->x;
  	$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
  	$s=str_replace("\r",'',$txt);
  	$nb=strlen($s);
  	if($nb>0 and $s[$nb-1]=="\n")
  		$nb--;
  	$b=0;
  	if($border)	{
  		if($border==1){
  			$border='LTRB';
  			$b='LRT';
  			$b2='LR';
  		}else{
  			$b2='';
  			if(is_int(strpos($border,'L')))
  				$b2.='L';
  			if(is_int(strpos($border,'R')))
  				$b2.='R';
  			$b=is_int(strpos($border,'T')) ? $b2.'T' : $b2;
  		}
  	}
  	$sep=-1;
  	$i=0;
  	$j=0;
  	$l=0;
  	$ns=0;
  	$nl=1;
  	while($i<$nb){
  		//Get next character
  		$c=$s[$i];
  		if($c=="\n"){
  			//Explicit line break
  			if($this->ws>0){
  				$this->ws=0;
  				$this->_out('0 Tw');
  			}
  			$this->Cell($w,$h,substr($s,$j,$i-$j),$b,2,$align,$fill);
  			$i++;
  			$sep=-1;
  			$j=$i;
  			$l=0;
  			$ns=0;
  			$nl++;
  			if($border and $nl==2)
  				$b=$b2;
  			continue;
  		}
  		if($c==' '){
  			$sep=$i;
  			$ls=$l;
  			$ns++;
  		}
  		$l+=$cw[$c];
  		if($l>$wmax){
  			//Automatic line break
  			if($sep==-1){
  				if($i==$j)
  					$i++;
  				if($this->ws>0){
  					$this->ws=0;
  					$this->_out('0 Tw');
  				}
  				$this->Cell($w,$h,substr($s,$j,$i-$j),$b,2,$align,$fill);
  			}else{
  				if($align=='J'){
  					$this->ws=($ns>1) ? round(($wmax-$ls)/1000*$this->FontSize/($ns-1),3) : 0;
  					$this->_out($this->ws.' Tw');
  				}
  				$this->Cell($w,$h,substr($s,$j,$sep-$j),$b,2,$align,$fill);
  				$i=$sep+1;
  			}
  			$sep=-1;
  			$j=$i;
  			$l=0;
  			$ns=0;
  			$nl++;
  			if($border and $nl==2)
  				$b=$b2;
  		}
  		else
  			$i++;
  	}
  	//Last chunk
  	if($this->ws>0){
  		$this->ws=0;
  		$this->_out('0 Tw');
  	}
  	if($border and is_int(strpos($border,'B')))
  		$b.='B';
  	$this->Cell($w,$h,substr($s,$j,$i),$b,2,$align,$fill);
  	$this->x=$this->lMargin;
  }
  
  function Write($h,$txt,$link=''){
  	//Output text in flowing mode
  	$cw=&$this->CurrentFont['cw'];
  	$w=$this->w-$this->rMargin-$this->x;
  	$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
  	$s=str_replace("\r",'',$txt);
  	$nb=strlen($s);
  	$sep=-1;
  	$i=0;
  	$j=0;
  	$l=0;
  	$nl=1;
  	while($i<$nb){
  		//Get next character
  		$c=$s{$i};
  		if($c=="\n"){
  			//Explicit line break
  			$this->Cell($w,$h,substr($s,$j,$i-$j),0,2,'',0,$link);
  			$i++;
  			$sep=-1;
  			$j=$i;
  			$l=0;
  			if($nl==1){
  				$this->x=$this->lMargin;
  				$w=$this->w-$this->rMargin-$this->x;
  				$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
  			}
  			$nl++;
  			continue;
  		}
  		if($c==' '){
  			$sep=$i;
  			$ls=$l;
  		}
  		$l+=$cw[$c];
  		if($l>$wmax){
  			//Automatic line break
  			if($sep==-1){
  				if($this->x>$this->lMargin){
  					//Move to next line
  					$this->x=$this->lMargin;
  					$this->y+=$h;
  					$w=$this->w-$this->rMargin-$this->x;
  					$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
  					$i++;
  					$nl++;
  					continue;
  				}
  				if($i==$j)
  					$i++;
  				$this->Cell($w,$h,substr($s,$j,$i-$j),0,2,'',0,$link);
  			}else	{
  				$this->Cell($w,$h,substr($s,$j,$sep-$j),0,2,'',0,$link);
  				$i=$sep+1;
  			}
  			$sep=-1;
  			$j=$i;
  			$l=0;
  			if($nl==1){
  				$this->x=$this->lMargin;
  				$w=$this->w-$this->rMargin-$this->x;
  				$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
  			}
  			$nl++;
  		}
  		else
  			$i++;
  	}
  	//Last chunk
  	if($i!=$j){
  		$w=round($l/1000*$this->FontSize,2);
  		$this->Cell($w,$h,substr($s,$j,$i),0,0,'',0,$link);
  	}
  }
  
  function Image($file,$x,$y,$w,$h=0,$type='',$link=''){
  	//Put an image on the page
  	if(!isset($this->images[$file])){
  		//First use of image, get info
  		if($type==''){
  			$pos=strrpos($file,'.');
  			if(!$pos)
  				$this->Error('Image file has no extension and no type was specified: '.$file);
  			$type=substr($file,$pos+1);
  		}
  		$type=strtolower($type);
  		$mqr=get_magic_quotes_runtime();
  		set_magic_quotes_runtime(0);
  		if($type=='jpg' or $type=='jpeg')
  			$info=$this->_parsejpg($file);
  		elseif($type=='png')
  			$info=$this->_parsepng($file);
  		else
  			$this->Error('Unsupported image file type: '.$type);
  		set_magic_quotes_runtime($mqr);
  		$info['n']=count($this->images)+1;
  		$this->images[$file]=$info;
  	}	else
  		$info=$this->images[$file];
  	//Automatic width or height calculus
  	if($w==0)
  		$w=round($h*$info['w']/$info['h'],2);
  	if($h==0)
  		$h=round($w*$info['h']/$info['w'],2);
  	$this->_out('q '.$w.' 0 0 '.$h.' '.$x.' -'.($y+$h).' cm /I'.$info['n'].' Do Q');
  	if($link)
  		$this->Link($x,$y,$w,$h,$link);
  }
  
  function Ln($h=''){
  	//Line feed; default value is last cell height
  	$this->x=$this->lMargin;
  	if(is_string($h))
  		$this->y+=$this->lasth;
  	else
  		$this->y+=$h;
  }
  
  function GetX(){
  	//Get x position
  	return $this->x;
  }
  
  function SetX($x){
  	//Set x position
  	if($x>=0)
  		$this->x=$x;
  	else
  		$this->x=$this->w+$x;
  }
  
  function GetY(){
  	//Get y position
  	return $this->y;
  }
  
  function SetY($y){
  	//Set y position and reset x
  	$this->x=$this->lMargin;
  	if($y>=0)
  		$this->y=$y;
  	else
  		$this->y=$this->h+$y;
  }
  
  function SetXY($x,$y){
  	//Set x and y positions
  	$this->SetY($y);
  	$this->SetX($x);
  }
  
  function Output($file='',$download=false){
  	//Output PDF to file or browser
  	global $HTTP_ENV_VARS;
  	if($this->state<3)
  		$this->Close();
  	if($file=='')	{
  		//Send to browser
  		Header('Content-Type: application/pdf');
  		if(headers_sent())
  			$this->Error('Some data has already been output to browser, can\'t send PDF file');
  		Header('Content-Length: '.strlen($this->buffer));
  		Header('Content-disposition: inline; filename=doc.pdf');
  		echo $this->buffer;
  	}	else{
  		if($download)	{
  			//Download file
  			if(isset($HTTP_ENV_VARS['HTTP_USER_AGENT']) and strpos($HTTP_ENV_VARS['HTTP_USER_AGENT'],'MSIE 5.5'))
  				Header('Content-Type: application/dummy');
  			else
  				Header('Content-Type: application/octet-stream');
  			if(headers_sent())
  				$this->Error('Some data has already been output to browser, can\'t send PDF file');
  			Header('Content-Length: '.strlen($this->buffer));
  			Header('Content-disposition: attachment; filename='.$file);
  			echo $this->buffer;
  		}else{
  			//Save file locally
  			$f=fopen($file,'wb');
  			if(!$f)
  				$this->Error('Unable to create output file: '.$file);
  			fwrite($f,$this->buffer,strlen($this->buffer));
  			fclose($f);
  		}
  	}
  }
  
  /****************************************************************************
  *                                                                           *
  *                              Private methods                              *
  *                                                                           *
  ****************************************************************************/
  function _begindoc(){
  	//Start document
  	$this->state=1;
  	$this->_out('%PDF-1.3');
  }
  
  function _enddoc(){
  	//Terminate document
  	$nb=$this->page;
  	if(!empty($this->AliasNbPages))	{
  		//Replace number of pages
  		for($n=1;$n<=$nb;$n++)
  			$this->pages[$n]=str_replace($this->AliasNbPages,$nb,$this->pages[$n]);
  	}	if($this->DefOrientation=='P'){
  		$wPt=$this->fwPt;
  		$hPt=$this->fhPt;
  	}	else{
  		$wPt=$this->fhPt;
  		$hPt=$this->fwPt;
  	}
  	$filter=($this->compress) ? '/Filter /FlateDecode ' : '';
  	for($n=1;$n<=$nb;$n++){
  		//Page
  		$this->_newobj();
  		$this->_out('<</Type /Page');
  		$this->_out('/Parent 1 0 R');
  		if(isset($this->OrientationChanges[$n]))
  			$this->_out('/MediaBox [0 0 '.$hPt.' '.$wPt.']');
  		$this->_out('/Resources 2 0 R');
  		if(isset($this->PageLinks[$n])){
  			$annots='/Annots [';
  			foreach($this->PageLinks[$n] as $pl){
  				$rect=round($pl[0],2).' '.round($pl[1],2).' '.round($pl[0]+$pl[2],2).' '.round($pl[1]-$pl[3],2);
  				$annots.='<</Type /Annot /Subtype /Link /Rect ['.$rect.'] /Border [0 0 0] ';
  				if(is_string($pl[4]))
  					$annots.='/A <</S /URI /URI ('.$pl[4].')>>>>';
  				else{
  					$l=$this->links[$pl[4]];
  					$annots.='/Dest ['.(1+2*$l[0]).' 0 R /XYZ 0 '.$l[1].' null]>>';
  				}
  			}
  			$this->_out($annots.']');
  		}
  		$this->_out('/Contents '.($this->n+1).' 0 R>>');
  		$this->_out('endobj');
  		//Page content
  		$p=($this->compress) ? gzcompress($this->pages[$n]) : $this->pages[$n];
  		$this->_newobj();
  		$this->_out('<<'.$filter.'/Length '.strlen($p).'>>');
  		$this->_out('stream');
  		$this->_out($p.'endstream');
  		$this->_out('endobj');
  	}
  	//Fonts
  	$nf=$this->n;
  	foreach($this->diffs as $diff){
  		//Encodings
  		$this->_newobj();
  		$this->_out('<</Type /Encoding /BaseEncoding /WinAnsiEncoding /Differences ['.$diff.']>>');
  		$this->_out('endobj');
  	}
  	foreach($this->FontFiles as $file=>$info){
  		//Font file embedding
  		$this->_newobj();
  		$this->FontFiles[$file]['n']=$this->n;
  		if(defined('FPDF_FONTPATH'))
  			$file=FPDF_FONTPATH.$file;
  		$size=filesize($file);
  		if(!$size)
  			$this->Error('Font file not found');
  		$this->_out('<</Length '.$size);
  		if(substr($file,-2)=='.z')
  			$this->_out('/Filter /FlateDecode');
  		$this->_out('/Length1 '.$info['originalsize']);
  		$this->_out('>>');
  		$this->_out('stream');
  		$f=fopen($file,'rb');
  		$this->_out(fread($f,$size));
  		fclose($f);
  		$this->_out('endstream');
  		$this->_out('endobj');
  	}
  	foreach($this->fonts as $k=>$font){
  		//Font objects
  		$this->_newobj();
  		$this->fonts[$k]['n']=$this->n;
  		$name=$font['name'];
  		$this->_out('<</Type /Font');
  		$this->_out('/BaseFont /'.$name);
  		if($font['type']=='core'){
  			//Standard font
  			$this->_out('/Subtype /Type1');
  			if($name!='Symbol' and $name!='ZapfDingbats')
  				$this->_out('/Encoding /WinAnsiEncoding');
  		}else{
  			//TrueType
  			$this->_out('/Subtype /TrueType');
  			$this->_out('/FirstChar 32');
  			$this->_out('/LastChar 255');
  			$this->_out('/Widths '.($this->n+1).' 0 R');
  			$this->_out('/FontDescriptor '.($this->n+2).' 0 R');
  			if($font['enc']){
  				if(isset($font['diff']))
  					$this->_out('/Encoding '.($nf+$font['diff']).' 0 R');
  				else
  					$this->_out('/Encoding /WinAnsiEncoding');
  			}
  		}
  		$this->_out('>>');
  		$this->_out('endobj');
  		if($font['type']!='core'){
  			//Widths
  			$this->_newobj();
  			$cw=&$font['cw'];
  			$s='[';
  			for($i=32;$i<=255;$i++)
  				$s.=$cw[chr($i)].' ';
  			$this->_out($s.']');
  			$this->_out('endobj');
  			//Descriptor
  			$this->_newobj();
  			$s='<</Type /FontDescriptor /FontName /'.$name;
  			foreach($font['desc'] as $k=>$v)
  				$s.=' /'.$k.' '.$v;
  			$file=$font['file'];
  			if($file)
  				$s.=' /FontFile2 '.$this->FontFiles[$file]['n'].' 0 R';
  			$this->_out($s.'>>');
  			$this->_out('endobj');
  		}
  	}
  	//Images
  	$ni=$this->n;
  	reset($this->images);
  	while(list($file,$info)=each($this->images)){
  		$this->_newobj();
  		$this->_out('<</Type /XObject');
  		$this->_out('/Subtype /Image');
  		$this->_out('/Width '.$info['w']);
  		$this->_out('/Height '.$info['h']);
  		if($info['cs']=='Indexed')
  			$this->_out('/ColorSpace [/Indexed /DeviceRGB '.(strlen($info['pal'])/3-1).' '.($this->n+1).' 0 R]');
  		else
  			$this->_out('/ColorSpace /'.$info['cs']);
  		$this->_out('/BitsPerComponent '.$info['bpc']);
  		$this->_out('/Filter /'.$info['f']);
  		if(isset($info['parms']))
  			$this->_out($info['parms']);
  		if(isset($info['trns']) and is_array($info['trns'])){
  			$trns='';
  			for($i=0;$i<count($info['trns']);$i++)
  				$trns.=$info['trns'][$i].' '.$info['trns'][$i].' ';
  			$this->_out('/Mask ['.$trns.']');
  		}
  		$this->_out('/Length '.strlen($info['data']).'>>');
  		$this->_out('stream');
  		$this->_out($info['data']);
  		$this->_out('endstream');
  		$this->_out('endobj');
  		//Palette
  		if($info['cs']=='Indexed'){
  			$this->_newobj();
  			$this->_out('<</Length '.strlen($info['pal']).'>>');
  			$this->_out('stream');
  			$this->_out($info['pal']);
  			$this->_out('endstream');
  			$this->_out('endobj');
  		}
  	}
  	//Pages root
  	$this->offsets[1]=strlen($this->buffer);
  	$this->_out('1 0 obj');
  	$this->_out('<</Type /Pages');
  	$kids='/Kids [';
  	for($i=0;$i<$this->page;$i++)
  		$kids.=(3+2*$i).' 0 R ';
  	$this->_out($kids.']');
  	$this->_out('/Count '.$this->page);
  	$this->_out('/MediaBox [0 0 '.$wPt.' '.$hPt.']');
  	$this->_out('>>');
  	$this->_out('endobj');
  	//Resources
  	$this->offsets[2]=strlen($this->buffer);
  	$this->_out('2 0 obj');
  	$this->_out('<</ProcSet [/PDF /Text /ImageB /ImageC /ImageI]');
  	$this->_out('/Font <<');
  	foreach($this->fonts as $font)
  		$this->_out('/F'.$font['i'].' '.$font['n'].' 0 R');
  	$this->_out('>>');
  	if(count($this->images)){
  		$this->_out('/XObject <<');
  		$nbpal=0;
  		reset($this->images);
  		while(list(,$info)=each($this->images))	{
  			$this->_out('/I'.$info['n'].' '.($ni+$info['n']+$nbpal).' 0 R');
  			if($info['cs']=='Indexed')
  				$nbpal++;
  		}
  		$this->_out('>>');
  	}
  	$this->_out('>>');
  	$this->_out('endobj');
  	//Info
  	$this->_newobj();
  	$this->_out('<</Producer (FPDF '.FPDF_VERSION.')');
  	if(!empty($this->title))
  		$this->_out('/Title ('.$this->_escape($this->title).')');
  	if(!empty($this->subject))
  		$this->_out('/Subject ('.$this->_escape($this->subject).')');
  	if(!empty($this->author))
  		$this->_out('/Author ('.$this->_escape($this->author).')');
  	if(!empty($this->keywords))
  		$this->_out('/Keywords ('.$this->_escape($this->keywords).')');
  	if(!empty($this->creator))
  		$this->_out('/Creator ('.$this->_escape($this->creator).')');
  	$this->_out('/CreationDate (D:'.date('YmdHis').')>>');
  	$this->_out('endobj');
  	//Catalog
  	$this->_newobj();
  	$this->_out('<</Type /Catalog');
  	if($this->ZoomMode=='fullpage')
  		$this->_out('/OpenAction [3 0 R /Fit]');
  	elseif($this->ZoomMode=='fullwidth')
  		$this->_out('/OpenAction [3 0 R /FitH null]');
  	elseif($this->ZoomMode=='real')
  		$this->_out('/OpenAction [3 0 R /XYZ null null 1]');
  	elseif(!is_string($this->ZoomMode))
  		$this->_out('/OpenAction [3 0 R /XYZ null null '.($this->ZoomMode/100).']');
  	if($this->LayoutMode=='single')
  		$this->_out('/PageLayout /SinglePage');
  	elseif($this->LayoutMode=='continuous')
  		$this->_out('/PageLayout /OneColumn');
  	elseif($this->LayoutMode=='two')
  		$this->_out('/PageLayout /TwoColumnLeft');
  	$this->_out('/Pages 1 0 R>>');
  	$this->_out('endobj');
  	//Cross-ref
  	$o=strlen($this->buffer);
  	$this->_out('xref');
  	$this->_out('0 '.($this->n+1));
  	$this->_out('0000000000 65535 f ');
  	for($i=1;$i<=$this->n;$i++)
  		$this->_out(sprintf('%010d 00000 n ',$this->offsets[$i]));
  	//Trailer
  	$this->_out('trailer');
  	$this->_out('<</Size '.($this->n+1));
  	$this->_out('/Root '.$this->n.' 0 R');
  	$this->_out('/Info '.($this->n-1).' 0 R>>');
  	$this->_out('startxref');
  	$this->_out($o);
  	$this->_out('%%EOF');
  	$this->state=3;
  }
  
  function _beginpage($orientation){
  	$this->page++;
  	$this->pages[$this->page]='';
  	$this->state=2;
  	$this->x=$this->lMargin;
  	$this->y=$this->tMargin;
  	$this->lasth=0;
  	$this->FontFamily='';
  	//Page orientation
  	if(!$orientation)
  		$orientation=$this->DefOrientation;
  	else{
  		$orientation=strtoupper($orientation{0});
  		if($orientation!=$this->DefOrientation)
  			$this->OrientationChanges[$this->page]=true;
  	}
  	if($orientation!=$this->CurOrientation){
  		//Change orientation
  		if($orientation=='P'){
  			$this->wPt=$this->fwPt;
  			$this->hPt=$this->fhPt;
  			$this->w=$this->fw;
  			$this->h=$this->fh;
  		}else{
  			$this->wPt=$this->fhPt;
  			$this->hPt=$this->fwPt;
  			$this->w=$this->fh;
  			$this->h=$this->fw;
  		}
  		$this->PageBreakTrigger=$this->h-$this->bMargin;
  		$this->CurOrientation=$orientation;
  	}
  	//Set transformation matrix
  	$this->_out(round($this->k,6).' 0 0 '.round($this->k,6).' 0 '.$this->hPt.' cm');
  }
  
  function _endpage(){
  	//End of page contents
  	$this->state=1;
  }
  
  function _newobj(){
  	//Begin a new object
  	$this->n++;
  	$this->offsets[$this->n]=strlen($this->buffer);
  	$this->_out($this->n.' 0 obj');
  }
  
  function _dounderline($x,$y,$txt){
  	//Underline text
  	$up=$this->CurrentFont['up'];
  	$ut=$this->CurrentFont['ut'];
  	$w=$this->GetStringWidth($txt)+$this->ws*substr_count($txt,' ');
  	return $x.' -'.($y-$up/1000*$this->FontSize).' '.$w.' -'.($ut/1000*$this->FontSize).' re f';
  }
  
  function _parsejpg($file){
  	//Extract info from a JPEG file
  	$a=GetImageSize($file);
  	if(!$a)
  		$this->Error('Missing or incorrect image file: '.$file);
  	if($a[2]!=2)
  		$this->Error('Not a JPEG file: '.$file);
  	if(!isset($a['channels']) or $a['channels']==3)
  		$colspace='DeviceRGB';
  	elseif($a['channels']==4)
  		$colspace='DeviceCMYK';
  	else
  		$colspace='DeviceGray';
  	$bpc=isset($a['bits']) ? $a['bits'] : 8;
  	//Read whole file
  	$f=fopen($file,'rb');
  	$data=fread($f,filesize($file));
  	fclose($f);
  	return array('w'=>$a[0],'h'=>$a[1],'cs'=>$colspace,'bpc'=>$bpc,'f'=>'DCTDecode','data'=>$data);
  }
  
  function _parsepng($file){
  	//Extract info from a PNG file
  	$f=fopen($file,'rb');
  	if(!$f)
  		$this->Error('Can\'t open image file: '.$file);
  	//Check signature
  	if(fread($f,8)!=chr(137).'PNG'.chr(13).chr(10).chr(26).chr(10))
  		$this->Error('Not a PNG file: '.$file);
  	//Read header chunk
  	fread($f,4);
  	if(fread($f,4)!='IHDR')
  		$this->Error('Incorrect PNG file: '.$file);
  	$w=$this->_freadint($f);
  	$h=$this->_freadint($f);
  	$bpc=ord(fread($f,1));
  	if($bpc>8)
  		$this->Error('16-bit depth not supported: '.$file);
  	$ct=ord(fread($f,1));
  	if($ct==0)
  		$colspace='DeviceGray';
  	elseif($ct==2)
  		$colspace='DeviceRGB';
  	elseif($ct==3)
  		$colspace='Indexed';
  	else
  		$this->Error('Alpha channel not supported: '.$file);
  	if(ord(fread($f,1))!=0)
  		$this->Error('Unknown compression method: '.$file);
  	if(ord(fread($f,1))!=0)
  		$this->Error('Unknown filter method: '.$file);
  	if(ord(fread($f,1))!=0)
  		$this->Error('Interlacing not supported: '.$file);
  	fread($f,4);
  	$parms='/DecodeParms <</Predictor 15 /Colors '.($ct==2 ? 3 : 1).' /BitsPerComponent '.$bpc.' /Columns '.$w.'>>';
  	//Scan chunks looking for palette, transparency and image data
  	$pal='';
  	$trns='';
  	$data='';
  	do
  	{
  		$n=$this->_freadint($f);
  		$type=fread($f,4);
  		if($type=='PLTE'){
  			//Read palette
  			$pal=fread($f,$n);
  			fread($f,4);
  		}elseif($type=='tRNS'){
  			//Read transparency info
  			$t=fread($f,$n);
  			if($ct==0)
  				$trns=array(substr($t,1,1));
  			elseif($ct==2)
  				$trns=array(substr($t,1,1),substr($t,3,1),substr($t,5,1));
  			else{
  				$pos=strpos($t,chr(0));
  				if(is_int($pos))
  					$trns=array($pos);
  			}
  			fread($f,4);
  		}	elseif($type=='IDAT'){
  			//Read image data block
  			$data.=fread($f,$n);
  			fread($f,4);
  		}
  		elseif($type=='IEND')
  			break;
  		else
  			fread($f,$n+4);
  	}
  	while($n);
  	if($colspace=='Indexed' and empty($pal))
  		$this->Error('Missing palette in '.$file);
  	fclose($f);
  	return array('w'=>$w,'h'=>$h,'cs'=>$colspace,'bpc'=>$bpc,'f'=>'FlateDecode','parms'=>$parms,'pal'=>$pal,'trns'=>$trns,'data'=>$data);
  }
  
  function _freadint($f){
  	//Read a 4-byte integer from file
  	$i=ord(fread($f,1))<<24;
  	$i+=ord(fread($f,1))<<16;
  	$i+=ord(fread($f,1))<<8;
  	$i+=ord(fread($f,1));
  	return $i;
  }
  
  function _escape($s){
  	//Add \ before \, ( and )
  	return str_replace(')','\\)',str_replace('(','\\(',str_replace('\\','\\\\',$s)));
  }
  
  function _out($s){
  	//Add a line to the document
  	if($this->state==2)
  		$this->pages[$this->page].=$s."\n";
  	else
  		$this->buffer.=$s."\n";
  }
  
  //=====
  
  function generate_pdf($day,$mon,$year,$plain=""){
    $maxdom = ($day<1)?$this->month_age($mon,$year):1;
    $d =$this->date2doy(($day<1)?1:$day,$mon,$year);
    $second=isset($_POST['cbxViewSecond'])?$_POST['cbxViewSecond']:0;
    $content="";
    $this->SetFillColor(255,0,0);
    $this->SetTextColor(255);
    $this->SetDrawColor(128,0,0);
    $this->SetLineWidth(.3);
    $this->SetFont('','B');
    for($j=0;$j<$maxdom;$j++){
      $dd=($day<1)?$this->change_format($j+1):$this->change_format($day);
      $content.="  <data>\n"
          ."    <year>$year</year>\n"
          ."    <month>".$this->change_format($mon)."</month>\n"
          ."    <date>$dd</date>\n"
          .(($plain==1) ||($plain!=1 && $_POST['cbxViewImsyak'])?"    <imsyak>".$this->show_hours($this->prayTime[$d-1][0],$second)."</imsyak>\n":"")
          ."    <fajr>".$this->show_hours($this->prayTime[$d-1][1],$second)."</fajr>\n"
          .(($plain==1) ||($plain!=1 && $_POST['cbxViewSunrise'])?"    <syuruq>".$this->show_hours($this->prayTime[$d-1][2],$second)."</syuruq>\n":"")
          ."    <dzuhr>".$this->show_hours($this->prayTime[$d-1][3],$second)."</dzuhr>\n"
          ."    <ashr>".$this->show_hours($this->prayTime[$d-1][4],$second)."</ashr>\n"
          ."    <maghrib>".$this->show_hours($this->prayTime[$d-1][5],$second)."</maghrib>\n"
          ."    <isha>".$this->show_hours($this->prayTime[$d-1][6],$second)."</isha>\n"
          ."  </data>\n";
      $d++;
    }
    return $content;
  }
  
  function generate_data($type,$day,$mon,$year,$txtTime="",$path="",$up="",$ajax=""){
    parent::generate_data($type,$day,$mon,$year,$txtTime,$path,$up,$ajax);
    $id=isset($_POST['adzanCity'])?$_POST['adzanCity']:(isset($_GET['id'])?$_GET['id']:$this->cfgArray['city']);
    list($qDirec,$qDistance)=$this->qibla($this->GEO_longitude,$this->GEO_latitude);
    $lat=$this->dtCity[$this->id][2].$this->unichr(176).$this->dtCity[$this->id][3].$this->unichr(39);
    $latsign=$this->dtCity[$this->id][4]?_S:_N;
    $long=$this->dtCity[$this->id][5].$this->unichr(176).$this->dtCity[$this->id][6].$this->unichr(39);
    $longsign=($this->dtCity[$id][7])?_E:_W;    
    $content="";
    $plain=isset($_GET['plain'])?$_GET['plain']:"";
    if($type==1){
      $content.=$this->generate_pdf($day,$mon,$year,$txtTime,$plain);
    }else if($type==2){
      $content.=$this->generate_pdf(0,$mon,$year,$txtTime,$plain);
    }else if($type==3){
      for($i=1; $i<=12; $i++){
        $content.=$this->generate_pdf(0,$i,$year,$txtTime,$plain);
      }
    }
    return $content; 
  }
  
  //End of class
}
  
