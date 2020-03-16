<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    Use Notifiable;
    Use SoftDeletes;

    protected $dates = [
		'birth',
		'deleted_at'
	];

	protected $fillable = [
		'uuid',
		'name',
		'birth',
		'email',
		'phone',
		'address',
		'complement',
        'district',
        'zip_code'
    ];

    protected $casts = [
		'birth' => 'date'
    ];

    public function isTrashed()
    {
        return empty($this->deleted_at) ? false : true;
    }

    public function order()
    {
        return $this->hasMany(Order::class, 'client_id');
    }
}
