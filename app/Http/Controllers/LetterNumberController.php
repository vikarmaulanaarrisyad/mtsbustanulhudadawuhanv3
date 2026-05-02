<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LetterNumberController extends Controller
{
    /**
     * Generate letter number for a specific model and type
     */
    public function generate(Request $request)
    {
        $request->validate([
            'model' => 'required|string',
            'type' => 'required|string',
            'column' => 'nullable|string',
        ]);

        $modelClass = "App\\Models\\" . $request->model;
        
        if (!class_exists($modelClass)) {
            return response()->json(['message' => 'Model not found'], 404);
        }

        $column = $request->column ?? 'letter_number';
        $number = $modelClass::generateLetterNumber($request->type, $column);

        return response()->json(['number' => $number]);
    }
}
