<?php

class ProjectsController {

    use ViewTrait;

    public $projects;

    public $controller  = 'Groups';
    public $method      = '';
    public $parameters  = array();

    public $title       = 'Entity {CC} Project Management';
    public $description = 'Manage your projects';
    public $keywords    = 'projects, project management';
    private static $url = 'projects';

    public function __construct ($method = '', $parameters = array()) {
        extract($this->pullGlobals());
        $this->method       = $method;
        $this->parameters   = array_values($parameters);

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
        switch ($app_preferences->get_order('projects')) {

            case 'alpha':
                $this->projects = $projects = Project::find('sql', "SELECT * FROM projects ORDER BY pname ASC");
                break;

            case 'newest':
                $this->projects = $projects = Project::find('sql', "SELECT * FROM projects ORDER BY created DESC");
                break;

            case 'due':
                $this->projects = $projects = Project::find('sql', "SELECT * FROM projects ORDER BY enddate ASC");
                break;

            default:
                $this->projects = $projects = Project::find('sql', "SELECT * FROM projects ORDER BY pname ASC");

        }

        // Filter out projects user does no have access to
        if ($projects) {
            $access = array();
            foreach ($projects as $project) {

                if ($current_user->role()->can_access($project->id())) {
                    $access[] = $project;
                }

            }
            $projects = $access;
        }

        require_once VIEWS . 'all.projects.html.php';
    }

    public function view($id) {
        extract($this->pullGlobals());

        if (!empty($id)) {
            $this->projects = $project = Project::find('id', $id[0]);
            if ($project) {
                require_once VIEWS . 'single.project.html.php';
            } else {
                require_once VIEWS . 'notfound.html.php';
            }

        } else {
            $this->all();
        }

    }

    public static function url() {
        return BASE_URL . self::$url.'/';
    }


}