<?php

class SettingsController {

    use ViewTrait;

    private $settings;

    public $controller  = 'Settings';
    public $method      = '';
    public $parameters  = array();

    public $title       = 'Entity {CC} Application Settings';
    public $description = 'Change the application\'s settings';
    public $keywords    = 'PHP, project management';
    private static $url = 'settings';

    public function __construct ($method = '', $parameters = array()) {
        extract($this->pullGlobals());
        $this->method       = $method;
        $this->parameters   = $parameters;
        $this->settings     = $app_settings;

        $this->open();
        if (!empty($this->method)) {

            if (method_exists($this, $this->method)) {
                $method = $this->method;
                $view->$method($this->parameters);
            } else {
                $this->all();
            }
        } else {
            $this->all();
        }
        $this->close();

    }

    public function all() {
        extract($this->pullGlobals());

        if (!$current_user->role()->can_access('settings', 'system')) {
            require_once VIEWS . 'restricted.html.php';
        } else {
            require_once VIEWS . 'settings.html.php';
        }
    }

    public function single() {

    }

    public function create()  {

    }

    public function store($post) {

    }

    public function getSetting($key) {
        if (array_key_exists($key, $this->settings)) {
            return $this->settings[$key];
        } else {
            return false;
        }
    }

    public static function url() {
        return BASE_URL . self::$url.'/';
    }

}