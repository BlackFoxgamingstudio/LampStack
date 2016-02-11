<?php

class ErrorController {

    use ViewTrait;

    public $controller  = 'User';
    public $method      = '';
    public $parameters  = array();

    public $title       = 'Entity {CC} Unexpected Application Error';
    public $description = 'Uh oh, something went wrong';
    public $keywords    = '';

    public function __construct () {
        extract($this->pullGlobals());
        $this->open();
        require_once VIEWS . 'error.html.php';
        $this->close();
    }


}