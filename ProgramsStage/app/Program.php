<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    public function user ()
    {
        return $this->belongsTo(User::class);
    }

    public function links ()
    {
        return $this->hasMany(Link::class);
    }
    protected $guarded = [];
}
