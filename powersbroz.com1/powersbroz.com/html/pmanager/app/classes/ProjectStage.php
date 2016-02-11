<?php
/**
 * @package Entity Project Stage Class and Methods
 * @version 1.0
 * @date 13 December 2014
 * @author Travis Coats, Zen Perfect Design
 * @location app/classes/
 *
 */

class ProjectStage {

    use Finder, Access, HTML;

    private     $id;
    public      $project;
    protected   $stagename;
    protected   $description;
    protected   $stagebrief;
    protected   $duedate;
    protected   $complete;
    private     $created;
    private     $updated;

    public      $tasks;

    private static $table = 'project_stages';

    // Constructor functions
    public function __construct($record) {
        $this->id 	        = $record['id'];
        $this->project      = Project::find('id', $record['projectid']);
        $this->stagename    = $record['stagename'];
        $this->description  = $record['description'];
        $this->stagebrief   = $record['stagebrief'];
        if ($record['duedate'] == '0000-00-00') {
            $this->duedate = false;
        } else {
            $this->duedate = new DateTime($record['duedate']);
        }
        if ($record['complete'] == '0') {
            $this->complete = false;
        } else {
            $this->complete = true;
        }
        $this->created = new DateTime($record['created']);
        $this->updated = new DateTime($record['updated']);


    }

    public function name() {
        return htmlspecialchars($this->stagename);
    }

    public function description() {
        return htmlspecialchars($this->description);
    }

    public function has_tasks() {
        $tasks = $this->get_tasks();
        if ($tasks) {
            unset($tasks);
            return true;
        } else {
            unset($tasks);
            return false;
        }
    }

    public function get_tasks() {
        $tasks = StageTask::find('sql', "SELECT * FROM project_staged_tasks WHERE stageid = ".$this->id()." ORDER BY created ASC");
        return $tasks;
    }

    public function number_of_tasks() {
        $tasks = gen_query("SELECT COUNT(id) AS count FROM project_staged_tasks WHERE stageid = ".$this->id()."");
        return $tasks['rows'][0]['count'];
    }

    public function has_brief() {
        if ($this->stagebrief == '') {
            return false;
        } else {
            return true;
        }
    }

    public function get_brief() {
        return $this->stagebrief;
    }

    public function is_complete() {
        return $this->complete;
    }

    public function completion() {
        $tasks = $this->get_tasks();
        if (count($tasks) > 0) {
            $total = count($tasks);
        }
        $completed = gen_query("SELECT * FROM project_staged_tasks WHERE stageid = ".$this->id." AND complete = 1");
        if ($completed['count'] == 0) {
            return 0;
        } else {
            return round(($completed['count'] / $total) * 100, 2);
        }
    }

    public function project() {
        return $this->project;
    }

    public function has_task_assignments() {
        $tasks = $this->get_tasks();
        if ($tasks) {
            for ($t = 0; $t < count($tasks);$t++) {
                $assignments = $tasks[$t]->get_assignments();
                if ($assignments) {
                    return true;
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
    }

    public function task_progress() {

    }

    public function assignment_workload() {
        // Output DATA
        $tasks = $this->get_tasks();
        $users = array();
        // TODO: This is returning only the number which a user is assigned to out of total tasks and not taking into account unassigned tasks
        if ($tasks) {
            for ($t = 0; $t < count($tasks);$t++) {
                $assignments = $tasks[$t]->get_assignments();
                if (empty($assignments)) {
                    // No one is assigned to this task
                } else {
                    // Someone is assigned to this task
                    for ($a = 0;$a < count($assignments);$a++) {

                        $user   = $assignments[$a];
                        //var_dump($user->id());exit;
                        $id     = $user->id();
                        $name   = $user->name();

                        if (isset($users[$id])) {
                            $users[$id]['count']++;
                            $users[$id]['name'] = $name;
                        } else {
                            $users[$id]['count'] = 1;
                            $users[$id]['name'] = $name;
                        }
                        $users[$id]['stat'] = round(($users[$id]['count'] / count($tasks)) * 100, 2);
                    }
                }

            }
            return $users;
        } else {
            return false;
        }
    }

    public function has_duedate() {
        return $this->duedate;
    }

    public function duedate_progress() {
        if ($this->has_duedate()) {

        } else {
            return false;
        }
    }

}