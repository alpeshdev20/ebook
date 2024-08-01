<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class Subscription
 * @package App\Models
 * @version August 8, 2020, 9:22 am UTC
 *
 * @property string user_id
 * @property string transaction_id
 * @property string subscription_plan_id
 * @property string plan_validity
 * @property string bank_ref_no
 * @property string order_status
 * @property string failure_message
 * @property string payment_mode
 * @property string card_name
 * @property string status_code
 * @property string status_message
 * @property string currency
 * @property integer amount
 */
class Subscription extends Model
{

    public $table = 'subscriptions';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';



    protected $primaryKey = 'id';

    public $fillable = [
        'user_id',
        'transaction_id',
        'subscription_plan_id',
        'plan_validity',
        'bank_ref_no',
        'order_status',
        'failure_message',
        'payment_mode',
        'card_name',
        'status_code',
        'status_message',
        'currency',
        'amount'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'string',
        'transaction_id' => 'string',
        'subscription_plan_id' => 'string',
        'plan_validity' => 'string',
        'bank_ref_no' => 'string',
        'order_status' => 'string',
        'failure_message' => 'string',
        'payment_mode' => 'string',
        'card_name' => 'string',
        'status_code' => 'string',
        'status_message' => 'string',
        'currency' => 'string',
        'amount' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'user_id' => 'required',
        'transaction_id' => 'required',
        'subscription_plan_id' => 'required',
        'plan_validity' => 'required'
    ];

    
}
