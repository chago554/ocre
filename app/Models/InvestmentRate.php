<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvestmentRate extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'instrument_name',
        'annual_rate',
    ];
}
