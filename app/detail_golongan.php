<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class detail_golongan extends Model
{	
    protected $table = 'detail_golongan';
    protected $fillable = ['ID_DETAIL_GOLONGAN','NAMA_DETAIL_GOLONGAN','ID_NILAI','ID_GOLONGAN'];
    public $timestamps = false;
     
}