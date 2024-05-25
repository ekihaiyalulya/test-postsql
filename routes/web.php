<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoController;
use App\Models\TodoResponse;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get("/v1", function () {
    $res = new TodoResponse(true, "Message received", null);
    return response($res->to_json(), 200, ["Content-Type" => "application/json"]);
});

Route::get("/v1/todos", [TodoController::class, "index"])->name("todos.index");
Route::get("/v1/todos/{id}", [TodoController::class, "show"])->name("todos.show");
Route::post("/v1/todos", [TodoController::class, "store"])->name("todos.store");
Route::post("/v1/todos/{id}", [TodoController::class, "update"])->name("todos.update");
Route::delete("/v1/todos/{id}", [TodoController::class, "delete"])->name("todos.delete");
