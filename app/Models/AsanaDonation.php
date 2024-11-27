<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsanaDonation extends Model
{
    use HasFactory;

    protected $fillable = [
        'gid',
        'title',
        'hit_job_id',
        'asana_project_id',
        'status',
        'user_id',
        'donation_created_at',
    ];

    public function fields()
    {
        return $this->hasMany(AsanaDonationField::class, 'asana_donation_id');
    }
    
}
