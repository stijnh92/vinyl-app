<?php

declare(strict_types=1);

namespace App\Controller;

use App\ApiClient\DiscogsApiClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SellerStatsController extends AbstractController
{
    public function __construct(private readonly DiscogsApiClient $discogsClient) {
    }

    #[Route('/stats/seller/{username}')]
    public function __invoke(string $username): Response
    {
        $sellers = [];
        $releases = $this->discogsClient->getReleases($username);

        foreach($releases as $release) {
            $seller = null;
            $notes = $release->getNotes();
            foreach($notes as $note) {
                if ($note->getFieldId() === 5 && $note->getValue() !== '') {
                    $seller = $note->getValue();
                    $sellers[$seller]['albums'][] = $release->getArtist() . ' - ' . $release->getTitle();
                    break;
                }
            }

            if ($seller !== null) {
                $totalAmount = $sellers[$seller]['totalAmount'] ?? 0.0;
                foreach($notes as $note) {
                    if ($note->getFieldId() === 4) {
                        $sellers[$seller]['totalAmount'] = $totalAmount + (float) $note->getValue();
                        break;
                    }
                }

            } else {
                $sellers['undefined']['albums'][] = $release->getArtist() . ' - ' . $release->getTitle();
                $sellers['undefined']['totalAmount'] = 0;
            }
        }

        foreach($sellers as &$seller) {
            $seller['count'] = count($seller['albums']);
            $seller['average'] = $seller['totalAmount'] / $seller['count'];
        }
        
        uksort($sellers, 'strcasecmp');

        return new JsonResponse($sellers);
        
        dump($sellers); die;

        // return $this->render('releases/list.html.twig');
    }
}
