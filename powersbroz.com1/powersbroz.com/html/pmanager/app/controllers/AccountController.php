<?php

class AccountController {

    use ViewTrait;

    public $controller  = 'Account';
    public $method      = '';
    public $parameters  = array();

    public $title       = 'Entity {CC} ';
    public $description = 'Edit your account';
    public $keywords    = 'account, account management';
    private static $url = 'account';

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
        require_once VIEWS . 'account.html.php';
    }

    public function edit() {
        extract($this->pullGlobals());
        require_once VIEWS . 'account.edit.html.php';
    }

    public function timers() {
        extract($this->pullGlobals());
        require_once VIEWS . 'timer.items.html.php';
    }

    public function payment() {
        extract($this->pullGlobals());
        if ($current_user->role()->can('invoice', 'create')) {
            require_once VIEWS . 'account.paymentinfo.html.php';
        } else {
            require_once VIEWS . 'restricted.html.php';
        }
    }

    public function single() {

    }

    public function create()  {

    }

    public function store($post) {

    }

    public static function url() {
        return BASE_URL . self::$url.'/';
    }

}