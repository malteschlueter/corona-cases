<?php

declare(strict_types=1);

namespace App\Scraper\Parser;

use App\Dto\Data;
use App\Dto\Indicator;
use Symfony\Component\DomCrawler\Crawler;

final class HamburgParser implements Parser
{
    public function parse(Data $data, string $content): Data
    {
        $crawler = new Crawler($content);

        $incidenceElements = $crawler->filter('.teaser-text.c_chartheadline');

        $incidenceIndicator = null;
        foreach ($incidenceElements as $element) {
            if (str_starts_with($element->textContent, 'Bei der Inzidenz wird die Anzahl')) {
                $incidenceValues = (new Crawler($element))->filter('strong');

                $incidenceValue = (float) str_replace(',', '.', $incidenceValues->first()->text());
                $incidenceDateString = str_replace(['(', ')'], '', $incidenceValues->last()->text());
                $incidenceDate = \DateTimeImmutable::createFromFormat('d.m.Y', $incidenceDateString);
                if (!$incidenceDate instanceof \DateTimeImmutable) {
                    throw new \RuntimeException('Incidence not parsable.');
                }
                $incidenceDate = $incidenceDate->setTime(0, 0);

                $incidenceIndicator = Indicator::createIncidenceNewInfections($incidenceValue, $incidenceDate);
            }
        }

        if (null === $incidenceIndicator) {
            throw new \RuntimeException('Incidence not parsable.');
        }

        $data->addIndicator($incidenceIndicator);

        return $data;
    }
}
