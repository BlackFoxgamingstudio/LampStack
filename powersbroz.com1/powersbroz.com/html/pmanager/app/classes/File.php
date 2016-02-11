<?php
/**
 * @package Entity File Class and Methods
 * @version 1.0
 * @date 08 February 2015
 * @author Travis Coats, Zen Perfect Design
 * @location app/classes/
 *
 */

class File {

    use Finder, Access, HTML;

    private     $id;
    protected   $filename;
    protected   $description;
    protected   $type;
    protected   $filesize;
    protected   $location;
    protected   $trash;
    protected   $uploadedby;
    protected   $isdocument;
    private     $created;
    private     $updated;

    private static $images         = array('png', 'jpg', 'jpeg');
    private static $documents      = array('doc', 'docx', 'txt', 'pdf', 'xls', 'xlsx', 'rtf');
    private static $not_allowed    = array('js', 'php', 'py', 'json', 'html', 'xml', 'asp', 'htaccess');

    private static $table = 'files';

    // Constructor functions
    public function __construct($record) {
        $this->id 	        = $record['id'];
        $this->filename     = $record['filename'];
        $this->description  = $record['description'];
        $this->type         = $record['type'];
        $this->filesize     = $record['filesize'];
        $this->location     = $record['location'];
        if ($record['trashcan'] == 0) {
            $this->trash    = false;
        } else {
            $this->trash    = true;
        }
        $this->uploadedby   = User::find('id', $record['uploadedby']);
        if ($record['isdocument'] == 0) {
            $this->isdocument    = false;
        } else {
            $this->isdocument    = true;
        }
        $this->created      = new DateTime($record['created']);
        $this->updated      = new DateTime($record['updated']);

    }

    public function name() {
        return htmlspecialchars($this->filename);
    }

    public function real_name() {
        return $this->location;
    }

    public function description() {
        return htmlspecialchars($this->description);
    }

    public function type() {
        return $this->type;
    }

    public function size() {
        return convert_bytes($this->filesize);
    }

    public function in_trash() {
        return $this->trash;
    }

    public function is_document() {
        return $this->isdocument;
    }

    public function path() {
        return $this->location;
    }

    public function creator() {
        return $this->uploadedby;
    }

    // Security Methods

    public function is_image($filename) {

    }

    public function demo($array = array()) {
        global $current_user;
        $class  = (isset($array['class'])) ? $array['class']: false;
        $width  = (isset($array['width'])) ? $array['width']: false;
        $height = (isset($array['height'])) ? $array['height']: false;


        if (strpos($this->type, 'image') !== false) {
            // It is an image
            if (file_exists($this->location)) {
                $imageclass     = ($class) ? $class : 'img img-thumbnail img-responsive';
                $imageWidth     = ($width) ? 'width="'.$width.'"': '';
                $imageHeight    = ($height) ? 'height="'.$height.'"' : '';
                $url            = str_replace(FILES_PATH, BASE_URL.'files/', $this->location);
                echo '<img class="'.$imageclass.'" src="'.$url.'" alt="'.$this->name().'" '.$imageWidth.' '. $imageHeight .'/>';
            } else {
                echo '<span>No Demo</span>';
            }

        }

        if (strpos($this->type, 'audio') !== false) {
            // It is an audio file
            echo '<audio controls>
                    <source src="'.FILES_URL.$this->location.'" type="audio/mpeg">
                    Your browser does not support the audio element or this is an unsupported file type.
                  </audio> ';
        }

        if (strpos($this->type, 'video') !== false) {
            // It is a video file
            echo ' <video width="256" height="176" controls>
                        <source src="'.FILES_URL.$this->location.'" type="video/mp4">
                        Your browser does not support the video tag or this is an unsupported file type
                   </video> ';
        }
    }

    public static function acceptable_upload($filename, $scope = 'file') {

        // Check multiple parts of array separated by .
        $parts = explode('.', $filename);
        foreach ($parts as $part) {
            $part = strtolower($part);
            switch($scope) {

                case 'image':
                    if (in_array($part, self::$images)) {
                        return true;
                    }
                    break;

                case 'document':
                    if (in_array($part, self::$documents)) {
                        return true;
                    }
                    break;

                case 'file':
                    if (in_array($part, self::$not_allowed)) {
                        return false;
                    }
                    break;
            }
            $pos = strpos('php',$part);
            if($pos !== false) {
                return false;
            }
        }
        // Check true extension
        $extension = strtolower(end($parts));
        switch($scope) {

            case 'image':
                if (in_array($extension, self::$images)) {
                    return true;
                } else {
                    return false;
                }
                break;

            case 'document':
                if (in_array($extension, self::$documents)) {
                    return true;
                } else {
                    return false;
                }
                break;

            case 'file':
                if (in_array($extension, self::$not_allowed)) {
                    return false;
                } else {
                    return true;
                }
                break;
        }

    }

    public static function upload($type = 'user', $files = array(), $params = array()) {
        global $con;
        global $system_notification;

        $response = array(); // Setup initial response to fail

        // Setup naming convention, database record, and directory / destination
        switch ($type) {

            case 'user':

                if (!File::acceptable_upload($files['user-image']['name'], 'image')) {

                    ActivityLog::log_action(
                        $params['current_user'],
                        "security-warning",
                        $params['current_user']->name().' tried uploading an avatar image that is currently not allowed by the application. Filename: '.$files['user-image']['name']
                    );
                    $response['title']      = 'Upload Error';
                    $response['message']    = 'The image type you have selected is not allowed';
                    break;
                }

                $path       = $files['user-image']['name'];
                $filename   = pathinfo($path, PATHINFO_FILENAME);
                $ext        = pathinfo($path, PATHINFO_EXTENSION);
                $type       = $files['user-image']['type'];
                $tmpname    = $files['user-image']['tmp_name'];
                $size       = $files['user-image']['size']; // Integer in bytes

                $newname    = remove_special_chars($params['target_user']->username());
                $newsave    = $newname.'.'.$ext;

                // Move file and check that it exists
                move_uploaded_file($tmpname, AVATARS_PATH.$newsave);

                if (file_exists(AVATARS_PATH.$newsave)) {

                    $sql = "UPDATE users SET avatar = '".$newsave."' WHERE id = ".$params['target_user']->id();
                    $exec = $con->gate->query($sql);
                    if ($exec) {

                        ActivityLog::log_action($params['current_user'], "upload", $params['current_user']->name().' uploaded a new image for user: '.$params['target_user']->name());
                        $response['title']      = 'Upload Successful';
                        $response['message']    = "You have successfully changed your account avatar";
                        break;

                    } else {

                        $response['title']      = 'Upload Error';
                        $response['message']    = "Oh no! The file may have been uploaded but the application was unable to update the user's record";
                        break;

                    }
                } else {
                    $response['title']      = 'Upload Error';
                    $response['message']    = 'Uh oh, there was an error uploading the new avatar';
                    break;
                }

                break;

            case 'group':

                break;

            case 'project':

                if (!File::acceptable_upload($files['project-image']['name'], 'image')) {

                    ActivityLog::log_action($params['user'], "security-warning", $params['user']->name().' tried uploading a project image that is currently not allowed by the application. Filename: '.$files['project-image']['name']);
                    $response['title']      = 'Upload Error';
                    $response['message']    = 'The image type you have selected is not allowed';
                    break;
                }

                $path       = $files['project-image']['name'];
                $filename   = pathinfo($path, PATHINFO_FILENAME);
                $ext        = pathinfo($path, PATHINFO_EXTENSION);
                $type       = $files['project-image']['type'];
                $tmpname    = $files['project-image']['tmp_name'];
                $size       = $files['project-image']['size']; // Integer in bytes

                $newname    = remove_special_chars($params['project']->name());
                $newsave    = $newname.'.'.$ext;

                // Move file and check that it exists
                move_uploaded_file($tmpname, PROJECT_IMAGES_PATH.$newsave);

                if (file_exists(PROJECT_IMAGES_PATH.$newsave)) {

                    $sql = "UPDATE projects SET image = '".$newsave."' WHERE id = ".$params['project']->id();
                    $exec = $con->gate->query($sql);
                    if ($exec) {

                        ActivityLog::log_action($params['user'], "upload", $params['user']->name().' uploaded a new image for the project: '.$params['project']->name());
                        ProjectHistory::log_action($params['user'], $params['project']->id(), 'upload', $params['user']->name().' uploaded a new project images');
                        $response['title']      = 'Upload Successful';
                        $response['message']    = "You have successfully changed this project's image";
                        break;

                    } else {

                        $response['title']      = 'Upload Error';
                        $response['message']    = "Oh no! The file may have been uploaded but the application was unable to update the project's record";
                        break;

                    }
                } else {
                    $response['title']      = 'Upload Error';
                    $response['message']    = 'Uh oh, there was an error uploading the new project image';
                    break;
                }

                break;

            case 'personal':

                break;

            case 'form':
                if (!isset($params['form'])) {
                    ActivityLog::log_action(
                        $params['user'],
                        "system-error",
                        'The application failed to identify the form field name for custom form'
                    );
                    $response['title']      = 'Form not set';
                    $response['message']    = 'Uh oh, the form for this form\'s file input field was not set properly';
                    break;
                }
                $form = Form::find('id', $params['form']);
                if (!isset($files[$params['field-name']]['name'])) {
                    ActivityLog::log_action(
                        $params['user'],
                        "system-error",
                        'The application failed to identify the file input field name for custom form: '.$form->name()
                    );
                    $response['title']      = 'Field name not set';
                    $response['message']    = 'Uh oh, the field name for this form\'s file input field was not set properly';
                    break;
                }
                $fieldName = $params['field-name'];
                if (!File::acceptable_upload($files[$fieldName]['name'])) {

                    ActivityLog::log_action($params['user'], "security-warning", $params['user']->name().' tried uploading a file using the custom form that is currently not allowed by the application. Filename: '.$files[$fieldName]['name']);
                    $response['title']      = 'Upload Error';
                    $response['message']    = 'The image type you have selected is not allowed';
                    break;
                }

                // Setup form's directory if it doesn't exist already
                if (!file_exists(FORM_FILES_PATH.$form->slug())) {
                    $directory = mkdir(FORM_FILES_PATH.$form->slug(), 0755, true);
                    if (!$directory) {
                        $response['title']      = 'Unable to create Form Directory';
                        $response['message']    = 'Application was unable to create a new folder to store this form\'s files. Make sure the files/forms/ directory is writeable.';
                        break;
                    }
                }

                $path           = $files[$fieldName]['name'];
                $filename       = pathinfo($path, PATHINFO_FILENAME);
                $ext            = pathinfo($path, PATHINFO_EXTENSION);
                $type           = $files[$fieldName]['type'];
                $tmpname        = $files[$fieldName]['tmp_name'];
                $size           = $files[$fieldName]['size']; // Integer in bytes
                $dateUploaded   = new DateTime('Now');

                $newname        = remove_special_chars($filename).$dateUploaded->format('Y-m-d');
                $newsave        = $newname.'.'.$ext;
                $newPath        = FORM_FILES_PATH.$form->slug().'/'.$newsave;

                // Move file and check that it exists
                move_uploaded_file($tmpname, $newPath);

                if (file_exists($newPath)) {

                    $response['title']      = 'Upload Successful';
                    $response['message']    = "You have successfully changed this project's image";
                    $response['saved-name'] = $newsave;
                    break;
                } else {
                    $response['title']      = 'Upload Error';
                    $response['message']    = 'The file could not be moved to this form\'s files directory';
                }
                break;

        }

        return $response;

    }

    public static function download($type = 'file', $path = '') {
        if (empty($path)) {
            return false;
        } else {

            switch ($type) {

                case 'file':

                    break;

                case 'stage-brief':

                    break;

                default:
                    return false;


            }


        }
    }

    public static function user_usage($id) {

        $sql    = "SELECT SUM(filesize) AS 'totalspace' FROM files WHERE uploadedby = ".$id;
        $files  = gen_query($sql);
        if ($files['count'] > 0) {
            return $files['rows'][0]['totalspace'];
        } else {
            return 0;
        }

    }

    public static function user_usage_readable($id) {
        return convert_bytes(self::user_usage($id));
    }

    public static function list_not_allowed() {
        return self::$not_allowed;
    }

    public static function get_user_directory($userid, $directory) {
        return ROOT_PATH . 'files/'.$userid.'/'.$directory;
    }

}