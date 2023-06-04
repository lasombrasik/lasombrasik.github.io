<?php

namespace App\Http\Controllers;

use App\Models\GermanWord;
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
                'article' => 'nullable|string|max:10',
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
    public function update(Request $request, GermanWord $word): RedirectResponse
    {
        $this->authorize('update', $word);

        $validated = $request->validate(
            [
                'article' => 'nullable|string|max:10',
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
            ]
        );

        $word->update($validated);

        return redirect(route('words.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id): Response
    {
        $word = GermanWord::find($id);

        if (!$word) {
            Inertia::render('Words/Index')->with('error', 'Запись не найдена.');
        }

        $word->destroy($id);

        return Inertia::render('Words/Index')->with('message', 'Запись успешно удалена.');
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
        $userId = Auth::user()->id;

        return Inertia::render('Words/RandomWord', [
            'words' => GermanWord::with('user:id,name')
                ->where('user_id', $userId)
                ->orderBy('word')
                ->get(),
        ]);
    }
}
