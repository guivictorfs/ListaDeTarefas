<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Tarefa extends Model
{
    protected $fillable = ['nome', 'custo', 'data_limite', 'ordem'];

    protected $dates = ['data_limite'];
}

