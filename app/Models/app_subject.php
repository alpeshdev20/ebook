<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

/**
 * Class app_subject
 * @package App\Models
 * @version May 5, 2020, 8:29 am UTC
 *
 * @property string $subject_name
 * @property string $department_id
 */
class app_subject extends Model
{

    public $table = 'app_subject';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';



    protected $primaryKey = 'id';

    public $fillable = [
        'subject_name',
        'department_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'subject_name' => 'string',
        'department_id' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'subject_name' => 'required',
        'department_id' => 'required'
    ];

    
}
