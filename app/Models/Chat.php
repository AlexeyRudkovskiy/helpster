<?php

namespace App\Models;

use App\Traits\HasUUID;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chat extends Model
{
    use HasFactory, HasUUID;

    protected $fillable = [
        'user_id', 'organization_id'
    ];

    protected $with = [
        'customer'
    ];

    protected $appends = [
        'last_message',
        'customer_name',
        'updated_timestamp'
    ];

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function lastMessage(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->messages()->latest()->first()
        );
    }

    public function customerName(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->customer->full_name
        );
    }

    public function updatedTimestamp(): Attribute
    {
        return Attribute::make(
            get: fn() => Carbon::parse($this->updated_at)->getTimestamp()
        );
    }

    public function files(): HasMany
    {
        return $this->hasMany(File::class);
    }

}
