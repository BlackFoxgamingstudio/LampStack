<?php
/**
 * @package Entity Notification Class
 * @version 1.0
 * @date 15 February 2015
 * @author Travis Coats, Zen Perfect Design
 * @location app/classes/
 *
 */

class UserNotification {

    use Finder, HTML, Access;

    private     $id;
    protected   $userid;
    protected   $category;
    protected   $message;
    private     $created;
    private     $updated;

    private static $table = 'user_notifications';

    private static $icons = array(
        'none'      => '<i class="fa fa-bullhorn"></i>',
        'post'      => '<i class="fa fa-quote-left></i>"',
        'message'   => '<i class="fa fa-comment"></i>',
        'create'    => '<i class="fa fa-plus"></i>',
        'delete'    => '<i class="fa fa-bomb"></i>',
        'edit'      => '<i class="fa fa-edit"></i>',
        'complete'  => '<i class="fa fa-check-circle"></i>'
    );

    // Constructor functions
    public function __construct($record) {
        $this->id 	    = $record['id'];
        $this->userid   = $record['userid'];
        $this->category = $record['category'];
        $this->message  = $record['message'];
        $this->created  = new DateTime($record['created']);
        $this->updated  = new DateTime($record['updated']);
    }

    public static function insert($userid, $message, $category = "none") {

    }

}