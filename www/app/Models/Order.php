<?php

namespace App\Models;

use App\Models\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    Use SoftDeletes;

    protected $dates = [
		'deleted_at'
	];

	protected $fillable = [
		'uuid',
    ];

    /**
     * Relationship Many to Many
     */
     public function pastel()
     {
         return $this->belongsToMany(Pastel::class, 'order_pastels');
     }

     public function client()
     {
         return $this->belongsTo(Client::class, 'client_id');
     }
}
