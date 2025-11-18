<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

final class Instance extends Model
{
    /** @use HasFactory<\Database\Factories\InstanceFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'url',
    ];

    public function url(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => Str::finish($value, '/'),
            set: fn (string $value) => $value,
        );
    }
}
