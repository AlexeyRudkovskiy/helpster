<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property Collection chats
 * @property string full_name
 * @property string description
 */
class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'description',
        'unique_id'
    ];

    public function chats()
    {
        return $this->hasMany(Chat::class);
    }

}
