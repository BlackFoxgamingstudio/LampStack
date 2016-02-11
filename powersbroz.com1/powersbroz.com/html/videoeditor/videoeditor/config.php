<?php

/*

VideoEditor config

*/

$root_path = dirname( __FILE__ );

$config = array();
$config['video_path'] = $root_path . "/input/";
$config['output_path'] = $root_path . "/output/";
$config['tmp_path'] = $root_path . "/tmp/";
$config['log_path'] = $root_path . "/tmp/log.txt";

$config['ffmpeg_path'] = 'ffmpeg';//FFmpeg path on your server. Windows: C:\ffmpeg\bin\ffmpeg.exe
$config['debug'] = false;//debug mode
$config['lang'] = 'en';//Language - en | ru
$config['upload_allowed'] = array('mp4','flv','avi','mpg','webm','3gp','ogv','mp3');//allowed to upload
$config['use_ffmpeg_concat_filter'] = true;//Use FFmpeg concat filter (only for similar video) - http://ffmpeg.org/ffmpeg-filters.html#concat

//mysql configuration (optional) - used for users
$config['db'] = array(
    'user' => 'db212369',
    'password' => 'PowersBros45me',
    'host' => 'internal-db.s212369.gridserver.com',
    'db_name' => 'db212369_veditor',
    'users_table' => 'users'
);

//users (ignored if mysql configured)
$config['users'] = array(
    array(
        'id' => 1,
        'username' => 'admin',
        'password' => '111111',
        'type' => 'admin'
    ),
    array(
        'id' => 2,
        'username' => 'user1',
        'password' => '222222',
        'type' => 'user'
    )
);

//access permissions - upload, delete_input_files, delete_output_files, create_video
$config['users_access_permissions'] = array(
    'admin' => array(
        'upload',
        'delete_output_files',
        'delete_input_files',
        'create_video'
    ),
    'user' => array(
        'upload',
        'delete_output_files',
        'delete_input_files',
        'create_video'
    )
);

$config['player'] = 'flowplayer';//Player - flowplayer | videojs | codoplayer
$config['max_log_size'] = 10 * 1024;//Max log size in bytes
$config['max_output_files_count'] = false;//The maximum number of files in a folder output/. Will be deleted the old files. false - without limitation.

$config['use_mencoder'] = false;//Use mencoder - experimental
$config['use_mkvmerge'] = false;//Use mkvmerge for WEBM and MKV - splitting video no strictly for time
$config['use_mp4box'] = false;//Use MP4Box (gpac) - MP4 only - splitting video no strictly for time

//Settings for downloading from youtube
$config['youtube_download'] = array(
    'quality' => 'medium',//hd720 | medium
    'type' => 'mp4'
);

//conversion parameters
//FFmpeg
$config['ffmpeg_string_arr'] = array(
    'flv' => '-vcodec flv -s {resolution} -aspect {aspect} -b:v {quality} -acodec libmp3lame -b:a 64k -f {format}',
    'mp4' => '-vcodec libx264 -s {resolution} -aspect {aspect} -b:v {quality} -acodec libmp3lame -b:a 64k -f {format}',
    'webm' => '-vcodec libvpx -s {resolution} -aspect {aspect} -b:v {quality} -acodec libvorbis -b:a 64k -f {format}',
    'ogv' => '-vcodec libtheora -s {resolution} -aspect {aspect} -b:v {quality} -acodec libvorbis -b:a 64k',
    'mp3' => '-vn -f mp3 -ab 192k'
);

//Mencoder
$config['mencoder_string_arr'] = array(
    'flv' => '-oac mp3lame -lameopts abr:br=64 -ovc lavc -lavcopts vcodec=flv:vbitrate={quality} -of lavf -lavfopts format=flv -ofps 24 -vf scale={resolution} -noskip -mc 0',
    'mp4' => '-oac mp3lame -lameopts abr:br=64 -ovc x264 -x264encopts pass=1:qp=22:threads=0:bframes=2:bitrate={quality} -of lavf -lavfopts format=mp4 -ofps 24 -vf scale={resolution} -noskip -mc 0',
    //not working for me
    'webm' => '',//'-oac lavc -ovc lavc -lavcopts threads=3:acodec=libvorbis:vcodec=libvpx:abitrate=64:vbitrate={quality} -ffourcc VP80 -of lavf -lavfopts format=webm -ofps 24 -vf scale={resolution} -noskip -mc 0',
    'ogv' => '',//'-oac lavc -ovc lavc -lavcopts threads=3:acodec=libvorbis:vcodec=libtheora:abitrate=64:vbitrate={quality} -of lavf -lavfopts format=ogv -ofps 24 -vf scale={resolution} -noskip -mc 0'
    'mp3' => ''
);

//Secret code to the accept links
$config['secret_code'] = 'SXWEkJ2s';

//mail config
$config['mail'] = array(
    'email_from' => 'admin@example.com',
    'email_from_name' => 'Online Video Editor',
    'smtp_host' => '',
    'smtp_port' => '25',//25 | 587 ...
    'smtp_username' => '',
    'smtp_password' => '',
    'smtp_secure' => ''//ssl | tls
);