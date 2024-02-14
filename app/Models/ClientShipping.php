<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientShipping extends Model
{
    use HasFactory;

    protected $fillable = ['client_id', 'content'];

    protected $casts = [
        "content" => "array"
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'ClientID', 'client_id');
    }
}
