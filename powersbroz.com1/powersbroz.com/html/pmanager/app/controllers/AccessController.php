<?php

class AccessController {

    use ViewTrait;

    public $controller  = 'Access';
    public $method      = '';
    public $parameters  = array();

    public $title       = 'Entity {CC} Access Role Management';
    public $description = 'Manage and create access roles for the application';
    public $keywords    = 'PHP, project management';
    private static $url = 'access';

    public function __construct ($method = '', $parameters = array()) {
        extract($this->pullGlobals());
        $this->open();
        $this->method       = $method;
        $this->parameters   = array_values($parameters);

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
        $this->roles = $roles = Role::find('all');
        if (!$current_user->role()->can_access('roles', 'system')) {
            require_once VIEWS.'restricted.html.php';
        } else {
            require_once VIEWS . 'all.roles.html.php';
        }

    }

    public function view($id) {
        extract($this->pullGlobals());
        if (empty($id)) {
            $this->all();
        } else {
            $this->roles = $role = Role::find('id', $id[0]);
            if (!$current_user->role()->can_access('roles', 'system')) {
                require_once VIEWS.'restricted.html.php';
            } else {
                require_once VIEWS . 'single.role.html.php';
            }
        }


    }

    public function create()  {
        extract($this->pullGlobals());
        //$this->roles = $roles = Role::find('all');
        if (!$current_user->role()->can_access('roles', 'system')) {
            require_once VIEWS.'restricted.html.php';
        } else {
            require_once VIEWS . 'new.role.html.php';
        }
    }

    public function store($post) {

    }

    public static function url() {
        return BASE_URL . self::$url.'/';
    }


}