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

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): string
    {
        return "You are an expert bookstore manager. Analyze the provided customer review. " .
            "Return a very short sentiment label (Positive, Neutral, or Negative) " .
            "and a one-sentence executive summary of their feedback.";
    }

    /**
     * Get the list of messages comprising the conversation so far.
     *
     * @return Message[]
     */
    public function messages(): iterable
    {
        return [];
    }

    /**
     * Get the tools available to the agent.
     *
     * @return Tool[]
     */
    public function tools(): iterable
    {
        return [];
    }
}
