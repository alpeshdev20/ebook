<?php

namespace App;

use Eloquent as Model;

/**
 * Class ExternalApp
 * @package App\Models
 * @version March 15, 2021, 2:15 pm UTC
 *
 * @property string $name
 * @property string $url
 * @property string $status
 */
class ExternalApp extends Model
{

    public $table = 'external_apps';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';



    protected $primaryKey = 'id';

    public $fillable = [
        'name',
        'url',
        'status',
        'public_key',
        'private_key'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'url' => 'string',
        'status' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required|string',
        'url' => 'required|string',
        'status' => 'required|string',
        'public_key' => 'required|string',
        'private_key' => 'required|string',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
