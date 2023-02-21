<?php

declare(strict_types=1);

namespace App\Controller;

use App\ApiClient\DiscogsApiClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use \DateTime;

class TimeStatsController extends AbstractController
{
    public function __construct(private readonly DiscogsApiClient $discogsClient) {
    }

    #[Route('/stats/time/{username}')]
    public function __invoke(string $username): Response
    {
        $periods = [];
        $releases = $this->discogsClient->getReleases($username);

        foreach($releases as $release) {
            $period = null;
            $notes = $release->getNotes();
            foreach($notes as $note) {
                if ($note->getFieldId() === 7 && $note->getValue() !== '') {
                    $date = DateTime::createFromFormat('d/m/Y', $note->getValue());        
                    $period = $date->format('Y-m');
                    $periods[$period]['albums'][] = $release->getArtist() . ' - ' . $release->getTitle();
                    break;
                }
            }

            if ($period !== null) {
                $totalAmount = $periods[$period]['totalAmount'] ?? 0.0;
                foreach($notes as $note) {
                    if ($note->getFieldId() === 4) {
                        $periods[$period]['totalAmount'] = $totalAmount + (float) $note->getValue();
                        break;
                    }
                }

            }
        }

        foreach($periods as &$period) {
            $period['count'] = count($period['albums']);
            $period['average'] = $period['totalAmount'] / $period['count'];
        }
        
        uksort($periods, 'strcasecmp');

        return new JsonResponse($periods);
    }
}
