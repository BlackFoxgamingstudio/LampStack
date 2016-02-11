<?php
/**
 * @package Entity Logic File, All Functions
 * @version 1.0
 * @date 21 October 2014
 * @author Travis Coats, Zen Perfect Design
 * @location app/
 *
 */

// Common HTML Output

function restricted_button() {
    echo '<a href="#" title="No Access" class="btn btn-default"><i class="fa fa-lock"></i> Restricted</a>';
}

function demo_die() {
    if (DEMO) {
        echo DEMO_MSG; exit;
    }
}

// Directory and File Functions

// This function scans the files folder recursively, and builds a large array

function recursive_scan($dir){
    $files = array();
    // Is there actually such a folder/file?
    if(file_exists($dir)){
        foreach(scandir($dir) as $f) {
            if(!$f || $f[0] == '.') {
                continue; // Ignore hidden files
            }
            if(is_dir($dir . '/' . $f)) {
                // The path is a folder
                $files[] = array(
                    "name" => $f,
                    "type" => "folder",
                    "path" => $dir . '/' . $f,
                    "items" => recursive_scan($dir . '/' . $f) // Recursively get the contents of the folder
                );
            } else {
                // It is a file
                $files[] = array(
                    "name" => $f,
                    "type" => "file",
                    "path" => $dir . '/' . $f,
                    "size" => filesize($dir . '/' . $f) // Gets the size of this file
                );
            }
        }
    }
    return $files;
}

// General Database Functions

function gen_query($query) {
    global $con;
    $all = $con->gate->query($query);
    if ($all->num_rows > 0) {
        $results = array();
        while($row = $all->fetch_array(MYSQLI_ASSOC)) {
            $results[] = $row;
        }
        $count = count($results);
        $master_array = array("rows" => $results, "count" => $count);
        return $master_array;
    } else {
        $master_array = array("count" => 0);
        return $master_array;
    }
}

// Text Functions

function random_string_gen($length = 15) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $random_string = '';
    for ($i = 0; $i < $length; $i++) {
        $random_string .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $random_string;
}

function shorten($string, $int) {
    // Remove breaks
    $string = str_replace('<br />', '', $string);
    if (strlen($string) < $int) {
        return $string;
    } else {
        $string = substr($string, 0, $int);
        return $string . " ...";
    }
}

function readable_time(DateTime $date) {
    $hour = $date->format('H');
    $minutes = $date->format('i');

    if ($hour > 12) {
        $rh = $hour - 12;
        $meridian = 'PM';
    } else {
        $rh = $hour;
        $meridian = 'AM';
    }

    return $rh.':'.$minutes.' '.$meridian;

}

function convert_bytes($bytes) {
    if ($bytes >= 1073741824) {
        $bytes = number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        $bytes = number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        $bytes = number_format($bytes / 1024, 2) . ' KB';
    } elseif ($bytes > 1) {
        $bytes = $bytes . ' bytes';
    } elseif ($bytes == 1) {
        $bytes = $bytes . ' byte';
    } else {
        $bytes = '0 bytes';
    }

    return $bytes;
}

function convert_to_bytes($number, $unit, $format = '') {
    $unit = strtolower($unit);

    switch ($unit) {

        case 'kb':
            return ($format == 'plain') ? $number * 1024: number_format($number * 1024);
            break;

        case 'mb':
            return ($format == 'plain') ? $number * 1048576 : number_format($number * 1048576);
            break;

        case 'gb':
            return ($format == 'plain') ? $number * 1073741824 : number_format($number * 1073741824);
            break;

        default:
            return $number;

    }
}

function format_phone($string) {
    $string = preg_replace('/[^0-9]/','',$string);

    if(strlen($string) == 10) {
        $areaCode = substr($string, 0, 3);
        $nextThree = substr($string, 3, 3);
        $lastFour = substr($string, 6, 4);

        return htmlspecialchars('('.$areaCode.') '.$nextThree.'-'.$lastFour);
    } else if (strlen($string) < 10) {
        $nextThree = substr($string, 0, 3);
        $lastFour = substr($string, 3, 4);

        return htmlspecialchars($nextThree.'-'.$lastFour);
    } else {
        $countryCode = substr($string, 0, strlen($string)-10);
        $areaCode = substr($string, -10, 3);
        $nextThree = substr($string, -7, 3);
        $lastFour = substr($string, -4, 4);

        return htmlspecialchars('+'.$countryCode.' ('.$areaCode.') '.$nextThree.'-'.$lastFour);
    }
}

function remove_special_chars($string) {

    $new = strtolower(preg_replace('/ |[!@#$%\^&*)(\'"+=._-]+/', '-', $string));
    return trim($new, '-');

}

function do_get_hash($password) {
    $options = [
        'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM),
    ];
    return password_hash($password, PASSWORD_BCRYPT, $options);
}

// Form functions

function get_countries($name = 'country', $class = '', $selected = '') {

    $countries = array(
        'North America' => array(
            'Antigua and Barbuda',
            'Bahamas',
            'Barbados',
            'Belize',
            'Canada',
            'Costa Rica',
            'Cuba',
            'Dominica',
            'Dominican Republic',
            'El Salvador',
            'Grenada',
            'Guatemala',
            'Haiti',
            'Honduras',
            'Jamaica',
            'Mexico',
            'Nicaragua',
            'Panama',
            'Saint Kitts and Nevis',
            'Saint Lucia',
            'Saint Vincent and the Grenadines',
            'Trinidad and Tobago',
            'United States'
        ),
        'Europe' => array(
            'Albania',
            'Andorra',
            'Armenia',
            'Austria',
            'Azerbaijan',
            'Belarus',
            'Belgium',
            'Bosnia and Herzegovina',
            'Bulgaria',
            'Croatia',
            'Cyprus',
            'Czech Republic',
            'Denmark',
            'Estonia',
            'Finland',
            'France',
            'Georgia',
            'Germany',
            'Greece',
            'Hungary',
            'Iceland',
            'Ireland',
            'Italy',
            'Latvia',
            'Liechtenstein',
            'Lithuania',
            'Luxembourg',
            'Macedonia',
            'Malta',
            'Moldova',
            'Monaco',
            'Montenegro',
            'Netherlands',
            'Norway',
            'Poland',
            'Portugal',
            'Romania',
            'San Marino',
            'Serbia',
            'Slovakia',
            'Slovenia',
            'Spain',
            'Sweden',
            'Switzerland',
            'Ukraine',
            'United Kingdom',
            'Vatican City'
        ),
        'Asia' => array(
            'Afghanistan',
            'Bahrain',
            'Bangladesh',
            'Bhutan',
            'Brunei',
            'Burma (Myanmar)',
            'Cambodia',
            'China',
            'East Timor',
            'India',
            'Indonesia',
            'Iran',
            'Iraq',
            'Israel',
            'Japan',
            'Jordan',
            'Kazakhstan',
            'Korea, North',
            'Korea, South',
            'Kuwait',
            'Kyrgyzstan',
            'Laos',
            'Lebanon',
            'Malaysia',
            'Maldives',
            'Mongolia',
            'Nepal',
            'Oman',
            'Pakistan',
            'Philippines',
            'Qatar',
            'Russian Federation',
            'Saudi Arabia',
            'Singapore',
            'Sri Lanka',
            'Syria',
            'Tajikistan',
            'Thailand',
            'Turkey',
            'Turkmenistan',
            'United Arab Emirates',
            'Uzbekistan',
            'Vietnam',
            'Yemen'
        ),
        'South America' => array(
            'Argentina',
            'Bolivia',
            'Brazil',
            'Chile',
            'Colombia',
            'Ecuador',
            'Guyana',
            'Paraguay',
            'Peru',
            'Suriname',
            'Uruguay',
            'Venezuela'
        ),
        'Africa' => array(
            'Algeria',
            'Angola',
            'Benin',
            'Botswana',
            'Burkina',
            'Burundi',
            'Cameroon',
            'Cape Verde',
            'Central African Republic',
            'Chad',
            'Comoros',
            'Congo',
            'Congo, Democratic Republic of',
            'Djibouti',
            'Egypt',
            'Equatorial Guinea',
            'Eritrea',
            'Ethiopia',
            'Gabon',
            'Gambia',
            'Ghana',
            'Guinea',
            'Guinea-Bissau',
            'Ivory Coast',
            'Kenya',
            'Lesotho',
            'Liberia',
            'Libya',
            'Madagascar',
            'Malawi',
            'Mali',
            'Mauritania',
            'Mauritius',
            'Morocco',
            'Mozambique',
            'Namibia',
            'Niger',
            'Nigeria',
            'Rwanda',
            'Sao Tome and Principe',
            'Senegal',
            'Seychelles',
            'Sierra Leone',
            'Somalia',
            'South Africa',
            'South Sudan',
            'Sudan',
            'Swaziland',
            'Tanzania',
            'Togo',
            'Tunisia',
            'Uganda',
            'Zambia',
            'Zimbabwe'
        ),
        'Oceania' => array(
            'Australia',
            'Fiji',
            'Kiribati',
            'Marshall Islands',
            'Micronesia',
            'Nauru',
            'New Zealand',
            'Palau',
            'Papua New Guinea',
            'Samoa',
            'Solomon Islands',
            'Tonga',
            'Tuvalu',
            'Vanuatu'
        )
    );

    if ($class == '') {
        $html  = '<select name="'.$name.'" id="'.$name.'">';
    } else {
        $html  = '<select class="'.$class.'" name="'.$name.'" id="'.$name.'">';
    }

    foreach ($countries as $key => $value) {

        $html .= '<optgroup label="'.$key.'">';

        foreach ($value as $country) {
            if ($selected != '') {
                if ($country == $selected) {
                    $html .= '<option value="'.$country.'" selected>'.$country.'</option>';
                } else {
                    $html .= '<option value="'.$country.'">'.$country.'</option>';
                }
            } else {
                $html .= '<option value="'.$country.'">'.$country.'</option>';
            }
        }

        $html .= '</optgroup>';

    }

    $html .= '</select>';
    return $html;
}

function display_meter($progress, $total, $format = '') {
    $percentage = round(($progress / $total) * 100, 2);

    $html  = '<div class="meter">';
    $html .= '<div class="meter-bar" style="width:'.$percentage.'%"></div>';
    $html .= '</div>';

    return $html;

}