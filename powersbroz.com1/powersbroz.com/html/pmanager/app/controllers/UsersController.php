<?php

class UsersController {

    use ViewTrait;

    public $users;

    public $controller  = 'Users';
    public $method      = '';
    public $parameters  = array();

    public $title       = 'Entity {CC} User Management';
    public $description = 'Manage your user accounts';
    public $keywords    = 'users, user management';
    private static $url = 'users';

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
        switch ($app_preferences->get_order('users')) {
            case 'alpha':
                $this->users = $users = User::find('sql', "SELECT * FROM users ORDER BY lname ASC");
                break;

            case 'newest':
                $this->users = $users = User::find('sql', "SELECT * FROM users ORDER BY created DESC");
                break;

            default: // Oldest
                $this->users = $users = User::find('sql', "SELECT * FROM users ORDER BY created ASC");
        }

        if (!$app_preferences->display_locked()) {

            for ($u = 0;$u < count($this->users);$u++) {
                if (!$this->users[$u]->is_active()) {
                    unset($this->users[$u]);
                }
            }

            $this->users = $users = array_values($this->users);

        }

        // Filter out users that user does no have access to
        if ($users) {
            $access = array();
            foreach ($users as $user) {

                if ($current_user->role()->can_access($user->id(), 'user')) {
                    $access[] = $user;
                }

            }
            $users = $access;
        }

        $this->users = $users;

        require_once VIEWS . 'all.users.html.php';
    }

    public function view($id) {
        extract($this->pullGlobals());
        $this->users = $user = User::find('id', $id[0]);
        require_once VIEWS . 'single.user.html.php';
    }

    public static function url() {
        return BASE_URL . self::$url.'/';
    }


}