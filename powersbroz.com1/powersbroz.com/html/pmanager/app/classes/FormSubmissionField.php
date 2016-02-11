<?php
/**
 * @package Entity Form Submission Field Class
 * @version 1.0
 * @date 27 June 2015
 * @author Travis Coats, Zen Perfect Design
 * @location app/classes/
 *
 */

class FormSubmissionField {

    use Finder, Access, HTML;

    private     $id;
    private     $submission_id;
    protected   $parent_form;
    protected   $parent_field;
    protected   $value;

    private     $created;
    private     $updated;

    private static $table = 'form_submission_fields';


    public function __construct($record) {

        $this->id               = $record['id'];
        $this->submission_id    = $record['submission_id'];
        $this->parent_form      = $record['parent_form'];
        $this->parent_field     = $record['parent_field'];
        $this->value            = $record['submission_value'];
        $this->created          = new DateTime($record['created']);
        $this->updated          = new DateTime($record['updated']);
    }

    public function unique_id() {
        return $this->submission_id;
    }

    public function value() {
        return $this->value;
    }

    public function created() {
        return $this->created;
    }

    public function form() {
        return Form::find('id', $this->parent_form);
    }

    public function field() {
        return FormField::find('id', $this->parent_field);
    }

    public function type() {
        return $this->field()->type();
    }

}