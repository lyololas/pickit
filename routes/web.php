<?php
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Middleware;
Route::get('login', [LoginController::class, 'create'])->name('login');
Route::post('login', [LoginController::class,'store']);
Route::post('logout', [LoginController::class,'destroy'])->middleware('auth');
    Route::get('/', function () {

        return Inertia('Home');
    });
    Route::get('/settings', function () {
        return Inertia('Settings');
    });
    Route::get('/users', function () {
        return Inertia::render('Users/Index', [
            'users' => User::query()
            ->when(Request::input('search'), function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->paginate(10)
            ->WithQueryString(),
            'filters' => Request::only(['search'])
        ]);
    
        
    });
    Route::get('/users/create', function(){
        return Inertia::render('Users/Create');
    });
    Route::post('/users', function(){
        $attributes = Request::validate([
            'name' => 'required',
            'email' => ['required', 'email'],
            'password' => 'required'
        ]);
        User::create($attributes);
        return redirect('/users');
    });
