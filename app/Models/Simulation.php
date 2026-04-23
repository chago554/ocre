<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Simulation extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'initial_capital',
        'months_term',
        'final_yied'
    ];

    /**
     * Relacion con la tabla users
     *
     * @return void
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
