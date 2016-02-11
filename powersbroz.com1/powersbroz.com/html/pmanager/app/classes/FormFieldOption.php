<?php
/**
 * @package Entity Form Field Option Class
 * @version 1.0
 * @date 21 June 2015
 * @author Travis Coats, Zen Perfect Design
 * @location app/classes/
 *
 */

class FormFieldOption {

    use Finder, Access, HTML;

    private     $id;
    private     $option_parent;
    protected   $option_label;
    protected   $option_value;
    protected   $selected;
    protected   $option_position;

    private     $created;
    private     $updated;

    private static $table = 'form_fields';

    public function __construct($record) {

        $this->id               = $record['id'];
        $this->option_parent    = $record['option_parent'];
        $this->option_label     = $record['option_label'];
        $this->option_value     = $record['option_value'];
        if ($record['selected'] == 1) {
            $this->selected     = true;
        } else {
            $this->selected     = false;
        }
        $this->option_position  = $record['option_position'];
        $this->created          = new DateTime($record['created']);
        $this->updated          = new DateTime($record['updated']);

    }

    public function label() {
        return $this->option_label;
    }

    public function value() {
        return $this->option_value;
    }

    public function is_selected () {
        return $this->selected;
    }

}