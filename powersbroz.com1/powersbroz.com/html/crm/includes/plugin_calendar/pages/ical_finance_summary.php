<?php 
/** 
  * Copyright: dtbaker 2012
  * Licence: Please check CodeCanyon.net for licence details. 
  * More licence clarification available here:  http://codecanyon.net/wiki/support/legal-terms/licensing-terms/ 
  * Deploy: 10474 31adef9c9cf17cbd18100c8b1824e959
  * Envato: 893ecafa-6fb9-4299-930f-7526a262c4e8
  * Package Date: 2016-01-13 13:46:18 
  * IP Address: 76.104.145.50
  */

header('Content-type: text/calendar; charset=utf-8');
header('Content-Disposition: inline; filename="cal.ics"');

$show_previous_weeks = module_config::c('dashboard_income_previous_weeks',7);
$date_start = date('Y-m-d', mktime(1, 0, 0, date('m'), date('d')-date('w')-(($show_previous_weeks+2)*7)+1, date('Y')));
$date_end = date('Y-m-d', strtotime('-1 day',mktime(1, 0, 0, date('m'), date('d')+(6-date('w'))-(2*7)+2, date('Y'))));
$result = module_finance::get_finance_summary($date_start,$date_end,7,$show_previous_weeks);
/*
print_r($result);

echo 'BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//Ultimate Client Manager/Calendar Plugin v1.0//EN
CALSCALE:GREGORIAN
X-WR-CALNAME:'._l('Dashboard Summary').'
X-WR-TIMEZONE:UTC
';


//$local_timezone_string = date('e');
//$local_timezone = new DateTimeZone($local_timezone_string);
//$local_time = new DateTime("now", $local_timezone);
$timezone_hours = module_config::c('timezone_hours',0);



        $time = strtotime($timezone_hours.' hours',strtotime($alert['date']));
        echo 'BEGIN:VEVENT
UID:'.md5(mt_rand(1,100)).'@ultimateclientmanager.com
';
        // work out the UTC time for this event, based on the timezome we have set in the configuration options

        echo 'DTSTAMP:'.date('Ymd').'T090000Z
DTSTART;VALUE=DATE:'.date('Ymd',$time).'
DTEND;VALUE=DATE:'.date('Ymd',strtotime('+1 day',$time)).'
SUMMARY: '.$alert['item'].(isset($alert['name']) ? ' - '.$alert['name'] : '').'
DESCRIPTION:<a href="'.$alert['link'].'">'._('Open Link').'</a>
END:VEVENT
';
    


echo 'END:VCALENDAR';

*/