<?php

declare(strict_types=1);

namespace App\Dto;

use Symfony\Component\Serializer\Annotation as Serializer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

final class Indicator
{
    private const COLOR_GREEN = 'green';
    private const COLOR_RED = 'red';
    private const COLOR_YELLOW = 'yellow';

    public function __construct(
        public string $name,
        public string $color,
        public float $value,
        #[Serializer\Context(normalizationContext: [DateTimeNormalizer::FORMAT_KEY => 'Y-m-d'])]
        public ?\DateTimeImmutable $date = null
    ) {
    }

    public static function createIncidenceNewInfections(float $value, \DateTimeImmutable $date): self
    {
        $color = match (true) {
            $value >= 30 => self::COLOR_RED,
            $value >= 20 => self::COLOR_YELLOW,
            default => self::COLOR_GREEN,
        };

        return new self('incidence_new_infections', $color, $value, $date);
    }
}
