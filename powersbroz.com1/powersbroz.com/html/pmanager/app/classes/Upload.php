<?php
/**
 * @package Entity Upload Class
 * @version 1.0
 * @date 26 November 2014
 * @author Travis Coats, Zen Perfect Design
 * @location app/classes/
 *
 */

class Upload {
    // $_FILES array properties
    public $name;
    public $type;
    public $tmp_name;
    public $error;
    public $size;

    // Extended properties for security
    public $extension;
    private static $banned = array('php', 'xml', 'js', 'py', 'rb');
    private static $allowed_images = array('jpg', 'jpeg', 'png', 'gif');

    public function __construct($file) {
        $this->name     = $file['name'];
        $this->type     = $file['type'];
        $this->tmp_name = $file['tmp_name'];
        $this->error    = $file['error'];
        $this->size     = $file['size'];

    }

    public function upload_image() {

    }

    public function upload_file() {

    }

    public function formatBytes($bytes, $precision = 2) {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }

}