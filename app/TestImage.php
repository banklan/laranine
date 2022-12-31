<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TestImage extends Model
{
    protected $fillable = ['image_url'];

    protected $table = 'test_images';
}
