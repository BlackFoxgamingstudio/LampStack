<?php

/**
 * @package videoEditor
 * @author Andchir <andchir@gmail.com>
 * @version 1.5.1
 */

class videoEditor {
    
    public $config;
    public $output;
    
    public function __construct( $config = array() )
    {
        
        $this->config = array_merge( array(
            'video_path' => dirname( __FILE__ ) . '/input/',
            'output_path' => dirname( __FILE__ ) . '/output/',
            'tmp_path' => dirname( __FILE__ ) . '/tmp/',
            'log_path' => dirname( __FILE__ ) . '/tmp/log.txt',
            'ffmpeg_path' => 'ffmpeg',
            'session_id' => 'my',
            'lang' => 'en',
            'user_id' => '',
            'is_admin' => false,
            'user_dir' => '',
            'upload_allowed' => array('mp4','flv','avi','mpg','webm'),
            'out_video_formats' => array('mp4','flv','webm','ogv','mp3'),
            'access_permissions' => array( 'upload', 'delete_output_files', 'delete_input_files', 'create_video' ),
            'out_video_sizes' => array( 360, 480, 576, 720),
            'youtube_download' => array( 'quality' => 'medium', 'type' => 'mp4' ),
            'max_output_files_count' => false,
            'use_mp4box' => false,
            'use_mencoder' => false,
            'use_avidemux' => false,
            'use_ffmpeg_concat_filter' => false,
            'ffmpeg_string_arr' => array(
                'flv' => '-vcodec flv -s {resolution} -aspect {aspect} -b:v {quality} -acodec libmp3lame -b:a 64k',//libfaac | aac
                'mp4' => '-vcodec libx264 -s {resolution} -aspect {aspect} -b:v {quality} -acodec libmp3lame -b:a 64k',//mpeg4
                'webm' => '-vcodec libvpx -s {resolution} -aspect {aspect} -b:v {quality} -acodec libvorbis -b:a 64k',
                'ogv' => '-vcodec libtheora -s {resolution} -aspect {aspect} -b:v {quality} -acodec libvorbis -b:a 64k'
            )
        ), $config );
        
        $this->output = array( 'error' => false, 'data' => array() );
        
        //create user directories
        if( !empty( $config['user_id'] ) ){
            
            if( !empty( $_GET['uid'] ) && is_numeric( $_GET['uid'] ) &&  $this->config['is_admin'] ){
                $config['user_id'] = intval( $_GET['uid'] );
            }
            
            $this->createUserDirs();
            
        }
        
    }
    
    /**
     * createUserDirs
     *
     */
    public function createUserDirs(){
        
        if( !$this->config['user_id'] ) return false;
        
        $paths = array( 'video_path', 'output_path', 'tmp_path' );
        $this->config['user_dir'] = sprintf( '%06d', $this->config['user_id'] ) . '/';
        
        foreach( $paths as $path_name ){
            
            $this->config[$path_name] = $this->config[$path_name] . $this->config['user_dir'];
            
            if( !is_dir( $this->config[$path_name] ) ){
                @mkdir( $this->config[$path_name], 0777 );
            }
            
        }
        
        //log file path
        if( !empty( $this->config['log_path'] ) ){
            $last_slash = strrpos( $this->config['log_path'], '/' );
            $this->config['log_path'] = substr( $this->config['log_path'], 0, $last_slash ) . '/' . $this->config['user_dir'] . substr( $this->config['log_path'], $last_slash + 1 );
        }
        return true;
        
    }
    
    /**
     * utf8_basename
     *
     * @param string $file_path 
     */
    public function utf8_basename( $file_path )
    {
        
        $temp_arr = explode('/',$file_path);
        
        return array_pop( $temp_arr );
        
    }
    
    /**
     * getFrame
     * 
     * @param string $time
     * @param string $video_file_path
     * @param string $file_out_path
     */
    public function getFrame( $time, $video_file_path, $file_out_path )
    {
        //mencoder
        if( $this->config['use_mencoder'] ){
            
            $temp_dir = $this->config['tmp_path'] . $this->config['session_id'];
            if( !is_dir( $temp_dir ) ){
                mkdir( $temp_dir, 0777 );
            }
            $seconds = $this->timeToSeconds( $time );
            $command = "cd '{$temp_dir}' && mplayer -frames 1 -ss {$seconds} -vo jpeg -nosound '{$video_file_path}'";
            
            @exec( $command );
            
            $sceenshot_path = $temp_dir . '/00000001.jpg';
            
            if( file_exists($sceenshot_path) ){
                @rename( $sceenshot_path, $file_out_path );
                @chmod( $file_out_path, 0777 );
                //delete temp directory
                $this->deleteDirectory( $temp_dir );
            }
            
        }
        //ffmpeg
        else{
            
            $command = $this->config['ffmpeg_path'] . " -ss {$time} -i \"{$video_file_path}\" -frames:v 1 -y \"{$file_out_path}\" 2>&1";
            exec( $command );
            @chmod( $file_out_path, 0777 );
            
        }
        
        $this->logging( $command );
        
        return $this->config['user_dir'] . $this->utf8_basename( $file_out_path );
        
    }
    
    /**
     * getFilesList
     *
     * @param string $dir_path
     */
    public function getFilesList( $dir_path, $limit = false )
    {
        
        $out = array();
        
        $files = array_diff(scandir($dir_path), array(".", ".."));
        
        foreach( $files as $file ){
            array_push( $out, $dir_path . $file );
        }
        
        usort($out, function($a, $b) {
            return filemtime($a) < filemtime($b);
        });
        
        if( $limit !== false ){
            $out = array_splice( $out, 0, $limit );
        }
        
        return $out;
        
    }
    
    /**
     * timeToSeconds
     *
     * @param string $time
     */
    public function timeToSeconds( $time )
    {
        
        $output = 0;
        
        $time_arr = explode(':',$time);
        $t = array(3600, 60, 1);
        
        foreach( $time_arr as $k => $tt ){
            $output += ( floatval( $tt ) * $t[$k] );
        }
        
        return $output;
        
    }
    
    
    /**
     * secondsToTime
     * 
     * @param float $seconds
     */
    public function secondsToTime( $sec ){
        
        if( !is_float( $sec ) ) $seconds = floatval( $sec );
        
        $hours   = floor($sec / 3600);
        $minutes = floor(($sec - ($hours * 3600)) / 60);
        $seconds = $sec - ($hours * 3600) - ($minutes * 60);
        $seconds = floor( $seconds );
        
        if ( $hours < 10 ) { $hours   = "0".$hours; }
        if ( $minutes < 10 ) { $minutes = "0".$minutes; }
        if ( $seconds < 10 ) { $seconds = "0".$seconds; }
        $time = $hours.':'.$minutes.':'.$seconds;
        
        return $time;
        
    }
    
    
    /**
     * execute cmd in the background
     * 
     * @param string $cmd
     */
    public function execInBackground( $cmd ) {
        if (substr(php_uname(), 0, 7) == "Windows"){
            pclose(popen("start /B ". $cmd, "r")); 
        }
        else {
            exec($cmd . " > /dev/null &");
        }
    }
    
    /**
     * upload
     *
     * @param string $link
     */
    public function action_upload( $link )
    {
        
        if( !$this->isPermitted( 'upload' ) ){
            header( "refresh:4;url=" . str_replace('action.php','',$_SERVER['PHP_SELF']) );
            header( "Content-Type: text/html; charset=UTF-8" );
            echo LANG_NOT_PERMITTED;
            exit;
        }
        
        $link = !empty( $_POST['link'] ) && !is_array( $_POST['link'] ) ? urldecode(trim( $_POST['link'] )) : '';
        
        if( $link ){
            
            if( ( strpos($link, 'youtube.com/') !== false) || (strpos($link, 'youtu.be/') !== false)) {
                
                $upload_path = $this->config['video_path'] . date('d-m-y_H-i-s') . '.mp4';
                $this->downloadFromYoutube( $link, $upload_path, $this->config['youtube_download'] );
                
            }else{
                
                $name = $this->utf8_basename( $link );
                $temp_arr = explode('.',$name);
                $ext = end($temp_arr);
                if( in_array( $ext, $this->config['upload_allowed'] ) ){
                    $file_name = $name;//date('d-m-y_H-i-s') . '.' . $ext;
                    file_put_contents( $this->config['video_path'].$file_name, file_get_contents( $link ) );
                    @chmod( $this->config['video_path'].$file_name, 0777 );
                }
                
            }
            
        }else{
            
            if ( $_FILES["file"]["error"] == UPLOAD_ERR_OK ) {
                
                $tmp_name = $_FILES["file"]["tmp_name"];
                $name = $_FILES["file"]["name"];
                $temp_arr = explode('.',$name);
                $ext = end($temp_arr);
                
                if( in_array( $ext, $this->config['upload_allowed'] ) ){
                    
                    $file_name = $name;//date('d-m-y_H-i-s') . '.' . $ext;
                    move_uploaded_file($tmp_name, $this->config['video_path'] . $file_name);
                    @chmod( $this->config['video_path'] . $file_name, 0777 );
                    
                }
            }
            
        }
        
        header("Location: " . str_replace('action.php','',$_SERVER['PHP_SELF']) );
        
    }
    
    
    /**
     * remove_video
     *
     */
    public function action_remove_video()
    {
        
        $name = !empty( $_POST['name'] ) && !is_array( $_POST['name'] ) ? urldecode(trim( $_POST['name'] )) : '';
        $name = str_replace( $this->config['user_dir'], '', $name );
        $type = !empty( $_POST['type'] ) && !is_array( $_POST['type'] ) ? urldecode(trim( $_POST['type'] )) : 'input';
        
        $file_path = $type == 'input' ? $this->config['video_path'] : $this->config['output_path'];
        $file_path .= $name;
        $ext = $this->getFileExtension( $file_path );
        
        if( !$this->isPermitted( 'delete_' . $type . '_files' ) ){
            $this->output['msg'] = LANG_NOT_PERMITTED;
            $this->output['error'] = true;
            return false;
        }
        
        if( in_array( $ext, $this->config['upload_allowed'] ) && file_exists( $file_path ) ){
            
            @unlink($file_path);
            $this->output['data']['msg'] = 'OK';
            
        }
        
        return true;
        
    }
    
    /**
     * get_list
     *
     */
    public function action_get_list()
    {
        
        $type = !empty( $_POST['type'] ) && !is_array( $_POST['type'] ) ? urldecode(trim( $_POST['type'] )) : '';
        
        if( in_array( $type, array('input','output') ) ){
            
            $dir_path = $type == 'input' ? $this->config['video_path'] : $this->config['output_path'];
            
            $files = $this->getFilesList( $dir_path );
            
            $this->output['data']['list'] = array();
            
            foreach( $files as $k => $f_name ){
                
                $this->output['data']['list'][] = array(
                    'name' => $this->utf8_basename( $f_name ),
                    'path' => $this->config['user_dir'],
                    'size' => round( ( filesize( $f_name ) / 1024 / 1024 ), 2),
                    'time' => date ("d.m.y H:i:s", filemtime( $f_name ) )
                );
                
            }
            
        }
        
    }
    
    /**
     * get_video
     *
     */
    public function action_get_video()
    {
        
        $name = !empty( $_POST['name'] ) && !is_array( $_POST['name'] ) ? urldecode(trim( $_POST['name'] )) : '';
        $name = str_replace( $this->config['user_dir'], '', $name );
        $type = !empty( $_POST['type'] ) && !is_array( $_POST['type'] ) ? urldecode(trim( $_POST['type'] )) : 'input';
        
        if( $name ){
            
            $dir_path = $type == 'input' ? $this->config['video_path'] : $this->config['output_path'];
            $video_file_path = $dir_path . $name;
            $ext = $this->getFileExtension( $video_file_path );
            
            $time = $this->getDuration( $video_file_path );
            
            $this->output['data']['time'] = $time;
            
            $file_in = $this->config['tmp_path'] . $this->config['session_id'] . "_frame-in.jpg";
            $file_out = $this->config['tmp_path'] . $this->config['session_id'] . "_frame-out.jpg";
            
            $time_arr = explode(':',$time);
            $time_arr[2] = floor($time_arr[2]);
            $time = implode(':',$time_arr);
            
            $this->output['data']['path'] = $this->config['user_dir'];
            if( !in_array( $ext, array( 'mp3', 'wav' ) ) ){
                $this->output['data']['image_in'] = $this->getFrame( '00:00:00', $video_file_path, $file_in );
                $this->output['data']['image_out'] = $this->getFrame( $time, $video_file_path, $file_out );
            }
            
        }
        
    }
    
    
    /**
     * get_frame
     *
     */
    public function action_get_frame()
    {
        
        $name = !empty( $_POST['name'] ) && !is_array( $_POST['name'] ) ? urldecode(trim( $_POST['name'] )) : '';
        $time = !empty( $_POST['time'] ) && !is_array( $_POST['time'] ) ? urldecode(trim( $_POST['time'] )) : '';
        $suffix = !empty( $_POST['suffix'] ) && !is_array( $_POST['suffix'] ) ? urldecode(trim( $_POST['suffix'] )) : '';
        $ext = $this->getFileExtension( $name );
        
        if( $name && !in_array( $ext, array( 'mp3', 'wav' ) ) ){
            
            $video_file_path = $this->config['video_path'] . $name;
            
            $image_file_path = $this->config['tmp_path'] . $this->config['session_id'] . '_frame' . $suffix . ".jpg";
            $this->output['data']['image'] = $this->getFrame( $time, $video_file_path, $image_file_path );
            
        }
        
    }
    
    
    /**
     * create_preview
     *
     */
    public function action_create_preview()
    {
        
        $name = !empty( $_POST['name'] ) && !is_array( $_POST['name'] ) ? urldecode(trim( $_POST['name'] )) : '';
        $name = str_replace( $this->config['user_dir'], '', $name );
        $times = !empty( $_POST['times'] ) && is_array( $_POST['times'] ) ? $_POST['times'] : '';
        
        if( $name ){
            
            $video_file_path = $this->config['video_path'] . $name;
            $input_info = pathinfo($video_file_path);
            $preview_path = $this->config['tmp_path'] . $this->config['session_id'] . '_preview' . '.' . $input_info['extension'];
            
            if( file_exists( $preview_path ) ) @unlink( $preview_path );
            
            $this->splitVideo( $video_file_path, $preview_path, array( $times[0], $times[1] ) );
            
            $this->output['data']['video'] = $this->config['user_dir'] . $this->utf8_basename( $preview_path );
            $this->output['msg'] = 'OK';
            
        }
        
    }
    
    
    /**
     * remove_preview
     *
     */
    public function action_remove_preview()
    {
        
        $tmp_files = $this->getFilesList( $this->config['tmp_path'] );
        
        if( !empty( $tmp_files ) ){
            
            foreach( $tmp_files as $f_path ){
                
                if( strpos( $f_path, $this->config['session_id'].'_preview' ) !== false ){
                    @unlink($f_path);
                }
                
            }
            
        }
        
        return true;
        
    }
    
    
    /**
     * create_video
     *
     */
    public function action_create_video()
    {
        
        if( !$this->isPermitted( 'create_video' ) ){
            $this->output['msg'] = LANG_NOT_PERMITTED;
            $this->output['error'] = true;
            return false;
        }
        
        $name = !empty( $_POST['name'] ) && !is_array( $_POST['name'] ) ? urldecode(trim( $_POST['name'] )) : '';
        $type = !empty( $_POST['type'] ) && !is_array( $_POST['type'] ) ? urldecode(trim( $_POST['type'] )) : 'input';
        $segments = !empty( $_POST['segments'] ) && is_array( $_POST['segments'] ) ? $_POST['segments'] : array();
        $opt_quality = !empty( $_POST['quality'] ) && in_array( $_POST['quality'], array( 'low', 'medium', 'high' ) ) ? $_POST['quality'] : '';
        $opt_size = !empty( $_POST['size'] ) && in_array( $_POST['size'], $this->config['out_video_sizes'] ) ? $_POST['size'] : $this->config['out_video_sizes'][0];
        $opt_format = !empty( $_POST['format'] ) && in_array( $_POST['format'], $this->config['out_video_formats'] ) ? $_POST['format'] : $this->config['out_video_formats'][0];
        
        $video_file_path = $type == 'input' ? $this->config['video_path'] : $this->config['output_path'];
        $video_file_path .= $name;
        
        $input_info = pathinfo($video_file_path);
        
        $output_video_path = $this->config['output_path'] . 'output-' . date('d-m-y_H-i-s') . '.' . $input_info['extension'];
        
        if( !$name ){ return false; }
        
        if( !empty( $segments ) ){
            
            //create segments video files
            $segments_paths = array();
            
            foreach( $segments as $k => $segment ){
                
                $segment_path = $this->config['tmp_path'] . $input_info['filename'] . "-" . $k . "." . $input_info['extension'];
                
                $this->splitVideo( $video_file_path, $segment_path, $segment );
                
                array_push( $segments_paths, $segment_path );
                
            }
            
            $this->joinVideos( $segments_paths, $output_video_path, true );
            
        }else{
            
            copy( $video_file_path, $output_video_path );
            
        }
        
        if( $opt_quality ){
            
            $ext = $this->getFileExtension( $output_video_path );
            $temp_video_path = $this->config['output_path'] . $this->config['session_id'] . '_encode_source.' . $ext;
            rename( $output_video_path, $temp_video_path );
            
            $this->convertVideo( $temp_video_path, array( 'quality' => $opt_quality, 'size' => $opt_size, 'format' => $opt_format ) );
            
        }else{
            
            $this->output['data']['output'] = basename( $output_video_path );
            $this->chkOutputFilesLimit();//delete old files
            
        }
        
    }
    
    
    /**
     * convertVideo
     *
     * @param string $input_path
     * @param array $options
     */
    public function convertVideo( $input_path, $options = array() )
    {
        
        $this->output['data']['output'] = $this->utf8_basename( $input_path );
        
        //If queue
        if( !empty( $options['input_array'] ) ){
            $queue = $options['input_array'];
            unset($options['input_array']);
            $_SESSION['queue'] = array(
                'current' => $this->utf8_basename( $input_path ),
                'sources' => $queue,
                'options' => $options
            );
        }
        
        $aspect = $this->getAspectRatio( $input_path );
        $output_path = $this->config['output_path'] . 'output-' . date('d-m-y_H-i-s') . '_'. uniqid() . '.' . $options['format'];
        $log_path = $this->config['tmp_path'] . $this->config['session_id'] . "_video.log";
        if( file_exists( $log_path ) ){ @unlink( $log_path ); }
        
        //mencoder
        if( $this->config['use_mencoder'] && !empty( $this->config['mencoder_string_arr'][$options['format']] ) ){
            
            $mencoder_string = $this->getConvertString( $options, $aspect, 'mencoder' );
            
            if( !$mencoder_string ) return false;
            
            $command = "mencoder '{$input_path}' {$mencoder_string} -o '{$output_path}' 2> \"{$log_path}\"";
            
        }
        //ffmpeg
        else{
            
            $ffmpeg_string = $this->getConvertString( $options, $aspect, 'ffmpeg' );
            
            if( !$ffmpeg_string ) return false;
            
            $command = $this->config['ffmpeg_path'] . " -i \"{$input_path}\" {$ffmpeg_string} -y \"{$output_path}\" 2> \"{$log_path}\"";
            
        }
        
        $this->execInBackground( $command );
        
        $this->output['msg'] = 'in_process';
        
        $this->logging( $command );
        
        return true;
        
    }
    
    
    /**
     * action_join_video
     *
     */
    public function action_join_video()
    {
        
        if( !$this->isPermitted( 'create_video' ) ){
            $this->output['msg'] = LANG_NOT_PERMITTED;
            $this->output['error'] = true;
            return false;
        }
        
        $names = !empty( $_POST['name'] ) && is_array( $_POST['name'] ) ? $_POST['name'] : array();
        if( !empty( $names ) ) array_map('urldecode',$names);
        $opt_quality = !empty( $_POST['quality'] ) && in_array( $_POST['quality'], array( 'low', 'medium', 'high' ) ) ? $_POST['quality'] : '';
        $opt_size = !empty( $_POST['size'] ) && in_array( $_POST['size'], $this->config['out_video_sizes'] ) ? $_POST['size'] : '';
        $opt_format = !empty( $_POST['format'] ) && in_array( $_POST['format'], $this->config['out_video_formats'] ) ? $_POST['format'] : '';
        $delete_source = isset( $_POST['delete_source'] ) ? !empty( $_POST['delete_source'] ) : false;
        
        if( empty( $names ) || count( $names ) == 1 ){
            
            $this->output['msg'] = LANG_ERROR_JOIN_NOSELECTED;
            $this->output['error'] = true;
            return false;
            
        }
        
        //segments
        $segments_paths = array();
        foreach( $names as $k => $name ){
            
            $segment_path = $this->config['output_path'] . str_replace( $this->config['user_dir'], '', $name );
            array_push( $segments_paths, $segment_path );
            
        }
        
        $ext = $opt_format ? $opt_format : $this->getFileExtension( $segments_paths[0] );
        $output_video_path = $this->fixOsPaths( $this->config['output_path'] . 'output-' . date('d-m-y_H-i-s') . '.' . $ext );
        
        $options = $opt_quality ? array( 'quality' => $opt_quality, 'size' => $opt_size, 'format' => $opt_format ) : array();
        
        $this->joinVideos( $segments_paths, $output_video_path, $delete_source, $options );
        
        return true;
        
    }
    
    
    /**
     * getConvertString
     *
     * @param array $options
     * @param string $aspect
     * @param string $encoder
     */
    public function getConvertString( $options, $aspect, $encoder = '' )
    {
        
        if( empty( $options ) || !$options['quality'] || !$options['size'] || !$options['format'] ) return false;
        if( !$encoder ) $encoder = $this->config['use_mencoder'] ? 'mencoder' : 'ffmpeg';
        
        $quality_arr = array(
            'low' => '600k',
            'medium' => '800k',
            'high' => '2500k'
        );
        
        $quality = $quality_arr[$options['quality']];
        
        //4:3
        if( $aspect == '4:3' ){
            
            $resolutions_arr = array(
                '360' => '480x360',
                '480' => '640x480',
                '576' => '720x576',
                '720' => '960x720'
            );
        }
        //16:9
        else{
            
            $resolutions_arr = array(
                '360' => '640x360',
                '480' => '720х480',
                '576' => '960x540',
                '720' => '1280x720'
            );
            
        }
        
        $resolution = $resolutions_arr[$options['size']];
        
        $encoder_string = isset( $this->config[ $encoder . '_string_arr'][$options['format']] ) ? $this->config[ $encoder . '_string_arr'][$options['format']] : '';
        
        if( !$encoder_string ) return false;
        
        //mencoder options
        if( $encoder == 'mencoder' ){
            
            $resolution = str_replace( 'x', ':', $resolution );
            $quality = str_replace( 'k', '', $quality );
            
        }
        
        $encoder_string = str_replace(
            array( '{quality}', '{resolution}', '{aspect}', '{format}' ),
            array( $quality, $resolution, $aspect, $options['format'] ),
            $encoder_string
        );
        
        return $encoder_string;
        
    }
    
    
    /**
     * splitVideo
     *
     * @param string $video_file_path
     * @param string $output_path
     * @param array $times
     */
    public function splitVideo( $video_file_path, $output_path, $times ){
        
        if( file_exists( $output_path ) ) @unlink( $output_path );
        
        $input_info = pathinfo( $video_file_path );
        
        //mkvmerge
        if( $this->config['use_mkvmerge'] && in_array( $input_info['extension'], array('webm','mkv') ) ){
            
            $seconds_in = $this->timeToSeconds($times[0]);
            $seconds_out = $this->timeToSeconds($times[1]);
            $frames_in = floor( $seconds_in * 24 );
            $frames_out = floor( $seconds_out * 24 );
            
            $command = "mkvmerge";
            if( $input_info['extension'] == 'webm' ){
                $command .= " --webm";
            }
            $command .= " --split parts:{$times[0]}-{$times[1]} '{$video_file_path}' -o '{$output_path}'";
            
        }
        
        //mencoder
        else if( $this->config['use_mencoder'] ){
            
            $seconds_in = $this->timeToSeconds($times[0]);
            $seconds_out = $this->timeToSeconds($times[1]);
            $seconds_total = floor( $seconds_out - $seconds_in );
            $seconds_in = floor( $seconds_in );
            
            $codecs_arr = array( 'flv' => 'flv', 'mp4' => 'x264', 'webm' => 'libvpx', 'ogv' => 'libtheora' );
            $vcodec = $codecs_arr[ $input_info['extension'] ];
            
            $command = "mencoder -ss {$seconds_in} -endpos {$seconds_total} -oac copy";
            if( $input_info['extension'] == 'mp4' ){
                $command .= " -fafmttag 0x706D";
            }
            $command .= " -ovc copy -of lavf -lavfopts format={$input_info['extension']} -noskip -mc 0";//-lavcopts vcodec={$vcodec} 
            $command .= " '{$video_file_path}' -o '{$output_path}' 2>&1";
            
        }
        //avidemux
        //not work
        /*
        else if( $this->config['use_avidemux'] ){
            
            $seconds_in = $this->timeToSeconds($times[0]);
            $seconds_out = $this->timeToSeconds($times[1]);
            $frames_in = floor( $seconds_in * 24 );
            $frames_out = floor( $seconds_out * 24 );
            
            $command = "avidemux --nogui --load '{$video_file_path}' --begin {$frames_in} --end {$frames_out} --audio-codec PCM --save '{$output_path}' --quit 2>&1";
            
        }
        */
        //mp4box
        else if( $this->config['use_mp4box'] && $input_info['extension'] == 'mp4' ){
            
            $time_in = $this->timeToSeconds($times[0]);
            $time_out = $this->timeToSeconds($times[1]);
            
            $command = "MP4Box -splitx {$time_in}:{$time_out} '{$video_file_path}' -out '{$output_path}' 2>&1";
            
        }
        //ffmpeg
        else{
            
            $command = $this->config['ffmpeg_path'] . " -i \"{$video_file_path}\" -ss {$times[0]} -to {$times[1]} -c copy -y \"{$output_path}\" 2>&1";
            
        }
        
        @exec( $command );
        @chmod( $output_path, 0777 );
        
        $this->logging( $command );
        
        return true;
        
    }
    
    
    /**
     * joinVideos
     *
     * @param array $segments_paths
     * @param string $output_video_path
     * @param boolean $delete_segments
     */
    public function joinVideos( $segments_paths, $output_video_path, $delete_segments = false, $options = array() ){
        
        $command = '';
        
        if( count( $segments_paths ) == 1 ){
            
            copy( $segments_paths[0], $output_video_path );
            @chmod( $output_video_path, 0777 );
            return true;
            
        }
        
        //check videos
        $diff_formats = false;
        $diff_resolution = false;
        $ext = '';
        $res = '';
        foreach( $segments_paths as $segments_path ){
            
            if( !$ext && !$res ){
                $ext = $this->getFileExtension( $segments_path );
                $res = $this->getResolution( $segments_path );
                continue;
            }
            
            $tmp_res = $this->getResolution( $segments_path );
            if( $this->getFileExtension( $segments_path ) != $ext ){
                $diff_formats = true;
            }
            if( implode( 'x', $tmp_res ) != implode( 'x', $res ) ){
                $diff_resolution = true;
            }
            
        }
        
        //error
        if( empty( $options ) && ( $diff_formats || $diff_resolution ) ){
            
            $this->output['msg'] = LANG_ERROR_JOIN_FILES_DIFFERENT;
            $this->output['error'] = true;
            return false;
            
        }
        
        if( empty( $options ) ){
            
            $command = $this->getJoinCommand( $segments_paths, $output_video_path );
            
            if( $command ){
                exec( $command );
                @chmod( $output_video_path, 0777 );
            }
            
            //remove temp files
            if( $delete_segments ){
                foreach( $segments_paths as $k => $segments_path ){
                    @unlink( $segments_path );
                }
            }
            
            $list_path = $this->fixOsPaths( $this->config['tmp_path'] . 'concate-list.txt' );
            if( !empty( $list_path ) ) @unlink( $list_path );
            
            if( $command ){ $this->logging( $command ); }
            
        }
        //if need convertation
        else{
            
            if( $diff_resolution || !$this->config['use_ffmpeg_concat_filter'] ){
                
                $options['input_array'] = array();
                foreach( $segments_paths as $segments_path ){
                    array_push( $options['input_array'], $this->utf8_basename( $segments_path ) );
                }
                $this->convertVideo( $segments_paths[0], $options );
                
            }else{
                
                $aspect = $this->getAspectRatio( $segments_paths[0] );
                $log_path = $this->config['tmp_path'] . $this->config['session_id'] . "_video.log";
                $filter_str = $this->concatFilterCommand( $segments_paths );
                $ffmpeg_string = $this->getConvertString( $options, $aspect, 'ffmpeg' );
                $command = $this->config['ffmpeg_path'] . " {$filter_str} {$ffmpeg_string} -y \"{$output_video_path}\" 2> \"{$log_path}\"";
                
                $this->logging( $command );
                $this->execInBackground( $command );
                
            }
            
            $this->output['msg'] = 'in_process';
            
        }
        
        return true;
        
    }
    
    
    /**
     * getJoinCommand
     *
     * @param array $segments_paths
     * @param string $output_video_path
     */
    public function getJoinCommand( $segments_paths, $output_video_path ){
        
        $ext = $this->getFileExtension( $output_video_path );
        
        //mkvmerge
        if( $this->config['use_mkvmerge'] && in_array( $ext, array('webm','mkv') ) ){
            
            $command = "mkvmerge";
            foreach( $segments_paths as $k => $segments_path ){
                $command .= " " . ($k > 0 ? '+' : '') . "'{$segments_path}'";
            }
            if( $ext == 'webm' ){
                $command .= " --webm";
            }
            $command .= " -o '{$output_video_path}'";
            
        }
        
        //mencoder
        /*
        else if( $this->config['use_mencoder'] && $ext == 'mp4' ){
            
            $command = "mencoder";
            foreach( $segments_paths as $k => $segments_path ){
                $command .= " '{$segments_path}'";
            }
            $command .= " -o '{$output_video_path}' -oac copy -ovc copy -forceidx -fafmttag 0x706d -of lavf -lavfopts format={$ext} -lavcopts vcodec={$ext} -noskip -mc 0";
            
            //$command = "cat";
            //foreach( $segments_paths as $k => $segments_path ){
            //    $command .= " '{$segments_path}'";
            //}
            //$command .= " > '{$output_video_path}'";
            
        }
        */
        //mp4box
        else if( $this->config['use_mp4box'] && $ext == 'mp4' ){
            
            $command = 'MP4Box';
            foreach( $segments_paths as $k => $segments_path ){
                $command .= " -cat '{$segments_path}'";
            }
            $command .= " '{$output_video_path}'";
            
        }
        //ffmpeg
        else{
            
            $log_path = $this->config['tmp_path'] . $this->config['session_id'] . "_video.log";
            $list_path = $this->fixOsPaths( $this->config['tmp_path'] . 'concate-list.txt' );
            $list_path_arr = array();
            $list_content = '';
            foreach( $segments_paths as $k => $segments_path ){
                $list_content .= "file '{$segments_path}'" . PHP_EOL;
                array_push( $list_path_arr, $this->fixOsPaths( $segments_path ) );
            }
            $list_content = $this->fixOsPaths( $list_content );
            file_put_contents( $list_path, $list_content );
            @chmod( $list_path, 0777 );
            
            $command = $this->config['ffmpeg_path'] . " -f concat -i \"{$list_path}\" -c copy -y \"{$output_video_path}\" 2> \"{$log_path}\"";
            
        }
        
        return $command;
        
    }
    
    public function concatFilterCommand( $path_arr ){
        
        $output = '';
        $filter_str = '';
        
        foreach( $path_arr as $k => $path ){
            
            $output .= " -i \"{$path}\"";
            $filter_str .= " [{$k}:0] [{$k}:1]";
        
        }
        
        $filter_str = trim($filter_str);
        $output .= " -filter_complex \"{$filter_str} concat=n=" . count( $path_arr ) . ":v=1:a=1 [v] [a]\" -map \"[v]\" -map \"[a]\"";
        
        return trim( $output );
        
    }
    
    
    /**
     * getFileExtension
     *
     * @param string $file_name
     */
    public function getFileExtension( $file_name ){
        
        $temp_arr = explode('.',$file_name);
        $ext = end($temp_arr);
        
        return strtolower( $ext );
        
    }
    
    
    /**
     * getAspectRatio
     *
     * @param string $input_path
     */
    public function getAspectRatio( $input_path )
    {
        
        $output = '4:3';
        
        $resolution = $this->getResolution( $input_path );
        if( $resolution !== false ){
            
            $output = ( $resolution[0] / $resolution[1] ) > 1.4 ? '16:9' : '4:3';
            
        }
        
        return $output;
        
    }
    
    
    /**
     * getResolution
     *
     * @param string $input_path
     */
    public function getResolution( $input_path )
    {
        
        $command = $this->config['ffmpeg_path'] . " -i \"{$input_path}\" 2>&1";
        
        ob_start();
        passthru( $command );
        $video_info = ob_get_clean();
        //ob_end_clean();
        
        preg_match('/(\d{3,})x(\d{3,})/', $video_info, $matches);
        
        if( $matches ){
            
            $width = intval( $matches[1] );
            $height = intval( $matches[2] );
            
            return array( $width, $height );
            
        }
        
        return false;
        
    }
    
    /**
     * getDuration
     *
     * @param string $video_file_path
     * @param string $content
     */
    public function getDuration( $video_file_path, $content = '' ){
        
        $duration = 0;
        if( $video_file_path && !$content ){
            $command = $this->config['ffmpeg_path'] . " -i \"{$video_file_path}\" 2>&1";
            $content = shell_exec( $command );
        }
        
        if( $content ){
            preg_match_all("/Duration: (.*?), start:/", $content, $matches);
            
            $rawDuration = $matches[1];
            if( is_array( $rawDuration ) && count( $rawDuration ) > 1 ){
                foreach( $rawDuration as $dur ){
                    $duration += $this->timeToSeconds( $dur );
                }
                $output = $this->secondsToTime( $duration );
            }else{
                $output = is_array( $rawDuration ) ? $rawDuration[0] : $rawDuration;
            }
        }
        
        return $output;
        
    }
    
    
    /**
     * getYoutubeId
     *
     * @par string $video_url
     */
    public function getYoutubeId( $video_url )
    {
        // http://stackoverflow.com/a/10315969/2252921
        preg_match('/^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$/', $video_url, $founded);
        
        return !$founded ? '' : $founded[1];
        
    }
    
    
    /**
     * downloadFromRemote_youtube
     *
     * @param string $video_url
     * @param string $upload_path
     * @param array $options
     */
    public function downloadFromYoutube( $video_url, $upload_path, $options = array( 'quality' => 'medium', 'type' => 'mp4' ) ){
        
        if( ( strpos($video_url, 'youtube.com/') !== false) || (strpos($video_url, 'youtu.be/') !== false)) {
            
            $video_id = $this->getYoutubeId( $video_url );
            $video_info = file_get_contents('http://youtube.com/get_video_info?video_id='.$video_id.'&ps=default&eurl=&gl=US&hl=en');
            if( $video_info === false ) return false;
            
            parse_str($video_info, $video_info_arr);
            
            if( !isset( $video_info_arr['url_encoded_fmt_stream_map'] ) ){
                if( $video_info_arr['status'] == 'fail' && !empty( $video_info_arr['reason'] ) ){
                    echo $video_info_arr['reason'];
                    exit;
                }
                return false;
            }
            
            $video_download_link = '';
            $formats_arr = explode(',', $video_info_arr['url_encoded_fmt_stream_map']);
            
            foreach( $formats_arr as $vid ) {
                
                parse_str( $vid, $vid_data );
                if( !isset( $vid_data['url'] ) ) continue;
                
                $this->logging( 'Youtube data: ' . print_r( $vid_data, true ) );
                
                if( $vid_data['quality'] == $options['quality'] && strpos( $vid_data['type'], '/'.$options['type'] ) !== false ){
                    $video_download_link = $vid_data['url'];
                    break;
                }
                
            }
            
            if( $video_download_link ){
                
                file_put_contents( $upload_path, file_get_contents( $video_download_link ) );
                @chmod( $upload_path, 0777 );
                
                return true;
                
            }
            
        }
        
        return false;
        
    }
    
    
    /**
     * progress
     *
     */
    public function action_progress()
    {
        
        $log_path = $this->config['tmp_path'] . $this->config['session_id'] . "_video.log";
        $encoder = $this->config['use_mencoder'] ? 'mencoder' : 'ffmpeg';
        
        $content = @file_get_contents( $log_path );
        
        $queue_left = 0;
        $queue_size = 0;
        $percent = 100;
        $queue_percent = 100;
        
        if($content){
            
            //mencoder
            if( $encoder == 'mencoder' ){
                
                preg_match_all( "/(\d*)%/", $content, $matches );
                
                $current = array_pop( $matches );
                if ( is_array( $current ) ){ $current = array_pop($current); }
                $percent = intval( preg_replace( "/\D+/", '', $current ) );
                if( $percent > 100 || $percent >= 98 ) $percent = 100;
                
            }
            
            //ffmpeg
            else{
                
                //input path
                preg_match("/Input #.* from '(.*?)':/", $content, $matches);
                if( !$matches ) return false;
                $input_path = $matches[1];
                
                $duration = $this->getDuration( '', $content );
                $duration = $this->timeToSeconds( $duration );
                
                //current time
                preg_match_all( "/time=(.*?) bitrate/", $content, $matches );
                
                $rawTime = array_pop( $matches );
                if ( is_array( $rawTime ) ){ $rawTime = array_pop($rawTime); }
                if( !empty( $rawTime ) ){
                    $time = $this->timeToSeconds( $rawTime );
                }else{
                    $time = $duration;
                }
                
                $percent = round( ( $time / $duration ) * 100 );
                
                if( $percent > 100 ) $percent = 100;
                
            }
            
        }
        
        $queue_percent = $percent;
        
        //If queue
        if( !empty( $_SESSION['queue'] ) && !empty( $_SESSION['queue']['sources'] ) ){
            
            $cur_index = array_search( $_SESSION['queue']['current'], $_SESSION['queue']['sources'] );
            $queue_size = count( $_SESSION['queue']['sources'] );
            $queue_left = count( $_SESSION['queue']['sources'] ) - $cur_index - 1;
            $queue_percent = ( ( 100 / $queue_size ) * $cur_index ) + ( ( 100 / $queue_size ) * ( $percent / 100 ) );
            $queue_percent = round( $queue_percent );
            
            if( $queue_left && $percent == 100 ){
                
                $_SESSION['queue']['current'] = $_SESSION['queue']['sources'][ ( $cur_index + 1 ) ];
                $input_path = $this->config['output_path'] . $_SESSION['queue']['current'];
                //$queue_left -= 1;
                //$queue_percent = ( 100 / ( $queue_left + 1 ) ) * ( $percent / 100 );
                //$queue_percent = round( $queue_percent );
                
                $this->convertVideo( $input_path, $_SESSION['queue']['options'] );
                
            }
            
            //Join and delete segments
            else if( $percent == 100 ){
                
                $out_paths =  $this->getFilesList( $this->config['output_path'], count( $_SESSION['queue']['sources'] ) );
                $out_paths = array_reverse( $out_paths );
                
                $ext = $this->getFileExtension( $out_paths[0] );
                $output_video_path = $this->fixOsPaths( $this->config['output_path'] . 'output-' . date('d-m-y_H-i-s') . '.' . $ext );
                
                $this->joinVideos( $out_paths, $output_video_path, true );
                
                $queue_percent = 100;
                
            }
            
        }
        
        //delete temp files
        if( $queue_percent == 100 ){
            
            $_SESSION['queue'] = null;
            unset( $_SESSION['queue'] );
            
            foreach( $this->config['upload_allowed'] as $ext ){
                $temp_video_path = $this->config['output_path'] . $this->config['session_id'] . '_encode_source.' . $ext;
                if( file_exists( $temp_video_path ) ){
                    @unlink( $temp_video_path );
                }
            }
            //@unlink( $log_path );
            $this->chkOutputFilesLimit();//delete old files
            
        }
        
        $this->output['data']['percent'] = $percent;
        $this->output['data']['queue_percent'] = $queue_percent;
        $this->output['data']['queue_size'] = $queue_size;
        $this->output['data']['queue_left'] = $queue_left;
        
        return true;
        
    }
    
    
    /**
     * action_show_log
     *
     */
    public function action_show_log(){
        
        $root_path = dirname( __FILE__ );
        $video_log_path = $this->config['tmp_path'] . $this->config['session_id'] . "_video.log";
        
        if( file_exists( $video_log_path ) ){
            $this->output['data']['output'] = file_get_contents( $video_log_path );
            $this->output['data']['output'] = str_replace( $root_path, '[ROOT_PATH]', $this->output['data']['output'] );
        }
        
        if( !empty( $this->config['log_path'] ) && file_exists( $this->config['log_path'] ) ){
            $this->output['data']['event_log'] = file_get_contents( $this->config['log_path'] );
            $this->output['data']['event_log'] = str_replace( $root_path, '[ROOT_PATH]', $this->output['data']['event_log'] );
        }
        
        if( empty( $this->output['data']['output'] ) && empty( $this->output['data']['event_log'] ) ){
            $this->output['error'] = true;
            $this->output['msg'] = LANG_LOG_EMPTY;
        }
        
    }
    
    
    /**
     * action_rename
     *
     */
    public function action_rename(){
        
        $old_name = !empty( $_POST['old_name'] ) && !is_array( $_POST['old_name'] ) ? urldecode(trim( $_POST['old_name'] )) : '';
        $new_name = !empty( $_POST['new_name'] ) && !is_array( $_POST['new_name'] ) ? urldecode(trim( $_POST['new_name'] )) : '';
        $ext = $this->getFileExtension( $old_name );
        
        if( strpos( $new_name, '.' . $ext ) === false ){
            $new_name .= '.' . $ext;
        }
        
        $files = $this->getFilesList( $this->config['output_path'] );
        $file_index = -1;
        $new_exists = false;
        
        foreach( $files as $k => $file ){
            
            if( $this->utf8_basename( $file ) == $old_name ){
                $file_index = $k;
            }
            if( $this->utf8_basename( $file ) == $new_name ){
                $new_exists = true;
                break;
            }
            
        }
        
        if( $new_exists ){
            
            $this->output['error'] = true;
            $this->output['msg'] = LANG_ERROR_NEWFILE_EXISTS;
            
        }else if( $file_index > -1 ){
            
            $new_path = $this->config['output_path'] . $new_name;
            rename( $files[$file_index], $new_path );
            $this->output['error'] = false;
            
        }
        
    }
    
    
    /**
     * deleteDirectory
     *
     * @param string $dir
     */
    function deleteDirectory( $dir ) {
        
        if (!file_exists($dir)) return true;
        if (!is_dir($dir)) return unlink($dir);
        
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') continue;
            if (!$this->deleteDirectory($dir.DIRECTORY_SEPARATOR.$item)) return false;
        }
        
        return rmdir($dir);
        
    }
    
    
    /**
     * logging
     *
     * @param string $str
     */
    public function logging( $str ){
        
        if( empty( $this->config['log_path'] ) ) return false;
        
        if( file_exists( $this->config['log_path'] ) && filesize( $this->config['log_path'] ) >= $this->config['max_log_size'] ){
            @unlink( $this->config['log_path'] );
        }
        
        $fp = fopen( $this->config['log_path'], 'a' );
        
        $str = "\n\n" . date( 'd/m/Y H:i:s' ) . "\n" . $str;
        
        fwrite( $fp, $str );
        fclose( $fp );
        @chmod( $this->config['log_path'], 0777 );
        
        return true;
        
    }
    
    
    /**
     * chkOutputFilesLimit
     *
     */
    public function chkOutputFilesLimit(){
        
        if( $this->config['max_output_files_count'] ){
            
            $files = $this->getFilesList( $this->config['output_path'] );
            
            if( !empty( $files ) && count( $files ) > $this->config['max_output_files_count'] ){
                
                $files = array_reverse( $files );
                $files = array_slice( $files, 0, ( count( $files ) - $this->config['max_output_files_count'] ) );
                
                foreach( $files as $file_path ){
                    @unlink($file_path);
                }
                
            }
            
        }
        
        return true;
        
    }
    
    public function fixOsPaths( $str ){
        
        $is_windows = strpos( php_uname(), 'Windows' ) !== false;
        
        if( $is_windows ){
            $str = str_replace( '/', '\\', $str );
        }
        
        return $str;
    }
    
    /**
     * isPermitted
     *
     * @param string $str
     */
    public function isPermitted( $str ){
        
        return in_array( $str, $this->config['access_permissions'] );
        
    }
    
}
