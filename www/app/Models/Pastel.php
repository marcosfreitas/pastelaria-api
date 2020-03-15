<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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
        'birth' => 'date',
        'price' => 'decimal:2'
    ];

    /**
     * Relationship Many to Many
     */

     public function order()
     {
         return $this->belongsToMany(Pastel::class, 'order_pastels');
     }
}
