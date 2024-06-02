<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class Place extends Model
{
    use HasFactory;
    // protected $fillable = [];
    protected $guarded = [];

    public function getImageAsset()
    {
        if (File::exists($this->image)) {
            return asset($this->image);
        }

        return 'https://placehold.co/80x80?text=No+Image';
    }
}
