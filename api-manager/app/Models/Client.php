<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id', 'name', 'email', 'phone', 
        'city', 'state', 'photo', 'age', 'welcome_email_sent'
    ];
}
