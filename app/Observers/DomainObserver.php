<?php

namespace App\Observers;

use App\Domain;
use Illuminate\Support\Facades\Log;

class DomainObserver
{
    /**
     * Handle the domain "created" event.
     *
     * @param  \App\Domain  $domain
     * @return void
     */
    public function created(Domain $domain)
    {
        try {
            api()->store('domains', [
                'name' => $domain->name,
                'url' => $domain->url
            ]);
        }
        catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    /**
     * Handle the domain "updated" event.
     *
     * @param  \App\Domain  $domain
     * @return void
     */
    public function updated(Domain $domain)
    {
        try {
            $response = api()->update('domains', $domain->id, [
                'name' => $domain->name,
                'url' => $domain->url
            ]);

            if (is_null($response)) {
                Log::debug('Domain for the some reason not found in API. Attempt to create domain...');

                try {
                    api()->store('domains', [
                        'name' => $domain->name,
                        'url' => $domain->url
                    ]);
                }
                catch (\Exception $e) {
                    Log::error($e->getMessage());
                }
            }
        }
        catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    /**
     * Handle the domain "deleted" event.
     *
     * @param  \App\Domain  $domain
     * @return void
     */
    public function deleted(Domain $domain)
    {
        try {
            api()->destroy('domains', $domain->id);
        }
        catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    /**
     * Handle the domain "restored" event.
     *
     * @param  \App\Domain  $domain
     * @return void
     */
    public function restored(Domain $domain)
    {
        //
    }

    /**
     * Handle the domain "force deleted" event.
     *
     * @param  \App\Domain  $domain
     * @return void
     */
    public function forceDeleted(Domain $domain)
    {
        try {
            api()->destroy('domains', $domain->id);
        }
        catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
