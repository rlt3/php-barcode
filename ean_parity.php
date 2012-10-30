<?php

define ("PARITY_KEY", serialize (array(
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
                                )));

define ("LEFT_PARITY", serialize (array(
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
                                      )));

define ("RIGHT_PARITY", serialize (array(
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
                                      )));

define ("GUARDS", serialize (array(
                                  'start' => "101",
                                  'middle' => "01010",
                                  'end' => "101",
                                      )));

$PARITY_KEY   = unserialize(PARITY_KEY);
$LEFT_PARITY  = unserialize(LEFT_PARITY);
$RIGHT_PARITY = unserialize(RIGHT_PARITY);
$GUARD        = unserialize(GUARDS);

function ean_checksum($ean){
  $ean=(string)$ean;
  $even=true; $esum=0; $osum=0;
  for ($i=strlen($ean)-1;$i>=0;$i--){
	if ($even) $esum+=$ean[$i];	else $osum+=$ean[$i];
	$even=!$even;
  }
  return (10-((3*$esum+$osum)%10))%10;
}
