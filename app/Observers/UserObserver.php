<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    public function created(User $user) {
        $defaultCategories = config('defaultCategories');

        foreach($defaultCategories as $name => $iconPath) {
            $user->categories()->create(["name" => $name, "icon" => $iconPath]);
        }
    }
}
