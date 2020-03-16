<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pastel extends Model
{
    Use SoftDeletes;

    protected $dates = [
		'deleted_at'
	];

	protected $fillable = [
		'name',
		'price',
		'photo',
    ];

    protected $casts = [
        'price' => 'decimal:2'
    ];

    /**
     * Relationship Many to Many
     */

     public function order()
     {
         return $this->belongsToMany(Pastel::class, 'order_pastels');
     }

     /**
      * Photo atribute mutator
      *
      * @return string
      */
     public function getPhotoAttribute()
     {

    	if (Storage::disk('local')->exists('pastels/'.(string) $this->uuid)) {

            $images = Storage::disk('local')->files('pastels/'.(string) $this->uuid);
            $extension = explode('.', $images[0]);

    		return [
                'image_path' => $images[0],
                'extension' => end($extension)
            ];

        }

    	return [];
	}
}
