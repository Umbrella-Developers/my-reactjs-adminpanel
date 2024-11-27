<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:
    <h1>Application Setup</h1>
<p>
    <h2>Requirements</h2>
    <ul>
        <li>PHP version that is being used for the development of the application is 7.4.19.</li>
        <li>MYSQL version being used during the development is 5.7.33.</li>
        <li>You will need to install any local server like (XAMP, Laragon, Wamp) etc.</li>
        <li>You will need to install composer in your system as it is required to run a Laravel application.</li>
    </ul>
</p>
    <h2>For setting up Laravel project</h2>
    <p>Go to GitHub and move to your repository. Clone the code in the git repository. After cloning, open up the application code inside an IDE like VS Code and do the following.</p>
<p>
    <h2>Environment and Database Setup</h2>
    <p>Laravel environment settings have been saved in the <code>.env.example</code> file. In this step, we configure the Application settings.</p>
    <p>We need to fill in the following details to set the environment:</p>
    <ul>
        <li>App Name</li>
        <li>App URL: <code>http://xyz.com/(root directory folder name)</code></li>
        <li>Database Connection</li>
        <li>Database Host</li>
        <li>Database Port</li>
        <li>Database Name</li>
        <li>Database User Name</li>
        <li>Database Password</li>
    </ul>
    <p>Create a new database inside MYSQL. Once everything is set, go to the application folder and open the terminal.</p>
    <p>There is a database backup also available inside the project folder. If you import the backup, you can skip points 3 and 4.</p>
    <p>Run the following commands to proceed:</p>
    <ol>
        <li>composer install</li>
        <li>php artisan optimize</li>
        <li>php artisan migrate</li>
        <li>php artisan db:seed</li>
        <li>php artisan serve</li>
    </ol>
    <p>With <code>composer install</code> it will install the required packages.</p>
    <p>With <code>php artisan optimize</code> it will clear cache and configurations.</p>
    <p>With <code>php artisan migrate</code> it will migrate database tables to your newly created database inside MYSQL.</p>
    <p>With <code>php artisan db:seed</code> it will update tables with data for users, roles, and permissions.</p>
    <p>With <code>php artisan serve</code> you will be able to run the project in a local environment.</p>
    <p>Once the application is started, visit <a href="http://localhost:8000/login">http://localhost:8000/login</a> (the port can be different in your case).</p>
    <p>After logging in, you will start seeing the response.</p>
</p>
    <p>
    <h2>To login to the system use the following credentials</h2>
    <h3>For Super Admin</h3>
    <ul>
        <li>Email: <code>superadmin@example.com</code></li>
        <li>Password: <code>password</code></li>
    </ul>
    <h3>For Admin</h3>
    <ul>
        <li>Email: <code>admin@example.com</code></li>
        <li>Password: <code>password</code></li>
    </ul>
</p>
     <h1>API Documentation</h1>
    <h2>Middleware Flow</h2>
    <ol>
        <li>
            <strong>Device Check</strong><br>
            <strong>Method:</strong> Middleware<br>
            <strong>Route:</strong> check.device<br>
            <strong>Description:</strong> Ensures the request is coming from a verified device.
        </li>
        <li>
            <strong>Sanctum Authentication</strong><br>
            <strong>Method:</strong> Middleware<br>
            <strong>Route:</strong> auth<br>
            <strong>Description:</strong> Protects routes requiring user authentication via Laravel Sanctum.
        </li>
        <li>
            <strong>Additional Middleware</strong><br>
            <strong>Method:</strong> Middleware<br>
            <strong>Route:</strong> auth, verified, permissions<br>
            <strong>Description:</strong> Ensures user authentication, email verification, and proper permissions.
        </li>
    </ol>
    <h2>Authentication Flow</h2>
    <ul>
        <li>
            <strong>Display Login Page</strong><br>
            <strong>Method:</strong> GET<br>
            <strong>Route:</strong> /login<br>
            <strong>Description:</strong> Display login page.<br>
            <strong>Controller:</strong> AuthController@show
        </li>
        <li>
            <strong>User Registration</strong><br>
            <strong>Method:</strong> POST<br>
            <strong>Route:</strong> /auth/register<br>
            <strong>Description:</strong> Registers a new user.<br>
            <strong>Controller:</strong> AuthController@store
        </li>
        <li>
            <strong>User Login</strong><br>
            <strong>Method:</strong> POST<br>
            <strong>Route:</strong> /auth/login<br>
            <strong>Description:</strong> Authenticates a user.<br>
            <strong>Controller:</strong> AuthController@login
        </li>
        <li>
            <strong>User Logout</strong><br>
            <strong>Method:</strong> POST<br>
            <strong>Route:</strong> /auth/logout<br>
            <strong>Description:</strong> Logs out the user.<br>
            <strong>Controller:</strong> AuthController@logout
        </li>
        <li>
            <strong>Retrieve Authenticated User</strong><br>
            <strong>Method:</strong> GET<br>
            <strong>Route:</strong> /user<br>
            <strong>Description:</strong> Retrieves the authenticated user's information.<br>
            <strong>Controller:</strong> function (Request $request) { return $request->user(); }
        </li>
    </ul>
    <h2>User Management Flow</h2>
    <ul>
        <li>
            <strong>List Users</strong><br>
            <strong>Method:</strong> GET<br>
            <strong>Route:</strong> /users<br>
            <strong>Description:</strong> Lists all users.<br>
            <strong>Controller:</strong> UserController@index
        </li>
        <li>
            <strong>Create User</strong><br>
            <strong>Method:</strong> GET<br>
            <strong>Route:</strong> /users/create<br>
            <strong>Description:</strong> Displays form to create a new user.<br>
            <strong>Controller:</strong> UserController@create
        </li>
        <li>
            <strong>Store User</strong><br>
            <strong>Method:</strong> POST<br>
            <strong>Route:</strong> /users<br>
            <strong>Description:</strong> Stores a new user.<br>
            <strong>Controller:</strong> UserController@store
        </li>
        <li>
            <strong>Show User</strong><br>
            <strong>Method:</strong> GET<br>
            <strong>Route:</strong> /users/{user}<br>
            <strong>Description:</strong> Shows a single user.<br>
            <strong>Controller:</strong> UserController@show
        </li>
        <li>
            <strong>Edit User</strong><br>
            <strong>Method:</strong> GET<br>
            <strong>Route:</strong> /users/{user}/edit<br>
            <strong>Description:</strong> Displays form to edit user information.<br>
            <strong>Controller:</strong> UserController@edit
        </li>
        <li>
            <strong>Update User</strong><br>
            <strong>Method:</strong> PUT<br>
            <strong>Route:</strong> /users/{user}<br>
            <strong>Description:</strong> Updates user information.<br>
            <strong>Controller:</strong> UserController@update
        </li>
        <li>
            <strong>Delete User</strong><br>
            <strong>Method:</strong> DELETE<br>
            <strong>Route:</strong> /users/{user}<br>
            <strong>Description:</strong> Deletes a user.<br>
            <strong>Controller:</strong> UserController@destroy
        </li>
    </ul>
    <h2>Permission Management Flow</h2>
    <ul>
        <li>
            <strong>List Permissions</strong><br>
            <strong>Method:</strong> GET<br>
            <strong>Route:</strong> /permissions<br>
            <strong>Description:</strong> Lists all permissions.<br>
            <strong>Controller:</strong> PermissionController@index
        </li>
        <li>
            <strong>Create Permission</strong><br>
            <strong>Method:</strong> GET<br>
            <strong>Route:</strong> /permissions/create<br>
            <strong>Description:</strong> Displays form to create a new permission.<br>
            <strong>Controller:</strong> PermissionController@create
        </li>
        <li>
            <strong>Store Permission</strong><br>
            <strong>Method:</strong> POST<br>
            <strong>Route:</strong> /permissions<br>
            <strong>Description:</strong> Stores a new permission.<br>
            <strong>Controller:</strong> PermissionController@store
        </li>
        <li>
            <strong>Show Permission</strong><br>
            <strong>Method:</strong> GET<br>
            <strong>Route:</strong> /permissions/{permission}<br>
            <strong>Description:</strong> Shows a single permission.<br>
            <strong>Controller:</strong> PermissionController@show
        </li>
        <li>
            <strong>Edit Permission</strong><br>
            <strong>Method:</strong> GET<br>
            <strong>Route:</strong> /permissions/{permission}/edit<br>
            <strong>Description:</strong> Displays form to edit permission information.<br>
            <strong>Controller:</strong> PermissionController@edit
        </li>
        <li>
            <strong>Update Permission</strong><br>
            <strong>Method:</strong> PUT<br>
            <strong>Route:</strong> /permissions/{permission}<br>
            <strong>Description:</strong> Updates permission information.<br>
            <strong>Controller:</strong> PermissionController@update
        </li>
        <li>
            <strong>Delete Permission</strong><br>
            <strong>Method:</strong> DELETE<br>
            <strong>Route:</strong> /permissions/{permission}<br>
            <strong>Description:</strong> Deletes a permission.<br>
            <strong>Controller:</strong> PermissionController@destroy
        </li>
    </ul>
    <h2>Role Management Flow</h2>
    <ul>
        <li>
            <strong>List Roles</strong><br>
            <strong>Method:</strong> GET<br>
            <strong>Route:</strong> /roles<br>
            <strong>Description:</strong> Lists all roles.<br>
            <strong>Controller:</strong> RoleController@index
        </li>
        <li>
            <strong>Create Role</strong><br>
            <strong>Method:</strong> GET<br>
            <strong>Route:</strong> /roles/create<br>
            <strong>Description:</strong> Displays form to create a new role.<br>
            <strong>Controller:</strong> RoleController@create
        </li>
        <li>
            <strong>Store Role</strong><br>
            <strong>Method:</strong> POST<br>
            <strong>Route:</strong> /roles<br>
            <strong>Description:</strong> Stores a new role.<br>
            <strong>Controller:</strong> RoleController@store
        </li>
        <li>
            <strong>Show Role</strong><br>
            <strong>Method:</strong> GET<br>
            <strong>Route:</strong> /roles/{role}<br>
            <strong>Description:</strong> Shows a single role.<br>
            <strong>Controller:</strong> RoleController@show
        </li>
        <li>
            <strong>Edit Role</strong><br>
            <strong>Method:</strong> GET<br>
            <strong>Route:</strong> /roles/{role}/edit<br>
            <strong>Description:</strong> Displays form to edit role information.<br>
            <strong>Controller:</strong> RoleController@edit
        </li>
        <li>
            <strong>Update Role</strong><br>
            <strong>Method:</strong> PUT<br>
            <strong>Route:</strong> /roles/{role}<br>
            <strong>Description:</strong> Updates role information.<br>
            <strong>Controller:</strong> RoleController@update
        </li>
        <li>
            <strong>Delete Role</strong><br>
            <strong>Method:</strong> DELETE<br>
            <strong>Route:</strong> /roles/{role}<br>
            <strong>Description:</strong> Deletes a role.<br>
            <strong>Controller:</strong> RoleController@destroy
        </li>
        <li>
            <strong>Associate Permissions to Role</strong><br>
            <strong>Method:</strong> POST<br>
            <strong>Route:</strong> /roles/rolePermissionAssociation/{id}<br>
            <strong>Description:</strong> Associates permissions to a role.<br>
            <strong>Controller:</strong> RoleController@association
        </li>
        <li>
            <strong>Get Role Permissions</strong><br>
            <strong>Method:</strong> GET<br>
            <strong>Route:</strong> /roles/getRolePermissions/{id}<br>
            <strong>Description:</strong> Retrieves permissions associated with a role.<br>
            <strong>Controller:</strong> RoleController@rolePermissions
        </li>
    </ul>
    <h2>Page Management Flow</h2>
    <ul>
        <li>
            <strong>List Pages</strong><br>
            <strong>Method:</strong> GET<br>
            <strong>Route:</strong> /pages<br>
            <strong>Description:</strong> Lists all pages.<br>
            <strong>Controller:</strong> PageController@index
        </li>
        <li>
            <strong>Create Page</strong><br>
            <strong>Method:</strong> GET<br>
            <strong>Route:</strong> /pages/create<br>
            <strong>Description:</strong> Displays form to create a new page.<br>
            <strong>Controller:</strong> PageController@create
        </li>
        <li>
            <strong>Store Page</strong><br>
            <strong>Method:</strong> POST<br>
            <strong>Route:</strong> /pages<br>
            <strong>Description:</strong> Stores a new page.<br>
            <strong>Controller:</strong> PageController@store
        </li>
        <li>
            <strong>Show Page</strong><br>
            <strong>Method:</strong> GET<br>
            <strong>Route:</strong> /pages/{page}<br>
            <strong>Description:</strong> Shows a single page.<br>
            <strong>Controller:</strong> PageController@show
        </li>
        <li>
            <strong>Edit Page</strong><br>
            <strong>Method:</strong> GET<br>
            <strong>Route:</strong> /pages/{page}/edit<br>
            <strong>Description:</strong> Displays form to edit page information.<br>
            <strong>Controller:</strong> PageController@edit
        </li>
        <li>
            <strong>Update Page</strong><br>
            <strong>Method:</strong> PUT<br>
            <strong>Route:</strong> /pages/{page}<br>
            <strong>Description:</strong> Updates page information.<br>
            <strong>Controller:</strong> PageController@update
        </li>
        <li>
            <strong>Delete Page</strong><br>
            <strong>Method:</strong> DELETE<br>
            <strong>Route:</strong> /pages/{page}<br>
            <strong>Description:</strong> Deletes a page.<br>
            <strong>Controller:</strong> PageController@destroy
        </li>
    </ul>
    <h2>Configuration Management Flow</h2>
    <ul>
        <li>
            <strong>List Configurations</strong><br>
            <strong>Method:</strong> GET<br>
            <strong>Route:</strong> /configurations<br>
            <strong>Description:</strong> Lists all configurations.<br>
            <strong>Controller:</strong> ConfigurationController@index
        </li>
        <li>
            <strong>Create Configuration</strong><br>
            <strong>Method:</strong> GET<br>
            <strong>Route:</strong> /configurations/create<br>
            <strong>Description:</strong> Displays form to create a new configuration.<br>
            <strong>Controller:</strong> ConfigurationController@create
        </li>
        <li>
            <strong>Show Configuration</strong><br>
            <strong>Method:</strong> GET<br>
            <strong>Route:</strong> /configurations/{configuration}<br>
            <strong>Description:</strong> Shows a single configuration.<br>
            <strong>Controller:</strong> ConfigurationController@show
        </li>
        <li>
            <strong>Edit Configuration</strong><br>
            <strong>Method:</strong> GET<br>
            <strong>Route:</strong> /configurations/{configuration}/edit<br>
            <strong>Description:</strong> Displays form to edit configuration information.<br>
            <strong>Controller:</strong> ConfigurationController@edit
        </li>
        <li>
            <strong>Delete Configuration</strong><br>
            <strong>Method:</strong> DELETE<br>
            <strong>Route:</strong> /configurations/{configuration}<br>
            <strong>Description:</strong> Deletes a configuration.<br>
            <strong>Controller:</strong> ConfigurationController@destroy
        </li>
    </ul>
    <h2>FAQ Management Flow</h2>
    <ul>
        <li>
            <strong>List FAQs</strong><br>
            <strong>Method:</strong> GET<br>
            <strong>Route:</strong> /faqs<br>
            <strong>Description:</strong> Lists all FAQs.<br>
            <strong>Controller:</strong> FaqController@index
        </li>
        <li>
            <strong>Create FAQ</strong><br>
            <strong>Method:</strong> GET<br>
            <strong>Route:</strong> /faqs/create<br>
            <strong>Description:</strong> Displays form to create a new FAQ.<br>
            <strong>Controller:</strong> FaqController@create
        </li>
        <li>
            <strong>Store FAQ</strong><br>
            <strong>Method:</strong> POST<br>
            <strong>Route:</strong> /faqs<br>
            <strong>Description:</strong> Stores a new FAQ.<br>
            <strong>Controller:</strong> FaqController@store
        </li>
        <li>
            <strong>Show FAQ</strong><br>
            <strong>Method:</strong> GET<br>
            <strong>Route:</strong> /faqs/{faq}<br>
            <strong>Description:</strong> Shows a single FAQ.<br>
            <strong>Controller:</strong> FaqController@show
        </li>
        <li>
            <strong>Edit FAQ</strong><br>
            <strong>Method:</strong> GET<br>
            <strong>Route:</strong> /faqs/{faq}/edit<br>
            <strong>Description:</strong> Displays form to edit FAQ information.<br>
            <strong>Controller:</strong> FaqController@edit
        </li>
        <li>
            <strong>Update FAQ</strong><br>
            <strong>Method:</strong> PUT<br>
            <strong>Route:</strong> /faqs/{faq}<br>
            <strong>Description:</strong> Updates FAQ information.<br>
            <strong>Controller:</strong> FaqController@update
        </li>
        <li>
            <strong>Delete FAQ</strong><br>
            <strong>Method:</strong> DELETE<br>
            <strong>Route:</strong> /faqs/{faq}<br>
            <strong>Description:</strong> Deletes a FAQ.<br>
            <strong>Controller:</strong> FaqController@destroy
        </li>
    </ul>
    <h2>Application Log Management Flow</h2>
    <ul>
        <li>
            <strong>List Application Logs</strong><br>
            <strong>Method:</strong> GET<br>
            <strong>Route:</strong> /application-logs<br>
            <strong>Description:</strong> Lists all application logs.<br>
            <strong>Controller:</strong> ApplicationLogController@index
        </li>
        <li>
            <strong>Show Application Log</strong><br>
            <strong>Method:</strong> GET<br>
            <strong>Route:</strong> /application-logs/{applicationLog}<br>
            <strong>Description:</strong> Shows a single application log.<br>
            <strong>Controller:</strong> ApplicationLogController@show
        </li>
    </ul>
</body>
</html>
