<?php
/**
 * @package Entity Project Stage Task Note Class and Methods
 * @version 1.0
 * @date 13 December 2014
 * @author Travis Coats, Zen Perfect Design
 * @location app/classes/
 *
 */

class StageTaskNote {

    use Finder, Access, HTML;

    private     $id;
    public      $taskid;
    protected   $notesubject;
    protected   $notebody;
    public      $user;
    private     $created;
    private     $updated;

    private static $table = 'project_staged_task_notes';

    // Constructor functions
    public function __construct($record) {
        $this->id 	            = $record['id'];
        $this->taskid           = StageTask::find('id', $record['taskid']);
        $this->notesubject      = $record['notesubject'];
        $this->notebody         = $record['notebody'];
        $this->user             = User::find('id', $record['userid']);
        $this->created          = new DateTime($record['created']);
        $this->updated          = new DateTime($record['updated']);

    }

    public function name() {
        return htmlspecialchars($this->notesubject);
    }

    public function description() {
        return nl2br(htmlspecialchars($this->notebody));
    }

    public function date_created() {
        return $this->created;
    }

}