<?php

class ActivityController extends ViewController {

    use ViewTrait;

    public $controller  = 'Activity';
    public $method      = '';
    public $parameters  = array();

    public $title       = 'Entity {CC} Application Activity';
    public $description = 'A powerful PHP-based project management system designed by Zen Perfect Design';
    public $keywords    = 'PHP, project management';
    private static $url = 'activity';

    public function __construct ($method = '', $parameters = array()) {
        extract($this->pullGlobals());
        $this->method       = $method;
        $this->parameters   = $parameters;

        if ($this->method != 'printer') {
            $this->open();
        }

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

        if ($this->method != 'printer') {
            $this->close();
        }

    }

    public function all() {
        extract($this->pullGlobals());
        if (!$current_user->role()->can_access('activity', 'system')) {
            require_once VIEWS . 'restricted.html.php';
        } else {
            $activity = ActivityLog::find('sql', "SELECT * FROM app_activity WHERE visible = 1 ORDER BY created DESC");
            require_once VIEWS . 'activity.html.php';
        }
    }

    public function printer() {
        extract($this->pullGlobals());
        $activity = ActivityLog::find('sql', "SELECT * FROM app_activity WHERE visible = 1 ORDER BY created DESC");
        require_once VIEWS . 'templates/activity.print.html.php';
    }

    public static function url() {
        return BASE_URL . self::$url . '/';
    }


}