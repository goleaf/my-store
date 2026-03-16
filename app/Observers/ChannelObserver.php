<?php

namespace App\Observers;

use App\Models\Channel;
use App\Models\Contracts;

class ChannelObserver
{
    /**
     * Handle the User "created" event.
     *
     * @return void
     */
    public function created(Contracts\Channel $channel)
    {
        $this->ensureOnlyOneDefault($channel);
    }

    /**
     * Handle the User "updated" event.
     *
     * @return void
     */
    public function updated(Contracts\Channel $channel)
    {
        $this->ensureOnlyOneDefault($channel);
    }

    /**
     * Handle the User "deleted" event.
     *
     * @return void
     */
    public function deleted(Contracts\Channel $channel)
    {
        //
    }

    /**
     * Handle the User "forceDeleted" event.
     *
     * @return void
     */
    public function forceDeleted(Contracts\Channel $channel)
    {
        //
    }

    /**
     * Ensures that only one default channel exists.
     *
     * @param  Channel  $savedChannel  The channel that was just saved.
     */
    protected function ensureOnlyOneDefault(Contracts\Channel $savedChannel): void
    {
        // Wrap here so we avoid a query if it's not been set to default.
        if ($savedChannel->default) {
            $channel = Channel::whereDefault(true)->where('id', '!=', $savedChannel->id)->first();

            if ($channel) {
                $channel->default = false;
                $channel->saveQuietly();
            }
        }
    }
}
