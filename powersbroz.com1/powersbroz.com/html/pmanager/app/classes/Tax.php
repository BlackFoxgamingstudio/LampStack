<?php
/**
 * @package Entity Tax Class and Methods
 * @version 1.0
 * @date 28 December 2014
 * @author Travis Coats, Zen Perfect Design
 * @location app/classes/
 *
 */

class Tax {

    use Finder, Access, HTML;

    private     $id;
    protected   $tname;
    protected   $tdesc;
    protected   $tpercent;
    private     $created;
    private     $updated;

    private static $table = 'taxes';

    // Constructor functions
    public function __construct($record) {
        $this->id 	        = $record['id'];
        $this->tname        = $record['tname'];
        $this->tdesc        = $record['tdesc'];
        $this->tpercent     = $record['tpercent'];
        $this->created      = new DateTime($record['created']);
        $this->updated      = new DateTime($record['updated']);
    }

    public function name() {
        return htmlspecialchars($this->tname);
    }

    public function description() {
        return nl2br(htmlspecialchars($this->tdesc));
    }

    public function rate() {
        return $this->tpercent;
    }

}