<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Short_url extends Model
{
    protected $fillable = ['url_mobile','url_tablet','url_desktop'];
}
