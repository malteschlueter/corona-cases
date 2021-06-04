<?php

declare(strict_types=1);

namespace App\Dto;

use Symfony\Component\Serializer\Annotation as Serializer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

final class Data
{
    /**
     * @var Indicator[]
     */
    public array $indicators = [];

    public function __construct(
        public string $source,
        #[Serializer\Context(normalizationContext: [DateTimeNormalizer::FORMAT_KEY => 'Y-m-d'])]
        public \DateTimeImmutable $prDate
    ) {
    }

    public function addIndicator(Indicator $indicator): void
    {
        $this->indicators[$indicator->name] = $indicator;
    }
}
