<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsanaProject extends Model
{
    use HasFactory;

    protected $fillable = [
        'gid',
        'name',
        'status',
        'workspace_gid',
    ];

    public function donations()
    {
        return $this->hasMany(AsanaDonation::class, 'asana_project_id');
    }
}
