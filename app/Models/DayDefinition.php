<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class DayDefinition extends Model
{
    use HasFactory;

    public function dayIdentifiers(): BelongsToMany
    {
        return $this->belongsToMany(
            DayIdentifier::class,
            'day_definitions_have_day_identifiers',
            'day_definition_id',
            'day_identifier'
        );
    }
}
