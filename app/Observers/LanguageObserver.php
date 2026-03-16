<?php

namespace App\Observers;

use App\Facades\DB;
use App\Models\Language;
use App\Models\Contracts;

class LanguageObserver
{
    /**
     * Handle the Language "created" event.
     *
     * @return void
     */
    public function created(Contracts\Language $language)
    {
        $this->ensureOnlyOneDefault($language);
    }

    /**
     * Handle the Language "updated" event.
     *
     * @return void
     */
    public function updated(Contracts\Language $language)
    {
        $this->ensureOnlyOneDefault($language);
    }

    /**
     * Handle the Language "deleted" event.
     *
     * @return void
     */
    public function deleting(Contracts\Language $language)
    {
        DB::transaction(function () use ($language) {
            $language->urls()->delete();
        });
    }

    /**
     * Handle the Language "forceDeleted" event.
     *
     * @return void
     */
    public function forceDeleted(Contracts\Language $language)
    {
        //
    }

    /**
     * Ensures that only one default language exists.
     *
     * @param  \App\Models\Contracts\Language  $savedLanguage  The language that was just saved.
     */
    protected function ensureOnlyOneDefault(Contracts\Language $savedLanguage): void
    {
        // Wrap here so we avoid a query if it's not been set to default.
        if ($savedLanguage->default) {
            Language::withoutEvents(function () use ($savedLanguage) {
                Language::whereDefault(true)->where('id', '!=', $savedLanguage->id)->update([
                    'default' => false,
                ]);
            });
        }
    }
}
