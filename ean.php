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

   $key = $parity[substr($number, 0, 1)];
   echo $number, '<br>';
   echo $key, '<br>';
   echo strlen($number), '<br>';

   /**
    * The following incantations use the parity key (based off the 
    * first digit of the unencoded number) to encode the first six
    * digits of the barcode. The last 6 use the same parity.
    *
    * So, if the key is 010101, the first digit (of the first six
    * digits) uses odd parity encoding. The second uses even. The
    * third uses odd, and so on.
    */

   $barcode = $guard['start'];
   for($i=1;$i<=strlen($number);$i++)
   {
      if($i<7)
         $barcode .= $left[$key[$i-1]][substr($number, $i, 1)];
      else
         $barcode .= $right[substr($number, $i, 1)];
   }
   $barcode .= $guard['end'];

   echo '<br>', $barcode, '<br>';
}

encode(1234567890123);
