<?php

    use App\Http\Controllers\Api\AttendeeController;
    use App\Http\Controllers\Api\EventController;
    use App\Models\AuthController;
    use App\Models\Event;
    use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [AuthController::class,'login']);
Route::post('/logout', [AuthController::class,'logout'])->middleware("auth:sanctum");

Route::get('/events',[EventController::class, 'index']);
Route::post('/events',[EventController::class, 'store']);
Route::get('/events/{event}',[EventController::class, 'show']);
Route::patch('/events/{event}',[EventController::class, 'update']);
Route::delete('/events/{event}',[EventController::class, 'destroy']);

// above code works functionally exactly as down one line.

//Route::apiResource('events', EventController::class);
Route::apiResource('events.attendees', AttendeeController::class)->except('update');
