<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiscountCode extends Model
{
    /** Popust koji kod donosi (%). */
    public const PERCENT = 10;

    /** Koliko minuta od generisanja kod važi. */
    public const VALID_MINUTES = 15;

    protected $fillable = ['code', 'email', 'is_used', 'used_at'];

    protected $casts = [
        'is_used' => 'boolean',
        'used_at' => 'datetime',
    ];

    public static function normalizeCode(?string $code): string
    {
        return strtoupper(trim((string) $code));
    }

    public static function normalizeEmail(?string $email): string
    {
        return strtolower(trim((string) $email));
    }

    public static function findFor(?string $code, ?string $email): ?self
    {
        return static::where('code', static::normalizeCode($code))
            ->where('email', static::normalizeEmail($email))
            ->first();
    }

    /** null ako je kod upotrebljiv, inače razlog: not_found | already_used | expired */
    public static function rejectionReason(?self $row): ?string
    {
        if (! $row) {
            return 'not_found';
        }
        if ($row->is_used) {
            return 'already_used';
        }
        if ($row->created_at->addMinutes(self::VALID_MINUTES)->isPast()) {
            return 'expired';
        }

        return null;
    }
}
