<?php

declare(strict_types=1);

namespace App\Command;

use App\Scraper\Scraper;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

#[AsCommand(
    name: 'app:fetch-corona-cases'
)]
final class AppFetchCoronaCasesCommand extends Command
{
    /**
     * @param Scraper[] $scraper
     */
    public function __construct(
        #[TaggedIterator('app.scraper')]
        private iterable $scraper,
        private SerializerInterface $serializer,
        private string $dataPath
    ) {
        parent::__construct();
    }

    public function run(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $filesystem = new Filesystem();

        foreach ($this->scraper as $scraper) {
            $io->info(sprintf('Fetching "%s"', $scraper->getId()));

            $data = $scraper->scrap();

            $jsonData = $this->serializer->serialize(
                $data,
                JsonEncoder::FORMAT,
                [JsonEncode::OPTIONS => \JSON_PRETTY_PRINT]
            );

            $filename = sprintf('%s/%s.latest.json', $this->dataPath, $scraper->getId());
            $filesystem->dumpFile($filename, $jsonData);
        }

        $io->success('Fetched corona cases successful.');

        return 0;
    }
}
