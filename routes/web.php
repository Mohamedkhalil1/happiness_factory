<?php


use App\Http\Controllers\Auth\LoginController;
use App\Http\Livewire\Attendance\AttIndex;
use App\Http\Livewire\Auth\Login;
use App\Http\Livewire\Auth\Register;
use App\Http\Livewire\CategoryEmploye\Index as CategoryIndex;
use App\Http\Livewire\Client\CliIndex;
use App\Http\Livewire\Dashboard;
use App\Http\Livewire\Employee\EmpIndex;
use App\Http\Livewire\Inventory\InvIndex;
use App\Http\Livewire\Order\OrdCreate;
use App\Http\Livewire\Order\OrdIndex;
use App\Http\Livewire\Product\ProCreate;
use App\Http\Livewire\Product\ProIndex;
use App\Http\Livewire\ProductCategory\PCatIndex;
use App\Http\Livewire\Profile\Show;
use App\Http\Livewire\Provider\PrvIndex;
use App\Http\Livewire\Salary\SalIndex;
use App\Http\Livewire\Season\SeaIndex;
use App\Http\Livewire\Transaction\TraCreate;
use App\Http\Livewire\Transaction\TraIndex;
use App\Http\Livewire\User\Create;
use Illuminate\Support\Facades\Route;

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

Route::group([
    'middleware' => 'guest',
], function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
});


Route::group([
    'middleware' => 'auth',
], function () {
    Route::get('/', Dashboard::class)->name('dashboard');
    Route::get('/profile', Show::class)->name('profile.show');
    Route::get('/user/create', Create::class)->name('user.create');
    Route::group([

        'prefix' => 'employees',
        'as'     => 'employees.',
    ], function () {
        Route::get('/', EmpIndex::class)->name('index');
        Route::get('/categories', CategoryIndex::class)->name('categories.index');
        Route::get('/salaries', SalIndex::class)->name('salaries.index');
        Route::get('/attendances', AttIndex::class)->name('attendances.index');
    });

    Route::group([

        'prefix' => 'products',
        'as'     => 'products.',
    ], function () {
        Route::get('/', ProIndex::class)->name('index');
        Route::get('/create', ProCreate::class)->name('create');
        Route::get('/categories', PCatIndex::class)->name('categories.index');
    });

    Route::group([
        'prefix' => 'inventories',
        'as'     => 'inventories.',
    ], function () {
        Route::get('/', InvIndex::class)->name('index');
    });

    Route::group([
        'prefix' => 'clients',
        'as'     => 'clients.',
    ], function () {
        Route::get('/', CliIndex::class)->name('index');
    });

    Route::group([
        'prefix' => 'orders',
        'as'     => 'orders.',
    ], function () {
        Route::get('/', OrdIndex::class)->name('index');
        Route::get('/create', OrdCreate::class)->name('create');
    });

    Route::get('/transactions', TraIndex::class)->name('transactions.index');
    Route::get('/seasons', SeaIndex::class)->name('seasons.index');
    Route::get('/providers', PrvIndex::class)->name('providers.index');
});

Route::post('logout', [LoginController::class, 'logout'])->name('logout');


