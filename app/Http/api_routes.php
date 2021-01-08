<?php


/*
 * User Login
 */
Route::get('/api/secure', 'API\AuthApiController@secure');
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
