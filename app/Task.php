<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\TList;
use App\User;

class Task extends Model
{
		protected $fillable = ['name', 'completed'];
		protected $casts = ['completed'=> 'boolean'];

		public function user()
		{
			  return $this->belongsTo(User::class);
		}

		public function list()
		{
			  return $this->belongsTo(TList::class);
		}
}
