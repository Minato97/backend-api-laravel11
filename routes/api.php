<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    Route::get('/ping', function () {
        return response()->json([
            'message' => 'Laravel 11 API funcionando 🚀'
        ]);
    });

});
