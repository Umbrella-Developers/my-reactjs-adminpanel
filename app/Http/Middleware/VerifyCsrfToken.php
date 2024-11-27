<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'faqs', 
        'web', 
        'faqs/2',
        'configurations/create',
        'configurations',
        'configurations/1/edit',
        'configurations/1',
        'permissions?name=staffs',
        'permissions',
        'roles/rolePermissionAssociation/17',
        'store?two_factor_code=594661&user_id=104',
        'store',
        'verify',
        'auth/login',
        'users/updatePassword/110?password=testpassword&confirm_password=testpassword',
        'users/updatePassword',
        'users',
        'users/updatePassword/110',
        'auth/logout',
        'users/verifyEmail',
        'users/passwordResetEmail',
        'users/newPassword',
        'configurations/generalSettings',
        'socialMediaUpdate',
        'emailTemplatesUpdate',
        'configurations/generalSettingsEdit',
        'configurations/socialMediaEdit',
        'configurations/emailTemplatesEdit',
        'configurations/update/generalSettingsUpdate',
        'configurations/update/socialMediaUpdate',
        'configurations/update/emailTemplatesUpdate',
        'applicationlogs',
        'auth/logout',
        'logout',
        '/users/verifyUserEmail',

    ];
}
