<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\TList;
use App\User;

class Share extends Model
{
    protected $fillable = ['email', 'create', 'complete', 'delete', 'update'];
    protected $casts = ['create'=> 'boolean', 'complete'=> 'boolean', 'delete'=> 'boolean', 'update'=> 'boolean', ];

    public function list()
    {
    		return $this->belongsTo(TList::class);
    }

    public function user()
    {
    		return $this->belongsTo(User::class);
    }
}
