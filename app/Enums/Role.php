<?php
namespace App\Enums;

enum Role: string
{
    case USER   = 'user';
    case AUTHOR = 'author';
    case ADMIN  = 'admin';
}
