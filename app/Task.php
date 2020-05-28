<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\TList;
use App\User;

class Task extends Model
{
		protected $fillable = ['name', 'completed', 'new_position'];
		protected $casts = ['completed'=> 'boolean'];
		protected $hidden = ['user', 'list'];

		public function user()
		{
			  return $this->belongsTo(User::class);
		}

		public function list()
		{
			  return $this->belongsTo(TList::class);
		}

		public function setNewPositionAttribute($new_position)
		{
				if ($new_position < $this->list->tasks()->count() - 1) {
				    if ($this->id != $this->list->tasks()->orderBy('position', 'asc')->skip($new_position)->first()->id) {
				        if ($new_position > 0) {
				            $adjacent_tasks = $this->list->tasks()
				            														 ->orderBy('position', 'asc')
		                                             ->skip($new_position - 1)
		                                             ->take(2)
		                                             ->get();

				            $this->position = $adjacent_tasks->avg('position');

				            if (abs($this->position-$adjacent_tasks[0]->position) < 0.01) {
				                $this->position += 1;
				                $this->list->tasks()->where('position', '>', $adjacent_tasks[0]->position)->increment('position', 1);
				            } elseif (abs($this->position-$adjacent_tasks[1]->position) < 0.01) {
				                $this->list->tasks()->where('position', '>=', $adjacent_tasks[1]->position)->increment('position', 1);
				            }
				        } else {
				            $this->position = $this->list->tasks()->orderBy('position', 'asc')->first()->position / 2;
				            if ($this->position < 0.01) {
				                $this->position++;
				                $this->list->tasks()->increment('position', 1);
				            }
				        }
				    }
				} else {
				    $this->position = $this->list->tasks()->orderBy('position', 'desc')->first()->position + 1;
				}
		}
}
