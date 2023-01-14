<?php

namespace App\ApiClient;

use App\Model\Discogs\Release;
use App\Model\Discogs\ReleasesApiRequest;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class DiscogsApiClient
{
    public function __construct(
        private readonly HttpClientInterface $discogsClient,
        private readonly DenormalizerInterface $normalizer
    ) {
    }

    /**
    * @return Release[]
    */
    public function getReleases(string $username): array
    {
        $releases = [];
        $hasNextPage = true;

        $url = sprintf('/users/%s/collection/folders/0/releases?sort=artist&sort_order=asc&per_page=100', $username);
        while($hasNextPage) {
            $response = $this->discogsClient->request('GET', $url);

            $releasesApiRequest = $this->normalizer->denormalize(
                $response->toArray(),
                ReleasesApiRequest::class
            );
            $releases = array_merge($releases, $releasesApiRequest->getReleases());

            $pagination = $releasesApiRequest->getPagination();
            if ($pagination->hasNext()) {
                $url = $pagination->getNext();
            } else {
                $hasNextPage = false;
            }
        }

        return $releases;
    }
}
