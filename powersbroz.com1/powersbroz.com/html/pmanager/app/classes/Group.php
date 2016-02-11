<?php
/**
 * @package Entity Group Class and Methods
 * @version 1.0
 * @date 11 November 2014
 * @author Travis Coats, Zen Perfect Design
 * @location app/classes/
 *
 */

class Group {

    use Finder, Access, HTML;

    private     $id;
    protected   $gname;
    protected   $description;
    protected   $image;
    protected   $owner = false;

    protected   $members = false;

    private     $created;
    private     $updated;

    private static $table = 'groups';

    // Constructor functions
    public function __construct($record) {
        $this->id 	        = $record['id'];
        $this->gname        = $record['gname'];
        $this->description  = $record['description'];
        $this->image        = $record['image'];
        if ($record['owner'] == 0) {
            $this->owner = false;
        } else {
            $this->owner = User::find('id', $record['owner']);
        }
        $this->set_members();
        $this->created      = new DateTime($record['created']);
        $this->updated      = new DateTime($record['updated']);


    }

    public function name() {
        return htmlspecialchars($this->gname);
    }

    public function description() {
        return nl2br(htmlspecialchars($this->description));
    }

    public function image() {
        if ($this->image != '') {
            if (file_exists(ROOT_PATH.'img/groups/'.$this->image)) {
                return GROUP_IMAGES.$this->image;
            } else {
                return GROUP_IMAGES.'default.png';
            }
        } else {
            return GROUP_IMAGES.'default.png';
        }
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

    public function get_owner() {
        return $this->owner();
    }

    public function is_assigned($id) {
        $query = gen_query("SELECT * FROM project_members WHERE memberid = ".$this->id." AND projectid = ".$id." AND is_group = 1");
        if ($query['count'] > 0) {
            return true;
        } else {
            return false;
        }
    }

    protected function set_members() {
        $records = gen_query("SELECT * FROM group_members WHERE groupid = ".$this->id.' ORDER BY created DESC');
        if ($records['count'] > 0) {
            for ($i = 0;$i < $records['count'];$i++) {
                $this->members[$i]['user']      = User::find('id', $records['rows'][$i]['userid']);
                if (!$this->members[$i]['user']) {
                    gen_query("DELETE FROM group_members WHERE id = ".$records['rows'][$i]['id']);
                    unset($this->members[$i]['user']);
                    continue;
                }
                $this->members[$i]['created']   = new DateTime($records['rows'][$i]['created']);
                $this->members[$i]['updated']   = new DateTime($records['rows'][$i]['updated']);
            }
        }
    }

    public function members() {
        return $this->members;
    }

    // Statistic functions

    public function has_members() {
        return $this->members;
    }

    public function total_members() {
        if ($this->members) {
            return count($this->members);
        } else {
            return '0';
        }
    }

    public function total_days() {
        global $now;
        $total_days         = $this->created->diff($now)->format('%a');
        return $total_days;
    }

    public static function total_owned() {
        $records = gen_query("SELECT * FROM groups WHERE NOT owner = 0");
        if ($records['count'] > 0) {
            return $records['count'];
        } else {
            return 0;
        }
    }

    public static function is_member($groupid, $userid) {
        $query = gen_query("SELECT * FROM group_members WHERE userid = ".$userid." AND groupid = ".$groupid);
        if ($query['count'] > 0) {
            return true;
        } else {
            return false;
        }
    }

    public static function user_group_list(User $user) {
        $userID = $user->id();
        $groups = Group::find('sql', "SELECT * FROM groups");
        $array = array();
        foreach ($groups as $g) {
            if (static::is_member($g->id(), $userID)) {
                $array[] = $g;
            }
        }
        return $array;
    }



}