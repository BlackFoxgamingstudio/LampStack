<?php

class InvoicesController {

    use ViewTrait;

    public $invoices;

    public $controller  = 'Invoices';
    public $method      = '';
    public $parameters  = array();

    public $title       = 'Entity {CC} Invoice Management';
    public $description = 'Manage your invoices';
    public $keywords    = 'invoices, invoice management';
    private static $url = 'invoices';

    public function __construct ($method = '', $parameters = array()) {
        extract($this->pullGlobals());
        $this->method       = $method;
        $this->parameters   = array_values($parameters);

        if ($this->method != 'printer') {
            $this->open();
        }
        if (!empty($this->method)) {

            if (method_exists($this, $this->method)) {
                $method = $this->method;
                $this->$method($this->parameters);
            } else {
                $this->all();
            }
        } else {
            $this->all();
        }
        if ($this->method != 'printer') {
            $this->close();
        }


    }

    public function all() {
        extract($this->pullGlobals());
        $this->invoices = $invoices = Invoice::find('all');
        if ($current_user->role()->is_staff()) {
            require_once VIEWS . 'staff.all.invoices.html.php';
        } else {
            require_once VIEWS . 'all.invoices.html.php';
        }

    }

    public function view($id) {
        $this->single($id);
    }

    public function single($id) {
        extract($this->pullGlobals());
        if (empty($id)) {
            $this->all();
        } else {
            $this->invoices = $invoices = Invoice::find('sql', "SELECT * FROM invoices WHERE inumber = '".$id[0]."'");

            $invoice = array_shift($invoices);

            // Stripe specific checks
            if ($invoice->is_company_invoice()) {

                if ($app_settings->appStripeCapable()) {
                    $stripeReady = true;
                } else {
                    $stripeReady = false;
                }

            } else {

                if ($invoice->payee()->hasStripe()) {
                    $stripeReady = true;
                } else {
                    $stripeReady = false;
                }

            }

            //var_dump($stripeReady);exit;
            require_once VIEWS . 'single.invoice.html.php';
        }

    }

    public function printer($invoiceNumber) {
        $this->invoices = $invoices = Invoice::find('sql', "SELECT * FROM invoices WHERE inumber = '".$invoiceNumber[0]."'");
        $invoice = array_shift($invoices);

        require_once VIEWS . 'templates/invoice.print.html.php';

    }

    public function history() {
        extract($this->pullGlobals());
        if ($current_user->role()->is_staff()) {
            // Show all invoices
            $this->invoices = $invoices = Invoice::find('all');
        } else {
            // Show only those invoices applicable to that user
            $this->invoices = $invoices = Invoice::find('sql', "SELECT * FROM invoices WHERE paidby = ".$current_user->id()." OR paidto = ".$current_user->id());
        }
        require_once VIEWS . 'search.invoices.html.php';
    }

    public static function url() {
        return BASE_URL . self::$url.'/';
    }


}