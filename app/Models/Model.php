<?php

namespace App\Models;

use Eloquence\Behaviours\CamelCasing;
use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel
{
    use CamelCasing;
}