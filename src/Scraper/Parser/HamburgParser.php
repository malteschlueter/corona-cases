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

        $incidenceElements = $crawler->filter('[data-label="Zahlen"]');

        $incidenceIndicator = null;

        foreach ($incidenceElements as $element) {
            if ('6-Tage-Inzidenz' === $element->textContent) {
                $incidenceValueAndDate = (new Crawler($element))->siblings();
                $incidenceValueElement = $incidenceValueAndDate->filter('[data-label="Wert"]')->first();
                $incidenceDateElement = $incidenceValueAndDate->filter('[data-label="Stand"]')->first();

                match (true) {
                    0 === $incidenceValueElement->count() => throw $this->createException('Value not found.'),
                    0 === $incidenceDateElement->count() => throw $this->createException('Date not found.'),
                    default => null,
                };

                $incidenceValue = (float) str_replace(',', '.', $incidenceValueElement->text());
                $incidenceDate = \DateTimeImmutable::createFromFormat('d.m.Y', $incidenceDateElement->text());
                if (!$incidenceDate instanceof \DateTimeImmutable) {
                    throw $this->createException('Date not formattable.');
                }
                $incidenceDate = $incidenceDate->setTime(0, 0);

                $incidenceIndicator = Indicator::createIncidenceNewInfections($incidenceValue, $incidenceDate);

                break;
            }
        }

        if (null === $incidenceIndicator) {
            throw $this->createException();
        }

        $data->addIndicator($incidenceIndicator);

        return $data;
    }

    private function createException(?string $reason = null): \RuntimeException
    {
        $message = 'Incidence with ' . __CLASS__ . ' is not parsable.';

        if (null !== $reason) {
            $message .= ' ' . $reason;
        }

        return new \RuntimeException($message);
    }
}
