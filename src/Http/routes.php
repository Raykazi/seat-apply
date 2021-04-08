<?php


Route::group([
    'namespace' => 'Raykazi\Seat\SeatApplication\Http\Controllers',
    'prefix' => 'application'
], function () {

    Route::group([
        'middleware' => ['web', 'auth'],
    ], function () {

        Route::get('/', [
            'as'   => 'application.request',
            'uses' => 'ApplicationController@getMainPage',
            'middleware' => 'can:application.apply'
        ]);
        Route::get('/admin', [
            'as'   => 'application.list',
            'uses' => 'ApplicationAdminController@getApplications',
            'middleware' => 'can:application.recruiter'
        ]);
        Route::get('/questions', [
            'as'   => 'application.questions',
            'uses' => 'ApplicationAdminController@getQuestions',
            'middleware' => 'can:application.director'
        ]);
        Route::get('/about', [
            'as'   => 'application.about',
            'uses' => 'ApplicationController@getAboutView',
            'middleware' => 'can:application.apply'
        ]);

        Route::post('/submitApp', [
            'as'   => 'application.submitApp',
            'uses' => 'ApplicationController@submitApp',
            'middleware' => 'can:application.apply'
        ]);
        Route::post('/submitQuestion', [
            'as'   => 'application.submitQuestion',
            'uses' => 'ApplicationAdminController@submitQuestion',
            'middleware' => 'can:application.director'
        ]);
        Route::post('/submitSettings', [
            'as'   => 'application.submitSettings',
            'uses' => 'ApplicationAdminController@updateSettings',
            'middleware' => 'can:application.director'
        ]);
        Route::get('/admin/{application_id}/{action}', [
            'as'   => 'application.recruiter',
            'uses' => 'ApplicationAdminController@updateApplication',
            'middleware' => 'can:application.recruiter'
        ])->where(['action' => 'Accept|Reject|Interview|Review']);

        Route::get('/question/{qid}', [
            'as' => 'application.question',
            'uses' => 'ApplicationAdminController@getQuestion',
            'middleware' => 'can:application.director',
        ]);
        Route::delete('/question/{qid}')
            ->name('application.question.delete')
            ->uses('ApplicationAdminController@deleteQuestion')
            ->middleware('can:application.director');
    });
});
