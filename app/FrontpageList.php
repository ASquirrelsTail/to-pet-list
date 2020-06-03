<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\TList;

class FrontpageList extends Model
{

    public function list()
    {
    		return $this->belongsTo(TList::class, 'list_id');
    }
}
