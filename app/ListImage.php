<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Tlist;

class ListImage extends Model
{
	  protected $table = 'images';
    protected $fillable = ['path'];

    public function list()
    {
    	  return $this->belongsTo(TList::class, 'list_id');
    }
}
