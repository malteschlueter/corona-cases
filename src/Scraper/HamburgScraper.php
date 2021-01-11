<?php

declare(strict_types=1);

namespace App\Scraper;

use App\Dto\Data;
use App\HttpClient\CacheClient;
use App\Scraper\Parser\HamburgParser;

class HamburgScraper implements Scraper
{
    private const ID = 'hamburg';
    private const URL = 'https://www.hamburg.de/corona-zahlen/';

    public function __construct(
        private CacheClient $client,
        private HamburgParser $parser
    ) {
    }

    public function getId(): string
    {
        return self::ID;
    }

    public function scrap(): Data
    {
        $data = new Data(self::URL, new \DateTimeImmutable());
        $content = $this->client->request($data->source);

        $this->parser->parse($data, $content);

        return $data;
    }
}
