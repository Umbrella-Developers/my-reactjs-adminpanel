<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Exception;

class AsanaDonationField extends Model
{
    use HasFactory;

    protected $fillable = [
            'gid',
            'name',
            'value',
            'field_object',
            'field_type',
            'asana_donation_id',
            'status',
    ];

    protected static function boot()
    {
        parent::boot();

        static::retrieved(function ($model) {
            if (!empty($model->name)) {
                $fieldArray = [];
                $configurationsData = Configuration::where('type', 'encryption')->where('value', '1')->get();
                foreach($configurationsData as $data){
                    $fieldArray[] = $data->name;
                }
                if(in_array($model->gid, $fieldArray)){
                    $model->value = Crypt::decryptString($model->value);
                }
            }
            if (!empty($model->field_object)) {
                // $model->field_object = Crypt::decryptString($model->field_object);
            }
        });
    }

    /**
     * Decrypt the specified column.
     *
     * @param string $value
     * @return string
     */
    public function decryptColumn($value)
    {
        return Crypt::decryptString($value);
    }

    public function donation()
    {
        return $this->belongsTo(AsanaDonation::class, 'asana_donation_id', 'id');
    }
}
