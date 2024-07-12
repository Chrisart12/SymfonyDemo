<?php

namespace App\Enums;

enum CodeStatus: int
{
    case NotFound = 404;
    case ErrorServer = 500;
    case NotAllow = 403;
    case Success = 200;
}