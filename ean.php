<?php
function encode($number)
{
   $parity = array(
               0 => "000000",
               1 => "001011",
               2 => "001101",
               3 => "001110",
               4 => "010011",
               5 => "011001",
               6 => "011100",
               7 => "010101",
               8 => "010110",
               9 => "011010"
                  );

   $left = array(
            // Odd Encoding
            0 => array (
                  0 => "0001101",
                  1 => "0011001",
                  2 => "0010011",
                  3 => "0111101",
                  4 => "0100011",
                  5 => "0110001",
                  6 => "0101111",
                  7 => "0111011",
                  8 => "0110111",
                  9 => "0001011"
                       ),
            // Even Encoding
            1 => array (
                  0 => "0100111",
                  1 => "0110011",
                  2 => "0011011",
                  3 => "0100001",
                  4 => "0011101",
                  5 => "0111001",
                  6 => "0000101",
                  7 => "0010001",
                  8 => "0001001",
                  9 => "0010111"
                       )
               );

   $right = array(
             0 => "1110010",
             1 => "1100110",
             2 => "1101100",
             3 => "1000010",
             4 => "1011100",
             5 => "1001110",
             6 => "1010000",
             7 => "1000100",
             8 => "1001000",
             9 => "1110100"
                 );

   $guard = array(
             'start' => "101",
             'middle' => "01010",
             'end' => "101",
                 );

   /**
    * The following incantations use the parity key (based off the 
    * first digit of the unencoded number) to encode the first six
    * digits of the barcode. The last 6 use the same parity.
    *
    * So, if the key is 010101, the first digit (of the first six
    * digits) uses odd parity encoding. The second uses even. The
    * third uses odd, and so on.
    */

   $key = $parity[substr($number, 0, 1)];
   $number .= ean_checksum($number);

   $barcode[] = $guard['start'];

   for($i=1;$i<=strlen($number)-1;$i++)
   {
      if($i<7)
         $barcode[] = $left[$key[$i-1]][substr($number, $i, 1)];
      else
         $barcode[] = $right[substr($number, $i, 1)];
      if($i==6)
         $barcode[] = $guard['middle'];
   }

   $barcode[] = $guard['end'];

   $scale = 8;

   $height = $scale*60;
   $width  = 1.6*$height;

   $image = imagecreate($width, $height);

   $bg_color=ImageColorAllocate($image, 0xFF, 0xFF, 0xFF);
   $bar_color=ImageColorAllocate($image, 0x00, 0x00, 0x00);
   $text_color=ImageColorAllocate($image, 0x00, 0x00, 0x00);

   define("MAX", $height*0.05);
   define("FLOOR", $height*0.8);
   define("WIDTH", $scale*0.8);

   $x = $height*0.20;
   $y = 100;

   /**
    * For each encoded number (a binary number, e.g. 6 => "0000101")
    * draw a bar for '1', leave blank for 0;
    *
    * For the guards, draw a taller bar.
    */

   foreach($barcode as $bar)
   {
      $tall = 0;

      if(strlen($bar)==3 || strlen($bar)==5)
         $tall = ($scale*10);

      for($i=1;$i<=strlen($bar);$i++)
      {
         if(substr($bar, $i-1, 1)==='1')
            imagefilledrectangle($image, $x, MAX, $x+WIDTH, FLOOR+$tall, $bar_color);
         $x += WIDTH;
      }
   }

   /**
    * Draw the text
    *
    * For the 1st digit, needs to be on the left of the first guard.
    *
    * Next 6 digits are in the first area. Next 6 in the next;
    *
    * if $i%6 == 0, add some width
    */

   //define("TEXT_MAX", );
   //define("TEXT_FLOOR", );
   //define("TEXT_WIDTH", );

   $x = $width*0.05;
   $y = $height*0.825;

   $font=dirname(__FILE__)."/"."FreeSansBold.ttf";

   //for($i=0;$i<strlen($number);$i++)
   //{
   //   $fontsize = $scale*(12/2);
   //   imagettftext($image, $fontsize, 0, $x, $y+60, $text_color, $font, $number[$i]);
   //   if($i==0 || $i==6)
   //      $x += 50;
   //   $x += 43;
   //}

   //echo '<pre>';
   //echo $number, "\n";
   //echo strlen($number), "\n";
   //print_r($barcode);
   //echo '</pre>';

   header("Content-Type: image/png; name=\"barcode.png\"");
   imagepng($image);
   imagedestroy($image);
}

function ean_checksum($ean)
{
   $esum=0; $osum=0;
   for ($i=strlen($ean)-1;$i>=0;$i--)
   {
      if (i%2==0) 
         $esum+=$ean[$i];	
      else 
         $osum+=$ean[$i];
   }
   return (10-((3*$esum+$osum)%10))%10;
}

function random()
{
  return substr(number_format(time() * rand(),0,'',''),0,12);
}

encode(random());
