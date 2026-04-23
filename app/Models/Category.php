<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'icon',
        'type',
    ];


    /**
     *  Relacion con la tabla transactions
     *
     * @return void
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
