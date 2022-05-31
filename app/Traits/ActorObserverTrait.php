<?php

namespace App\Traits;

use App\Observers\ActorObserver;

trait ActorObserverTrait 
{
    // model action observer
    protected static function boot()
    {
        parent::boot();

        $class = get_called_class();
        $class::observe(new ActorObserver());
    }
}