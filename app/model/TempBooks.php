<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $book_image
 * @property string $created_at
 * @property string $updated_at
 */
class TempBooks extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'temp_books';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['datum', 'created_at', 'updated_at'];

}
