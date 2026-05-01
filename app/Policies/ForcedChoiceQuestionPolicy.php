<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ForcedChoiceQuestion;
use Illuminate\Auth\Access\HandlesAuthorization;

class ForcedChoiceQuestionPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ForcedChoiceQuestion');
    }

    public function view(AuthUser $authUser, ForcedChoiceQuestion $forcedChoiceQuestion): bool
    {
        return $authUser->can('View:ForcedChoiceQuestion');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ForcedChoiceQuestion');
    }

    public function update(AuthUser $authUser, ForcedChoiceQuestion $forcedChoiceQuestion): bool
    {
        return $authUser->can('Update:ForcedChoiceQuestion');
    }

    public function delete(AuthUser $authUser, ForcedChoiceQuestion $forcedChoiceQuestion): bool
    {
        return $authUser->can('Delete:ForcedChoiceQuestion');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:ForcedChoiceQuestion');
    }

    public function restore(AuthUser $authUser, ForcedChoiceQuestion $forcedChoiceQuestion): bool
    {
        return $authUser->can('Restore:ForcedChoiceQuestion');
    }

    public function forceDelete(AuthUser $authUser, ForcedChoiceQuestion $forcedChoiceQuestion): bool
    {
        return $authUser->can('ForceDelete:ForcedChoiceQuestion');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ForcedChoiceQuestion');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ForcedChoiceQuestion');
    }

    public function replicate(AuthUser $authUser, ForcedChoiceQuestion $forcedChoiceQuestion): bool
    {
        return $authUser->can('Replicate:ForcedChoiceQuestion');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ForcedChoiceQuestion');
    }

}