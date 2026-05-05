{{-- This note goes in User.php --}}
{{-- Add this method to the User model to use 'username' as the login field --}}

<?php
// Add this method inside the User class in app/Models/User.php:

    /**
     * Override the default username field for authentication.
     * Laravel's Auth::attempt() matches against this field.
     */
    public function getAuthIdentifierName(): string
    {
        return 'username';
    }
