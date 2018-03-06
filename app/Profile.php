<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class profile extends Model
{
  use SoftDeletes;

  protected $table = 'profiles';

  protected $fillable = ['user_id', 'first_name', 'last_name'];

}
