<?php
/**
 * @package Entity User Class and Methods
 * @version 1.0
 * @date 06 February 2014
 * @author Travis Coats, Zen Perfect Design
 * @location app/classes/
 *
 */

class User {

    use Finder;
    use Access;
    use HTML;

    // Basic Information
    protected   $id;
    protected   $fname;
    protected   $lname;
    private     $uname;
    private     $pass;
    protected   $email;
    protected   $bio;
    // Social Profile
    protected   $avatar;
    protected   $website;
    protected   $facebook;
    protected   $twitter;
    protected   $googleplus;
    protected   $linkedin;
    protected   $skype;
    protected   $yahoo;
    // Contact Information
    protected   $homephone;
    protected   $cellphone;
    protected   $workphone;
    protected   $fax;
    protected   $addressone;
    protected   $addresstwo;
    protected   $city;
    protected   $state;
    protected   $zip;
    protected   $country;
    // Account Status
    private     $role;
    private     $active;
    private     $stripeSecretKey;
    private     $stripePublishKey;
    private     $paypalEmail;
    private     $created;
    private     $updated;

    private     $fileBaseDir;
    private static $table = 'users';

    // Constructor functions
    public function __construct($record) {
        $this->id 	        = $record['id'];
        $this->fname        = $record['fname'];
        $this->lname 	    = $record['lname'];
        $this->uname        = $record['uname'];
        $this->pass         = $record['pass'];
        $this->email        = $record['email'];
        $this->bio          = $record['bio'];
        $this->avatar       = $record['avatar'];
        $this->website      = $record['website'];
        $this->facebook     = $record['facebook'];
        $this->twitter      = $record['twitter'];
        $this->googleplus   = $record['googleplus'];
        $this->linkedin     = $record['linkedin'];
        $this->skype        = $record['skype'];
        $this->yahoo        = $record['yahoo'];
        $this->homephone    = $record['homephone'];
        $this->cellphone    = $record['cellphone'];
        $this->workphone    = $record['workphone'];
        $this->fax          = $record['fax'];
        $this->addressone   = $record['addressone'];
        $this->addresstwo   = $record['addresstwo'];
        $this->city         = $record['city'];
        $this->state        = $record['state'];
        $this->zip          = $record['zip'];
        $this->country      = $record['country'];
        $this->role         = Role::find('id',$record['role']);
        if ($record['active'] == 0) {
            $this->active = false;
        } else {
            $this->active = true;
        }
        // Payment Settings
        $this->stripeSecretKey  = ($record['stripe_secret_key']) ? $record['stripe_secret_key'] : false;
        $this->stripePublishKey = ($record['stripe_publish_key']) ? $record['stripe_publish_key'] : false;
        $this->paypalEmail      = ($record['paypal_email']) ? $record['paypal_email'] : false;

        $this->created      = new DateTime($record['created']);
        $this->updated      = new DateTime($record['updated']);

        $this->fileBaseDir = FILES_PATH . $this->id().'/';
    }

    public function username() {
        return $this->uname;
    }

    public function password() {
        return $this->pass;
    }

    public function getHash() {
        return $this->passHash;
    }

    public function name() {
        return $this->fname." ".$this->lname;
    }

    public function role() {
        return $this->role;
    }

    public function email() {
        return $this->email;
    }

    public function bio($flag = 'profile') {
        if ($flag == 'profile') {
            return nl2br(htmlspecialchars($this->bio));
        } else {
            return htmlspecialchars($this->bio);
        }
    }

    public function img() {
        if ($this->avatar != '' && file_exists(AVATARS_PATH.$this->avatar)) {
            // Compile src
            return AVATARS.$this->avatar;
        } else {
            return AVATARS.'default.png';
        }
    }

    public function hasStripe() {
        if ($this->stripeSecretKey && $this->stripePublishKey) {
            return true;
        }
        return false;
    }

    public function stripeSecretKey() {
        return $this->stripeSecretKey;
    }

    public function stripePublishKey() {
        return $this->stripePublishKey;
    }

    public function paypalEmail() {
        return $this->paypalEmail;
    }

    public static function authenticate($username, $password, $email = '') {
        global $con;
        global $app_settings;
        // Sanitize user provided input
        $uname      = mysqli_real_escape_string($con->gate, $username);
        $pass       = mysqli_real_escape_string($con->gate, $password);
        $email      = mysqli_real_escape_string($con->gate, $email);
        // Locate account by username
        if ($app_settings->get('login_require_email')) {
            $account    = gen_query("SELECT id FROM users WHERE uname = '".$uname."' AND email = '".$email."'");
        } else {
            $account    = gen_query("SELECT id FROM users WHERE uname = '".$uname."'");
        }


        if ($account['count'] > 0) {
            // Grab record as User object
            $user = self::find('id', $account['rows'][0]['id']);
            // Case-sensitive check
            if ($uname !== $user->username()) {
                return false;
            }
            if ($app_settings->get('login_require_email')) {
                if ($email !== $user->email()) {
                    return false;
                }
            }
            // Check password hash
            if (!password_verify($pass, $user->password())) {
                return false;
            }
            return $user;
        } else {
            return false;
        }
    }

    public function get_assigned_groups() {
        $query = gen_query("SELECT groupid, created FROM group_members WHERE userid = ".$this->id);
        $objects = array();
        if ($query['count'] > 0) {
            for ($i = 0; $i < $query['count'];$i++) {
                $objects[$i] = $query['rows'][$i];
            }
            return $objects;
        } else {
            return false;
        }
    }

    public function get_assigned_projects() {
        // Search direct and indirect assignments and return object of type project
        $projects           = array();
        $projects_direct    = gen_query("SELECT projectid FROM project_members WHERE memberid = ".$this->id." AND is_group = 0");
        $groups             = gen_query("SELECT groupid FROM group_members WHERE userid = ".$this->id);

        // Move projects into array
        if ($projects_direct['count'] > 0) {
            foreach ($projects_direct['rows'] as $p) {
                $projects[$p['projectid']] = '<i class="fa fa-user"></i> Directly Assigned';
            }
        }

        if ($groups['count'] > 0) {
            foreach($groups['rows'] as $g) {
                $indirect = gen_query("SELECT projectid FROM project_members WHERE is_group = 1 AND memberid = ".$g['groupid']);
                if ($indirect['count'] > 0) {
                    foreach ($indirect['rows'] as $i) {
                        $projects[$i['projectid']] = '<i class="fa fa-users"></i> Assigned by Group';
                    }
                }
            }
        }
        $objects = array();
        if (empty($projects)) {
            return false;
        } else {

            //$projects = array_unique($projects);

            foreach ($projects as $key => $value) {

                $objects[] = array('project' => Project::find('id', $key), 'relationship' => $value);

            }
            return $objects;
        }


    }

    // Statistic functions

    public function project_assignment_count() {
        $query = gen_query("SELECT * FROM project_members WHERE memberid = ".$this->id." AND is_group = 0");
        if ($query['count'] > 0) {
            return $query['count'];
        }
        return 0;
    }

    public static function total_active() {
        $users = gen_query("SELECT id FROM users WHERE active = 1");
        if ($users['count'] > 0) {
            return $users['count'];
        } else {
            return '0';
        }
    }

    public static function total_staff() {
        $records = gen_query("SELECT * FROM users INNER JOIN user_roles ON users.role=user_roles.id WHERE user_roles.classification=1");
        return $records['count'];
    }

    public static function total_contractors() {
        $records = gen_query("SELECT * FROM users INNER JOIN user_roles ON users.role=user_roles.id WHERE user_roles.classification=2");
        return $records['count'];
    }

    public static function total_clients() {
        $records = gen_query("SELECT * FROM users INNER JOIN user_roles ON users.role=user_roles.id WHERE user_roles.classification=3");
        return $records['count'];
    }

    public static function total_unassigned() {

    }

    public static function percentage_grouped() {
        $total_users    = self::total();
        $total_grouped  = gen_query("SELECT users.id,COUNT(group_members.userid) AS NumberOfGroup FROM group_members
LEFT JOIN users
ON group_members.userid=users.id
GROUP BY UserID");
        $percentage     = round(($total_grouped['count'] / $total_users) * 100, 2);
        return $percentage;
    }

    public function is_active() {
        return $this->active;
    }

    public function is_assigned($flag = '', $id) {
        switch ($flag) {

            case 'group':
                $query = gen_query("SELECT * FROM group_members WHERE userid = ".$this->id." AND groupid = ".$id);
                if ($query['count'] > 0) {
                    return true;
                } else {
                    return false;
                }
                break;
            case 'project':
                $project = Project::find('id', $id);
                if (!$project) {
                    return false;
                }
                return $project->is_user_assigned($this->id);
                break;
            case 'stagetask':
                $query = gen_query("SELECT * FROM project_staged_task_assignments WHERE stagetaskid = ".$id." AND userid = ".$this->id);
                if ($query['count'] > 0) {
                    return true;
                }
                return false;
                break;
            default:
                return false;

        }

    }

    public function is_lead($objectid, $flag = 'project') {

        switch ($flag) {

            case 'project':
                $project = Project::find('id', $objectid);
                if (!$project) {
                    return false;
                } else {
                    if ($project->has_owner()) {
                        if ($project->owner()->id() == $this->id) {
                            return true;
                        }
                    }
                    return false;
                }

                break;

            case 'group':
                $group = Group::find('id', $objectid);
                if (!$group) {
                    return false;
                } else {
                    if ($group->has_owner()) {
                        if ($group->owner()->id() == $this->id) {
                            return true;
                        }
                    }
                    return false;
                }
                break;

            default:
                return false;

        }

    }

    public function profile_completion() {
        $total = 18;
        $completed = 0;
        if ($this->email != '') {
            $completed++;
        }
        if ($this->avatar != '') {
            $completed++;
        }
        if ($this->website != '') {
            $completed++;
        }
        if ($this->facebook != '') {
            $completed++;
        }
        if ($this->twitter != '') {
            $completed++;
        }
        if ($this->googleplus != '') {
            $completed++;
        }
        if ($this->linkedin != '') {
            $completed++;
        }
        if ($this->skype != '') {
            $completed++;
        }
        if ($this->yahoo != '') {
            $completed++;
        }
        if ($this->homephone != '') {
            $completed++;
        }
        if ($this->cellphone != '') {
            $completed++;
        }
        if ($this->workphone != '') {
            $completed++;
        }
        if ($this->fax != '') {
            $completed++;
        }
        if ($this->addressone !=  '' || $this->addresstwo != '') {
            $completed++;
        }
        if ($this->city != '') {
            $completed++;
        }
        if ($this->state != '') {
            $completed++;
        }
        if ($this->zip != '') {
            $completed++;
        }
        if ($this->country != '') {
            $completed++;
        }

        $completion = ($completed / $total) * 100;
        return round($completion, 2).'%';

    }

    public function member_since($flag = '') {
        global $now;
        if ($flag == '') {
            return $this->created->format('F d, Y');
        } else {
            return $now->diff($this->created)->format('%a') .' days';
        }

    }

    public function has_address() {
        if ($this->addressone !=  '' || $this->addresstwo != '') {
            return true;
        }
        if ($this->city != '') {
            return true;
        }
        if ($this->state != '') {
            return true;
        }
        if ($this->zip != '') {
            return true;
        }
        if ($this->country != '') {
            return true;
        }
        return false;
    }

    // Checks uname and email should be unique
    public static function is_duplicate($string, $field = 'uname') {
        $query = gen_query("SELECT * FROM ".self::$table." WHERE ".$field. " = '".$string."'");
        if ($query['count'] > 0) {
            return true;
        } else {
            return false;
        }
    }

    // HTML Functions

    public function activation_btn_html() {

        $html = '';
        if ($this->active) {
            $html .= '<span class="btn btn-warning toggleActivationUserBtn" user="'.$this->id().'"><i class="fa fa-lock"></i> Deactivate</span>';
        } else {
            $html .= '<span class="btn btn-success toggleActivationUserBtn" user="'.$this->id().'"><i class="fa fa-unlock"></i> Activate</span>';
        }

        return $html;

    }

}