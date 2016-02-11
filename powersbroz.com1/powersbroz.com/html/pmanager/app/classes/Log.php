<?php

class Log {

    protected $action;
    protected $description;

    public function action_icon() {

        switch($this->action) {

            case 'create':
                return '<span class="label label-success"><i class="fa fa-plus-circle"></i></span>';
                break;

            case 'edit':
                return '<span class="label label-warning"><i class="fa fa-edit"></i></span>';
                break;

            case 'delete':
                return '<span class="label label-danger"><i class="fa fa-times-circle-o"></i></span>';
                break;

            case 'assign':
                return '<span class="label label-success"><i class="fa fa-link"></i></span>';
                break;

            case 'unassign':
                return '<span class="label label-danger"><i class="fa fa-chain-broken"></i></span>';
                break;

            case 'upload':
                return '<span class="label label-success"><i class="fa fa-upload"></i></span>';
                break;

            case 'download':
                return '<span class="label label-primary"><i class="fa fa-download"></i></span>';
                break;

            case 'login':
                return '<span class="label label-primary"><i class="fa fa-unlock"></i></span>';
                break;

            case 'logout':
                return '<span class="label label-primary"><i class="fa fa-lock"></i></span>';
                break;

            case 'pw-reset':
                return '<span class="label label-warning"><i class="fa fa-refresh"></i></span>';
                break;

            case 'complete':
                return '<span class="label label-success"><i class="fa fa-check-circle-o"></i></span>';
                break;

            case 'register':
                return '<span class="label label-success"><i class="fa fa-user"></i></span>';
                break;

            case 'update':
                return '<span class="label label-success"><i class="fa fa-refresh"></i></span>';
                break;

            case 'security-warning':
                return '<span class="label label-dark"><i class="glyphicon glyphicon-fire fire"></i></span>';
                break;

            case 'system-error':
                return '<span class="label label-dark"><i class="fa fa-bug lava"></i></span>';
                break;

            case 'post':
                return '<span class="label label-success"><i class="fa fa-comment"></i></span>';
                break;

            default:
                return '<i class="fa fa-question-circle"></i>';

        }

    }

}