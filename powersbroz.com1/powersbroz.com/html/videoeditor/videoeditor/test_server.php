<?php

/*

Test server for video editing

*/

$output = array();

$output['os'] = array( 'name' => 'OS', 'value' => php_uname() );
$output['server'] = array( 'name' => 'Web server', 'value' => $_SERVER['SERVER_SOFTWARE'] );
$output['php'] = array( 'name' => 'PHP version', 'value' => phpversion() );

$output['ffmpeg_path'] = array( 'name' => 'FFMPEG path', 'value' => trim(shell_exec('which ffmpeg')) );

if( $output['ffmpeg_path']['value'] ){
    
    $output['ffmpeg_version'] = array( 'name' => 'FFMPEG version', 'value' => '' );
    
    $ffmpeg_ver = shell_exec('ffmpeg -version');
    $output['ffmpeg_version']['value'] = substr( $ffmpeg_ver, 0, strpos( $ffmpeg_ver, "\n" ) );
    
    preg_match( "/[\d.]+/", $output['ffmpeg_version']['value'], $matches );
    
    $ver = 0;
    if( $matches ){
        $ver = floatval($matches[0]);
    }
    
    if( !empty( $ver ) && $ver < 1.2 ){
        $output['ffmpeg_version']['value'] = '<span style="color:red;">' . $output['ffmpeg_version']['value'] . ' - <b>FAIL</b></span></span>';
    }else{
        $output['ffmpeg_version']['value'] = '<span style="color:green;">' . $output['ffmpeg_version']['value'] . ' - <b>OK</b></span>';
    }
    
}

$output['mp4box_path'] = array( 'name' => 'MP4Box (GPAC) path', 'value' => trim(shell_exec('which MP4Box')) );
$output['mencoder_path'] = array( 'name' => 'Mencoder path', 'value' => trim(shell_exec('which mencoder')) );
$output['mplayer_path'] = array( 'name' => 'Mplayer path', 'value' => trim(shell_exec('which mplayer')) );
$output['mkvmerge_path'] = array( 'name' => 'MKVMerge path', 'value' => trim(shell_exec('which mkvmerge')) );

echo "<table border=\"1\" cellpadding=\"10\">\n";

foreach($output as $out){
    
    echo "<tr>\n";
    echo "<td>{$out['name']}</td>";
    echo "<td>" . ( $out['value'] ? $out['value'] : '<span style="color:red;">Not installed!</span>' ) . "</td>";
    echo "<tr>\n";
    
    echo "\n";
    
}

echo "</table>\n";


