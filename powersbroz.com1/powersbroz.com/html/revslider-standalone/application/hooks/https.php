<?php
class Https {
	public function trigger_https() {
		$ci =& get_instance();
		if ($ci->input->server('HTTPS') && $ci->input->server('HTTPS') != 'off')
		{
			$ci->config->config['base_url'] = str_replace('http://', 'https://', $ci->config->config['base_url']);
		}
		else
		{
			$ci->config->config['base_url'] = str_replace('https://', 'http://', $ci->config->config['base_url']);
		}
	}
}