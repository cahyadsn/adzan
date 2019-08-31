<?
function imagesmoothline ( $image , $x1 , $y1 , $x2 , $y2 , $color ){
 $colors = imagecolorsforindex ( $image , $color );
 if ( $x1 == $x2 ) {
  imageline ( $image , $x1 , $y1 , $x2 , $y2 , $color ); // Vertical line
 } else {
  $m = ( $y2 - $y1 ) / ( $x2 - $x1 );
  $b = $y1 - $m * $x1;
  if ( abs ( $m ) <= 1 ){
   $x = min ( $x1 , $x2 );
   $endx = max ( $x1 , $x2 );
   while ( $x <= $endx ) {
    $y = $m * $x + $b;
    $y == floor ( $y ) ? $ya = 1 : $ya = $y - floor ( $y );
    $yb = ceil ( $y ) - $y;
    $tempcolors = imagecolorsforindex ( $image , imagecolorat ( $image , $x , floor ( $y ) ) );
    $tempcolors['red'] = $tempcolors['red'] * $ya + $colors['red'] * $yb;
    $tempcolors['green'] = $tempcolors['green'] * $ya + $colors['green'] * $yb;
    $tempcolors['blue'] = $tempcolors['blue'] * $ya + $colors['blue'] * $yb;
    if ( imagecolorexact ( $image , $tempcolors['red'] , $tempcolors['green'] , $tempcolors['blue'] ) == -1 ) imagecolorallocate ( $image , $tempcolors['red'] , $tempcolors['green'] , $tempcolors['blue'] );
    imagesetpixel ( $image , $x , floor ( $y ) , imagecolorexact ( $image , $tempcolors['red'] , $tempcolors['green'] , $tempcolors['blue'] ) );
    $tempcolors = imagecolorsforindex ( $image , imagecolorat ( $image , $x , ceil ( $y ) ) );
    $tempcolors['red'] = $tempcolors['red'] * $yb + $colors['red'] * $ya;
     $tempcolors['green'] = $tempcolors['green'] * $yb + $colors['green'] * $ya;
    $tempcolors['blue'] = $tempcolors['blue'] * $yb + $colors['blue'] * $ya;
    if ( imagecolorexact ( $image , $tempcolors['red'] , $tempcolors['green'] , $tempcolors['blue'] ) == -1 ) imagecolorallocate ( $image , $tempcolors['red'] , $tempcolors['green'] , $tempcolors['blue'] );
    imagesetpixel ( $image , $x , ceil ( $y ) , imagecolorexact ( $image , $tempcolors['red'] , $tempcolors['green'] , $tempcolors['blue'] ) );
    $x ++;
   }
  }else{
   $y = min ( $y1 , $y2 );
   $endy = max ( $y1 , $y2 );
   while ( $y <= $endy ){
    $x = ( $y - $b ) / $m;
    $x == floor ( $x ) ? $xa = 1 : $xa = $x - floor ( $x );
    $xb = ceil ( $x ) - $x;
    $tempcolors = imagecolorsforindex ( $image , imagecolorat ( $image , floor ( $x ) , $y ) );
    $tempcolors['red'] = $tempcolors['red'] * $xa + $colors['red'] * $xb;
    $tempcolors['green'] = $tempcolors['green'] * $xa + $colors['green'] * $xb;
    $tempcolors['blue'] = $tempcolors['blue'] * $xa + $colors['blue'] * $xb;
    if ( imagecolorexact ( $image , $tempcolors['red'] , $tempcolors['green'] , $tempcolors['blue'] ) == -1 ) imagecolorallocate ( $image , $tempcolors['red'] , $tempcolors['green'] , $tempcolors['blue'] );
    imagesetpixel ( $image , floor ( $x ) , $y , imagecolorexact ( $image , $tempcolors['red'] , $tempcolors['green'] , $tempcolors['blue'] ) );
    $tempcolors = imagecolorsforindex ( $image , imagecolorat ( $image , ceil ( $x ) , $y ) );
    $tempcolors['red'] = $tempcolors['red'] * $xb + $colors['red'] * $xa;
    $tempcolors['green'] = $tempcolors['green'] * $xb + $colors['green'] * $xa;
    $tempcolors['blue'] = $tempcolors['blue'] * $xb + $colors['blue'] * $xa;
    if ( imagecolorexact ( $image , $tempcolors['red'] , $tempcolors['green'] , $tempcolors['blue'] ) == -1 ) imagecolorallocate ( $image , $tempcolors['red'] , $tempcolors['green'] , $tempcolors['blue'] );
    imagesetpixel ( $image , ceil ( $x ) , $y , imagecolorexact ( $image , $tempcolors['red'] , $tempcolors['green'] , $tempcolors['blue'] ) );
    $y ++;
   }
  }
 }
}
function dashedcircle($im, $cx, $cy, $radius, $colour, $dashsize=5) {
  $dash=false;
  for ($angle=0; $angle<=(180+$dashsize); $angle+=$dashsize) {
     $x = ($radius * cos(deg2rad($angle)));
     $y = ($radius * sin(deg2rad($angle)));
     if ($dash) {
        imageline($im, $cx+$px, $cy+$py, $cx+$x, $cy+$y, $colour); 
        imageline($im, $cx-$px, $cx-$py, $cx-$x, $cy-$y, $colour); 
     }
     $dash=!$dash;
     $px=$x;
     $py=$y;
  } 
}
$d=25;
$img     = imagecreatefrompng("qebla.png");
//$img = imagecreate(2*$d, 2*$d); 
$w   = imagecolorallocatealpha($img, 127, 255, 255, 127);
$b  = imagecolorallocate ($img,255, 0, 0);
$colour = imagecolorallocate($img, 0, 0, 255); 
dashedcircle($img, $d, $d, $d, $colour, 3);
$sdt=$_GET['s'];
$x1=cos(deg2rad($sdt-90))*$d+$d;
$y1=sin(deg2rad($sdt-90))*$d+$d;
imagesmoothline ( $img , $d ,$d , $x1 , $y1 , $b );
header("Content-type: image/png"); 
imagepng($img); 
//imagepng($im); 
//imagedestroy($im); 
imagedestroy($img); 
?>