<?php
/**
 * @package Entity Form Submission Class
 * @version 1.0
 * @date 27 June 2015
 * @author Travis Coats, Zen Perfect Design
 * @location app/classes/
 *
 */

class FormSubmission {

    use Finder, Access, HTML;

    private     $id;
    private     $ip_address;
    private     $parent_form;
    protected   $status;
    private     $created;
    private     $updated;

    private static $table = 'form_submissions';

    private static $status_codes = array(
        '0'     =>  'Unopened',
        '1'     =>  'Opened - Awaiting response from you',
        '2'     =>  'Responded - Awaiting confirmation from client',
        '3'     =>  'Confirmed',
        '4'     =>  'Closed'
    );


    public function __construct($record) {

        $this->id               = $record['id'];
        $this->ip_address       = $record['ip_address'];
        $this->parent_form      = $record['parent_form'];
        $this->status           = $record['status'];
        $this->created          = new DateTime($record['created']);
        $this->updated          = new DateTime($record['updated']);

    }

    public function ip() {
        return $this->ip_address;
    }

    public function status() {
        return self::$status_codes[$this->status];
    }

    public function created() {
        return $this->created;
    }

    public function form() {
        return Form::find('id', $this->parent_form);
    }



}