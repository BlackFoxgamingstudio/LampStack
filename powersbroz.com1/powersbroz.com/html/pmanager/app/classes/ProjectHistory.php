<?php
/**
 * @package Entity Project History Class and Methods
 * @version 1.0
 * @date 12 June 2015
 * @author Travis Coats, Zen Perfect Design
 * @location app/classes/
 *
 */

class ProjectHistory extends Log {

    use Finder, Access, HTML;

    private     $id;
    protected   $user;
    protected   $project;
    protected   $action;
    protected   $description;
    protected   $date;

    private static $table = "project_history";

    public function __construct($record) {
        $this->id 	        = $record['id'];
        $this->user         = User::find('id', $record['userid']);
        $this->project      = Project::find('id', $record['projectid']);
        $this->action       = $record['action'];
        $this->description  = $record['description'];
        $this->date         = new DateTime($record['created']);
    }

    public static function log_action(User $user, $projectid, $action, $message) {
        global $con;
        $now        = new DateTime('Now');
        $action     = $con->secure($action);
        $message    = $con->secure($message);
        $sql = "INSERT INTO project_history (userid, projectid, action, description, created) VALUES (".$user->id().", ".$projectid.", '".$action."', '".$message."', '".$now->format('Y-m-d H:i:s')."')";
        $con->gate->query($sql);
    }

    public function user() {
        if ($this->user) {
            return $this->user->name();
        } else {
            return 'User account deleted';
        }
    }

    public function project() {
        return $this->project;
    }

    public function description() {
        return $this->description;
    }

    public function created() {
        return $this->date->format('F d, Y ').' @ '. readable_time($this->date);
    }

    public static function get_history($projectid) {
        return self::find('sql', "SELECT * FROM project_history WHERE projectid =".$projectid." ORDER BY created DESC");
    }


}