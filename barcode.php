<?php
include("ean.php");

$code = (isset($_GET['code'])) ? $_GET['code'] : random();

encode($code);
