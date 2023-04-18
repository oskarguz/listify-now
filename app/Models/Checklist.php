<?php

namespace App\Models;

use App\Enum\ChecklistType;
use App\Enum\ModificationAccess;
use App\Enum\Visibility;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Checklist extends Model
{
    use HasFactory, HasUuids;

    protected $attributes = [
        'name' => '',
        'type' => ChecklistType::Default,
        'modification_access' => ModificationAccess::Private,
        'visibility' => Visibility::Private,
        'created_by_id' => null,
        'updated_by_id' => null,
    ];

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'type' => ChecklistType::class,
        'visibility' => Visibility::class,
        'modification_access' => ModificationAccess::class
    ];

    protected $with = ['items', 'createdBy', 'updatedBy'];

    public function items(): HasMany
    {
        return $this->hasMany(ChecklistItem::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
