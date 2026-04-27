<?php

namespace App\Ai\Agents;

use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Messages\Message;
use Laravel\Ai\Promptable;

class ReviewAnalystAgent implements Agent, Conversational, HasTools
{
    use Promptable;

    protected string $driver = 'gemini';

    public function instructions(): string
    {
        return "You are the PageTurner AI Lead Analyst. Your goal is to synthesize multiple customer reviews into a single, cohesive 'Executive Summary'. " .
            "Avoid generic phrases like 'The reviews are positive'. Instead, identify specific themes (e.g., 'Readers praise the world-building but found the pacing slow'). " .
            "Always maintain a professional yet literary tone. Keep your response to one paragraph.";
    }

    public function messages(): iterable { return []; }
    public function tools(): iterable { return []; }
}