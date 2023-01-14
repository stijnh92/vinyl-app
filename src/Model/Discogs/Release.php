<?php

namespace App\Model\Discogs;

use Symfony\Component\Serializer\Annotation\SerializedPath;
use Symfony\Component\Serializer\Annotation\Groups;

class Release
{
    #[Groups(['read'])]
    private int $id;

    #[Groups(['read'])]
    #[SerializedPath('[basic_information][title]')]
    private string $title;

    #[SerializedPath('[basic_information][year]')]
    private int $year;

    #[SerializedPath('[basic_information][artists]')]
    private array $artists;

    #[Groups(['read'])]
    private string $artist;

    #[SerializedPath('[basic_information][genres]')]
    private array $genres;

    #[Groups(['read'])]
    #[SerializedPath('[basic_information][cover_image]')]
    private string $coverImage;

    #[SerializedPath('[basic_information][thumb]')]
    private string $thumb;

    private array $notes;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function setYear(int $year): void
    {
        $this->year = $year;
    }

    /**
     * @return Artist[]
     */
    public function getArtists(): array
    {
        return $this->artists;
    }

    public function setArtists(array $artists): void
    {
        $this->artists = $artists;
    }

    public function addArtist(Artist $artist): void
    {
        $this->artists[] = $artist;
    }

    public function getArtist(): string
    {
        $artistNames = array_map(function ($artist) { return $artist->getName(); }, $this->artists);
        return implode(', ', $artistNames);
    }

    public function getGenres(): array
    {
        return $this->genres;
    }

    public function setGenres(array $genres): void
    {
        $this->genres = $genres;
    }

    public function getCoverImage(): string
    {
        return $this->coverImage;
    }

    public function setCoverImage(string $coverImage): void
    {
        $this->coverImage = $coverImage;
    }

    public function getThumb(): string
    {
        return $this->thumb;
    }

    public function setThumb(string $thumb): void
    {
        $this->thumb = $thumb;
    }

    /**
     * @return Note[]
     */
    public function getNotes(): array
    {
        return $this->notes;
    }

    public function setNotes(array $notes): void
    {
        $this->notes = $notes;
    }

    public function addNote(Note $note): void
    {
        $this->notes[] = $note;
    }
}
