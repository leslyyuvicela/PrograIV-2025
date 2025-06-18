<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\StageController;
use App\Http\Controllers\CategoryController;

Route::get("/pruebita", function () {
        return "Hola mundo, todo furula";
})
->middleware('jwt.auth');
Route::post('/auth/validate-email', [AuthController::class, 'validateEmail']);
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login'])->name('login');
Route::put('/profile', [AuthController::class, 'UpdateBasicProfile']);
Route::get('/recommended-fundraisings', [CampaignController::class, 'recommendedFundraisings']);

Route::middleware('jwt.auth')->group(function () {
        Route::get('/auth/who',[AuthController::class, 'who']);
        Route::post('/auth/logout',[AuthController::class, 'logout']);

        Route::post('/create-fundraising', [CampaignController::class, 'store']);


        

        Route::post('/campaigns/{campaign}/desactivate', [CampaignController::class, 'desactivateCampaign']);
        Route::post('/campaigns/{campaign}/activate', [CampaignController::class, 'activateCampaign']);

        Route::delete('/stage/{stage}', [CampaignController::class, 'destroyStage']);
        Route::put('/stages/{stage}', [CampaignController::class, 'updateStages']);
        Route::get('/categories', [CategoryController::class, 'index']);
       
       
});


         



