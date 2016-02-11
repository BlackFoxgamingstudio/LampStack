<?php

require_once '../boot.php';

if (!isset($_GET['project'])) {
    die('Improper access');
} else {

    $project = Project::find('id', $_GET['project']);
    if (!$project) {
        die('No project could be found');
    }

    $members = $project->get_team_unique();
    $html = '<div class="well well-sm push-vertical">';
    $html .= '<h4>Users assigned:</h4>';


    if (count($members) > 0) {
        $html .= '<ul class="list-unstyled">';
        // Should be user objects
        foreach ($members as $member) {
            $html .= '<li><i class="fa fa-user"></i> '.$member->name().'</li>';
        }
        $html .= '</ul>';

    } else {

        $html .= '<p>This project has no members assigned to it</p>';

    }

    $html .= '</div>';

    echo $html;

}