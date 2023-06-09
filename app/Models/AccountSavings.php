<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountSavings extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'account_savings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'account_number',
        'account_type_id',
        'customer_id',
        'day_term',
        'code',
    ];
}
