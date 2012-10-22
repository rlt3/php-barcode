<?php
include("ean.php");

class Barcode
{
   public $code;
   public $type;

   function __construct($code=null, $type='EAN-13')
   {
      $this->code = (isset($code)) ? $code : self::randomCode();

      encode($this->code);
   }

   private static function randomCode()
   {
     return substr(number_format(time() * rand(),0,'',''),0,12);
   }
}

new Barcode($_GET['code']);
