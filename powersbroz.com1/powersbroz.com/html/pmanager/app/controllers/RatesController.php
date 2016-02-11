<?php

class RatesController {

    use ViewTrait;

    public $wages;

    public $controller  = 'Rates';
    public $method      = '';
    public $parameters  = array();

    public $title       = 'Entity {CC} Rates, Wages, and Taxes';
    public $description = 'Set and update wages and taxes for the application';
    public $keywords    = 'wages, rates, taxes';
    private static $url = 'rates';

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
        $this->wages = $wages = Wage::find('all');
        require_once VIEWS . 'all.rates.html.php';
    }

    public function single($id) {

    }

    public static function url() {
        return BASE_URL . self::$url.'/';
    }


}