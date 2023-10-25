<?php

use App\Http\Controllers\ProfileController;
use App\Models\GermanWord;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WordController;
use App\Http\Controllers\NumberController;
use Inertia\Inertia;

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
    $words = Auth::check()
        ? GermanWord::with('user:id,name')
            ->where('user_id', Auth::user()->id)
            ->latest()
            ->get()
        : [];

    return Inertia::render('Words/Index', [
        'words' => $words,
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::resource('words', WordController::class)
    ->only(['index', 'create', 'store', 'update', 'destroy'])
    ->middleware(['auth', 'verified']);

Route::get('words/added-words', [WordController::class, 'allAddedWords']
    )->name('words.all')
    ->middleware(['auth', 'verified']);

Route::get('words/random-words', [WordController::class, 'randomWord']
)->name('words.random')
    ->middleware(['auth', 'verified']);

Route::get('words/words-filling', [WordController::class, 'wordFilling']
)->name('words.filling')
    ->middleware(['auth', 'verified']);

Route::get('words/not-display-word/{wordId}', [WordController::class, 'notDisplayWord']
)->name('words.not_display_word')
    ->middleware(['auth', 'verified']);

Route::get('generate-random-number', [NumberController::class, 'generateRandomNumber']
)->name('generate.random.number')
    ->middleware(['auth', 'verified']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
