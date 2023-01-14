<?php

namespace App\Model\Discogs;

use Symfony\Component\Serializer\Annotation\SerializedPath;

class Pagination
{
    private int $page;
    private int $pages;
    #[SerializedPath('[per_page]')]
    private int $perPage;
    private int $items;
    private array $urls;

    public function getPage(): int
    {
        return $this->page;
    }

    public function setPage(int $page): void
    {
        $this->page = $page;
    }

    public function getPages(): int
    {
        return $this->pages;
    }

    public function setPages(int $pages): void
    {
        $this->pages = $pages;
    }

    public function getPerPage(): int
    {
        return $this->perPage;
    }

    public function setPerPage(int $perPage): void
    {
        $this->perPage = $perPage;
    }

    public function getItems(): int
    {
        return $this->items;
    }

    public function setItems(int $items): void
    {
        $this->items = $items;
    }

    public function getUrls(): array
    {
        return $this->urls;
    }

    public function setUrls(array $urls): void
    {
        $this->urls = $urls;
    }

    public function getNext(): ?string
    {
        return $this->urls['next'] ?? null;
    }

    public function hasNext(): bool
    {
        return $this->getNext() !== null;
    }
}
