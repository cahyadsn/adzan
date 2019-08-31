<?php
/*
BISMILLAAHIRRAHMAANIRRAHIIM - In the Name of Allah, Most Gracious, Most Merciful
================================================================================
filename	: 
purpose	: 
create	: 2006/03/28
last edit	: 060328,070430,120116
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
session_start();
if ($_SESSION['adzanAdmin']!=1) {
  header("Location: index.php?msg=1");
  exit();
}
include "../class/class.config.php";
$cfg=new config("../");
$menu=5;
include "admin.header.php";
?>
   <div id="header-title"><?php echo _ABOUT;?></div>
   <div class="clr"></div>
     <div class="content-form">
        <div class="form-block" style="padding: 0px;">
<?php if($cfg->cfgArray['lang']=="indonesian"){ ?>
<p align="center">
<i><b>BISMILLAAHIRRAHMAANIRRAHIIM</b><br />Dengan menyebut Asma Allah, Yang Maha Pemurah, Maha Penyayang</i><br /><br />
"<i>... Sesungguhnya Shalat itu adalah kewajiban yang ditentukan waktunya<br />atas orang-orang beriman</i>"<br />
<b>QS. An Nisaa [Wanita] (4):103 </b>
</p>
<p><b>ADZAN</b> adalah program jadwal waktu shalat yang ditulis dalam bahasa PHP. Keluaran yang dihasilkan oleh program <b>ADZAN</b> cukup kecil, sesuai dengan HTML 4.01 dan juga ditampilkan dengan baik pada browser moderen yang mendukung HTML4+ dan JavaScript. <b>ADZAN</b> memerlukan PHP versi 4.2 atau yang lebih tinggi</p>
<p><b>ADZAN</b> dibuat oleh Cahya DSN, seorang pemrogram aplikasi web dari Indonesia, yang mengembangkan aplikasi web dengan PHP, juga 'bermain' dengan bahasa pemrograman Delphi, Java dan lain-lainnya.</p>
<p>Silakan gunakan alamat <a href="mailto:cahyadsn@yahoo.com?subject=adzan ver. <?php echo $_SESSION['version'];?>" onMouseOver="javascript:window.status='kirim email ke pembuat';return true;" onMouseOut="javascript:window.status='adzan';return true;" title="kirim email ke pembuat">cahyadsn@yahoo.com</a> untuk mengirim email yang berkaitan dengan <b>ADZAN</b> release ver ver. <?php echo $_SESSION['version'];?></p>
<p>
<pre style="color:red;">
**************************** PERHATIAN ***********************
* Program ini tidak menjamin hasil perhitungan yang ditampilkan
* pasti benar secara kaidah Islam  dan perlu dicatat bahwa 
* penentuan waktu shalat adalah sangat rumit, seperti halnya
* pada kasus dimana diukur pada derajat lintang yang tinggi, 
* misalnya : "kapan awal waktu shalat Isha dimana matahari tidak
* pernah terbenam?" Anda harus berkonsultasi dengan ulama yang
* menguasai hal ini untuk menginterprestasikan hasil dari
* program ini
**************************************************************
</pre>
</p>
<?php } else { ?>
<p align="center">
<i><b>BISMILLAAHIRRAHMAANIRRAHIIM</b><br/>In the Name of Allah, Most Gracious, Most Merciful</i><br /><br />
"<i>...For such prayers are enjoined on believers at stated times</i>"<br />
<b>An-Nisaa [Women] (4):103</b>
</p>
<p><b>ADZAN</b> is a islamic prayer time schedule written in PHP. The output produced by <b>ADZAN</b> is very small, HTML 4.01 compliant and also looks fine with any modern browser which supports HTML4+ and JavaScript. <b>ADZAN</b> requires PHP version 4.2 or higher</p>
<p><b>ADZAN</b> is developed by Cahya DSN, a Indonesian web application programmer who, besides web development with PHP, does a lot of Delphi, Java and Others language playing.</p>
<p>Please use the address <a href="mailto:cahyadsn@yahoo.com?subject=adzan ver. <?php echo $_SESSION['version'];?>" onMouseOver="javascript:window.status='send email to author';return true;" onMouseOut="javascript:window.status='adzan';return true;" title="send email to author">cahyadsn@yahoo.com</a> for email related to <b>ADZAN</b> release ver ver. <?php echo $_SESSION['version'];?></p>
<p>
<pre style="color:red;">
*************************** ATTENTION ***********************
* This program does not presume to provide theologically-correct 
* results and it should be noted that the rules are extremely 
* complex, especially at high latitudes: i.e., "when does Isha 
* start when the sun never sets?" You should consult a religious 
* advisor for guidance in interpreting the results of this program.
**************************************************************
</pre>
</p>
<?php } ?>
        </div>
      </div>
<? include "admin.footer.php"; ?>