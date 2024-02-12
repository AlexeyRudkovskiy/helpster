<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @property Collection $users
 * @property Collection $chats
 */
class Organization extends Model
{
    use HasFactory;

    protected $fillable = [ 'name', 'app_id', 'title', 'subtitle' ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function chats(): HasMany
    {
        return $this->hasMany(Chat::class);
    }

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }

}
