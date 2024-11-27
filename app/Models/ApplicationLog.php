<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationLog extends Model
{
    use HasFactory;

    protected $table = "application_logs";

    protected $fillable = [
        'model' ,
        'error_type' ,
        'module' ,
        'log' ,
        'message',
        'stack_trace',
        'level',
    ];
}
