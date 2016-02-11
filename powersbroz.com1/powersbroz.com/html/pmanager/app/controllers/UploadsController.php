<?php

class UploadsController {

    use ViewTrait;

    public $controller  = 'Uploads';
    public $method      = '';
    public $parameters  = array();

    public $title       = 'Entity {CC} Manage Files';
    public $description = 'Organize your project files and share them with team members';
    public $keywords    = 'files, file management';
    private static $url = 'uploads';

    public function __construct ($method = '', $parameters = array()) {
        extract($this->pullGlobals());
        $this->method       = $method;
        $this->parameters   = $parameters;
        $this->settings     = $app_settings;

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

        $userFileUsage  = File::user_usage($current_user->id());
        $userFileLimit  = $current_user->role()->file_limit('plain');

        $unusedSpace   = (($userFileLimit - $userFileUsage) / $userFileLimit) * 100;
        $userSpace     = ($userFileUsage / $userFileLimit) * 100;

        if (!$current_user->role()->can('file', 'create')) {
            require_once VIEWS . 'restricted.html.php';
        } else {
            require_once VIEWS . 'all.files.html.php';
        }
    }

    public function single() {

    }

    public function create()  {

    }

    public function store($post) {

    }

    public static function url() {
        return BASE_URL . self::$url.'/';
    }

}