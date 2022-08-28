<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $dateFormat = 'U';
    protected $casts = [
        'publish_date' => 'timestamp',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
//        'publish_date'=>'datetime:U',
//        'created_at'=>'datetime:U',
//        'updated_at'=>'datetime:U',
        'texts' => 'array'
    ];
    protected $dates = [
        'publish_date',
        'created_at',
        'updated_at',
    ];
    protected $fillable = [
        'title',
        'subtitle',
        'img',
        'link',
        'publish_date',
        'texts',
        'created_at',
        'updated_at',
    ];

}
