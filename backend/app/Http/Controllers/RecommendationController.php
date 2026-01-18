<?php

namespace App\Http\Controllers;

use App\Models\Person;
use App\Services\AIRecommendationService;
use Illuminate\Http\Request;

class RecommendationController extends Controller
{
    public function store(
        Request $request,
        Person $person,
        AIRecommendationService $service
    ) {
        if ($person->user_id !== $request->user()->id) {
            abort(403);
        }

        $validated = $request->validate([
            'occasion' => 'required|string',
            'budget_min' => 'nullable|integer',
            'budget_max' => 'nullable|integer',
        ]);

        $recommendation = $service->generate(
            $person,
            $validated['occasion'],
            $validated['budget_min'] ?? null,
            $validated['budget_max'] ?? null
        );

        return response()->json($recommendation, 201);
    }
}