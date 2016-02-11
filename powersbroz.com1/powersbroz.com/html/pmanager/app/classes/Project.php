<?php
/**
 * @package Entity Project Class and Methods
 * @version 1.0
 * @date 09 November 2014
 * @author Travis Coats, Zen Perfect Design
 * @location app/classes/
 *
 */

class Project {

    use Finder, Access, HTML;

    private     $id;
    public      $owner;
    protected   $pname;
    protected   $desc;
    public      $image;
    protected   $startdate;
    protected   $enddate;
    protected   $active;
    protected   $complete;
    private     $created;
    private     $updated;

    // Helper containers for other objects
    public     $stages  = false;
    public     $tasks   = false;
    public     $team    = false;


    private static $table = 'projects';

    // Constructor functions
    public function __construct($record) {
        $this->id 	        = $record['id'];
        if (!empty($record['owner'])) {
            $this->owner    = User::find('id', $record['owner']);
        } else {
            $this->owner    = false;
        }
        $this->pname        = $record['pname'];
        $this->desc         = $record['description'];
        $this->image        = $record['image'];
        $this->startdate    = new DateTime($record['startdate']);
        $this->enddate      = new DateTime($record['enddate']);
        if ($record['active'] == '1') {
            $this->active   = true;
        } else {
            $this->active   = false;
        }
        if ($record['complete'] == '1') {
            $this->complete   = true;
        } else {
            $this->complete   = false;
        }
        $this->created      = new DateTime($record['created']);
        $this->updated      = new DateTime($record['updated']);
    }

    public function name() {
        return htmlspecialchars($this->pname);
    }

    public function description() {
        return htmlspecialchars($this->desc);
    }

    public function raw_startdate() {
        return $this->startdate;
    }

    public function startdate($format = 'F d, Y') {
        return $this->startdate->format($format);
    }

    public function raw_enddate() {
        return $this->enddate;
    }

    public function enddate($format = 'F d, Y') {
        return $this->enddate->format($format);
    }

    public function image() {
        if ($this->image != '') {
            if (file_exists(PROJECT_IMAGES_PATH.$this->image)) {
                return PROJECT_IMAGES.$this->image;
            } else {
                return PROJECT_IMAGES.'default.png';
            }
        } else {
            return PROJECT_IMAGES.'default.png';
        }
    }

    public function is_complete() {
        if (!$this->active && $this->complete) {
            return true;
        }
        return false;
    }

    public function owner() {
        return $this->owner;
    }

    public function has_owner() {
        if ($this->owner) {
            return true;
        }
        return false;
    }

    public function is_owner($id) {
        if ($this->has_owner()) {

            if ($this->owner()->id() == $id) {
                return true;
            }

        }
        return false;
    }

    public function members() {
        global $con;

        // Return an array of members as Objects
        $query = gen_query("SELECT * FROM project_members WHERE projectid =".$this->id);

        if ($query['count'] > 0) {
            $array = array();
            for ($m = 0; $m < $query['count'];$m++) {
                $member = $query['rows'][$m];
                if ($member['is_group'] == 1) {
                    $group = Group::find('id', $member['memberid']);
                    if (!$group) {
                        $con->gate->query("DELETE FROM project_members WHERE id = ".$member['id']);
                    } else {
                        $array[] = $group;
                    }
                }
                if ($member['is_group'] == 0) {
                    $user = User::find('id', $member['memberid']);
                    if (!$user) {
                        $con->gate->query("DELETE FROM project_members WHERE id = ".$member['id']);
                    } else {
                        $array[] = $user;
                    }
                }
            }

            return array_values($array);
        } else {
            return false;
        }

    }

    public function get_team($string = 'all') {
        $team = array();
        if ($string == 'users') {
            $members = gen_query("SELECT memberid FROM project_members WHERE projectid = ".$this->id.' AND is_group = 0');
            if ($members['count'] > 0) {
                for ($t = 0;$t < $members['count'];$t++) {
                    $team[] = User::find('id', $members['rows'][$t]['memberid']);
                }
                return $team;
            } else {
                return false;
            }
        } elseif ($string == 'groups') {
            $members = gen_query("SELECT memberid FROM project_members WHERE projectid = ".$this->id.' AND is_group = 1');
            if ($members['count'] > 0) {
                for ($t = 0;$t < $members['count'];$t++) {
                    $team[] = Group::find('id', $members['rows'][$t]['memberid']);
                }
                return $team;
            } else {
                return false;
            }
        } else {
            $members = gen_query("SELECT memberid, is_group FROM project_members WHERE projectid = ".$this->id.'');
            if ($members['count'] > 0) {
                for ($t = 0;$t < $members['count'];$t++) {
                    if ($members['rows'][$t]['is_group'] == '0') {
                        $team[] = User::find('id', $members['rows'][$t]['memberid']);
                    } else {
                        $team[] = Group::find('id', $members['rows'][$t]['memberid']);
                    }
                }
                return $team;
            } else {
                return false;
            }
        }

    }

    public function get_team_unique() {
        $individuals = array();
        // Grab the users who are individually assigned already
        $members = gen_query("SELECT memberid FROM project_members WHERE projectid = ".$this->id.' AND is_group = 0');
        if ($members['count'] > 0) {

            for ($m = 0; $m < $members['count'];$m++) {
                $individuals[] = User::find('id', $members['rows'][$m]['memberid']);
            }

        }
        unset($members);
        // Grab groups for looping and create an empty array
        // to store the users from each group assigned
        $groups = gen_query("SELECT memberid FROM project_members WHERE projectid = ".$this->id." AND is_group = 1");
        if ($groups['count'] > 0) {

            for ($i = 0;$i < $groups['count'];$i++) {
                // Actual group members as gen query
                $gm = gen_query("SELECT userid FROM group_members WHERE groupid = ".$groups['rows'][$i]['memberid']);
                // Test to see if there are any group members and
                // assign them to the individuals array
                if ($gm['count'] > 0) {

                    for ($i = 0; $i < $gm['count'];$i++) {
                        $individuals[] = User::find('id', $gm['rows'][$i]['userid']);
                    }
                }
            }

        }
        unset($groups);
        //var_dump($individuals);exit;
        return $individuals;

    }

    public function is_user_assigned($userid) {
        // Is user directly assigned?
        $query = gen_query("SELECT * FROM project_members WHERE memberid = ".$userid. " AND projectid = ".$this->id. " AND is_group = 0");

        if ($query['count'] > 0) {
            return true;
        }
        // Is user assigned to group that is assigned?
        $user = User::find('id', $userid);
        $groups = $user->get_assigned_groups();
        if ($groups) {
            for ($i = 0; $i < count($groups);$i++) {
                $group = Group::find('id', $groups[$i]['groupid']);
                if ($group->is_assigned($this->id)) {
                    return true;
                }
            }
        }
        return false;
    }

    // Statistic functions

    public static function total_active() {
        $projects = gen_query("SELECT id FROM projects WHERE active = 1 AND complete = 0");
        if ($projects['count'] > 0) {
            return $projects['count'];
        } else {
            return '0';
        }
    }

    public static function total_complete() {
        $projects = gen_query("SELECT id FROM projects WHERE active = 0 AND complete = 1");
        if ($projects['count'] > 0) {
            return $projects['count'];
        } else {
            return '0';
        }
    }

    public function total_invoices() {
        $invoices = Invoice::find('sql', "SELECT * FROM invoices WHERE iproject = ".$this->id);
        if (!$invoices) {
            return 0;
        } else {
            return count($invoices);
        }
    }

    public function total_hours() {
        $records = gen_query("SELECT * FROM timer_items WHERE tproject = ".$this->id);
        if ($records['count'] > 0) {
            $hours      = 0;
            $minutes    = 0;
            $seconds    = 0;
            for ($i = 0;$i < $records['count'];$i++) {
                $hours      += $records['rows'][$i]['thours'];
                $minutes    += $records['rows'][$i]['tminutes'];
                $seconds    += $records['rows'][$i]['tseconds'];
            }

            $minute_portion = $seconds / 60;
            $minutes        = $minutes + $minute_portion;
            $hour_portion   = $minutes / 60;
            $hours = $hours + $hour_portion;

            return round($hours, 2);

        } else {
            return 0;
        }
    }

    public function has_started() {
        global $now;
        if ($now < $this->startdate) {
            return false;
        } else {
            return true;
        }
    }

    public function has_stages() {
        $stages = $this->get_stages();
        if ($stages) {
            unset($stages);
            return true;
        } else {
            return false;
        }
    }

    public function get_stages() {
        $stages = ProjectStage::find('sql', "SELECT * FROM project_stages WHERE projectid = ".$this->id);
        return $stages;
    }

    public function has_tasks() {
        $tasks = $this->get_tasks();
        if ($tasks['count'] > 0) {
            unset($tasks);
            return true;
        } else {
            return false;
        }
    }

    public function get_tasks() {
        $tasks = ProjectTask::find('sql', "SELECT * FROM project_tasks WHERE projectid = ".$this->id);
        return $tasks;
    }

    public function progress($option = 'time', $display = 'value') {
        global $now;
        switch ($option) {

            case 'time':
                // Math
                $total_days         = $this->enddate->diff($this->startdate)->format('%a');
                $remaining_days     = $this->enddate->diff($now)->format('%a');
                $days_passed        = $total_days - $remaining_days;

                if ($now > $this->enddate) {
                    $percentage = 100;
                } else {
                    $percentage = (($days_passed / $total_days)) * 100;
                }

                if ($display == 'value') {
                    // Give a number back
                    return round($percentage, 2).'%';
                } else {
                    // Display the bar
                    $html  = '<div class="meter">';
                    if ($now > $this->enddate && !$this->complete) {
                        $html .= '<div class="meter-bar-alert" style="width:'.round($percentage, 0).'%;"></div>';
                    } else {
                        $html .= '<div class="meter-bar" style="width:'.round($percentage, 0).'%;"></div>';
                    }
                    $html .= '</div>';
                    if ($now > $this->enddate) {
                        if ($remaining_days == 0) {
                            $html .= '<p class="text-center push-vertical">Project Length: '.$total_days.' days &infin; Due Today!</p>';
                        } else {
                            $html .= '<p class="text-center push-vertical">Project Length: '.$total_days.' days &infin; Overdue: '.$remaining_days.' days</p>';
                        }
                    } else {
                        $html .= '<p class="text-center push-vertical">Project Length: '.$total_days.' days &infin; Remaining: '.$remaining_days.' days</p>';
                    }
                    return $html;
                }
                break;

            case 'tasks':

                break;

        }
    }

    public function is_overdue() {
        global $now;
        if ($this->enddate < $now && !$this->complete) {
            return true;
        } else {
            return false;
        }
    }

    public function is_future() {
        return $this->has_started();
    }

    public function box_class() {
        if ($this->complete) {
            return 'project-box project-box-complete';
        } elseif ($this->is_overdue()) {
            return 'project-box project-box-overdue';
        } elseif ($this->startdate > new DateTime('now')) {
            return 'project-box project-box-future';
        } else {
            return 'project-box';
        }
    }

    public function list_class() {
        if ($this->is_overdue()) {
            return 'alert alert-danger';
        } elseif ($this->complete) {
            return 'alert alert-success';
        } else {
            return '';
        }
    }

    public function due_notice_class() {
        // Comparator
        $today = new DateTime('Now');
        // Empty class string
        $class = '';
        if ($this->enddate < $today) {
            // Project is overdue
            $class .= 'noticeOverdue ';
        }
        if ($this->enddate->diff($today)->format('%a') <= 30 && $this->enddate > $today) {
            $class .= 'noticeThirty ';
        }
        if ($this->enddate->diff($today)->format('%a') <= 60 && $this->enddate > $today) {
            $class .= 'noticeSixty';
        }
        return $class;
    }

    public static function user_project_list(User $user) {
        $userID = $user->id();
        /*
        $projects = Project::find('sql', "SELECT projects.id, projects.owner, projects.pname, projects.description, projects.image, projects.startdate, projects.enddate, projects.active, projects.complete, projects.created, projects.updated FROM projects LEFT OUTER JOIN project_members ON projects.id = project_members.projectid WHERE project_members.isgroup = 0 AND project_members.memberid = ".$userID);
        */
        $projects = Project::find('sql', "SELECT * FROM projects WHERE complete = 0");
        $array = array();
        foreach ($projects as $p) {
            if ($p->is_user_assigned($userID)) {
                $array[] = $p;
            }
        }
        return $array;
    }

}