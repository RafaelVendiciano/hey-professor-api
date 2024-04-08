<?php
use App\Models\User;

if(!function_exists('user')) {
    
    function user(): ?User {
        if(auth()->check()) {
            /** @var User $user */
            $user = auth()->user();
            return $user;
        }

        return null;
    }
}