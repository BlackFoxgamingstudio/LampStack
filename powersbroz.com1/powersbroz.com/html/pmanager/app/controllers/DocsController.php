<?php

class DocsController {

    use ViewTrait;

    public $controller  = 'Docs';
    public $method      = '';
    public $parameters  = array();

    public $title       = 'Entity {CC} Application Documentation';
    public $description = 'Learn how to use the application';
    public $keywords    = 'help';
    private static $url = 'docs';

    public function __construct ($method = '', $parameters = array()) {
        extract($this->pullGlobals());
        $this->method       = $method;
        $this->parameters   = $parameters;

        $this->open();
        if (!empty($this->method)) {

            if (method_exists($this, $this->method)) {
                $method = $this->method;
                $this->$method($this->parameters);
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

        if (!$current_user->role()->can_access('docs', 'system')) {
            require_once VIEWS . 'restricted.html.php';
        } else {
            require_once VIEWS . 'documentation.html.php';
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
        return BASE_URL . self::$url .'/';
    }


}