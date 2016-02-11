<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Nwdthemes Standalone Slider Revolution
 *
 * @package     StandaloneRevslider
 * @author		Nwdthemes <mail@nwdthemes.com>
 * @link		http://nwdthemes.com/
 * @copyright   Copyright (c) 2015. Nwdthemes
 * @license     http://themeforest.net/licenses/terms/regular
 */


if( ! function_exists('WP_Filesystem'))
{
	/**
	 *	Init filesystem class
	 */

	function WP_Filesystem() {

		global $wp_filesystem;

		$ci = &get_instance();
		$ci->load->library('filesystem');

		$wp_filesystem = $ci->filesystem;

		return true;
	}
}

if( ! function_exists('unzip_file'))
{
	/**
	 *	Unzip file
	 *
	 *	@param	string	Zip file
	 *	@param	string	Destination path
	 *	@return boolean
	 */

	function unzip_file($file, $path) {
		if (class_exists('ZipArchive'))
		{
			$zip = new ZipArchive;
			$zipResult = $zip->open($file, ZIPARCHIVE::CREATE);
			if ($zipResult === true)
			{
				$zip->extractTo($path);
				$zip->close();
			}
		}
		else
		{
			include_once APPPATH . "libraries/pclzip.lib.php";
			$pclZip = new PclZip($file);
			$pclZipResult = $pclZip->extract(PCLZIP_OPT_PATH, $path);
			$zipResult = $pclZipResult !== 0;
		}
		return $zipResult;
	}
}

if( ! function_exists('recurse_move'))
{
	/**
	 * Move files recursively
	 *
	 * @param	string	$src
	 * @param	string	$dst
	 */

	function recurse_move($src, $dst) {
		$dir = opendir($src);
		wp_mkdir_p($dst);
		while(false !== ( $file = readdir($dir)) ) {
			if (( $file != '.' ) && ( $file != '..' )) {
				if ( is_dir($src . '/' . $file) )
				{
					recurse_move($src . '/' . $file,$dst . '/' . $file);
				}
				else
				{
					rename($src . '/' . $file,$dst . '/' . $file);
				}
			}
		}
		closedir($dir);
		rmdir($src);
	}
}
