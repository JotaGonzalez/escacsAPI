<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class game extends Model
{
    public $primaryKey  = 'id';
    protected $table = 'game';

    public function retrievePieces() {
        return $this->hasMany('App\Pieza', 'id');
    }
}
