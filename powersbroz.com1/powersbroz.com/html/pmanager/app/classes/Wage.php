<?php
/**
 * @package Entity Wage Class and Methods
 * @version 1.0
 * @date 28 December 2014
 * @author Travis Coats, Zen Perfect Design
 * @location app/classes/
 *
 */

class Wage {

    use Finder, Access, HTML;

    private     $id;
    private     $billingcode;
    protected   $wname;
    protected   $wdesc;
    protected   $flat       = false;
    protected   $wrate;
    //protected   $overhead;
    private     $created;
    private     $updated;

    private static $table = 'wages';

    // Constructor functions
    public function __construct($record) {
        $this->id 	        = $record['id'];
        $this->billingcode  = $record['billingcode'];
        $this->wname        = $record['wname'];
        $this->wdesc        = $record['wdesc'];
        if ($record['is_flat'] == '1') {
            $this->flat     = true;
        } else {
            $this->flat     = false;
        }
        $this->wrate        = $record['wrate'];
        //$this->overhead     = $record['overhead'];
        $this->created      = new DateTime($record['created']);
        $this->updated      = new DateTime($record['updated']);
    }

    public function name() {
        return htmlspecialchars($this->wname);
    }

    public function billing_code() {
        return $this->billingcode;
    }

    public function description() {
        return nl2br(htmlspecialchars($this->wdesc));
    }

    public function rate() {
        return number_format($this->wrate,2);
    }

    public function unformed_rate() {
        return $this->wrate;
    }

    public function formatted_rate() {
        global $app_settings;
        $symbol =  Currency::find('id', $app_settings->get('wage_currency'))->symbol();
        return $symbol.' '.$this->rate();
    }
    /*
    public function overhead() {
        return number_format($this->overhead, 2);
    }
    */
    public function is_flat() {
        return $this->flat;
    }
}