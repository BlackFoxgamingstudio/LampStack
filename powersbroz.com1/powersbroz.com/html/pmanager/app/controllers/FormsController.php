<?php

class FormsController {

    use ViewTrait;

    private $forms;

    public $controller  = 'Forms';
    public $method      = '';
    public $parameters  = array();

    public $title       = 'Entity {CC} Form Management';
    public $description = 'Custom forms and custom form submission data';
    public $keywords    = 'forms, form management';
    private static $url = 'forms';

    public function __construct ($method = '', $parameters = array()) {
        extract($this->pullGlobals());
        $this->method       = $method;
        $this->parameters   = array_values($parameters);

        $this->open();
        if (!empty($this->method)) {

            if (method_exists($this, $this->method)) {
                $method = $this->method;
                if (empty($this->parameters)) {
                    $this->all();
                } else {
                    $this->$method($this->parameters);
                }
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
        $this->forms = $forms = Form::find('all');
        if (!$current_user->role()->is_staff()) {
            require_once VIEWS . 'restricted.html.php';
        } else {
            require_once VIEWS . 'all.forms.html.php';
        }
    }

    public function edit($slug) {
        extract($this->pullGlobals());
        $form = Form::find('sql', "SELECT * FROM forms WHERE slug = '".$slug[0]."'");
        if (!$form) {
            $system_notification->queue('No Form Found', 'The form you are trying to access does not exist');
            $this->all();
        } else {
            $form = array_shift($form);
            require_once VIEWS . 'single.form.html.php';
        }
    }

    public function single() {

    }

    public function create()  {

    }

    public function store($post) {

    }

    public function submissions($slug) {
        extract($this->pullGlobals());
        $form = Form::find('sql', "SELECT * FROM forms WHERE slug = '".$slug[0]."'");
        if (!$form) {
            require_once VIEWS . 'notfound.html.php';
        } else {
            $form = array_shift($form);
            $submissions = $form->get_submissions();
            require_once VIEWS . 'single.form.submissions.html.php';
        }
    }

    public static function url() {
        return BASE_URL . self::$url.'/';
    }

}