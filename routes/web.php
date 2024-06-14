<?php

use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;


// NOTE :- Always you have to pass the csrf-token in the headers of the post request
Route::get('/register', function () {
    return csrf_token();
});

// Note :- Get All Users
Route::get("/",[RegisterController::class, "getAllUsers"])->name("all-users");

// Note :- Create User
Route::post("/register", [RegisterController::class, "createUser"])->name("register.post");

// Note :- Login User
Route::post("/login", [RegisterController::class, "loginUser"])->name("login.post");

// Note :- Update User
Route::post("/update/{id}", [RegisterController::class, "updateUser"])->name("update");

// Note :- Delete User
Route::delete("/delete/{id}",[RegisterController::class, "deleteUser"])->name("delete");
