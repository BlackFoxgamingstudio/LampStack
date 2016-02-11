<?php

class LoginPageController {

    use ViewTrait;

    public $controller  = 'LoginPage';
    public $method      = '';
    public $parameters  = array();

    public $title       = 'Entity {CC} Login Page';
    public $description = 'Login using your username and password';
    public $keywords    = 'login, client portal';

    public function __construct ($method = '', $parameters = array()) {
        extract($this->pullGlobals());
        $this->method       = $method;
        $this->parameters   = $parameters;
        require_once VIEWS . 'login.html.php';
    }


}