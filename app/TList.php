<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Task;
use App\Share;

class TList extends Model
{
	protected $table = 'lists';
	protected $fillable = ['name', 'public'];
	protected $casts = ['public'=> 'boolean'];

	public function user()
	{
		  return $this->belongsTo(User::class);
	}

    public function tasks()
    {
    		return $this->hasMany(Task::class, 'list_id');
    }

    public function shares()
    {
    		return $this->hasMany(Share::class);
    }
}
