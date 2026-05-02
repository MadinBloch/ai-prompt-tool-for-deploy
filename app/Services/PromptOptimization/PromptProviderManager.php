<?php

namespace App\Services\PromptOptimization;

use Illuminate\Support\Arr;

class PromptProviderManager
{
    /**
     * Supported prompt optimization providers.
     *
     * @var array<int, string>
     */
    private array $supportedProviders = [
        'openai',
        'gemini',
        'groq',
    ];

    /**
     * Get the provider failover chain.
     *
     * @return array<int, string>
     */
    public function chain(?string $preferredProvider = null): array
    {
        $defaultProvider = config('ai.default');

        return array_values(array_unique(array_filter([
            $preferredProvider,
            in_array($defaultProvider, $this->supportedProviders, true) ? $defaultProvider : null,
            ...$this->supportedProviders,
        ])));
    }

    /**
     * Determine if the provider is supported by this pipeline.
     */
    public function supports(?string $provider): bool
    {
        if ($provider === null) {
            return true;
        }

        return Arr::exists(array_flip($this->supportedProviders), $provider);
    }
}
