<?php

namespace App\Model\Discogs;

class ReleasesApiRequest
{
    private Pagination $pagination;
    private array $releases;

    public function getPagination(): Pagination
    {
        return $this->pagination;
    }

    public function setPagination(Pagination $pagination): void
    {
        $this->pagination = $pagination;
    }

    public function getReleases(): array
    {
        return $this->releases;
    }

    public function setReleases(array $releases): void
    {
        $this->releases = $releases;
    }

    public function addRelease(Release $release): void
    {
        $this->releases[] = $release;
    }

}
