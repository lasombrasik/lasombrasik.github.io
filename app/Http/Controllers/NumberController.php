<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class NumberController extends Controller
{
    public function generateRandomNumber(): Response
    {
        return Inertia::render('Words/LearningNumbers');
    }
}
