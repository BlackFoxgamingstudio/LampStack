<?php

class NotFoundController {

    use ViewTrait;

    public $controller  = 'User';
    public $method      = '';
    public $parameters  = array();

    public $title       = 'Entity {CC} Resource Not Found';
    public $description = 'Uh oh, something went wrong';
    public $keywords    = '';

    public function __construct () {
        extract($this->pullGlobals());
        $this->open();
        require_once VIEWS . 'notfound.html.php';
        $this->close();
    }

}