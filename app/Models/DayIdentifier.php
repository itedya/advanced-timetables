<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DayIdentifier extends Model
{
    use HasFactory;

    protected $primaryKey = 'identifier';
    protected $fillable = ['identifier'];
}
