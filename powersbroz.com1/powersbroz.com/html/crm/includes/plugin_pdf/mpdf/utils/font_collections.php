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

/* This script prints out details of any TrueType collection font files
   in your font directory. Files ending wih .otc are examined.
   Point your browser to 
   http://your.domain/your_path_to _mpdf/utils/font_collections.php
   By default this will examine the folder /ttfonts/ (or the default font
   directory defined by _MPDF_TTFONTPATH.
   You can optionally define an alternative folder to examine by setting 
   the variable below (must be a relative path, or filesystem path):
*/


$checkdir = '';


//////////////////////////////////
//////////////////////////////////
//////////////////////////////////

ini_set("memory_limit","256M");


define('_MPDF_PATH','../');

include("../mpdf.php");
$mpdf=new mPDF('');
if ($checkdir) { 
	$ttfdir = $checkdir;
}
else { $ttfdir = _MPDF_TTFONTPATH; }



$mqr=ini_get("magic_quotes_runtime");
if ($mqr) { set_magic_quotes_runtime(0); }
if (!class_exists('TTFontFile_Analysis', false)) { include(_MPDF_PATH .'classes/ttfontsuni_analysis.php'); }
$ttf = new TTFontFile_Analysis();

$ff = scandir($ttfdir);

echo '<h3>Font collection files found in '.$ttfdir.' directory</h3>';
foreach($ff AS $f) {
	$ret = array();
	if (strtolower(substr($f,-4,4))=='.ttc' || strtolower(substr($f,-4,4))=='.ttcf') {	// Mac ttcf
		$ttf->getTTCFonts($ttfdir.$f);
		$nf = $ttf->numTTCFonts;
		echo '<p>Font collection file ('.$f.') contains the following fonts:</p>';
		for ($i=1; $i<=$nf; $i++) {
			$ret = $ttf->extractCoreInfo($ttfdir.$f, $i);
			$tfname = $ret[0];
			$bold = $ret[1];
			$italic = $ret[2];
			$fname = strtolower($tfname );
			$fname = preg_replace('/[ ()]/','',$fname );
			$style = '';
			if ($bold) { $style .= 'Bold'; }
			if ($italic) { $style .= 'Italic'; }
			if (!$style) { $style = 'Regular'; }


			echo '<div>['.$i.'] '.$tfname.' ('.$fname.') '.$style.'</div>';

		}
		echo '<hr />';
	}
}


exit;

?>