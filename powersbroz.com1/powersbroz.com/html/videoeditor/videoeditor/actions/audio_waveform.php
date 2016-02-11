<?php

/**
 * videoeditor action
 *
 * audio_waveform
 */

//based on: https://github.com/afreiday/php-waveform-png

if( !isset( $config ) ) exit;

//ini_set("display_errors",0);
ini_set("max_execution_time", "30000");

$is_win = strpos(php_uname(),'Window') !== false;
$input_path = $root_path . '/input/' . sprintf( '%06d', $config['user_id'] ) . '/';

define("DETAIL", 5);
define("DEFAULT_WIDTH", 820);
define("DEFAULT_HEIGHT", 300);
define("DEFAULT_FOREGROUND", "#B2E8FF");
define("DEFAULT_BACKGROUND", "#124154");

/**
 * GENERAL FUNCTIONS
 */
function findValues($byte1, $byte2){
    $byte1 = hexdec(bin2hex($byte1));
    $byte2 = hexdec(bin2hex($byte2));
    return ($byte1 + ($byte2*256));
}

/**
 * Great function slightly modified as posted by Minux at
 * http://forums.clantemplates.com/showthread.php?t=133805
 */
function html2rgb($input) {
    $input=($input[0]=="#")?substr($input, 1,6):substr($input, 0,6);
    return array(
        hexdec(substr($input, 0, 2)),
        hexdec(substr($input, 2, 2)),
        hexdec(substr($input, 4, 2))
    );
}

if ( empty( $_GET['audio'] ) ) { exit; }

$mp3_path = $input_path . urldecode( $_GET['audio'] );
$width = isset($_GET["width"]) ? (int) $_GET["width"] : DEFAULT_WIDTH;
$height = isset($_GET["height"]) ? (int) $_GET["height"] : DEFAULT_HEIGHT;
$foreground = isset($_GET["foreground"]) ? $_GET["foreground"] : DEFAULT_FOREGROUND;
$background = isset($_GET["background"]) ? $_GET["background"] : DEFAULT_BACKGROUND;
$draw_flat = false;

if( !file_exists($mp3_path) ){ exit; }

$wavs_to_process = array();

$tmpname = substr(md5(time()), 0, 10);
$lame_path = !$is_win ? trim(shell_exec('which lame')) : '';

if( !$is_win && $lame_path ){
    $command = $lame_path . " \"{$mp3_path}\" -m m -S -f -b 16 --resample 8 \"{$input_path}{$tmpname}.mp3\" && lame -S --decode \"{$input_path}{$tmpname}.mp3\" \"{$input_path}{$tmpname}.wav\"";
    exec( $command );
    unlink("{$input_path}{$tmpname}.mp3");
}else{
    $command = $config['ffmpeg_path'] . " -i \"{$mp3_path}\" -acodec pcm_u8 \"{$input_path}{$tmpname}.wav\"";
    exec( $command );
}
$wavs_to_process[] = "{$input_path}{$tmpname}.wav";
@chmod( "{$input_path}{$tmpname}.wav", 0777 );

// generate foreground color
list($r, $g, $b) = html2rgb( DEFAULT_FOREGROUND );

// process each wav individually
for($wav = 1; $wav <= sizeof($wavs_to_process); $wav++) {
    
    $filename = $wavs_to_process[$wav - 1];
    
    /**
     * Below as posted by "zvoneM" on
     * http://forums.devshed.com/php-development-5/reading-16-bit-wav-file-318740.html
     * as findValues() defined above
     * Translated from Croation to English - July 11, 2011
     */
    $handle = fopen($filename, "r");
    // wav file header retrieval
    $heading[] = fread($handle, 4);
    $heading[] = bin2hex(fread($handle, 4));
    $heading[] = fread($handle, 4);
    $heading[] = fread($handle, 4);
    $heading[] = bin2hex(fread($handle, 4));
    $heading[] = bin2hex(fread($handle, 2));
    $heading[] = bin2hex(fread($handle, 2));
    $heading[] = bin2hex(fread($handle, 4));
    $heading[] = bin2hex(fread($handle, 4));
    $heading[] = bin2hex(fread($handle, 2));
    $heading[] = bin2hex(fread($handle, 2));
    $heading[] = fread($handle, 4);
    $heading[] = bin2hex(fread($handle, 4));
    
    // wav bitrate 
    $peek = hexdec(substr($heading[10], 0, 2));
    $byte = $peek / 8;
    
    // checking whether a mono or stereo wav
    $channel = hexdec(substr($heading[6], 0, 2));
    
    $ratio = ($channel == 2 ? 40 : 80);
    
    // start putting together the initial canvas
    // $data_size = (size_of_file - header_bytes_read) / skipped_bytes + 1
    $data_size = floor((filesize($filename) - 44) / ($ratio + $byte) + 1);
    $data_point = 0;
    
    // now that we have the data_size for a single channel (they both will be the same)
    // we can initialize our image canvas
    if ( empty( $img ) ) {
        // create original image width based on amount of detail
        // each waveform to be processed with be $height high, but will be condensed
        // and resized later (if specified)
        $img = imagecreatetruecolor($data_size / DETAIL, $height * sizeof($wavs_to_process));
        
        // fill background of image
        if ($background == "") {
            // transparent background specified
            imagesavealpha($img, true);
            $transparentColor = imagecolorallocatealpha($img, 0, 0, 0, 127);
            imagefill($img, 0, 0, $transparentColor);
        } else {
            list($br, $bg, $bb) = html2rgb($background);
            imagefilledrectangle($img, 0, 0, (int) ($data_size / DETAIL), $height * sizeof($wavs_to_process), imagecolorallocate($img, $br, $bg, $bb));
        }
    }

    while(!feof($handle) && $data_point < $data_size){
        if ($data_point++ % DETAIL == 0) {
            $bytes = array();
            if( empty($bytes[1]) ) $bytes[1] = '';
            
            // get number of bytes depending on bitrate
            for ($i = 0; $i < $byte; $i++)
                $bytes[$i] = fgetc($handle);
            //var_dump($bytes); exit;
            switch($byte){
                // get value for 8-bit wav
                case 1:
                    $data = findValues($bytes[0], $bytes[1]);
                break;
                // get value for 16-bit wav
                case 2:
                    if(ord($bytes[1]) & 128)
                        $temp = 0;
                    else
                        $temp = 128;
                    $temp = chr((ord($bytes[1]) & 127) + $temp);
                    $data = floor(findValues($bytes[0], $temp) / 256);
                break;
            }
            
            // skip bytes for memory optimization
            fseek($handle, $ratio, SEEK_CUR);
            
            // draw this data point
            // relative value based on height of image being generated
            // data values can range between 0 and 255
            $v = (int) ($data / 255 * $height);
            
            // don't print flat values on the canvas if not necessary
            if (!($v / $height == 0.5 && !$draw_flat))
            // draw the line on the image using the $v value and centering it vertically on the canvas
            imageline(
                $img,
                // x1
                (int) ($data_point / DETAIL),
                // y1: height of the image minus $v as a percentage of the height for the wave amplitude
                $height * $wav - $v,
                // x2
                (int) ($data_point / DETAIL),
                // y2: same as y1, but from the bottom of the image
                $height * $wav - ($height - $v),
                imagecolorallocate($img, $r, $g, $b)
            );         
            
        } else {
            // skip this one due to lack of detail
            fseek($handle, $ratio + $byte, SEEK_CUR);
        }
    }
    
    // close and cleanup
    fclose($handle);
    
    // delete the processed wav file
    unlink($filename);
    
}

header("Content-Type: image/png");

// resample the image to the proportions defined in the form
$rimg = imagecreatetruecolor($width, $height);
// save alpha from original image
imagesavealpha($rimg, true);
imagealphablending($rimg, false);
// copy to resized
imagecopyresampled($rimg, $img, 0, 0, 0, 0, $width, $height, imagesx($img), imagesy($img));
imagepng($rimg);
imagedestroy($rimg);

imagedestroy($img);
exit;
