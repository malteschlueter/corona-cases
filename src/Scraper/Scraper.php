<?php

declare(strict_types=1);

namespace App\Scraper;

use App\Dto\Data;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('app.scraper')]
interface Scraper
{
    public function getId(): string;

    public function scrap(): Data;
}
