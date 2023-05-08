<?php

namespace Modules\User\Entities;

use App\Http\Middleware\Authenticate;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;
use Modules\User\Database\factories\UserFactory;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class User extends Authenticatable implements HasMedia
{
    use HasFactory, InteractsWithMedia, HasApiTokens;

    protected $fillable = [
        'id',
        'name',
        'email',
        'password'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected static function newFactory()
    {
        return UserFactory::new();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('avatar')
            ->fit(Manipulations::FIT_CROP, 360, 360)
            ->quality(85)
            ->nonQueued();
    }

    public function getAvatarAttribute(): ?string
    {
        $image = $this->getFirstMediaUrl('avatar');

        return $image ?: null;
    }

    public function configFilter(): HasMany {
        return $this->hasMany(ConfigFilter::class, 'customer_id');
    }
}
