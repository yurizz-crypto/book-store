<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ai\Agents\BookMatchmakerAgent; // Ensure you created this agent!

class AiController extends Controller
{
    public function matchmake(Request $request)
    {
        $request->validate(['prompt' => 'required|string|max:255']);

        try {
            $agent = new BookMatchmakerAgent();
            
            $recommendation = $agent->prompt($request->prompt)->text;

            return back()->with('ai_match', $recommendation)->withInput();

        } catch (\Exception $e) {
            return back()->withErrors(['ai_error' => 'The Matchmaker is a bit busy right now. Please try again in a moment!'])->withInput();
        }
    }

    
}