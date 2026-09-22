<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Support\Facades\Route;

class Navigation
{
    /**
     * Whether the user's role grants access to a menu item.
     */
    public static function canSee(?User $user, array $item): bool
    {
        if (! $user) {
            return false;
        }

        return empty($item['permission']) || $user->hasPermission($item['permission']);
    }

    /**
     * Whether a menu item should be shown. For groups, it is shown when the
     * user can see at least one child.
     */
    public static function itemVisible(?User $user, array $item): bool
    {
        if (! empty($item['children'])) {
            foreach ($item['children'] as $child) {
                if (self::canSee($user, $child)) {
                    return true;
                }
            }

            return false;
        }

        return self::canSee($user, $item);
    }

    /**
     * Whether the given item matches the current route (for highlighting).
     */
    public static function isActive(array $item): bool
    {
        $pattern = $item['match'] ?? ($item['route'] ?? null);

        return $pattern !== null && Route::currentRouteName() !== null
            && request()->routeIs($pattern);
    }

    /**
     * Whether any child of a group is the active route.
     */
    public static function groupActive(array $item): bool
    {
        foreach ($item['children'] ?? [] as $child) {
            if (self::isActive($child)) {
                return true;
            }
        }

        return false;
    }
}
