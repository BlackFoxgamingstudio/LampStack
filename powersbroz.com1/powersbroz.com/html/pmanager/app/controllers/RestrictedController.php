<?php

class RestrictedController {

    use ViewTrait;

    public $controller  = 'Restricted';
    public $method      = 'make';
    public $parameters  = array();

    public $title       = 'Entity {CC} Restricted Access';
    public $description = 'You are not authorized to view the contents of this page';
    public $keywords    = 'restricted access, members only';

    public function __construct () {
        extract($this->pullGlobals());
        require_once VIEWS . 'home.html.php';
    }


}