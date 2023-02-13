<?php

Route::group([
    'middleware' => ['web', 'auth'],
    'prefix' => 'doctrine',
    'namespace' => 'GreyZmeem\Seat\Doctrine\Http\Controllers',
], function () {
    Route::group([
        'middleware' => 'can:doctrine.view',
    ], function () {
        Route::get('/fitting/')
            ->name('doctrine.fittingList')
            ->uses('DoctrineController@fittingList');
        Route::get('/fitting.json')
            ->name('doctrine.fittingListJson')
            ->uses('DoctrineController@fittingListJson');
        Route::get('/fitting/{id}')
            ->name('doctrine.fittingDetail')
            ->uses('DoctrineController@fittingDetail');
        Route::group([
            'middleware' => 'can:doctrine.create',
        ], function () {
            Route::post('/fitting/')
                ->name('doctrine.fittingCreate')
                ->uses('DoctrineController@fittingCreate');
            Route::get('/fitting/{id}/edit')
                ->name('doctrine.fittingEdit')
                ->uses('DoctrineController@fittingEdit');
            Route::post('/fitting/{id}/edit')
                ->name('doctrine.fittingEdit')
                ->uses('DoctrineController@fittingUpdate');
            Route::post('/fitting/{id}/delete')
                ->name('doctrine.fittingDelete')
                ->uses('DoctrineController@fittingDelete');
        });

        Route::get('/')
            ->name('doctrine.doctrineList')
            ->uses('DoctrineController@doctrineList');
        Route::get('/{id}')
            ->name('doctrine.doctrineDetail')
            ->uses('DoctrineController@doctrineDetail');
        Route::group([
            'middleware' => 'can:doctrine.create',
        ], function () {
            Route::post('/')
                ->name('doctrine.doctrineCreate')
                ->uses('DoctrineController@doctrineCreate');
            Route::get('/{id}/edit')
                ->name('doctrine.doctrineEdit')
                ->uses('DoctrineController@doctrineEdit');
            Route::post('/{id}/edit')
                ->name('doctrine.doctrineUpdate')
                ->uses('DoctrineController@doctrineUpdate');
            Route::post('/{id}/delete')
                ->name('doctrine.doctrineDelete')
                ->uses('DoctrineController@doctrineDelete');
        });
    });
});
