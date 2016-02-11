<?php
/**
 * @package Entity Project Stage Task Attachment Class and Methods
 * @version 1.0
 * @date 06 November 2015
 * @author Travis Coats, Zen Perfect Design
 * @location app/classes/
 *
 */

class StageTaskAttachment {

    use Finder, Access;

    private $id;
    public  $stageTask;
    public  $file;
    public  $created;
    public  $updated;

    private static $table = 'project_staged_task_attachments';

    public function __construct($record) {

        $this->id           = $record['id'];
        $this->stageTask    = $record['stage_task_id'];
        $this->file         = $record['file_id'];
        $this->created      = new DateTime($record['created']);
        $this->updated      = new DateTime($record['updated']);

    }

    public function stageTask() {
        return $this->stageTask; // Returns stage task id
    }

    public function file() {
        return $this->file; // Returns new id
    }


}