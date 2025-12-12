<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryImage extends Model
{
    protected $table = 'gallery_images';
    protected $fillable = ['user_id', 'image'];
    public $timestamps = true;

    public function upload()
    {
        return $this->belongsTo(Upload::class, 'image', 'id');
    }
}
