<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class User_rates extends Pivot
{

    protected $table = 'user_rates';

    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
}
