<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\SmkMajor;
use Illuminate\Auth\Access\HandlesAuthorization;

class SmkMajorPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:SmkMajor');
    }

    public function view(AuthUser $authUser, SmkMajor $smkMajor): bool
    {
        return $authUser->can('View:SmkMajor');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:SmkMajor');
    }

    public function update(AuthUser $authUser, SmkMajor $smkMajor): bool
    {
        return $authUser->can('Update:SmkMajor');
    }

    public function delete(AuthUser $authUser, SmkMajor $smkMajor): bool
    {
        return $authUser->can('Delete:SmkMajor');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:SmkMajor');
    }

    public function restore(AuthUser $authUser, SmkMajor $smkMajor): bool
    {
        return $authUser->can('Restore:SmkMajor');
    }

    public function forceDelete(AuthUser $authUser, SmkMajor $smkMajor): bool
    {
        return $authUser->can('ForceDelete:SmkMajor');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:SmkMajor');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:SmkMajor');
    }

    public function replicate(AuthUser $authUser, SmkMajor $smkMajor): bool
    {
        return $authUser->can('Replicate:SmkMajor');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:SmkMajor');
    }

}