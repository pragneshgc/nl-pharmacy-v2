<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $name
 * @property string $slug
 */
class App extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    public function roles()
    {
        return $this->belongsToMany(Role::class)->using(AppRole::class);
    }

    public function modules()
    {
        return $this->belongsToMany(Module::class)->using(AppModule::class);
    }

    public function active_modules()
    {
        return $this->belongsToMany(Module::class)->using(AppModule::class)->where('status', 1);
    }
}