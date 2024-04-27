<?php

namespace App\Models;
enum TodoItemStatus: string
{
    case TODO = 'todo';
    case INP_PROGRESS = 'in_progress';
    case DONE = 'done';
}
