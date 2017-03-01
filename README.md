php-barcode
================

### About

This is a re-working of [php-barcode](http://www.ashberg.de/php-barcode/ "php-barcode") by Folke Ashberg. Currently, my version only creates EAN-13 barcodes, however I plan to make the source modular to allow for additions of ISBN, Code 12, and others.

### Why

Folke did a lot of good work, but it is nigh unreadable, I think, and I wanted to fix that. Because of this, too, it is really hard to add newer barcode types.

I also needed an on-the-fly barcode solution for a freelance job. This code has been in production for several years.

### Requirements

* PHP >= 5.4
* [PHP GD](https://secure.php.net/manual/en/book.image.php)

### How do I use this?

The signatures are fairly simple. Simply call `barcode` with the number of the
barcode and the scale of the barcode as an integer. The number needs to be
either 12 or 13 digits. The barcode will always be 13 digits even if only 12
are supplied as the 13th digit is the checksum of the first 12. The barcode's
scale will not go lower than 2 for scaling reasons and no higher than 12 for
memory reasons. A scale of 4 has worked well for my purposes in the past.

    $barcode = new Barcode(1349875921348, 4);
    $barcode = new Barcode(439457143245, 10);

The key thing to keep up with is the `FreeSansBold.ttf` file. By default the
Barcode class will look into PHP's calling directory for the font file. You can
specify a path as a third parameter.

    $barcode = new Barcode(123456789120, 4, "/path/to/FreeSansBold.ttf");

There are only two public methods: `image()` and `display()`. The first
function `image()` returns the PHP created image as a reference. This may be
used to save an image to file, e.g. `imagepng($barcode->image(),
"/path/to/storage/barcode.png")`. The second function `display()` simply calls
the correct headers and displays the barcode in the browser. Useful for
debugging.
