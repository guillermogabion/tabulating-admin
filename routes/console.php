<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('add-admin-and-student', function () {
    $adminData = [
        'userId' => "admin123",
        'name' => "Admin Admin",
        'email' => "admin@example.com",
        'password' => "Password01!",
        'role' => "admin",
        "status" => "old"
    ];

    $insData = [
        'userId' => "ins123",
        'name' => "Instructor Instructor",
        'email' => "instructor@example.com",
        'password' => "Password01!",
        'role' => "instructor",
        "status" => "new"

    ];
    $execData = [
        'userId' => "exec123",
        'name' => "Executive Executive",
        'email' => "executive@example.com",
        'password' => "Password01!",
        'role' => "executive",
        "status" => "new"

    ];
    $studentData = [
        'userId' => "stud123",
        'name' => "Student Student",
        'email' => "student@example.com",
        'password' => "Password01!",
        'role' => "student",
        "status" => "new"

    ];

    $users = [$adminData, $studentData, $execData, $insData];

    foreach ($users as $data) {
        if (User::where('email', $data['email'])->exists() || User::where('userId', $data['userId'])->exists()) {
            $this->error("User with email '{$data['email']}' or userId '{$data['userId']}' already exists. Skipping.");
            continue;
        }

        // Create the user
        $user = new User();
        $user->userId = $data['userId'];
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->role = $data['role'];
        $user->status = $data['status'];
        $user->password = Hash::make($data['password']);

        if ($user->save()) {
            $this->info("{$data['role']} added successfully.");
        } else {
            $this->error("Failed to add {$data['role']}.");
        }
    }
})->purpose('Add Admin and Student');
