<?php 
/** 
  * Copyright: dtbaker 2012
  * Licence: Please check CodeCanyon.net for licence details. 
  * More licence clarification available here:  http://codecanyon.net/wiki/support/legal-terms/licensing-terms/ 
  * Deploy: 10474 31adef9c9cf17cbd18100c8b1824e959
  * Envato: 893ecafa-6fb9-4299-930f-7526a262c4e8
  * Package Date: 2016-01-13 13:46:18 
  * IP Address: 76.104.145.50
  */

/////////////////
$unicode_ranges = array(
array('starthex'=> '0000', 'endhex'=>'007E', 'startdec'=> 0, 'enddec'=>126 , 'range'=>'Basic Latin'),
array('starthex'=> '00A0', 'endhex'=>'00FF', 'startdec'=> 160, 'enddec'=>255 , 'range'=>'Latin-1 Supplement'),
array('starthex'=> '0100', 'endhex'=>'017F', 'startdec'=> 256, 'enddec'=>383 , 'range'=>'Latin Extended-A'),
array('starthex'=> '0180', 'endhex'=>'024F', 'startdec'=> 384, 'enddec'=>591 , 'range'=>'Latin Extended-B'),
array('starthex'=> '0250', 'endhex'=>'02AF', 'startdec'=> 592, 'enddec'=>687 , 'range'=>'IPA Extensions'),
array('starthex'=> '02B0', 'endhex'=>'02FF', 'startdec'=> 688, 'enddec'=>767 , 'range'=>'Spacing Modifier Letters'),
array('starthex'=> '0300', 'endhex'=>'036F', 'startdec'=> 768, 'enddec'=>879 , 'range'=>'Combining Diacritical Marks', 'combining'=>true),
array('starthex'=> '0370', 'endhex'=>'03FF', 'startdec'=> 880, 'enddec'=>1023 , 'range'=>'Greek'),
array('starthex'=> '0400', 'endhex'=>'04FF', 'startdec'=> 1024, 'enddec'=>1279 , 'range'=>'Cyrillic'),
array('starthex'=> '0500', 'endhex'=>'052F', 'startdec'=> 1280, 'enddec'=>1327 , 'range'=>'Cyrillic Supplement'),
array('starthex'=> '0530', 'endhex'=>'058F', 'startdec'=> 1328, 'enddec'=>1423 , 'range'=>'Armenian'),
array('starthex'=> '0590', 'endhex'=>'05FF', 'startdec'=> 1424, 'enddec'=>1535 , 'range'=>'Hebrew', 'rtl'=>true, 'special'=>true),
array('starthex'=> '0600', 'endhex'=>'06FF', 'startdec'=> 1536, 'enddec'=>1791 , 'range'=>'Arabic', 'rtl'=>true, 'special'=>true),
array('starthex'=> '0700', 'endhex'=>'074F', 'startdec'=> 1792, 'enddec'=>1871 , 'range'=>'Syriac', 'rtl'=>true, 'special'=>true),
array('starthex'=> '0750', 'endhex'=>'077F', 'startdec'=> 1872, 'enddec'=>1919 , 'range'=>'Arabic Supplement', 'rtl'=>true, 'special'=>true),
array('starthex'=> '0780', 'endhex'=>'07BF', 'startdec'=> 1920, 'enddec'=>1983 , 'range'=>'Thaana', 'rtl'=>true, 'special'=>true),
array('starthex'=> '07C0', 'endhex'=>'07FF', 'startdec'=> 1984, 'enddec'=>2047 , 'range'=>'N\'Ko (Mandenkan)', 'rtl'=>true),
array('starthex'=> '0800', 'endhex'=>'083E', 'startdec'=> 2048, 'enddec'=> 2110, 'range'=>'Samaritan', 'rtl'=>true),
array('starthex'=> '0900', 'endhex'=>'097F', 'startdec'=> 2304, 'enddec'=>2431 , 'range'=>'Devanagari', 'indic'=>true, 'special'=>true),
array('starthex'=> '0980', 'endhex'=>'09FF', 'startdec'=> 2432, 'enddec'=>2559 , 'range'=>'Bengali', 'indic'=>true, 'special'=>true),
array('starthex'=> '0A00', 'endhex'=>'0A7F', 'startdec'=> 2560, 'enddec'=>2687 , 'range'=>'Gurmukhi', 'indic'=>true, 'special'=>true),
array('starthex'=> '0A80', 'endhex'=>'0AFF', 'startdec'=> 2688, 'enddec'=>2815 , 'range'=>'Gujarati', 'indic'=>true, 'special'=>true),
array('starthex'=> '0B00', 'endhex'=>'0B7F', 'startdec'=> 2816, 'enddec'=>2943 , 'range'=>'Oriya', 'indic'=>true, 'special'=>true),
array('starthex'=> '0B80', 'endhex'=>'0BFF', 'startdec'=> 2944, 'enddec'=>3071 , 'range'=>'Tamil', 'indic'=>true, 'special'=>true),
array('starthex'=> '0C00', 'endhex'=>'0C7F', 'startdec'=> 3072, 'enddec'=>3199 , 'range'=>'Telugu', 'indic'=>true, 'special'=>true),
array('starthex'=> '0C80', 'endhex'=>'0CFF', 'startdec'=> 3200, 'enddec'=>3327 , 'range'=>'Kannada', 'indic'=>true, 'special'=>true),
array('starthex'=> '0D00', 'endhex'=>'0D7F', 'startdec'=> 3328, 'enddec'=>3455 , 'range'=>'Malayalam', 'indic'=>true, 'special'=>true),
array('starthex'=> '0D80', 'endhex'=>'0DFF', 'startdec'=> 3456, 'enddec'=>3583 , 'range'=>'Sinhala', 'special'=>true),
array('starthex'=> '0E00', 'endhex'=>'0E7F', 'startdec'=> 3584, 'enddec'=>3711 , 'range'=>'Thai'),
array('starthex'=> '0E80', 'endhex'=>'0EFF', 'startdec'=> 3712, 'enddec'=>3839 , 'range'=>'Lao'),
array('starthex'=> '0F00', 'endhex'=>'0FFF', 'startdec'=> 3840, 'enddec'=>4095 , 'range'=>'Tibetan', 'special'=>true),
array('starthex'=> '1000', 'endhex'=>'109F', 'startdec'=> 4096, 'enddec'=>4255 , 'range'=>'Myanmar', 'special'=>true),
array('starthex'=> '10A0', 'endhex'=>'10FF', 'startdec'=> 4256, 'enddec'=>4351 , 'range'=>'Georgian'),
array('starthex'=> '1100', 'endhex'=>'11FF', 'startdec'=> 4352, 'enddec'=>4607 , 'range'=>'Hangul Jamo', 'cjk'=>true),
array('starthex'=> '1200', 'endhex'=>'137F', 'startdec'=> 4608, 'enddec'=>4991 , 'range'=>'Ethiopic'),
array('starthex'=> '1380', 'endhex'=>'139F', 'startdec'=> 4992, 'enddec'=>5023 , 'range'=>'Ethiopic Supplement'),
array('starthex'=> '13A0', 'endhex'=>'13FF', 'startdec'=> 5024, 'enddec'=>5119 , 'range'=>'Cherokee'),
array('starthex'=> '1400', 'endhex'=>'167F', 'startdec'=> 5120, 'enddec'=>5759 , 'range'=>'Unified Canadian Aboriginal Syllabics'),
array('starthex'=> '1680', 'endhex'=>'169F', 'startdec'=> 5760, 'enddec'=>5791 , 'range'=>'Ogham'),
array('starthex'=> '16A0', 'endhex'=>'16FF', 'startdec'=> 5792, 'enddec'=>5887 , 'range'=>'Runic'),
array('starthex'=> '1700', 'endhex'=>'171F', 'startdec'=> 5888, 'enddec'=> 5919, 'range'=>'Tagalog (Philippine)'),
array('starthex'=> '1720', 'endhex'=>'173F', 'startdec'=> 5920, 'enddec'=> 5951, 'range'=>'Hanunoo (Philippine)'),
array('starthex'=> '1740', 'endhex'=>'175F', 'startdec'=> 5952, 'enddec'=> 5983, 'range'=>'Buhid (Philippine)'),
array('starthex'=> '1760', 'endhex'=>'177F', 'startdec'=> 5984, 'enddec'=> 6015, 'range'=>'Tagbanwa (Philippine)'),
array('starthex'=> '1780', 'endhex'=>'17FF', 'startdec'=> 6016, 'enddec'=>6143 , 'range'=>'Khmer', 'special'=>true),
array('starthex'=> '1800', 'endhex'=>'18AF', 'startdec'=> 6144, 'enddec'=>6319 , 'range'=>'Mongolian', 'vertical'=>true),
array('starthex'=> '18B0', 'endhex'=>'18F5', 'startdec'=> 6320, 'enddec'=>6389 , 'range'=>'Canadian Syllabics'),
array('starthex'=> '1900', 'endhex'=>'194F', 'startdec'=> 6400, 'enddec'=> 6479, 'range'=>'Limbu'),
array('starthex'=> '1950', 'endhex'=>'197F', 'startdec'=> 6480, 'enddec'=> 6527, 'range'=>'Tai Le'),
array('starthex'=> '1980', 'endhex'=>'19DF', 'startdec'=> 6528, 'enddec'=> 6623, 'range'=>'New Tai Lue'),
array('starthex'=> '19E0', 'endhex'=>'19FF', 'startdec'=> 6624, 'enddec'=> 6655, 'range'=>'Khmer Symbols', 'special'=>true),
array('starthex'=> '1A00', 'endhex'=>'1A1F', 'startdec'=> 6656, 'enddec'=> 6687, 'range'=>'Buginese'),
array('starthex'=> '1A20', 'endhex'=>'1AAF', 'startdec'=> 6688, 'enddec'=> 6831, 'range'=>'Tai Tham'),
array('starthex'=> '1B00', 'endhex'=>'1B7F', 'startdec'=> 6912, 'enddec'=> 7039, 'range'=>'Balinese', 'special'=>true),
array('starthex'=> '1B80', 'endhex'=>'1BBF', 'startdec'=> 7040, 'enddec'=> 7103, 'range'=>'Sundanese'),
array('starthex'=> '1C00', 'endhex'=>'1C4F', 'startdec'=> 7168, 'enddec'=> 7247, 'range'=>'Lepcha (Rong)'),
array('starthex'=> '1C50', 'endhex'=>'1C7F', 'startdec'=> 7248, 'enddec'=> 7295, 'range'=>'Ol Chiki (Santali / Ol Cemet)'),
array('starthex'=> '1CD0', 'endhex'=>'1CFF', 'startdec'=> 7376, 'enddec'=> 7423, 'range'=>'Vedic Extensions'),
array('starthex'=> '1D00', 'endhex'=>'1D7F', 'startdec'=> 7424, 'enddec'=> 7551, 'range'=>'Phonetic Extensions'),
array('starthex'=> '1D80', 'endhex'=>'1DBF', 'startdec'=> 7552, 'enddec'=> 7615, 'range'=>'Phonetic Extensions Supplement'),
array('starthex'=> '1DC0', 'endhex'=>'1DFF', 'startdec'=> 7616, 'enddec'=> 7679, 'range'=>'Combining Diacritical Marks Supplement', 'combining'=>true),
array('starthex'=> '1E00', 'endhex'=>'1EFF', 'startdec'=> 7680, 'enddec'=>7935 , 'range'=>'Latin Extended Additional'),
array('starthex'=> '1F00', 'endhex'=>'1FFF', 'startdec'=> 7936, 'enddec'=>8191 , 'range'=>'Greek Extended'),
array('starthex'=> '2000', 'endhex'=>'206F', 'startdec'=> 8192, 'enddec'=>8303 , 'range'=>'General Punctuation'),
array('starthex'=> '2070', 'endhex'=>'209F', 'startdec'=> 8304, 'enddec'=>8351 , 'range'=>'Superscripts and Subscripts'),
array('starthex'=> '20A0', 'endhex'=>'20CF', 'startdec'=> 8352, 'enddec'=>8399 , 'range'=>'Currency Symbols'),
array('starthex'=> '20D0', 'endhex'=>'20FF', 'startdec'=> 8400, 'enddec'=>8447 , 'range'=>'Combining Marks for Symbols', 'combining'=>true),
array('starthex'=> '2100', 'endhex'=>'214F', 'startdec'=> 8448, 'enddec'=>8527 , 'range'=>'Letterlike Symbols'),
array('starthex'=> '2150', 'endhex'=>'218F', 'startdec'=> 8528, 'enddec'=>8591 , 'range'=>'Number Forms'),
array('starthex'=> '2190', 'endhex'=>'21FF', 'startdec'=> 8592, 'enddec'=>8703 , 'range'=>'Arrows'),
array('starthex'=> '2200', 'endhex'=>'22FF', 'startdec'=> 8704, 'enddec'=>8959 , 'range'=>'Mathematical Operators'),
array('starthex'=> '2300', 'endhex'=>'23FF', 'startdec'=> 8960, 'enddec'=>9215 , 'range'=>'Miscellaneous Technical'),
array('starthex'=> '2400', 'endhex'=>'243F', 'startdec'=> 9216, 'enddec'=>9279 , 'range'=>'Control Pictures'),
array('starthex'=> '2440', 'endhex'=>'245F', 'startdec'=> 9280, 'enddec'=>9311 , 'range'=>'Optical Character Recognition'),
array('starthex'=> '2460', 'endhex'=>'24FF', 'startdec'=> 9312, 'enddec'=>9471 , 'range'=>'Enclosed Alphanumerics'),
array('starthex'=> '2500', 'endhex'=>'257F', 'startdec'=> 9472, 'enddec'=>9599 , 'range'=>'Box Drawing'),
array('starthex'=> '2580', 'endhex'=>'259F', 'startdec'=> 9600, 'enddec'=>9631 , 'range'=>'Block Elements'),
array('starthex'=> '25A0', 'endhex'=>'25FF', 'startdec'=> 9632, 'enddec'=>9727 , 'range'=>'Geometric Shapes'),
array('starthex'=> '2600', 'endhex'=>'26FF', 'startdec'=> 9728, 'enddec'=>9983 , 'range'=>'Miscellaneous Symbols'),
array('starthex'=> '2700', 'endhex'=>'27BF', 'startdec'=> 9984, 'enddec'=>10175 , 'range'=>'Dingbats'),
array('starthex'=> '27C0', 'endhex'=>'27EF', 'startdec'=> 10176, 'enddec'=> 10223, 'range'=>'Miscellaneous Mathematical Symbols-A'),
array('starthex'=> '27F0', 'endhex'=>'27FF', 'startdec'=> 10224, 'enddec'=> 10239, 'range'=>'Supplemental Arrows-A'),
array('starthex'=> '2800', 'endhex'=>'28FF', 'startdec'=> 10240, 'enddec'=>10495 , 'range'=>'Braille Patterns'),
array('starthex'=> '2900', 'endhex'=>'297F', 'startdec'=> 10496, 'enddec'=> 10623, 'range'=>'Supplemental Arrows-B'),
array('starthex'=> '2980', 'endhex'=>'29FF', 'startdec'=> 10624, 'enddec'=> 10751, 'range'=>'Miscellaneous Mathematical Symbols-B'),
array('starthex'=> '2A00', 'endhex'=>'2AFF', 'startdec'=> 10752, 'enddec'=> 11007, 'range'=>'Supplemental Mathematical Operators'),
array('starthex'=> '2B00', 'endhex'=>'2BFF', 'startdec'=> 11008, 'enddec'=> 11263, 'range'=>'Miscellaneous Symbols and Arrows'),
array('starthex'=> '2C00', 'endhex'=>'2C5F', 'startdec'=> 11264, 'enddec'=> 11359, 'range'=>'Glagolitic'),
array('starthex'=> '2C60', 'endhex'=>'2C7F', 'startdec'=> 11360, 'enddec'=> 11391, 'range'=>'Latin Extended-C'),
array('starthex'=> '2C80', 'endhex'=>'2CFF', 'startdec'=> 11392, 'enddec'=> 11519, 'range'=>'Coptic'),
array('starthex'=> '2D00', 'endhex'=>'2D2F', 'startdec'=> 11520, 'enddec'=> 11567, 'range'=>'Georgian Supplement'),
array('starthex'=> '2D30', 'endhex'=>'2D7F', 'startdec'=> 11568, 'enddec'=> 11647, 'range'=>'Tifinagh'),
array('starthex'=> '2D80', 'endhex'=>'2DDF', 'startdec'=> 11648, 'enddec'=> 11743, 'range'=>'Ethiopic Extended'),
array('starthex'=> '2DE0', 'endhex'=>'2DFF', 'startdec'=> 11744, 'enddec'=> 11775, 'range'=>'Cyrillic Extended-A'),
array('starthex'=> '2E00', 'endhex'=>'2E7F', 'startdec'=> 11776, 'enddec'=> 11903, 'range'=>'Supplemental Punctuation'),

array('starthex'=> '2E80', 'endhex'=>'2EFF', 'startdec'=> 11904, 'enddec'=>12031 , 'range'=>'CJK Radicals Supplement', 'cjk'=>true),
array('starthex'=> '2F00', 'endhex'=>'2FDF', 'startdec'=> 12032, 'enddec'=>12255 , 'range'=>'Kangxi Radicals', 'cjk'=>true),
array('starthex'=> '2FF0', 'endhex'=>'2FFF', 'startdec'=> 12272, 'enddec'=>12287 , 'range'=>'Ideographic Description Characters', 'cjk'=>true),
array('starthex'=> '3000', 'endhex'=>'303F', 'startdec'=> 12288, 'enddec'=>12351 , 'range'=>'CJK Symbols and Punctuation', 'cjk'=>true),
array('starthex'=> '3040', 'endhex'=>'309F', 'startdec'=> 12352, 'enddec'=>12447 , 'range'=>'Hiragana', 'cjk'=>true),
array('starthex'=> '30A0', 'endhex'=>'30FF', 'startdec'=> 12448, 'enddec'=>12543 , 'range'=>'Katakana', 'cjk'=>true),
array('starthex'=> '3100', 'endhex'=>'312F', 'startdec'=> 12544, 'enddec'=>12591 , 'range'=>'Bopomofo', 'cjk'=>true),
array('starthex'=> '3130', 'endhex'=>'318F', 'startdec'=> 12592, 'enddec'=>12687 , 'range'=>'Hangul Compatibility Jamo', 'cjk'=>true),
array('starthex'=> '3190', 'endhex'=>'319F', 'startdec'=> 12688, 'enddec'=>12703 , 'range'=>'Kanbun', 'cjk'=>true),
array('starthex'=> '31A0', 'endhex'=>'31BF', 'startdec'=> 12704, 'enddec'=>12735 , 'range'=>'Bopomofo Extended', 'cjk'=>true),
array('starthex'=> '31C0', 'endhex'=>'31EF', 'startdec'=> 12736, 'enddec'=> 12783, 'range'=>'CJK Strokes', 'cjk'=>true),
array('starthex'=> '31F0', 'endhex'=>'31FF', 'startdec'=> 12784, 'enddec'=> 12799, 'range'=>'Katakana Phonetic Extensions', 'cjk'=>true),
array('starthex'=> '3200', 'endhex'=>'32FF', 'startdec'=> 12800, 'enddec'=>13055 , 'range'=>'Enclosed CJK Letters and Months', 'cjk'=>true),
array('starthex'=> '3300', 'endhex'=>'33FF', 'startdec'=> 13056, 'enddec'=>13311 , 'range'=>'CJK Compatibility', 'cjk'=>true),
array('starthex'=> '3400', 'endhex'=>'4DB5', 'startdec'=> 13312, 'enddec'=>19893 , 'range'=>'CJK Unified Ideographs Extension A', 'cjk'=>true),
array('starthex'=> '4DC0', 'endhex'=>'4DFF', 'startdec'=> 19904, 'enddec'=> 19967, 'range'=>'Yijing Hexagram Symbols', 'cjk'=>true),
array('starthex'=> '4E00', 'endhex'=>'9FFF', 'startdec'=> 19968, 'enddec'=>40959 , 'range'=>'CJK Unified Ideographs', 'cjk'=>true),
array('starthex'=> 'A000', 'endhex'=>'A48F', 'startdec'=> 40960, 'enddec'=>42127 , 'range'=>'Yi Syllables', 'cjk'=>true),
array('starthex'=> 'A490', 'endhex'=>'A4CF', 'startdec'=> 42128, 'enddec'=> 42191, 'range'=>'Yi Radicals', 'cjk'=>true),

array('starthex'=> 'A4D0', 'endhex'=>'A4FF', 'startdec'=> 42192, 'enddec'=> 42239, 'range'=>'Lisu'),

array('starthex'=> 'A500', 'endhex'=>'A63F', 'startdec'=> 42240, 'enddec'=> 42559, 'range'=>'Vai'),
array('starthex'=> 'A640', 'endhex'=>'A69F', 'startdec'=> 42560, 'enddec'=> 42655, 'range'=>'Cyrillic Extended-B'),

array('starthex'=> 'A6A0', 'endhex'=>'A6FF', 'startdec'=> 42656, 'enddec'=> 42751, 'range'=>'Bamum'),

array('starthex'=> 'A700', 'endhex'=>'A71F', 'startdec'=> 42752, 'enddec'=> 42783, 'range'=>'Modifier Tone Letters'),
array('starthex'=> 'A720', 'endhex'=>'A7FF', 'startdec'=> 42784, 'enddec'=> 43007, 'range'=>'Latin Extended-D'),
array('starthex'=> 'A800', 'endhex'=>'A82F', 'startdec'=> 43008, 'enddec'=> 43055, 'range'=>'Syloti Nagri'),

array('starthex'=> 'A840', 'endhex'=>'A87F', 'startdec'=> 43072, 'enddec'=> 43135, 'range'=>'Phags-pa', 'vertical'=>true),
array('starthex'=> 'A880', 'endhex'=>'A8DF', 'startdec'=> 43136, 'enddec'=> 43231, 'range'=>'Saurashtra'),

array('starthex'=> 'A900', 'endhex'=>'A92F', 'startdec'=> 43264, 'enddec'=> 43311, 'range'=>'Kayah Li'),
array('starthex'=> 'A930', 'endhex'=>'A95F', 'startdec'=> 43312, 'enddec'=> 43359, 'range'=>'Rejang'),

array('starthex'=> 'A960', 'endhex'=>'A97F', 'startdec'=> 43360, 'enddec'=> 43391, 'range'=>'Hangul Choseong', 'cjk'=>true),
array('starthex'=> 'A980', 'endhex'=>'A9DF', 'startdec'=> 43392, 'enddec'=> 43487, 'range'=>'Javanese'),

array('starthex'=> 'AA00', 'endhex'=>'AA5F', 'startdec'=> 43520, 'enddec'=> 43615, 'range'=>'Cham'),
array('starthex'=> 'AA60', 'endhex'=>'AA7B', 'startdec'=> 43616, 'enddec'=> 43647, 'range'=>'Myanmar', 'special'=>true),
array('starthex'=> 'AA80', 'endhex'=>'AADF', 'startdec'=> 43648, 'enddec'=> 43743, 'range'=>'Tai Viet'),

array('starthex'=> 'ABC0', 'endhex'=>'ABF9', 'startdec'=> 43968, 'enddec'=> 44025, 'range'=>'Meetei Mayek'),

array('starthex'=> 'AC00', 'endhex'=>'D7FF', 'startdec'=> 44032, 'enddec'=>55295 , 'range'=>'Hangul Syllables', 'cjk'=>true),

/*
array('starthex'=> 'D800', 'endhex'=>'DB7F', 'startdec'=> 55296, 'enddec'=>56191 , 'range'=>'High Surrogates', 'reserved'=>true),
array('starthex'=> 'DB80', 'endhex'=>'DBFF', 'startdec'=> 56192, 'enddec'=>56319 , 'range'=>'High Private Use Surrogates', 'reserved'=>true),
array('starthex'=> 'DC00', 'endhex'=>'DFFF', 'startdec'=> 56320, 'enddec'=>57343 , 'range'=>'Low Surrogates', 'reserved'=>true),
*/

array('starthex'=> 'E000', 'endhex'=>'F8FF', 'startdec'=> 57344, 'enddec'=>63743 , 'range'=>'Private Use', 'pua'=>true),

array('starthex'=> 'F900', 'endhex'=>'FAFF', 'startdec'=> 63744, 'enddec'=>64255 , 'range'=>'CJK Compatibility Ideographs', 'cjk'=>true),
array('starthex'=> 'FB00', 'endhex'=>'FB4F', 'startdec'=> 64256, 'enddec'=>64335 , 'range'=>'Alphabetic Presentation Forms'),
array('starthex'=> 'FB50', 'endhex'=>'FDFF', 'startdec'=> 64336, 'enddec'=>65023 , 'range'=>'Arabic Presentation Forms-A', 'rtl'=>true),

array('starthex'=> 'FE00', 'endhex'=>'FE0F', 'startdec'=> 65024, 'enddec'=> 65039, 'range'=>'Variation Selectors'),

array('starthex'=> 'FE10', 'endhex'=>'FE1F', 'startdec'=> 65040, 'enddec'=> 65055, 'range'=>'Vertical Forms'),
array('starthex'=> 'FE20', 'endhex'=>'FE2F', 'startdec'=> 65056, 'enddec'=>65071 , 'range'=>'Combining Half Marks', 'combining'=>true),
array('starthex'=> 'FE30', 'endhex'=>'FE4F', 'startdec'=> 65072, 'enddec'=>65103 , 'range'=>'CJK Compatibility Forms', 'cjk'=>true),
array('starthex'=> 'FE50', 'endhex'=>'FE6F', 'startdec'=> 65104, 'enddec'=>65135 , 'range'=>'Small Form Variants', 'cjk'=>true),
array('starthex'=> 'FE70', 'endhex'=>'FEFE', 'startdec'=> 65136, 'enddec'=>65278 , 'range'=>'Arabic Presentation Forms-B', 'rtl'=>true),
array('starthex'=> 'FEFF', 'endhex'=>'FEFF', 'startdec'=> 65279, 'enddec'=>65279 , 'range'=>'Specials'),
array('starthex'=> 'FF00', 'endhex'=>'FFEF', 'startdec'=> 65280, 'enddec'=>65519 , 'range'=>'Halfwidth and Fullwidth Forms', 'cjk'=>true),
array('starthex'=> 'FFF0', 'endhex'=>'FFFD', 'startdec'=> 65520, 'enddec'=>65533 , 'range'=>'Specials'),

/* PLANE 1 */

array('starthex'=> '10000', 'endhex'=>'1007F', 'startdec'=> 65536 , 'enddec'=> 65663, 'range'=>'Linear B Syllabary'),
array('starthex'=> '10080', 'endhex'=>'100FF', 'startdec'=> 65664 , 'enddec'=> 65791, 'range'=>'Linear B Ideograms'),
array('starthex'=> '10100', 'endhex'=>'1013F', 'startdec'=> 65792 , 'enddec'=> 65855, 'range'=>'Aegean Numbers'),
array('starthex'=> '10140', 'endhex'=>'1018F', 'startdec'=> 65856 , 'enddec'=> 65935, 'range'=>'Ancient Greek Numbers'),
array('starthex'=> '10190', 'endhex'=>'101CF', 'startdec'=> 65936 , 'enddec'=> 65999, 'range'=>'Ancient Symbols'),
array('starthex'=> '101D0', 'endhex'=>'101FF', 'startdec'=> 66000 , 'enddec'=> 66047, 'range'=>'Phaistos Disc'),
array('starthex'=> '10280', 'endhex'=>'1029F', 'startdec'=> 66176 , 'enddec'=> 66207, 'range'=>'Lycian'),
array('starthex'=> '102A0', 'endhex'=>'102DF', 'startdec'=> 66208 , 'enddec'=> 66271, 'range'=>'Carian'),
array('starthex'=> '10300', 'endhex'=>'1032F', 'startdec'=> 66304 , 'enddec'=> 66351, 'range'=>'Old Italic'),
array('starthex'=> '10330', 'endhex'=>'1034F', 'startdec'=> 66352 , 'enddec'=> 66383, 'range'=>'Gothic'),
array('starthex'=> '10380', 'endhex'=>'1039F', 'startdec'=> 66432 , 'enddec'=> 66463, 'range'=>'Ugaritic'),
array('starthex'=> '103A0', 'endhex'=>'103DF', 'startdec'=> 66464 , 'enddec'=> 66527, 'range'=>'Old Persian'),
array('starthex'=> '10400', 'endhex'=>'1044F', 'startdec'=> 66560 , 'enddec'=> 66639, 'range'=>'Deseret'),
array('starthex'=> '10450', 'endhex'=>'1047F', 'startdec'=> 66640 , 'enddec'=> 66687, 'range'=>'Shavian'),
array('starthex'=> '10480', 'endhex'=>'104AF', 'startdec'=> 66688 , 'enddec'=> 66735, 'range'=>'Osmanya'),
array('starthex'=> '10800', 'endhex'=>'1083F', 'startdec'=> 67584 , 'enddec'=> 67647, 'range'=>'Cypriot Syllabary'),
array('starthex'=> '10900', 'endhex'=>'1091F', 'startdec'=> 67840 , 'enddec'=> 67871, 'range'=>'Phoenician'),
array('starthex'=> '10920', 'endhex'=>'1093F', 'startdec'=> 67872 , 'enddec'=> 67903, 'range'=>'Lydian'),
array('starthex'=> '10A00', 'endhex'=>'10A5F', 'startdec'=> 68096 , 'enddec'=> 68191, 'range'=>'Kharoshthi'),

array('starthex'=> '11080', 'endhex'=>'110CF', 'startdec'=> 69760 , 'enddec'=> 69839, 'range'=>'Kaithi'),

array('starthex'=> '12000', 'endhex'=>'123FF', 'startdec'=> 73728 , 'enddec'=> 74751, 'range'=>'Cuneiform (Sumero-Akkadian)'),
array('starthex'=> '12400', 'endhex'=>'1247F', 'startdec'=> 74752 , 'enddec'=> 74879, 'range'=>'Cuneiform Numbers and Punctuation'),

array('starthex'=> '13000', 'endhex'=>'1342F', 'startdec'=> 77824 , 'enddec'=> 78895, 'range'=>'Egyptian Hieroglyphs'),

array('starthex'=> '1D000', 'endhex'=>'1D0FF', 'startdec'=> 118784 , 'enddec'=> 119039, 'range'=>'Byzantine Musical Symbols'),
array('starthex'=> '1D100', 'endhex'=>'1D1FF', 'startdec'=> 119040 , 'enddec'=> 119295, 'range'=>'Musical Symbols'),
array('starthex'=> '1D200', 'endhex'=>'1D24F', 'startdec'=> 119296 , 'enddec'=> 119375, 'range'=>'Ancient Greek Musical Notation'),
array('starthex'=> '1D300', 'endhex'=>'1D35F', 'startdec'=> 119552 , 'enddec'=> 119647, 'range'=>'Tai Xuan Jing Symbols'),
array('starthex'=> '1D360', 'endhex'=>'1D37F', 'startdec'=> 119648 , 'enddec'=> 119679, 'range'=>'Counting Rod Numerals'),
array('starthex'=> '1D400', 'endhex'=>'1D7FF', 'startdec'=> 119808 , 'enddec'=> 120831, 'range'=>'Mathematical Alphanumeric Symbols'),
array('starthex'=> '1F000', 'endhex'=>'1F02F', 'startdec'=> 126976 , 'enddec'=> 127023, 'range'=>'Mahjong Tiles'),
array('starthex'=> '1F030', 'endhex'=>'1F09F', 'startdec'=> 127024 , 'enddec'=> 127135, 'range'=>'Domino Tiles'),

/* PLANE 2 */

array('starthex'=> '20000', 'endhex'=>'2A6DF', 'startdec'=> 131072 , 'enddec'=> 173791, 'range'=>'CJK Unified Ideographs Extension B'),
array('starthex'=> '2A700', 'endhex'=>'2B734', 'startdec'=> 173824 , 'enddec'=> 177972, 'range'=>'CJK Unified Ideographs Extension C'),
array('starthex'=> '2F800', 'endhex'=>'2FA1F', 'startdec'=> 194560 , 'enddec'=> 195103, 'range'=>'CJK Compatibility Ideographs Supplement'),

);

?>