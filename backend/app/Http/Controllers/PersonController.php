<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\Http\Request;

class PersonController extends Controller
{
    public function index(Request $request)
    {
        return Person::where('user_id', $request->user()->id)->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'dob' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $person = Person::create([
            'user_id' => $request->user()->id,
            ...$validated,
        ]);

        return response()->json($person, 201);
    }

    public function show(Request $request, Person $person)
    {
        $this->authorizePerson($request, $person);

        return $person;
    }

    public function update(Request $request, Person $person)
    {
        $this->authorizePerson($request, $person);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'dob' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $person->update($validated);

        return $person;
    }

    public function destroy(Request $request, Person $person)
    {
        $this->authorizePerson($request, $person);

        $person->delete();

        return response()->json([
            'message' => 'Person deleted',
        ]);
    }

    private function authorizePerson(Request $request, Person $person)
    {
        if ($person->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized');
        }
    }
}