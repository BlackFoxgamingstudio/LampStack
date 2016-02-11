<?php
/**
 * @package Entity Email Template Class
 * @version 1.0
 * @date 14 February 2015
 * @author Travis Coats, Zen Perfect Design
 * @location app/classes/
 *
 */

class EmailTemplate {

    use Finder, HTML, Access;

    protected   $template;
    protected   $template_content;
    public      $parameters;

    public function __construct($template = 'basic', $params = array()) {
        global $app_settings;
        $path = ROOT_PATH.'app/views/templates/'.$template.'.email.html.php';

        if (!empty($params)) {
            foreach($params as $parameter => $value) {
                $this->parameters[$parameter] = $value;
            }
        }

        if (file_exists($path)) {
            $this->template         = $template;
            ob_start();
            $load = include $path;
            $this->template_content = ob_get_clean();
        } else {
            die('No email template could be located matching the one provided to the application');
        }
    }

    public function render() {
        return $this->template_content;
    }

}