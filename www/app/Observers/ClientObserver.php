<?php

namespace App\Observers;

use App\Models\Client;
use Illuminate\Support\Str;

class ClientObserver
{
    /**
     * Handle the app models client "creating" event.
     *
     * @param  \App\Models\Client  $client
     * @return void
     */
    public function creating(Client $client)
    {
        if (empty($client->uuid)) {
			$client->uuid = Str::uuid();
		}
    }

    /**
     * Handle the app models client "updated" event.
     *
     * @param  \App\Models\Client  $client
     * @return void
     */
    public function updated(Client $client)
    {
        //
    }

    /**
     * Handle the app models client "deleted" event.
     *
     * @param  \App\Models\Client  $client
     * @return void
     */
    public function deleted(Client $client)
    {
        //
    }

    /**
     * Handle the app models client "restored" event.
     *
     * @param  \App\Models\Client  $client
     * @return void
     */
    public function restored(Client $client)
    {
        //
    }

    /**
     * Handle the app models client "force deleted" event.
     *
     * @param  \App\Models\Client  $client
     * @return void
     */
    public function forceDeleted(Client $client)
    {
        //
    }
}
