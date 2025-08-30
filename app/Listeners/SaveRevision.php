<?php

namespace App\Listeners;

use App\Contracts\CanSaveRevision;
use Illuminate\Support\Facades\Auth;

class SaveRevision
{
    public function __construct() {}

    public function handle(CanSaveRevision $event): void
    {

        $revisions = $event->revisions();

        $attributes = $revisions->getParent()->getAttributes();

        unset($attributes['id'], $attributes['created_at'], $attributes['updated_at'], $attributes['deleted_at']);

        $revision = new ($revisions->getRelated())($attributes);

        $revision->created_at = now();

        $revision->createdBy()->associate(Auth::user());

        $revisions->save($revision);

    }
}
