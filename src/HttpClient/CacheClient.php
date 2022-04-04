<?php

declare(strict_types=1);

namespace App\HttpClient;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class CacheClient
{
    private readonly Filesystem $filesystem;

    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly string $cachePath,
        private readonly bool $cacheResponse
    ) {
        $this->filesystem = new Filesystem();
    }

    public function request(string $url): string
    {
        $cacheFile = sprintf('%s/%s.html', $this->cachePath, sha1($url));

        if ($this->cacheResponse && $this->filesystem->exists($cacheFile)) {
            return (string) file_get_contents($cacheFile);
        }

        $response = $this->client->request('GET', $url);

        $content = $response->getContent();

        if ($this->cacheResponse) {
            $this->filesystem->dumpFile($cacheFile, $content);
        }

        return $content;
    }
}
