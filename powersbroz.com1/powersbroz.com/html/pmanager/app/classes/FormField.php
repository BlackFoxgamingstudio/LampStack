<?php
/**
 * @package Entity Form Field Class
 * @version 1.0
 * @date 21 June 2015
 * @author Travis Coats, Zen Perfect Design
 * @location app/classes/
 *
 */

class FormField {

    use Finder, Access, HTML, HasDescriptorsTrait, HasDatesTrait;

    private     $id;
    private     $parent;
    protected   $label;
    protected   $name;
    protected   $description;
    protected   $type;
    protected   $placeholder;
    protected   $required     = false;
    protected   $position;

    private     $created;
    private     $updated;

    protected   $option_capable     = false;
    protected   $field_options      = array();

    private static $system_field_types = array(
        'input-text'        => array(
            "icon"      => "ui-input-text.png",
            "readable"  => "Text Field"
        ),
        "input-email"       => array(
            "icon"      => "ui-input-text.png",
            "readable"  => "Email Field"
        ),
        "input-date"        => array(
            "icon"      => "ui-input-text.png",
            "readable"  => "Date Field"
        ),
        "input-file"        => array(
            "icon"      => "file-search.png",
            "readable"  => "File Upload"
        ),
        "input-radio"       => array(
            "icon"      => "ui-input-radio.png",
            "readable"  => "Radio Group"
        ),
        "input-checkbox"    => array(
            "icon"      => "ui-input-checkbox.png",
            "readable"  => "Checkbox Group"
        ),
        "text-area"         => array(
            "icon"      => "ui-input-textarea.png",
            "readable"  => "Textarea"
        ),
        "select-box"        => array(
            "icon"      => "ui-input-dropdown.png",
            "readable"  => "Dropdown Box"
        )
    );

    protected   $scary_file_types = array(
        'PHP'       => 'php',
        'ZIP'       => 'zip',
        'Python'    => 'py',
        'Ruby'      => 'rb',
        'HTML'      => array('html', 'html', 'phtml')
    );

    protected   $picture_file_types = array(
        'JPEG'  => array ('jpg', 'jpeg'),
        'PNG'   => 'png',
        'GIF'   => 'gif'
    );

    protected   $document_file_types = array(
        'Word Documents'    => array('docx', 'doc'),
        'Excel Documents'   => array('csv', 'xls', 'xlsx'),
        'PDF'               => 'pdf'
    );

    private static $table = 'form_fields';

    public function __construct($record) {

        $this->id          = $record['id'];
        $this->parent      = $record['field_parent'];
        $this->label       = $record['field_label'];
        $this->name        = $record['field_name'];
        $this->description = $record['field_description'];
        $this->type        = $record['field_type'];
        $this->placeholder = $record['field_placeholder'];
        if ($record['field_required'] == 1) {
            $this->required = true;
        } else {
            $this->required = false;
        }
        $this->position   = $record['field_position'];

        $this->created          = new DateTime($record['created']);
        $this->updated          = new DateTime($record['updated']);

        if ($this->type == 'input-radio' || $this->type == 'input-checkbox' || $this->type == 'select-box') {
            $this->option_capable   = true;
            $this->field_options    = FormFieldOption::find('sql', "SELECT * FROM form_field_options WHERE option_parent = ".$this->id);
        }

    }

    public function parent() {
        return Form::find('id', $this->parent);
    }

    public function label() {
        return htmlspecialchars($this->label);
    }

    public function type() {
        return $this->type;
    }

    public function placeholder() {
        return $this->placeholder;
    }

    public function required() {
        return $this->required;
    }

    public function position() {
        return $this->position;
    }

    public function has_options() {
        return (empty($this->field_options)) ? false : true;
    }

    public function options_allowed() {
        return $this->option_capable;
    }

    public function get_options() {
        return $this->field_options;
    }

    public function render($disabled = false) {
        $html = '';
        $html .= '<label for="'.$this->name.'">'.$this->label.':</label>';
        switch ($this->type) {

            case 'input-text':
                if ($disabled) {
                    $html .= '<input disabled type="text" name="'.$this->name.'" id="'.$this->name.'" ';
                } else {
                    $html .= '<input type="text" name="'.$this->name.'" id="'.$this->name.'" ';
                }

                if ($this->placeholder != '') {
                    $html .= 'placeholder="'.$this->placeholder.'" ';
                }

                if ($this->required()) {
                    $html .= ' required ';
                }

                $html .= '/>';
                break;

            case 'text-area':
                $html .= '<textarea rows="5" name="'.$this->name.'" id="'.$this->name.'" ';
                if ($this->placeholder != '') {
                    $html .= 'placeholder="'.$this->placeholder.'" ';
                }

                if ($this->required()) {
                    $html .= ' required ';
                }

                $html .= '></textarea>';

                break;

            case 'input-email':
                $html .= '<input type="email" name="'.$this->name.'" id="'.$this->name.'" ';
                if ($this->placeholder != '') {
                    $html .= 'placeholder="'.$this->placeholder.'" ';
                }

                if ($this->required()) {
                    $html .= ' required ';
                }

                $html .= '/>';
                break;

            case 'input-date':
                $html .= '<input type="date" name="'.$this->name.'" id="'.$this->name.'" ';
                if ($this->placeholder != '') {
                    $html .= 'placeholder="'.$this->placeholder.'" ';
                }

                if ($this->required()) {
                    $html .= ' required ';
                }

                $html .= '/>';
                break;

            case 'select-box':
                if ($this->has_options()) {
                    $options = $this->get_options();
                    $html .= '<select name="'.$this->name().'">';
                    foreach ($options as $o) {
                        $html.= '<option value="'.$o->value().'">'.$o->label().'</option>';
                    }
                    $html .= '</select>';
                } else {
                    $html .= '<p class="alert alert-warning">This field has no options yet</p>';
                }
                break;

            case 'input-radio':
                if ($this->has_options()) {
                    $options = $this->get_options();
                    $html .= '<p>';
                    foreach ($options as $o) {
                        $html.= '<input type="radio" name="'.$this->name().'" value="'.$o->value().'" /> '.$o->label().' ';
                    }
                    $html .= '</p>';
                } else {
                    $html .= '<p class="alert alert-warning">This field has no options yet</p>';
                }
                break;

            case 'input-checkbox':
                if ($this->has_options()) {
                    $options = $this->get_options();
                    $html .= '<p>';
                    foreach ($options as $o) {
                        $html.= '<input type="checkbox" name="'.$this->name().'[]" value="'.$o->value().'" /> '.$o->label().' <br/>';
                    }
                    $html .= '</p>';
                } else {
                    $html .= '<p class="alert alert-warning">This field has no options yet</p>';
                }
                break;

            case 'input-file':
                $html .= '<input type="file" name="'.$this->name.'" id="'.$this->name.'" ';
                if ($this->placeholder != '') {
                    $html .= 'placeholder="'.$this->placeholder.'" ';
                }
                $html .= '/>';
                break;

            default:
                return false;

        }

        return $html;

    }

    public function field_icon($type) {
        switch ($type) {

            case 'input-text':

                break;

        }
    }

    public static function system_types() {
        return self::$system_field_types;
    }



}