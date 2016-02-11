<?php
/**
 * @package Entity Activity Log Class
 * @version 1.0
 * @date 09 December 2014
 * @author Travis Coats, Zen Perfect Design
 * @location app/classes/
 *
 */

class ActivityLog extends Log {

    use Finder, Access;

    private     $id;
    protected   $user;
    protected   $action;
    protected   $message;
    protected   $visible;
    private     $created;
    private     $updated;

    private static $table = 'app_activity';

    // Constructor functions
    public function __construct($record) {
        $this->id 	        = $record['id'];
        $this->user         = User::find('id',$record['userid']);
        $this->action       = $record['action'];
        $this->message      = $record['message'];
        if ($record['visible'] == 1) {
            $this->visible = true;
        } else {
            $this->visible = false;
        }
        $this->created      = new DateTime($record['created']);
        $this->updated      = new DateTime($record['updated']);
    }

    public function message() {
        return nl2br(htmlspecialchars($this->message));
    }

    public function created() {
        return $this->created;
    }

    public static function log_action(User $user, $action, $message) {
        global $con;
        $now        = new DateTime('Now');
        $action     = $con->secure($action);
        $message    = $con->secure($message);
        $sql = "INSERT INTO app_activity (userid, action, message, visible, created, updated) VALUES (".$user->id().", '".$action."', '".$message."', 1, '".$now->format('Y-m-d H:i:s')."', '".$now->format('Y-m-d H:i:s')."')";
        $con->gate->query($sql);
    }

    public static function erase_log() {
        global $con;
        $sql = "DELETE FROM app_activity";
        $con->gate->query($sql);
    }

    public static function clean_logs() {
        global $con;
        $con->gate->query("UPDATE ".self::$table." SET visible = 0");
    }

    public static function last_user_login() {

    }

    public static function user_last_login($userid) {
        $record = self::find('sql', "SELECT * FROM ".self::$table." WHERE action = 'login' AND userid = ".$userid." ORDER BY created DESC LIMIT 1");
        if (!$record) {
            return false;
        } else {
            return $record[0]->created()->format('F d, Y') . ' @ ' . readable_time($record[0]->created());
        }
    }


}