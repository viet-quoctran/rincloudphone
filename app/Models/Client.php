<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'api_token',
    ];

    protected $hidden = [
        'password',
        'api_token',
    ];

    /**
     * Tạo mã token duy nhất cho client.
     */
    public static function generateUniqueToken()
    {
        do {
            $token = bin2hex(random_bytes(30));
        } while (self::where('api_token', $token)->exists());

        return $token;
    }
}
