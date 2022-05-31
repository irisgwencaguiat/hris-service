<?php

namespace App\Observers;

use Auth;
use Carbon\Carbon;

class ActorObserver
{
    private $user_id;
    private $currentData;

    public function __construct()
    {
        $this->user_id = Auth::id();
        $this->currentData = Carbon::now();
    }

    public function creating($model)
    {
        $model->created_by = $this->user_id;

        $model = $this->observeIsActivated($model);
    }

    public function updating($model)
    {
        $model->updated_by = $this->user_id;

        $model = $this->observeIsActivated($model);
    }

    public function deleting($model)
    {
        $model->deleted_by = $this->user_id;
        $model->save();
    }

    /**
     * Utility methods
     */
    public function observeIsActivated($model)
    {
        // if is activated changed, update some properties
        if ($model->isDirty('is_activated')) {
            if ($model->is_activated) {
                $model->activated_by = $this->user_id;
                $model->activated_at = $this->currentData;

            } else {
                $model->deactivated_by = $this->user_id;
                $model->deactivated_at = $this->currentData;
            }
        }

        return $model;
    }
}
