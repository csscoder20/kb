<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Observers\EmailSettingsObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy([EmailSettingsObserver::class])]

class EmailSettings extends Model
{
    protected $table = 'email_settings';
    protected $fillable = ['key', 'value'];

    public $timestamps = true;

    public static function getValue(string $key, mixed $default = null): mixed
    {
        return static::where('key', $key)->value('value') ?? $default;
    }

    public static function setValue(string $key, mixed $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
    }

    public static function getAllAsArray(): array
    {
        return static::all()->pluck('value', 'key')->toArray();
    }

    public static function setBulk(array $data): void
    {
        foreach ($data as $key => $value) {
            static::setValue($key, $value);
        }
    }
}
