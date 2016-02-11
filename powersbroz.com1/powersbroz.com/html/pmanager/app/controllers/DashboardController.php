<?php

class DashboardController {

    use ViewTrait;

    public $controller  = 'Dashboard';
    public $method      = '';
    public $parameters  = array();

    public $title       = 'Entity {CC} Dashboard';
    public $description = 'A powerful PHP-based project management system designed by Zen Perfect Design';
    public $keywords    = 'PHP, project management';
    private static $url = '/';

    public function __construct ($method = '', $parameters = array()) {
        extract($this->pullGlobals());
        $this->open();
        $this->method       = $method;
        $this->parameters   = $parameters;

        require_once VIEWS . 'home.html.php';

        $this->close();

    }

    public static function url() {
        return BASE_URL;
    }


}