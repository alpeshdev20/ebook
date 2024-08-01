<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

/**
 * Class app_department
 * @package App\Models
 * @version May 5, 2020, 8:28 am UTC
 *
 * @property string $department_name
 * @property string $genre_id
 */
class app_department extends Model
{

    public $table = 'app_department';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';



    protected $primaryKey = 'id';

    public $fillable = [
        'department_name',
        'genre_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'department_name' => 'string',
        'genre_id' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'department_name' => 'required',
        'genre_id' => 'required'
    ];

    
}
