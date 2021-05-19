<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{

    public function program ()
    {
        return $this->belongsTo(Program::class);
    }

    public function user ()
    {
        return $this->belongsTo(User::class);
    }

    protected $guarded = [];
}
