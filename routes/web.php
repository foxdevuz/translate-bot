<?php

use App\Http\Controllers\AdsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::any("/sendAds", [AdsController::class, "sendAds"]);
