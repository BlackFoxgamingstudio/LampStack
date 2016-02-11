<?php
/**
 * @package Entity Timer Class and Methods
 * @version 1.0
 * @date 22 February 2014
 * @author Travis Coats, Zen Perfect Design
 * @location app/classes/
 *
 */

class Timer {

    use Finder, Access, HTML;

    private     $id;
    protected   $user;
    protected   $billable;
    protected   $rate;
    protected   $project;
    protected   $note;
    protected   $hours;
    protected   $minutes;
    protected   $seconds;
    protected   $closed;
    private     $created;
    private     $updated;

    private static $table = 'timer_items';

    // Constructor functions
    public function __construct($record) {
        $this->id 	        = $record['id'];
        $this->user         = User::find('id',$record['tuser']);
        if ($record['billable'] == 1) {
            $this->billable = true;
        } else {
            $this->billable = false;
        }
        if ($record['trate'] == 0) {
            $this->rate     = false;
        } else {
            $this->rate     = Wage::find('id', $record['trate']);
        }
        $this->project      = Project::find('id', $record['tproject']);
        $this->note         = $record['tnote'];
        $this->hours        = $record['thours'];
        $this->minutes      = $record['tminutes'];
        $this->seconds      = $record['tseconds'];
        if ($record['closed'] == 1) {
            $this->closed   = true;
        } else {
            $this->closed   = false;
        }
        $this->created      = new DateTime($record['created']);
        $this->updated      = new DateTime($record['updated']);
    }

    public function wage() {
        return $this->rate;
    }

    public function quick_string() {
        return $this->note.' '.$this->hours.'h '.$this->minutes.'m '.$this->seconds.'s';
    }

    public function billable() {
        return $this->billable;
    }

    public function calculate_rate($float) {
        $rate = round($float, 2);
        $minute_portion = round($this->seconds / 60, 2);
        $hour_portion = round(($this->minutes + $minute_portion) / 60, 2);
        $billed = ($this->hours + $hour_portion) * $rate;
        return $billed;
    }

    public static function user_time_total($userid) {
        $timers         = gen_query("SELECT * FROM timer_items WHERE tuser = ".$userid);
        $time_total     = 0;
        for ($i = 0; $i < $timers['count'];$i++) {
            // Add up
            $time_total += ($timers['rows'][$i]['thours'] * 60) * 60;
            $time_total += $timers['rows'][$i]['tminutes'] * 60;
            $time_total += $timers['rows'][$i]['tseconds'];
        }
        // Return in seconds
        return $time_total;
        //return self::convert_seconds($time_total);
    }

    // Helper methods

    public static function convert_seconds($seconds) {
        // extract hours
        $hours = floor($seconds / (60 * 60));

        // extract minutes
        $divisor_for_minutes = $seconds % (60 * 60);
        $minutes = floor($divisor_for_minutes / 60);

        // extract the remaining seconds
        $divisor_for_seconds = $divisor_for_minutes % 60;
        $seconds = ceil($divisor_for_seconds);

        // return the final array
        $obj = array(
            "h" => (int) $hours,
            "m" => (int) $minutes,
            "s" => (int) $seconds,
        );
        return $obj;
    }

    public static function get_man_hours($userid) {

        $seconds = self::user_time_total($userid);
        $hours = floor($seconds / (60 * 60));

        // extract minutes
        $divisor_for_minutes = $seconds % (60 * 60);
        $minutes = floor($divisor_for_minutes / 60);

        // extract the remaining seconds
        $divisor_for_seconds = $divisor_for_minutes % 60;
        $seconds = ceil($divisor_for_seconds);

        $hours += ($minutes / 60 ) + ($seconds / (60 * 60));


        return round($hours, 2);

    }
}