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

    // Whenever the email is set, associate that emails user with the share, if they exist.
    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
        $this->user()->associate(User::where('email', $this->email)->first());
    }

    public function getCanAttribute() {
        $permissions = array_filter(['complete', 'create', 'update', 'delete'], function ($permission) {
            return $this[$permission];
        });

        if (sizeof($permissions) > 1) return join(', ', array_slice($permissions, 0, -1)) . ' and ' . end($permissions);
        elseif (sizeof($permissions) == 1) return $permissions[0];
        else return false;
    }
}
