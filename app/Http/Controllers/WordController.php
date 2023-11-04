<?php

namespace App\Http\Controllers;

use App\Models\GermanWord;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class WordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $userId = Auth::user()->id;

        return Inertia::render('Words/Index', [
            'words' => GermanWord::with('user:id,name')
                ->where('user_id', $userId)
                ->latest()
                ->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate(
            [
                'article' => 'nullable|string|max:3|in:Der,Die,Das',
                'word' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('german_words')->where(function ($query) use ($request) {
                        return $query->where('user_id', $request->user()->id);
                    })
                ],
                'translation' => 'required|string|max:255',
            ],
            [
                'article.max' => 'The :attribute field is required.',
                'word.required' => 'The :attribute field is required.',
                'word.string' => 'The :attribute field is string.',
                'word.max' => 'The :attribute field max 255.',
                'word.unique' => 'This word has already been added'
            ]);

        $request->user()->words()->create($validated);

        return redirect(route('words.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(GermanWord $word)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GermanWord $word)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GermanWord $word): Response
    {
        $this->authorize('update', $word);

        $validated = $request->validate(
            [
                'article' => 'nullable|string|max:3|in:Der,Die,Das',
                'word' => 'required|string|max:255',
                'translation' => 'required|string|max:255',
            ],
            [
                'article.max' => 'The :attribute field is required.',
                'word.required' => 'The :attribute field is required.',
                'word.string' => 'The :attribute field is string.',
                'word.max' => 'The :attribute field max 255.',
            ]
        );

        $word->update($validated);

        if ($request->get('edit')) {
            return Inertia::render('Words/AddedWords');
        }

        return Inertia::render('Words/Index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id): Response
    {
        $word = GermanWord::find($id);

        if (!$word) {
            Inertia::render('Words/Index')->with('error', 'Entry not found.');
        }

        $word->destroy($id);

        return Inertia::render('Words/Index')->with('message', 'Has been delete!');
    }

    /**
     * @return Response
     */
    public function allAddedWords(): Response
    {
        $userId = Auth::user()->id;

        return Inertia::render('Words/AddedWords', [
            'words' => GermanWord::with('user:id,name')
                ->where('user_id', $userId)
                ->orderBy('word')
                ->get(),
        ]);
    }

    public function randomWord(): Response
    {
        $words = $this->getGermanWords();
        $visibleWords = $this->getVisibleGermanWords();

        return Inertia::render('Words/RandomWord', [
            'words' => $words,
            'visibleWords' => $visibleWords,
        ]);
    }

    public function wordFilling(): Response
    {
        $words = $this->getGermanWords();
        $visibleWords = $this->getVisibleGermanWords();

        return Inertia::render('Words/WordFilling', [
            'words' => $words,
            'visibleWords' => $visibleWords,
        ]);
    }

    public function notDisplayWord(Request $request, $wordId): Response
    {
        $word = GermanWord::query()->find($wordId);
        $memorizationLevel = $request->get('memorizationLevel');


        if (!$word || !$memorizationLevel) {
           return Inertia::render('Words/Index')->with('error', 'Entry not found.');
        }

        if ($memorizationLevel == 'learned') {
            $word->memorization_level = [
                'learned' => [
                    'value' => 1
                ],
                'remembered' => [
                    'value' => 0,
                    'update_date' => null,
                ],
            ];
        }

        if ($memorizationLevel == 'remembered') {
            $word->memorization_level = [
                'learned' => [
                    'value' => 0
                ],
                'remembered' => [
                    'value' => 1,
                    'update_date' => Carbon::now()->format('Y-m-d'),
                ],
            ];
        }

        $word->save();

        return Inertia::render('Words/RandomWord')->with('error', 'Has been added!');
    }

    private function getVisibleGermanWords()
    {
        $userId = Auth::user()->id;
        $oneMonthAgo = Carbon::now()->subMonths(1);

        return GermanWord::with('user:id,name')
            ->where('user_id', $userId)
            ->where(function ($query) {
                $query->whereJsonContains('memorization_level->learned->value', 0)
                    ->orWhereNull('memorization_level');
            })
            ->where(function ($query) use ($oneMonthAgo) {
                $query->orWhere('memorization_level->remembered->value', '!=', 1)
                    ->orWhere(function ($query) use ($oneMonthAgo) {
                        $query->where('memorization_level->remembered->update_date', '<=', $oneMonthAgo);
                    })
                    ->orWhereNull('memorization_level');
            })
            ->latest()
            ->get();
    }

    private function getGermanWords()
    {
        $userId = Auth::user()->id;
        return GermanWord::with('user:id,name')
            ->where('user_id', $userId)
            ->orderBy('word')
            ->get();
    }
}
