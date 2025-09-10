<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RequisitionapiController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::get('/requisition-list', [RequisitionapiController::class, 'list']);
Route::post('/requisition-create', [RequisitionapiController::class, 'create']);
Route::get('/requisition-view/{id}', [RequisitionapiController::class, 'view']); 


Route::post('/registration', [RequisitionapiController::class, 'reg']);
Route::post('/requisition-login', [RequisitionapiController::class, 'login']);