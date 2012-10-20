php-barcode
================

### About

This is a re-working of [php-barcode](http://www.ashberg.de/php-barcode/ "php-barcode") by Folke Ashberg. Currently, my version only creates EAN-13 barcodes, however I plan to make the source modular to allow for additions of ISBN, Code 12, and others.

### Why

Folke did a lot of good work, but it is nigh unreadable, I think, and I wanted to fix that. Because of this, too, it is really hard to add newer barcode types.


### How do I use this?

Simply clone the repository and travel to ``/barcode.php``. You will get a random barcode. If you want a specific one, go to ``/barcode.php?code=`` and input a 12 digit number.
