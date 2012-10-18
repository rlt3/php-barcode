<?php
class Barcode
{
   public $encoding;
   public $scale;

   function __construct($code=null, $type='EAN-13', $scale=6)
   {
      if($code==null) $code=$this->random();
      $this->encoding = $this->encode($code, $type);
      $this->scale = $scale;
   }

   function __destruct()
   {
      $this->barcode_print();
   }

   function random()
   {
     return substr(number_format(time() * rand(),0,'',''),0,12);
   }

   protected function checkBadCode($code)
   {
      if (preg_match("#[^0-9]#i",$ean))
         return array("text"=>"Invalid EAN-Code");

      if (strlen($ean)<12 || strlen($ean)>13)
         return array("text"=>"Invalid $encoding Code (must have 12/13 numbers)");
   }

   protected function encode($code,$type)
   {
      if($type === 'EAN-13')
         return $this->encode_ean($code);
      else if($type === 'ISBN')
         return $this->encode_isbn($code);
   }

   function encode_ean($code)
   {
      $digits=array(3211,2221,2122,1411,1132,1231,1114,1312,1213,3112);
      $mirror=array("000000","001011","001101","001110","010011","011001","011100","010101","010110","011010");
      $guard=array('start' => "9a1a",
                    'middle' => "1a1a1",
                    'end' => "a1a");

      // EAN checksum overwrites & occupies the last digit of the code
      $eansum=$this->barcode_gen_ean_sum($code);
      $ean  = $code;
      $ean .= $eansum;

      $line = $guard['start'];
      for ($i=1;$i<13;$i++)
      {
         $str=$digits[$ean[$i]];
         if ($i<7 && $mirror[$ean[0]][$i-1]==1)
            $line .= strrev($str);
         else 
            $line .= $str;
         if ($i==6) 
            $line .= $guard['middle'];
      }
      $line .= $guard['end'];

      /* create text */
      $pos=0;
      for ($i=0;$i<13;$i++)
      {
         $text[] = "$pos:12:{$ean[$i]}";

         if($i==0 || $i==6)
            $pos+=12;
         else
            $pos+=7;
      }

      return array(
         "encoding" => 'EAN-13',
         "bars" => $line,
         "text" => $text
      );
   }

   public function barcode_gen_ean_sum($ean)
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

   function barcode_print($mode = "png")
   {
      $scale = $this->scale;
      $text = $this->encoding['text'];
      $bars = $this->encoding['bars'];

      $bar_color=Array(0,0,0);
      $bg_color=Array(255,255,255);
      $text_color=Array(0,0,0);
      $font_loc=dirname(__FILE__)."/"."FreeSansBold.ttf";
      
      /* set defaults */
      $total_y = $scale * 60;
      $margin = 2*$scale;

      /* count total width */
      $xpos=0;
      $width=true;
      for ($i=0;$i<strlen($bars);$i++)
      {
         $val=strtolower($bars[$i]);
         if ($width)
         {
            $xpos+=$val*$scale;
            $width=false;
            continue;
         }
         if (preg_match("#[a-z]#", $val))
         {
            /* tall bar */
            $val=ord($val)-ord('a')+1;
         } 
         $xpos+=$val*$scale;
         $width=true;
      }

      /* allocate the image */

      $total_x = ($xpos)+$margin+$margin;
      $xpos=$margin;
      $barcode=imagecreate($total_x, $total_y);

      $bg_color=ImageColorAllocate($barcode, 0xFF, 0xFF, 0xFF);
      $bar_color=ImageColorAllocate($barcode, 0x00, 0x00, 0x00);
      $text_color=ImageColorAllocate($barcode, 0x00, 0x00, 0x00);

      $height=round($total_y-($scale*10));
      $height2=round($total_y-$margin);


      /* paint the bars */
      for ($i=0;$i<strlen($bars);$i++)
      {
         $val=strtolower($bars[$i]);
         if ($i%2==0)
         {
             $xpos+=$val*$scale;
             continue;
         }
         if (preg_match("#[a-z]#", $val))
         {
             /* tall bar */
             $val=ord($val)-ord('a')+1;
             $h=$height2;
         } 
         else $h=$height;

         imagefilledrectangle($barcode, $xpos, $margin, $xpos+($val*$scale)-1, $h, $bar_color);
         $xpos+=$val*$scale;
      }

      /* write out the text */
      foreach($this->encoding['text'] as $number)
      {
         $inf=explode(":", $number);
         $fontsize=$scale*(12/1.8);
         $fontheight=$total_y-($fontsize/2.7)+2;
         @imagettftext($barcode, $fontsize, 0, ($margin+5)+($scale*$inf[0])+2,
         $fontheight, $text_color, $font_loc, $inf[2]);
      }

      /* output the image */
      //echo '<pre>';
      //print_r($this->encoding);
      //echo '</pre>';
      header("Content-Type: image/png; name=\"barcode.png\"");
      imagepng($barcode);
   }
}

new Barcode(); 
