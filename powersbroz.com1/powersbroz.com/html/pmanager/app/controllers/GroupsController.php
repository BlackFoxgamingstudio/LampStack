<?php

class GroupsController {

    use ViewTrait;

    public $groups;

    public $controller  = 'Groups';
    public $method      = '';
    public $parameters  = array();

    public $title       = 'Entity {CC} Group Management';
    public $description = 'Manage and group your users';
    public $keywords    = 'groups, group management';
    private static $url = 'groups';

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
                $this->notFoundPage();
            }
        } else {
            $this->all();
        }
        $this->close();

    }

    public function all() {
        extract($this->pullGlobals());
        switch ($app_preferences->get_order('groups')) {

            case 'alpha':
                $this->groups = $groups = Group::find('sql', "SELECT * FROM groups ORDER BY gname ASC");
                break;

            case 'newest':
                $this->groups = $groups = Group::find('sql', "SELECT * FROM groups ORDER BY created DESC");
                break;

            case 'size':
                $this->groups = $groups = Group::find(
                    'sql',
                    "SELECT groups.id, groups.gname,
                            groups.description, groups.image,
                            groups.owner, groups.created,
                            groups.updated, count(group_members.id) AS members
                            FROM groups
                            LEFT OUTER JOIN group_members
                            ON groups.id=group_members.groupid
                            GROUP BY groups.gname
                            ORDER BY members DESC"
                );
                break;

            default:


        }

        // Filter out groups that user does no have access to
        if ($groups) {
            $access = array();
            foreach ($groups as $group) {

                if ($current_user->role()->can_access($group->id(), 'group')) {
                    $access[] = $group;
                }

            }
            $groups = $access;
        }
        require_once VIEWS . 'all.groups.html.php';
    }

    public function view($id) {
        extract($this->pullGlobals());
        $this->groups = $group = Group::find('id', $id[0]);
        require_once VIEWS . 'single.group.html.php';
    }

    public static function url() {
        return BASE_URL . self::$url.'/';
    }


}