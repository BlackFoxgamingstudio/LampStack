<?php
/**
 * @package Entity Application Boot File
 * @version 1.0
 * @date 28 June 2014
 * @author Travis Coats, Zen Perfect Design
 * @location app/
 *
 */

session_start();

// Load logic
require_once __DIR__ . '/logic.php';

// Load configuration
require_once __DIR__ . '/config.php';

// Load components

$app                = simplexml_load_file(ROOT_PATH.'app/app.xml') or die("Could not load the application settings. You must have the SimpleXML PHP module installed!");

// Global declarations
$current_user       = false; // Current logged in user initialized
$now                = new DateTime('Now');

// Load connection
require_once __DIR__ . '/classes/Connection.php';

// Load composer dependencies via autoloader
require_once __DIR__ . '/vendor/autoload.php';

// Load Interfaces
require_once __DIR__ . '/interfaces/ForumPostInterface.php';
require_once __DIR__ . '/interfaces/NotifyInterface.php';
require_once __DIR__ . '/interfaces/Exportable.php';

// Load traits
require_once __DIR__ . '/traits/finder.php';
require_once __DIR__ . '/traits/access.php';
require_once __DIR__ . '/traits/html.php';
require_once __DIR__ . '/traits/ViewTrait.php';
require_once __DIR__ . '/traits/HasDatesTrait.php';
require_once __DIR__ . '/traits/HasDescriptorsTrait.php';

// Load classes
require_once __DIR__ . '/classes/Log.php';
require_once __DIR__ . '/classes/SystemNotification.php';
require_once __DIR__ . '/classes/Cookie.php';
require_once __DIR__ . '/classes/View.php';
require_once __DIR__ . '/classes/User.php';
require_once __DIR__ . '/classes/Role.php';
require_once __DIR__ . '/classes/File.php';
require_once __DIR__ . '/classes/Project.php';
require_once __DIR__ . '/classes/ProjectHistory.php';
require_once __DIR__ . '/classes/ProjectStage.php';
require_once __DIR__ . '/classes/StageTask.php';
require_once __DIR__ . '/classes/StageTaskNote.php';
require_once __DIR__ . '/classes/StageTaskAttachment.php';
require_once __DIR__ . '/classes/ProjectTask.php';
require_once __DIR__ . '/classes/ProjectForumPost.php';
require_once __DIR__ . '/classes/Group.php';
require_once __DIR__ . '/classes/GroupForumPost.php';
require_once __DIR__ . '/classes/Wage.php';
require_once __DIR__ . '/classes/Tax.php';
require_once __DIR__ . '/classes/Invoice.php';
require_once __DIR__ . '/classes/Currency.php';
require_once __DIR__ . '/classes/CurrencyExchange.php';
require_once __DIR__ . '/classes/InvoiceCharge.php';
require_once __DIR__ . '/classes/Timer.php';
require_once __DIR__ . '/classes/Upload.php';
require_once __DIR__ . '/classes/Session.php';
require_once __DIR__ . '/classes/ActivityLog.php';
require_once __DIR__ . '/classes/Message.php';
require_once __DIR__ . '/classes/Calendar.php';
require_once __DIR__ . '/classes/EmailTemplate.php';
require_once __DIR__ . '/classes/UserNotification.php';
require_once __DIR__ . '/classes/Setting.php';
require_once __DIR__ . '/classes/Form.php';
require_once __DIR__ . '/classes/FormField.php';
require_once __DIR__ . '/classes/FormSubmission.php';
require_once __DIR__ . '/classes/FormSubmissionField.php';
require_once __DIR__ . '/classes/FormFieldOption.php';
require_once __DIR__ . '/classes/HealthMonitor.php';

// Load Owner Account
if ($app_settings->initialized()) {
    $app_owner              = User::find('id', $app_settings->get('owner_account'));
} else {
    $app_owner              = false;
}


// Load application controllers
require_once __DIR__ . '/controllers/RouteController.php';
require_once __DIR__ . '/controllers/ViewController.php';
require_once __DIR__ . '/controllers/DashboardController.php';
require_once __DIR__ . '/controllers/ActivityController.php';
require_once __DIR__ . '/controllers/UsersController.php';
require_once __DIR__ . '/controllers/GroupsController.php';
require_once __DIR__ . '/controllers/AccessController.php';
require_once __DIR__ . '/controllers/ProjectsController.php';
require_once __DIR__ . '/controllers/InvoicesController.php';
require_once __DIR__ . '/controllers/RatesController.php';
require_once __DIR__ . '/controllers/MessagesController.php';
require_once __DIR__ . '/controllers/SettingsController.php';
require_once __DIR__ . '/controllers/DocsController.php';
require_once __DIR__ . '/controllers/UploadsController.php';
require_once __DIR__ . '/controllers/FormsController.php';
require_once __DIR__ . '/controllers/AccountController.php';
require_once __DIR__ . '/controllers/NotFoundController.php';
require_once __DIR__ . '/controllers/ErrorController.php';
require_once __DIR__ . '/controllers/LoginPageController.php';
require_once __DIR__ . '/controllers/HealthMonitorController.php';

// Load application action switch board
require_once __DIR__ . '/controller.php';

$health_monitor         = new HealthMonitor($system_notification);
$system_notification    = $health_monitor->scan();

if ($app_settings->initialized()) {
    // Maintenance Mode
    if ($app_settings->get('maintenance_mode') == 1) {
        // Check session
        if (!isset($_SESSION['maintenance.override'])) {
            if (isset($_GET['key'])) {

                if ($_GET['key'] == $app_settings->get('maintenance_key')) {
                    $_SESSION['maintenance.override'] = true;
                } else {
                    require VIEWS . 'maintenance.html.php'; exit;
                }

            } else {
                require VIEWS . 'maintenance.html.php'; exit;
            }
        }
    }
}