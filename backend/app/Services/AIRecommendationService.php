<?php

namespace App\Services;

use App\Models\Person;
use App\Models\GiftRecommendation;
use App\Models\GiftItem;
use OpenAI;
use Throwable;

class AIRecommendationService
{
    public function generate(
        Person $person,
        string $occasion,
        ?int $budgetMin,
        ?int $budgetMax
    ): GiftRecommendation {
        // 1. Build profile summary
        $profileSummary = $this->buildProfileSummary($person);

        // 2. Call AI
        $aiResponse = $this->callAI(
            $profileSummary,
            $occasion,
            $budgetMin,
            $budgetMax
        );

        // 3. Persist recommendation
        $recommendation = GiftRecommendation::create([
            'user_id' => $person->user_id,
            'person_id' => $person->id,
            'occasion' => $occasion,
            'budget_min' => $budgetMin,
            'budget_max' => $budgetMax,
            'ai_profile_summary' => $profileSummary,
        ]);

        // 4. Persist gift items
        foreach ($aiResponse['gifts'] as $gift) {
            GiftItem::create([
                'gift_recommendation_id' => $recommendation->id,
                'title' => $gift['title'],
                'description' => $gift['description'] ?? null,
                'price_min' => $gift['price_min'] ?? null,
                'price_max' => $gift['price_max'] ?? null,
                'source' => $gift['source'] ?? null,
                'url' => $gift['url'] ?? null,
                'ai_reason' => $gift['reason'],
            ]);
        }

        return $recommendation->load('giftItems');
    }

    private function buildProfileSummary(Person $person): string
    {
        $lines = [];
        $lines[] = "Name: {$person->name}";

        if ($person->dob) {
            $lines[] = "Date of birth: {$person->dob->format('Y-m-d')}";
        }

        if ($person->notes) {
            $lines[] = "Notes: {$person->notes}";
        }

        foreach ($person->attributes as $attr) {
            $lines[] = ucfirst($attr->type) . ": {$attr->value}";
        }

        return implode("\n", $lines);
    }

    private function callAI(
    string $profileSummary,
    string $occasion,
    ?int $budgetMin,
    ?int $budgetMax
): array {
    $client = OpenAI::client(config('services.openai.key'));

    $prompt = <<<PROMPT
You are an expert gift recommendation assistant.

Based ONLY on the profile below, suggest 5 thoughtful gift ideas.

Profile:
{$profileSummary}

Occasion: {$occasion}
Budget range: {$budgetMin} - {$budgetMax}

Rules:
- Be specific and practical
- Do not include gift cards
- Avoid generic items
- Respect the budget

Return STRICT JSON in this format:

{
  "gifts": [
    {
      "title": "",
      "description": "",
      "price_min": 0,
      "price_max": 0,
      "source": "",
      "url": "",
      "reason": ""
    }
  ]
}
PROMPT;

    try {
        $response = $client->responses()->create([
            'model' => 'gpt-4.1-mini',
            'input' => $prompt,
        ]);

        return json_decode(
            $response->output_text,
            true,
            512,
            JSON_THROW_ON_ERROR
        );
    } catch (\OpenAI\Exceptions\RateLimitException $e) {
        // DEV fallback (IMPORTANT)
        return $this->mockResponse($budgetMin, $budgetMax);
    }
}
private function mockResponse(?int $budgetMin, ?int $budgetMax): array
{
    return [
        'gifts' => [
            [
                'title' => 'Minimalist fitness accessory set',
                'description' => 'A curated set of resistance bands and accessories',
                'price_min' => $budgetMin ?? 25,
                'price_max' => $budgetMax ?? 60,
                'source' => 'Amazon / Decathlon',
                'url' => null,
                'reason' => 'Matches their interest in fitness and minimalist style'
            ],
            [
                'title' => 'Neutral-tone yoga mat',
                'description' => 'High-quality, eco-friendly yoga mat',
                'price_min' => 30,
                'price_max' => 70,
                'source' => 'Liforme / Manduka',
                'url' => null,
                'reason' => 'Supports yoga practice and fits neutral aesthetic'
            ]
        ]
    ];
}
}