<?php
include "../class/class.config.php";
$cfg=new config();
$cfg->get_config("../");
$algo=$cfg->cfgArray['algo']?$cfg->cfgArray['algo']:1;
$pathChk="../";
include "../class/class.html.ajax.php";
$dtFile="../data/".$cfg->cfgArray['country'].".txt";
$Adzan1=new html_ajax($dtFile,$_GET['id'],"../","../","",1);
$d=$_GET['d']?$_GET['d']:date("j");
$m=$_GET['m']?$_GET['m']:date("n");
$y=$_GET['y']?$_GET['y']:date("Y");
$Adzan1->generate_data(4,$d,$m,$y,$Adzan1->cfgTime,"../");
?>