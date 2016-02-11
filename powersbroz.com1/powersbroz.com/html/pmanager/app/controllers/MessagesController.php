<?php

class MessagesController {

    use ViewTrait;

    public $messages;

    public $controller  = 'Messages';
    public $method      = '';
    public $parameters  = array();

    public $title       = 'Entity {CC} Messenger';
    public $description = 'Send and check messages';
    public $keywords    = 'messages, instant messenger';
    private static $url = 'messages';

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
        $this->messages = $messages = Message::find('all');
        require_once VIEWS . 'all.messages.html.php';
    }

    public function single($id) {
    }

    public static function url() {
        return BASE_URL . self::$url.'/';
    }


}