<?
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SmsController;

Route::post('/sms-endpoint', [SmsController::class, 'sendSms']);
