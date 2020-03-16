<?php

namespace App\Observers;

use App\Models\Pastel;
use Illuminate\Support\Str;

class PastelObserver
{
    /**
     * Handle the pastel "creating" event.
     *
     * @param  \App\Models\Pastel  $pastel
     * @return void
     */
    public function creating(Pastel $pastel)
    {
        if (empty($pastel->uuid)) {
			$pastel->uuid = Str::uuid();
		}
    }

    /**
     * Handle the pastel "updated" event.
     *
     * @param  \App\Models\Pastel  $pastel
     * @return void
     */
    public function updated(Pastel $pastel)
    {
        //
    }

    /**
     * Handle the pastel "deleted" event.
     *
     * @param  \App\Models\Pastel  $pastel
     * @return void
     */
    public function deleted(Pastel $pastel)
    {
        //
    }

    /**
     * Handle the pastel "restored" event.
     *
     * @param  \App\Models\Pastel  $pastel
     * @return void
     */
    public function restored(Pastel $pastel)
    {
        //
    }

    /**
     * Handle the pastel "force deleted" event.
     *
     * @param  \App\Models\Pastel  $pastel
     * @return void
     */
    public function forceDeleted(Pastel $pastel)
    {
        //
    }
}
