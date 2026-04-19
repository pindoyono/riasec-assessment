<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\RiasecCategory;
use Illuminate\Auth\Access\HandlesAuthorization;

class RiasecCategoryPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:RiasecCategory');
    }

    public function view(AuthUser $authUser, RiasecCategory $riasecCategory): bool
    {
        return $authUser->can('View:RiasecCategory');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:RiasecCategory');
    }

    public function update(AuthUser $authUser, RiasecCategory $riasecCategory): bool
    {
        return $authUser->can('Update:RiasecCategory');
    }

    public function delete(AuthUser $authUser, RiasecCategory $riasecCategory): bool
    {
        return $authUser->can('Delete:RiasecCategory');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:RiasecCategory');
    }

    public function restore(AuthUser $authUser, RiasecCategory $riasecCategory): bool
    {
        return $authUser->can('Restore:RiasecCategory');
    }

    public function forceDelete(AuthUser $authUser, RiasecCategory $riasecCategory): bool
    {
        return $authUser->can('ForceDelete:RiasecCategory');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:RiasecCategory');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:RiasecCategory');
    }

    public function replicate(AuthUser $authUser, RiasecCategory $riasecCategory): bool
    {
        return $authUser->can('Replicate:RiasecCategory');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:RiasecCategory');
    }

}