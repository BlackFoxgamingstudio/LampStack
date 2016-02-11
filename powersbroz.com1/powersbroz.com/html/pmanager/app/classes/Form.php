<?php
/**
 * @package Entity Form Class
 * @version 1.0
 * @date 20 June 2015
 * @author Travis Coats, Zen Perfect Design
 * @location app/classes/
 *
 */

class Form {

    use Finder, Access, HTML, HasDatesTrait, HasDescriptorsTrait;

    private     $id;
    protected   $name;
    protected   $slug;
    protected   $description;
    protected   $published      = false;
    private     $created;
    private     $updated;

    private     $fields         = array();
    private     $submissions    = array();

    private static $table = 'forms';

    public function __construct($record) {

        $this->id           = $record['id'];
        $this->name         = $record['name'];
        $this->slug         = $record['slug'];
        $this->description  = $record['description'];
        $this->created      = new DateTime($record['created']);
        $this->updated      = new DateTime($record['updated']);

        $this->fields       = FormField::find('sql', "SELECT * FROM form_fields WHERE field_parent = ".$record['id']." ORDER BY field_position ASC");
        $this->submissions  = FormSubmission::find('sql', "SELECT * FROM form_submissions WHERE parent_form = ".$this->id);

    }

    public function slug() {
        return $this->slug;
    }

    public function created() {
        return $this->created;
    }

    public function updated() {
        return $this->updated;
    }

    public function is_published() {
        return $this->published;
    }

    public function description() {
        return nl2br(htmlspecialchars($this->description));
    }

    public function has_fields() {
        if (!empty($this->fields)) {
            return true;
        }
        return false;
    }

    public function get_fields() {
        return $this->fields;
    }

    public function total_fields() {
        return count($this->fields);
    }

    public function get_submissions() {
        return $this->submissions;
    }

    public function total_submissions() {
        return count($this->submissions);
    }

    public function has_file_input() {
        foreach ($this->fields as $field) {
            if ($field->type() == 'input-file') {
                return true;
            }
        }
        return false;
    }

}