<?php

class HealthMonitor {

    private $system_notification;

    public function __construct(SystemNotification $system_notification) {
        $this->system_notification = $system_notification;
    }

    public function scan() {
        // Check for problems, errors, and common bad stuff and queue it up
        if (file_exists(ROOT_PATH.'app/install/')) {
            $this->system_notification->queueFlashMsg(
                'Install Folder Exists',
                'Delete your installation folder before someone creates an administrator account'
            );
        }
        return $this->system_notification;
    }

    public static function securityActivity() {
        return ActivityLog::find('sql', "SELECT * FROM app_activity WHERE action = 'security-warning' ORDER BY created DESC");
    }

    public static function bugActivity() {
        return ActivityLog::find('sql', "SELECT * FROM app_activity WHERE action = 'system-error' ORDER BY created DESC");
    }

}