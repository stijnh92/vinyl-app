<?php

namespace App\Model\Discogs;

use Symfony\Component\Serializer\Annotation\Groups;

class Artist
{
    #[Groups(['read'])]
    private int $id;

    #[Groups(['read'])]
    private string $name;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
