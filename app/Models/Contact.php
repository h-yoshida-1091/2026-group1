<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    // 一括代入（Mass Assignment）を許可するカラムを指定
    protected $fillable = ['name', 'email', 'subject', 'message'];
}
