<?php

namespace App\Http\Controllers;

use App\Models\Person;
use App\Models\PersonAttribute;
use Illuminate\Http\Request;

class PersonAttributeController extends Controller
{
    public function index(Request $request, Person $person)
    {
        $this->authorizePerson($request, $person);

        return $person->attributes;
    }

    public function store(Request $request, Person $person)
    {
        $this->authorizePerson($request, $person);

        $validated = $request->validate([
            'type' => 'required|string|max:50',
            'value' => 'required|string|max:1000',
        ]);

        $attribute = $person->attributes()->create($validated);

        return response()->json($attribute, 201);
    }

    public function update(Request $request, Person $person, PersonAttribute $attribute)
    {
        $this->authorizePerson($request, $person);
        $this->authorizeAttribute($person, $attribute);

        $validated = $request->validate([
            'type' => 'sometimes|string|max:50',
            'value' => 'sometimes|string|max:1000',
        ]);

        $attribute->update($validated);

        return $attribute;
    }

    public function destroy(Request $request, Person $person, PersonAttribute $attribute)
    {
        $this->authorizePerson($request, $person);
        $this->authorizeAttribute($person, $attribute);

        $attribute->delete();

        return response()->json([
            'message' => 'Attribute deleted',
        ]);
    }

    private function authorizePerson(Request $request, Person $person)
    {
        if ($person->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized');
        }
    }

    private function authorizeAttribute(Person $person, PersonAttribute $attribute)
    {
        if ($attribute->person_id !== $person->id) {
            abort(403, 'Unauthorized');
        }
    }
}