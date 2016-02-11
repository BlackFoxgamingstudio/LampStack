<?php
/**
 * @package Entity Invoice Charge Class and Methods
 * @version 1.0
 * @date 28 December 2014
 * @author Travis Coats, Zen Perfect Design
 * @location app/classes/
 *
 */

class InvoiceCharge {

    use Finder, Access;

    private     $id;
    protected   $invoiceid;
    protected   $cname;
    protected   $cdesc;
    protected   $is_manual;
    protected   $attachedtime;
    private     $amount;
    private     $created;
    private     $updated;

    private static $table = 'invoice_charges';

    // Constructor functions
    public function __construct($record) {
        $this->id 	            = $record['id'];
        $this->invoiceid        = $record['invoiceid'];
        $this->cname            = $record['cname'];
        $this->cdesc            = $record['cdesc'];
        if ($record['is_manual'] == '0') {
            $this->is_manual = false;
        } else {
            $this->is_manual = true;
        }
        if ($record['attachedtime'] == '') {
            $this->attachedtime = false;
        } else {
            $this->build_attached_time($record['attachedtime']);
        }
        $this->amount           = $record['amount'];
        $this->created          = new DateTime($record['created']);
        $this->updated          = new DateTime($record['updated']);

    }

    public function name() {
        return $this->cname;
    }

    public function description() {
        return nl2br(htmlspecialchars($this->cdesc));
    }

    public function amount() {
        return number_format($this->raw_amount(), 2);
    }

    public function raw_amount() {
        if ($this->has_taxes()) {
            return $this->tax_amount();
        }
        return $this->amount;
    }

    public function tax_amount() {
        return ($this->amount * ($this->total_tax_applied() / 100)) + $this->amount;
    }

    public function total_tax_applied() {
        $taxes  = $this->get_taxes();
        $tax    = 0;
        if ($taxes) {
            foreach ($taxes as $t) {
                $tax += $t->rate();
            }
        }
        return $tax;
    }

    public function invoice() {
        return Invoice::find('id', $this->invoiceid);
    }

    private function build_attached_time($string) {
        $this->attachedtime = array();
        $array = explode(',', $string);
        foreach ($array as $item) {
            $item = Timer::find('id', $item);
        }
        $this->attachedtime = $array;
    }

    public function has_taxes() {
        $taxes = gen_query("SELECT * FROM invoice_charge_tax_assignments WHERE chargeid = ".$this->id);
        if ($taxes['count'] > 0) {
            return true;
        }
        return false;
    }

    public function get_taxes() {
        if ($this->has_taxes()) {
            $objects = array();
            $taxes = gen_query("SELECT * FROM invoice_charge_tax_assignments WHERE chargeid = ".$this->id);
            for ($i = 0;$i < $taxes['count'];$i++) {
                $objects[] = Tax::find('id', $taxes['rows'][$i]['taxid']);
            }
            return $objects;
        }
        return false;
    }


}