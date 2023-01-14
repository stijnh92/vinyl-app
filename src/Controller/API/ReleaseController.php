<?php

declare(strict_types=1);

namespace App\Controller\API;

use App\ApiClient\DiscogsApiClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Annotation\SerializedPath;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;

class ReleaseController extends AbstractController
{
    public function __construct(
        private readonly DiscogsApiClient $discogsClient,
        private readonly SerializerInterface $serializer
    ) {
    }

    #[Route('/api/releases/{username}')]
    public function __invoke(string $username): JsonResponse
    {
        $releases = $this->discogsClient->getReleases($username);
        return new JsonResponse(
            $this->serializer->serialize(
                $releases,
                'json',
                [AbstractObjectNormalizer::GROUPS => ['read']]
            ), 200, [], true
        );
    }
}
