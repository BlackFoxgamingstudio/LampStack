<?php
/**
 * @package Entity Invoice Class and Methods
 * @version 1.0
 * @date 26 November 2014
 * @author Travis Coats, Zen Perfect Design
 * @location app/classes/
 *
 */

class Invoice {

    use Finder, Access;

    private     $id;
    protected   $inumber;
    protected   $iproject;
    protected   $iname;
    public      $inotes;
    protected   $currency;
    protected   $displaycompany     = false;
    protected   $paidby;
    protected   $paidto;
    protected   $duedate;
    protected   $creator;
    protected   $paid               = false;
    private     $created;
    private     $updated;

    private     $amount;

    private static $table = 'invoices';

    const OVERDUE_UNPAID     = "SELECT * FROM invoices WHERE paid = 0 and duedate < CURDATE() and duedate <> '0000-00-00'";
    const UNPAID             = "SELECT * FROM invoices WHERE paid = 0";
    const PAID               = "SELECT * FROM invoices WHERE paid = 1";

    // Constructor functions
    public function __construct($record) {
        $this->id 	            = $record['id'];
        $this->inumber          = $record['inumber'];
        if ($record['iproject'] == 0) {
            $this->iproject = false;
        } else {
            $this->iproject = Project::find('id', $record['iproject']);
        }
        $this->iname            = $record['iname'];
        $this->inotes           = $record['inotes'];
        $this->currency         = Currency::find('id', $record['currencyid']);
        if ($record['displaycompany'] == 1) {
            $this->displaycompany = true;
        }
        $this->paidby           = User::find('id', $record['paidby']);
        $this->paidto           = User::find('id', $record['paidto']);
        if ($record['duedate'] == '0000-00-00') {
            $this->duedate          = false;
        } else {
            $this->duedate          = new DateTime($record['duedate']);
        }
        $this->creator          = User::find('id', $record['created_by']);
        if ($record['paid'] == 1) {
            $this->paid         = true;
        }
        $this->created          = new DateTime($record['created']);
        $this->updated          = new DateTime($record['updated']);

    }

    public function number() {
        return $this->inumber;
    }

    public function name() {
        return $this->iname;
    }

    public function notes() {
        return nl2br(htmlspecialchars($this->inotes));
    }

    public function paid() {
        return $this->paid;
    }

    public function overdue() {
        $now = new DateTime('Now');
        if ($this->duedate) {
            if ($this->duedate < $now) {
                return true;
            } else {
                return false;
            }
        }
        return false;
    }

    public function has_project() {
        return $this->iproject;
    }

    public function amount() {
        $total = $this->raw_amount();
        if ($total > 0) {
            return $this->currency->symbol().' '.number_format($total, 2, '.', ',');
        } else {
            return $this->currency->symbol().' 0.00';
        }

    }

    public function raw_amount() {
        $amount     = 0;
        $charges    = InvoiceCharge::find('sql', "SELECT * FROM invoice_charges WHERE invoiceid = ".$this->id);
        if ($charges) {
            foreach ($charges as $charge) {
                $amount += $charge->raw_amount();
            }
        }
        return $amount;
    }

    public function raw_amount_pennies() {
        return $this->raw_amount() * 100;
    }

    public function taxed_amount() {
        if ($this->has_taxes()) {
            return $this->raw_amount() * ($this->get_tax_total() / 100) + $this->raw_amount();
        }
        return $this->raw_amount();
    }

    public function taxed_amount_pennies() {
        return $this->taxed_amount() * 100;
    }

    public function has_charges() {
        $charges = gen_query("SELECT * FROM invoice_charges WHERE invoiceid = ".$this->id);
        if ($charges['count'] > 0) {
            unset($charges);
            return true;
        } else {
            unset($charges);
            return false;
        }
    }

    public function get_charges() {
        if ($this->has_charges()) {
            $charges = InvoiceCharge::find('sql', "SELECT * FROM invoice_charges WHERE invoiceid = ".$this->id);
            return $charges;
        } else {
            return false;
        }
    }

    public function has_taxes() {
        $taxes = gen_query("SELECT * FROM invoice_tax_assignments WHERE invoiceid = ".$this->id);
        if ($taxes['count'] > 0) {
            return true;
        }
        return false;
    }

    public function get_taxes() {
        $objects = array();
        if ($this->has_taxes()) {
            $taxes = gen_query("SELECT * FROM invoice_tax_assignments WHERE invoiceid = ".$this->id);
            for($i = 0; $i < $taxes['count'];$i++) {
                $objects[] = Tax::find('id', $taxes['rows'][$i]['taxid']);
            }
            return $objects;
        }
        return false;
    }

    public function get_tax_total() {
        $taxes = $this->get_taxes();
        if (empty($taxes)) {
            return 0;
        } else {
            $rate = 0;
            for($i = 0;$i < count($taxes);$i++) {
                $rate += $taxes[$i]->rate();
            }
            return $rate;
        }
    }

    public function is_company_invoice() {
        return $this->displaycompany;
    }

    public function show_company() {
        global $app_settings;
        return $app_settings->get('company_name');
    }

    public function payor() {
        return $this->paidby;
    }

    public function payee() {
        return $this->paidto;
    }

    public function creator() {
        return $this->creator;
    }

    public function currency() {
        return $this->currency;
    }

    public function due() {
        return $this->duedate;
    }

    public function created() {
        return $this->created;
    }

    public function updated() {
        return $this->updated;
    }

    // Statistic functions

    public static function calculate_income_from_invoices(array $invoices) {
        if (empty($invoices)) {
            return 0;
        }
        global $app_settings;
        $system_rate = Currency::find('id', $app_settings->get('wage_currency'));
        $sum         = 0;
        foreach ($invoices as $i) {
            if ($i->currency()->id() == $system_rate->id()) {
                $sum += $i->raw_amount();
            } else {
                // Perform additional calculations
                $sum += ($system_rate->rate() / $i->currency()->rate()) * $i->raw_amount();
            }
        }
        return $sum;
    }

    public static function pending_income() {
        $invoices   = self::find('sql', self::UNPAID);
        return self::calculate_income_from_invoices($invoices);
    }

    public static function my_pending_income() {
        global $current_user;
        $invoices   = self::find('sql', "SELECT * FROM invoices WHERE paidto = ".$current_user->id()." AND paid = 0");
        return self::calculate_income_from_invoices($invoices);
    }

    public static function overdue_income() {
        $invoices   = self::find('sql', self::OVERDUE_UNPAID);
        return self::calculate_income_from_invoices($invoices);
    }

    public static function my_overdue_income() {
        global $current_user;
        $invoices   = self::find('sql', "SELECT * FROM invoices WHERE paidby = ".$current_user->id()." AND paid = 0 AND duedate < CURDATE()");
        return self::calculate_income_from_invoices($invoices);
    }

    public static function pending_payments() {
        $invoices   = self::find('sql', self::UNPAID);
        $filtered = array();
        foreach ($invoices as $i) {
            if (!$i->payee()->role()->is_staff() || $i->payor()->role()->is_staff()) {
                $filtered[] = $i;
            }
        }
        return self::calculate_income_from_invoices($filtered);
    }

    public static function my_pending_payments() {
        global $current_user;
        $invoices   = self::find('sql', "SELECT * FROM invoices WHERE paidby = ".$current_user->id()." AND paid = 0");
        return self::calculate_income_from_invoices($invoices);
    }

    public static function overdue_payments() {
        $invoices   = self::find('sql', self::OVERDUE_UNPAID);
        $filtered = array();
        foreach ($invoices as $i) {
            if (!$i->payee()->role()->is_staff() || $i->payor()->role()->is_staff()) {
                $filtered[] = $i;
            }
        }
        return self::calculate_income_from_invoices($filtered);
    }

    public static function my_overdue_payments() {
        global $current_user;
        $invoices   = self::find('sql', "SELECT * FROM invoices WHERE paidby = ".$current_user->id()." AND paid = 0 AND duedate < CURDATE()AND NOT duedate = '0000-00-00'");
        return self::calculate_income_from_invoices($invoices);
    }

    public static function total_income() {
        $invoices = self::find('sql', self::PAID);
        $filtered = array();
        foreach ($invoices as $i) {
            if ($i->payee()->role()->is_staff()) {
                $filtered[] = $i;
            }
        }
        return self::calculate_income_from_invoices($filtered);
    }

    public static function my_total_income() {
        global $current_user;
        $invoices = self::find('sql', "SELECT * FROM invoices WHERE paidto = ".$current_user->id()." AND paid = 1");
        return self::calculate_income_from_invoices($invoices);
    }

    public static function user_total_income($userid) {
        $invoices = self::find('sql', "SELECT * FROM invoices WHERE paidto = ".$userid." AND paid = 1");
        return self::calculate_income_from_invoices($invoices);
    }

    public static function total_payments() {
        $invoices = self::find('sql', self::PAID);
        $filtered = array();
        foreach ($invoices as $i) {
            if (! $i->payee()->role()->is_staff()) {
                $filtered[] = $i;
            }
        }
        return self::calculate_income_from_invoices($filtered);
    }

    public static function my_total_payments() {
        global $current_user;
        $invoices = self::find('sql', "SELECT * FROM invoices WHERE paidby = ".$current_user->id()." AND paid = 1");
        return self::calculate_income_from_invoices($invoices);
    }

    public static function user_total_payments($userid) {
        $invoices = self::find('sql', "SELECT * FROM invoices WHERE paidby = ".$userid." AND paid = 1");
        return self::calculate_income_from_invoices($invoices);
    }

    public static function total_open() {
        $invoices = self::find('sql', self::UNPAID);
        return count($invoices);
    }

    public static function my_total_open() {
        global $current_user;
        $invoices = self::find('sql', "SELECT * FROM invoices WHERE paidto = ".$current_user->id()." AND paid = 0");
        return count($invoices);
    }

    public static function total_open_payments() {
        $invoices = self::find('sql', self::UNPAID);
        $filtered = array();
        foreach ($invoices as $i) {
            if (!$i->payee()->role()->is_staff() || $i->payor()->role()->is_staff()) {
                $filtered[] = $i;
            }
        }
        return count($filtered);
    }

    public static function my_total_open_payments() {
        global $current_user;
        $invoices = self::find('sql', "SELECT * FROM invoices WHERE paidby = ".$current_user->id()." AND paid = 0");
        return count($invoices);
    }

    public static function format($number) {
        global $app_settings;
        $system_rate = Currency::find('id', $app_settings->get('wage_currency'));
        return $system_rate->symbol() . number_format($number, 2, '.', ',');
    }



}