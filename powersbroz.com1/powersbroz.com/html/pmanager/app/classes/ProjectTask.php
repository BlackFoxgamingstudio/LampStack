<?php
/**
 * @package Entity Project General Task Class and Methods
 * @version 1.0
 * @date 13 December 2014
 * @author Travis Coats, Zen Perfect Design
 * @location app/classes/
 *
 */

class ProjectTask {

    use Finder, Access, HTML;

    private     $id;
    public      $project;
    protected   $taskname;
    protected   $description;
    protected   $duedate;
    protected   $complete;
    private     $created;
    private     $updated;

    private static $table = 'project_staged_tasks';

    // Constructor functions
    public function __construct($record) {
        $this->id 	            = $record['id'];
        $this->project          = Project::find('id', $record['projectid']);
        $this->taskname         = $record['taskname'];
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
        $this->created          = new DateTime($record['created']);
        $this->updated          = new DateTime($record['updated']);

    }

    public function name() {
        return htmlspecialchars($this->taskname);
    }

    public function description() {
        return htmlspecialchars($this->description);
    }

    public function is_complete() {
        return $this->complete;
    }
}