<?php

declare(strict_types=1);

namespace App\Dto;

final class Data
{
    /**
     * @var Indicator[]
     */
    public array $indicators = [];

    public function __construct(
        public string $source,
        public \DateTimeImmutable $prDate
    ) {
    }

    public function addIndicator(Indicator $indicator): void
    {
        $this->indicators[$indicator->name] = $indicator;
    }
}
