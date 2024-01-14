<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{

    public function __toString()
    {
        return $this->name ?? 'No string representation available';
    }
}
