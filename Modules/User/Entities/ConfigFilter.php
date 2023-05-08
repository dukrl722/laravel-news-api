<?php

namespace Modules\User\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConfigFilter extends Model
{
    use HasFactory, Uuids;

    protected $fillable = [
        'uuid',
        'customer_id',
        'type',
        'value'
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'customer_id');
    }

}
