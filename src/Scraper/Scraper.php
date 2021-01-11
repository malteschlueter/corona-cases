<?php

declare(strict_types=1);

namespace App\Scraper;

use App\Dto\Data;

interface Scraper
{
    public function getId(): string;

    public function scrap(): Data;
}
