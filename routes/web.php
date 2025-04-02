<?php

use App\Http\Controllers\ResourceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/home', function () {
    return view('home');
})->name('home');

Route::get('/about', function () {
    return view('about');
})->name('about');

//CRUD

Route::get('/builds', [ResourceController::class, 'showAllBuilds'])->name('showAllBuilds');

Route::get('/builds/{id}/detail', [ResourceController::class, 'getDetailPart']);

Route::post('/builds/add', [ResourceController::class, 'addNewBuild'])->name('addNewBuild');

Route::delete('/builds/{id}/delete', [ResourceController::class, 'deleteBuild'])->name('deleteBuild');



