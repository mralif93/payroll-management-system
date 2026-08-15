<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
});

Route::get('/demo', function () {
    return view('demo-components');
});

Route::get('/admin', function () {
    return view('admin.dashboard');
});
