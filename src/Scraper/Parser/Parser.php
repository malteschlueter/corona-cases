<?php

declare(strict_types=1);

namespace App\Scraper\Parser;

use App\Dto\Data;

interface Parser
{
    public function parse(Data $data, string $content): Data;
}
