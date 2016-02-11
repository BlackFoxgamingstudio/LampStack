<?php
/**
 * @package Entity Message Class and Methods
 * @version 1.0
 * @date 31 January 2015
 * @author Travis Coats, Zen Perfect Design
 * @location app/classes/
 *
 */

class Message {

    use Finder, Access, HTML;

    private     $id;
    protected   $msubject;
    protected   $mbody;
    protected   $sender;
    protected   $recipient;
    protected   $viewed     = false;
    protected   $viewdate;
    protected   $trashcan   = false;
    protected   $isreply    = false;
    protected   $replyto    = false;
    private     $created;
    private     $updated;

    private static $table = 'messages';

    // Constructor functions
    public function __construct($record) {
        $this->id 	        = $record['id'];
        $this->msubject     = $record['msubject'];
        $this->mbody        = $record['mbody'];
        $this->sender       = User::find('id', $record['sender']);
        $this->recipient    = User::find('id', $record['recipient']);
        if ($record['viewed'] == 1) {
            $this->viewed   = true;
        } else {
            $this->viewed   = false;
        }
        if ($record['trashcan'] == 1) {
            $this->trashcan = true;
        }
        if ($record['isreply'] == 1) {
            $this->isreply = true;
        }
        if ($record['replyto'] != 0) {
            $this->isreply = self::find('id', $record['replyto']);
        }
        $this->created      = new DateTime($record['created']);
        $this->updated      = new DateTime($record['updated']);

    }

    public function subject() {
        return htmlspecialchars($this->msubject);
    }

    public function body() {
        return nl2br(htmlspecialchars($this->mbody));
    }

    public function to() {
        return $this->recipient;
    }

    public function from() {
        return $this->sender;
    }

    public function senddate() {
        return $this->created;
    }

    public function readdate() {
        return $this->updated;
    }

    public function viewed() {
        return $this->viewed;
    }

    public function mark_viewed() {
        global $con;
        global $now;
        $this->viewed = true;
        $exec = $con->gate->query("UPDATE messages SET viewed = 1, updated = '".$now->format('Y-m-d H:i:s')."' WHERE id = ".$this->id);
        return $exec;
    }


}