<?php
/**
 * @package Entity Project Stage Task Class and Methods
 * @version 1.0
 * @date 13 December 2014
 * @author Travis Coats, Zen Perfect Design
 * @location app/classes/
 *
 */

class StageTask {

    use Finder, Access, HTML;

    private     $id;
    public      $stage;
    protected   $stagetaskname;
    protected   $description;
    protected   $duedate;
    protected   $complete;
    public      $assignedto     = array();
    protected   $attachments;
    private     $created;
    private     $updated;

    private static $table = 'project_staged_tasks';

    // Constructor functions
    public function __construct($record) {
        global $con;
        $this->id 	            = $record['id'];
        $this->stage            = ProjectStage::find('id', $record['stageid']);
        $this->stagetaskname    = $record['stagetaskname'];
        $this->description      = $record['description'];
        if ($record['duedate'] == '0000-00-00') {
            $this->duedate  = false;
        } else {
            $this->duedate  = new DateTime($record['duedate']);
        }
        if ($record['complete'] == '0') {
            $this->complete = false;
        } else {
            $this->complete = true;
        }

        $this->assignedto = $this->get_assigned_users();

        $this->build_attachments();
        $this->created          = new DateTime($record['created']);
        $this->updated          = new DateTime($record['updated']);

    }

    public function name() {
        return htmlspecialchars($this->stagetaskname);
    }

    public function description() {
        return nl2br(htmlspecialchars($this->description));
    }

    public function stage() {
        return $this->stage;
    }

    public function is_complete() {
        return $this->complete;
    }

    public function is_unassigned() {
        if ($this->assignedto) {
            return true;
        }
        return false;
    }

    public static function assign_user($stagetaskid, $userid) {
        global $con;
        global $now;
        $con->gate->query(
            "INSERT INTO project_staged_task_assignments (stagetaskid, userid, created, updated)
            VALUES (".$stagetaskid.", ".$userid.", '".$now->format('Y-m-d H:i:s')."', '".$now->format('Y-m-d H:i:s')."')
            "
        );
    }

    public static function unassign_user($stagetaskid, $userid) {
        global $con;
        $con->gate->query(
            "DELETE FROM project_staged_task_assignments WHERE stagetaskid = ".$stagetaskid." AND userid = ".$userid
        );
    }

    public function get_assignments() {
        if (!empty($this->assignedto)) {
            return $this->assignedto;
        } else {
            return false;
        }
    }

    public function get_assigned_users() {
        $query = gen_query("SELECT * FROM project_staged_task_assignments WHERE stagetaskid = ".$this->id());
        $assignedto = array();
        for ($i = 0;$i < $query['count'];$i++) {
            $assignedto[] = User::find('id', $query['rows'][$i]['userid']);
        }
        return $assignedto;
    }

    public function get_assigned_userIDs() {
        $query = gen_query("SELECT * FROM project_staged_task_assignments WHERE stagetaskid = ".$this->id());
        $assignedto = array();
        for ($i = 0;$i < $query['count'];$i++) {
            $assignedto[] = $query['rows'][$i]['userid'];
        }
        return $assignedto;
    }

    public function attachments() {
        return $this->attachments;
    }

    public function build_attachments() {

        $attachments = StageTaskAttachment::find('sql', "SELECT * FROM project_staged_task_attachments WHERE stage_task_id = ".$this->id);

        if (!$attachments) {
            $this->attachments = false;
        } else {
            foreach ($attachments as $attached) {
                $this->attachments[] = File::find('id', $attached->file());
            }
        }

        /* Old Logic
        if ($string == '') {
            $this->attachments = array();
        } else {
            $data           = explode(',', $string);
            $removed        = array();
            for ($d = 0; $d < count($data);$d++) {
                $filecheck  = File::find('id', $data[$d]);
                if ($filecheck) {
                    $this->attachments[$d] = $filecheck;
                } else {
                    $removed[] = $data[$d];
                }
            }
            $this->remove_missing_attachments($removed, $string);
        }
        */
    }

    public function has_attachments() {
        if (!empty($this->attachments)) {
            return true;
        }
        return false;
    }

    public function attachment_array() {
        $compare = array();
        for ($i = 0; $i < count($this->attachments);$i++) {
            $compare[] = $this->attachments[$i]->id();
        }
        return $compare;
    }

    public function is_attached($id) {
        if ($this->has_attachments()) {
            // Compare file ids
            if (in_array($id, $this->attachment_array())) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function attachment_count() {
        return count($this->attachments);
    }

    public function has_notes() {
        $notes = StageTaskNote::find('sql', "SELECT * FROM project_staged_task_notes WHERE taskid = ".$this->id);
        if ($notes) {
            unset($notes);
            return true;
        } else {
            unset($notes);
            return false;
        }
    }

    public function get_notes() {
        $notes = StageTaskNote::find('sql', "SELECT * FROM project_staged_task_notes WHERE taskid = ".$this->id." ORDER BY created DESC");
        if ($notes) {
            return $notes;
        } else {
            unset($notes);
            return false;
        }
    }

    public function has_duedate() {
        if ($this->duedate) {
            return true;
        } else {
            return false;
        }
    }

    public function due() {
        if ($this->has_duedate()) {
            return $this->duedate->format('F d, Y');
        } else {
            return 'No Due Date';
        }
    }

    public function due_date_progress() {

    }

    public function get_parent_project() {
        return $this->stage()->project();
    }

    public static function is_assigned(StageTask $task, $userID) {
        $assignedUsers = $task->get_assigned_users();
        if (empty($assignedUsers)) {
            return false;
        } else {
            return in_array($userID, $assignedUsers);
        }
        return false;
    }

}