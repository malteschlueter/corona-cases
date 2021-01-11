<?php

declare(strict_types=1);

namespace App\Tests\Scraper\Parser;

use App\Dto\Data;
use App\Dto\Indicator;
use App\Scraper\Parser\HamburgParser;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Scraper\Parser\HamburgParser
 */
class HamburgParserTest extends TestCase
{
    public function testParse(): void
    {
        $parser = new HamburgParser();
        $fixtureContent = (string) file_get_contents(sprintf('%s/fixtures/hamburg.html', __DIR__));

        $expectedData = $this->getExpectedData();

        $data = new Data('foo', new \DateTimeImmutable('2021-01-11'));
        $parser->parse($data, $fixtureContent);

        $this->assertEquals($expectedData, $data);
    }

    private function getExpectedData(): Data
    {
        $data = new Data('foo', new \DateTimeImmutable('2021-01-11'));

        $incidenceIndicator = Indicator::createIncidenceNewInfections(162.3, new \DateTimeImmutable('2021-01-11'));
        $data->addIndicator($incidenceIndicator);

        return $data;
    }
}
