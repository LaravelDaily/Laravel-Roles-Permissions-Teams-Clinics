<?php

namespace App\Enums;

enum Permission: string
{
    case LIST_TEAM = 'list-team';
    case CREATE_TEAM = 'create-team';

    case LIST_USER = 'list-user';
    case CREATE_USER = 'create-user';

    case LIST_TASK = 'list-task';
    case CREATE_TASK = 'create-task';
    case EDIT_TASK = 'edit-task';
    case DELETE_TASK = 'delete-task';

    case SWITCH_TEAM = 'switch-team';
}
