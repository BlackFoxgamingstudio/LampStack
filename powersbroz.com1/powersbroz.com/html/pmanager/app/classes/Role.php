<?php
/**
 * @package Entity Role Class and Methods
 * @version 1.0
 * @date 09 November 2014
 * @author Travis Coats, Zen Perfect Design
 * @location app/classes/
 */

class Role {

    // Basic [Basic], System Actions [SysX], User Actions [UX], Group Actions [GX], Group Access Actions [GaX]
    // Project Actions [PX], Project Access Actions [PaX], Invoicing [InvX], File System Operations [FSX], Visibility Scope and Communication [VisX]

    use Finder, Access, HTML;

    public      $id;
    public      $name;
    private     $perms;
    protected   $desc;
    protected   $custom;
    protected   $classification;

    protected   $created;
    protected   $updated;

    private static $table = 'user_roles';

    // Constructor functions
    public function __construct($record) {
        $this->id       = $record['id'];
        $this->name     = $record['rolename'];
        $this->desc 	= $record['description'];
        $this->perms 	= self::build_permissions($record['perms']);
        $this->desc 	= $record['description'];
        if ($record['custom'] == 1) {
            $this->custom   = true;
        } else {
            $this->custom   = false;
        }
        $this->classification   = $record['classification'];
        $this->created  = new DateTime($record['created']);
        $this->updated  = new DateTime($record['updated']);
    }

    public function name() {
        return $this->name;
    }

    public function description() {
        return htmlspecialchars($this->desc);
    }

    public function is_custom() {
        return $this->custom;
    }

    public function is_staff() {
        if ($this->classification == 1) {
            return true;
        } else {
            return false;
        }
    }

    public function is_contractor() {
        if ($this->classification == 2) {
            return true;
        } else {
            return false;
        }
    }

    public function classification($flag = '') {
        switch($this->classification) {

            case '1':
                return ($flag == 'format') ? '<span class="label label-primary"><i class="fa fa-user"></i> Staff</span>' : 'Staff';
                break;
            case '2':
                return ($flag == 'format') ? '<span class="label label-danger"><i class="fa fa-briefcase"></i> Contractor</span>' : 'Contractor';
                break;
            case '3':
                return ($flag == 'format') ? '<span class="label label-success"><i class="fa fa-money"></i> Client</span>' : 'Client';
                break;
            default:
                return ($flag == 'format') ? '<span class="label label-default"><i class="fa fa-question-circle"></i> Unclassified</span>' : 'Unclassified';
        }
    }

    public function is_client() {
        if ($this->classification == 3) {
            return true;
        } else {
            return false;
        }
    }

    public function file_limit($format = '') {

        if ($format == 'readable') {
            return $this->perms['FSX']['maxSpace'] .' ' . $this->perms['FSX']['maxSpaceUnit'];
        }

        if ($format == 'plain') {
            return convert_to_bytes($this->perms['FSX']['maxSpace'], $this->perms['FSX']['maxSpaceUnit'], 'plain');
        }

        return convert_to_bytes($this->perms['FSX']['maxSpace'], $this->perms['FSX']['maxSpaceUnit']);
    }

    public static function total_custom() {
        $roles = gen_query("SELECT id FROM ".self::$table." WHERE custom = 1");
        return $roles['count'];
    }

    public static function total_staff() {
        $roles = gen_query("SELECT id FROM ".self::$table." WHERE classification = 1");
        return $roles['count'];
    }

    public static function total_contractors() {
        $roles = gen_query("SELECT id FROM ".self::$table." WHERE classification = 2");
        return $roles['count'];
    }

    public static function total_clients() {
        $roles = gen_query("SELECT id FROM ".self::$table." WHERE classification = 3");
        return $roles['count'];
    }

    /*
     * Permission Settings
     * Do not edit of this code!
     */

    public function can($category, $action) {

        switch($action) {

            case 'create':

                switch($category) {

                    case 'user':
                        if ($this->perms['UX']['create'] == 1) {
                            return true;
                        } else {
                            return false;
                        }
                        break;

                    case 'group':
                        if ($this->perms['GX']['create'] == 1) {
                            return true;
                        } else {
                            return false;
                        }
                        break;

                    case 'project':
                        if ($this->perms['PX']['create'] == 1) {
                            return true;
                        } else {
                            return false;
                        }
                        break;

                    case 'stage':
                        if ($this->perms['PX']['createStage'] == 1) {
                            return true;
                        } else {
                            return false;
                        }
                        break;

                    case 'stage-task':
                        if ($this->perms['PX']['createStageTask'] == 1) {
                            return true;
                        } else {
                            return false;
                        }
                        break;

                    case 'invoice':
                        if ($this->perms['InvX']['create'] == 1) {
                            return true;
                        } else {
                            return false;
                        }
                        break;

                    case 'file':
                        if ($this->perms['FSX']['upload'] == 1) {
                            return true;
                        } else {
                            return false;
                        }
                        break;

                    default:
                        return false;

                }

                break;

            case 'edit':

                switch($category) {
                    // TODO: Make sure group and project owners have override in method
                    case 'user':
                        if ($this->perms['UX']['edit'] == 1) {
                            return true;
                        } else {
                            return false;
                        }
                        break;

                    case 'group':
                        if ($this->perms['GX']['edit'] == 1) {
                            return true;
                        } else {
                            return false;
                        }
                        break;

                    case 'project':
                        if ($this->perms['PX']['edit'] == 1) {
                            return true;
                        } else {
                            return false;
                        }
                        break;

                    case 'projectStage':

                        break;

                    case 'invoice':
                        if ($this->perms['InvX']['edit'] == 1) {
                            return true;
                        } else {
                            return false;
                        }
                        break;

                    default:
                        return false;

                }

                break;

            case 'delete':

                switch($category) {

                    case 'user':
                        if ($this->perms['UX']['delete'] == 1) {
                            return true;
                        } else {
                            return false;
                        }
                        break;

                    case 'group':
                        if ($this->perms['GX']['delete'] == 1) {
                            return true;
                        } else {
                            return false;
                        }
                        break;

                    case 'project':
                        if ($this->perms['PX']['delete'] == 1) {
                            return true;
                        } else {
                            return false;
                        }
                        break;

                    case 'invoice':
                        if ($this->perms['InvX']['delete'] == 1) {
                            return true;
                        } else {
                            return false;
                        }
                        break;

                    default:
                        return false;

                }

                break;

            case 'assign':

                switch ($category) {

                    case 'project':
                        if ($this->perms['UX']['assignProject'] == 1) {
                            return true;
                        } else {
                            return false;
                        }
                        break;

                    case 'group':
                        if ($this->perms['UX']['assignGroup'] == 1) {
                            return true;
                        } else {
                            return false;
                        }
                        break;

                    case 'user':
                        if ($this->perms['UX']['assignGroup'] == 1 || $this->perms['UX']['assignProject'] == 1) {
                            return true;
                        } else {
                            return false;
                        }
                        break;

                    default:
                        return false;

                }

                break;

            case 'view':

                switch ($category) {

                    case 'user':
                        if ($this->perms['VisX']['addressBook'] == 'none') {
                            return false;
                        } else {
                            return true;
                        }
                        break;

                    default:
                        return false;

                }

                break;

            default:
                return false;

        }


    }

    public function can_access($objectid, $object = 'project') {
        global $current_user;
        switch($object) {

            case 'user':
                if ($this->perms['VisX']['addressBook'] == 'none') {
                    return false;
                } else {
                    $user = User::find('id', $objectid);
                    if (!$user) {
                        return false;
                    }
                    switch ($this->perms['VisX']['addressBook']) {

                        case 'full':
                            return true;
                            break;

                        case 'staff':
                            return ($user->role()->classification == 1) ? true : false;
                            break;

                        case 'assigned':

                            break;

                        default:
                            return false;

                    }

                }
                break;

            case 'project':
                if ($this->perms['PaX']['access'] == 'all') {
                    return true;
                } elseif($this->perms['PaX']['access'] == 'assigned') {

                    // Is assigned?
                    $project = Project::find('id', $objectid);
                    if ($project->is_user_assigned($current_user->id())) {
                        return true;
                    } else {
                        // Check to see if user is a project lead
                        if (!$project->owner()) {
                            return false;
                        } elseif($project->owner()->id() == $current_user->id()) {
                            return true;
                        } else {
                            return false;
                        }

                    }
                } else {
                    return false;
                }
                break;

            case 'group':
                if ($this->perms['GaX']['access'] == 'all') {
                    return true;
                } elseif ($this->perms['GaX']['access'] == 'assigned') {
                    // Is assigned?
                    $group = Group::find('id', $objectid);
                    if (Group::is_member($objectid, $current_user->id())) {
                        return true;
                    } else {
                        if ($group->has_owner()) {
                            if ($group->is_owner($current_user->id())) {
                                return true;
                            }
                        }
                        return false;
                    }
                } else {
                    return false;
                }
                break;

            case 'system':
                switch ($objectid) {

                    case 'roles':
                        if ($this->perms['SysX']['roles'] == 1) {
                            return true;
                        } else {
                            return false;
                        }
                        break;

                    case 'wages':
                        if ($this->perms['SysX']['wages'] == 1) {
                            return true;
                        } else {
                            return false;
                        }
                        break;

                    case 'settings':
                        if ($this->perms['SysX']['settings'] == 1) {
                            return true;
                        } else {
                            return false;
                        }
                        break;

                    case 'docs':
                        if ($this->perms['SysX']['docs'] == 1) {
                            return true;
                        } else {
                            return false;
                        }
                        break;

                    case 'activity':
                        if ($this->perms['SysX']['activity'] == 1) {
                            return true;
                        } else {
                            return false;
                        }
                        break;

                    default:
                        return false;

                }
                break;



        }
    }

    public function address_book() {

    }

    public static function build_permissions($string = '') {

        if ($string == '') {
            return false;
        }

        $array       = explode(',', $string);
        $permissions = array();

        foreach($array as $key => $value) {

            $unprocessed     = trim(trim($value, ']'), '[');

            $category_end    = strpos($unprocessed, '(');
            $category        = substr($unprocessed, 0, $category_end);

            $category_val    = rtrim(str_replace("$category(", '', $unprocessed), ')');
            $category_perms  = explode(';', $category_val);

            $permission_values = array();

            foreach($category_perms as $perm) {
                $temp = explode('-', $perm);
                $permission_values[$temp[0]] = $temp[1];
            }

            $permissions[$category] = $permission_values;

        }

        return $permissions;
    }

    public function show_permissions($format = true) {
        if ($format) {
            // Build System Actions
            $html = '<h2>System Actions</h2>';

            return $html;
        } else {
            return $this->perms;
        }
    }


}