<?php
/**
 * @package Entity Calendar Class and Methods
 * @version 1.0
 * @date 01 February 2014
 * @author Travis Coats, Zen Perfect Design
 * @location app/classes/
 *
 */

class Calendar {

    use Finder, Access, HTML;

    public $month;
    public $year;

    // Current
    public $currentday;
    public $currentmonth;
    public $currentyear;


    public function __construct($month, $year) {
        $this->month    = $month;
        $this->year     = $year;

        $this->currentday   = date('d');
        $this->currentmonth = date('n');
        $this->currentyear  = date('Y');
    }


    public function days_in_month() {
        $days = cal_days_in_month(CAL_GREGORIAN, $this->month, $this->year);
        return $days;
    }

    public function first_day() {
        // Provides offset Mon = 1 Friday = 5
        return date('N', mktime(0, 0, 0, $this->month, 1, $this->year));
    }

    public function weeks_in_month() {
        // Provides row number for table
        $days       = $this->days_in_month();
        $offset     = $this->first_day();
        if (($days + $offset) % 7 == 0) {
            $weeks  = ($days + $offset) / 7;
        } else {
            $round = ($days + $offset) / 7;
            $weeks  = (ceil($round));
        }
        return $weeks;
    }

    public function render_default() {
        $html  = '<table class="calendar-table">';
        $html .= '<thead><tr>';
        $html .= '<th>Monday</th>';
        $html .= '<th>Tuesday</th>';
        $html .= '<th>Wednesday</th>';
        $html .= '<th>Thursday</th>';
        $html .= '<th>Friday</th>';
        $html .= '<th>Saturday</th>';
        $html .= '<th>Sunday</th>';
        $html .= '</tr></thead>';
        $html .= '<tbody>';

        $date       = $this->first_day();
        $totaldays  = $this->days_in_month();
        $realdate   = 1;
        $rows       = $this->weeks_in_month();
        $row        = 0;
        $slots      = $rows * 7;
        $slot       = 1;

        while ($row < $rows) {
            // Table rows
            $html .= '<tr class="calendar-row">';

            for ($i = 1; $i <= 7; $i++) {
                if ($realdate > ($totaldays) || $slot < $date) {
                    $html .= '<td></td>';
                    $slot++;
                } else {
                    if ($this->currentday == $realdate && $this->currentmonth == $this->month && $this->currentyear == $this->year) {
                        $html .= '<td class="today"><div class="calendar-numerical">'.$realdate.'</div></td>';
                    } else {
                        $html .= '<td><div class="calendar-numerical">'.$realdate.'</div></td>';
                    }
                    $slot++;
                    $realdate++;
                }
            }

            $html .= '</tr>';
            $row++;
        }

        // Control row
        //$html .= '<tr class="calendar-control-row">';
        //$html .= '<td colspan="3">';
        //$html .= '<i class="fa fa-arrow-circle-left"></i>';
        //$html .= '</td>';
        //$html .= '<td></td>';
        //$html .= '<td colspan="3">';
        //$html .= '<i class="fa fa-arrow-circle-right"></i>';
        //$html .= '</td></tr>';
        $html .= '</tbody></table>';

        return $html;
    }

    public function render_detail() {

    }

    public function make_list() {

    }

}