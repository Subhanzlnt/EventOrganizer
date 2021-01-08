<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


/*
         * Login
         */
Route::get('login', 'UserLoginController@showLogin');
Route::post('login', 'UserLoginController@postLogin');
Route::group(['prefix' => 'api', 'middleware' => ['auth:api']], function () {
    /*
             * ---------------
             * Organisers
             * ---------------
             */


    /*
             * ---------------
             * Events
             * ---------------
             */
    Route::get('events/showall', 'API\EventsApiController@showall');
    Route::get('events/showtoday', 'API\EventsApiController@showtoday');
    /*Route::resource('events', 'API\EventsApiController');*/


    /*
             * ---------------
             * Attendees
             * ---------------
             */
    Route::get('attendees/showattendeesevent', 'API\AttendeesApiController@showattendeesevent');
    Route::get('attendees/checkinattendee', 'API\AttendeesApiController@checkinattendee');
    /*Route::resource('attendees', 'API\AttendeesApiController');*/


    /*
             * ---------------
             * Orders
             * ---------------
             */

    /*
             * ---------------
             * Users
             * ---------------
             */

    /*
             * ---------------
             * Check-In / Check-Out
             * ---------------
             */
});


/*
 * ---------------
 * Organisers
 * ---------------
 */


/*
 * ---------------
 * Events
 * ---------------
 */
Route::resource('events', API\EventsApiController::class);


/*
 * ---------------
 * Attendees
 * ---------------
 */
Route::resource('attendees', API\AttendeesApiController::class);


/*
 * ---------------
 * Orders
 * ---------------
 */

/*
 * ---------------
 * Users
 * ---------------
 */

/*
 * ---------------
 * Check-In / Check-Out
 * ---------------
 */


//  Route Login
