<?php

namespace App\Entity;

use App\Repository\MovieRepository;
use App\Validator\Constraints\MoviePosterExists;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MovieRepository::class)]
#[ORM\UniqueConstraint(name: 'IDX_UNIQUE_MOVIE_SLUG', fields: ['slug'])]
class Movie
{
    public final const SLUG_FORMAT = '\d{4}-'.Requirement::ASCII_SLUG;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotNull()]
    #[Assert\Length(min: 7)]
    #[Assert\Regex(pattern: '#'.self::SLUG_FORMAT.'#')]
    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[Assert\NotNull()]
    #[Assert\Length(min: 2)]
    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[Assert\NotNull()]
    #[Assert\Length(min: 4)]
    #[Assert\AtLeastOneOf(constraints: [
        new MoviePosterExists(),
        new Assert\Url()
    ])]
    #[ORM\Column(length: 255)]
    private ?string $poster = null;

    #[Assert\NotNull()]
    #[Assert\Length(min: 10)]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $plot = null;

    #[Assert\NotNull()]
    #[Assert\GreaterThan(value: '-200 years')]
    #[Assert\LessThanOrEqual(value: '+100 years')]
    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $releasedAt = null;

    #[Assert\Count(min: 1)]
    #[ORM\ManyToMany(targetEntity: Genre::class, inversedBy: 'movies')]
    private Collection $genres;

    public function __construct()
    {
        $this->genres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getPoster(): ?string
    {
        return $this->poster;
    }

    public function setPoster(string $poster): static
    {
        $this->poster = $poster;

        return $this;
    }

    public function getPlot(): ?string
    {
        return $this->plot;
    }

    public function setPlot(string $plot): static
    {
        $this->plot = $plot;

        return $this;
    }

    public function getReleasedAt(): ?\DateTimeImmutable
    {
        return $this->releasedAt;
    }

    public function setReleasedAt(\DateTimeImmutable $releasedAt): static
    {
        $this->releasedAt = $releasedAt;

        return $this;
    }

    /**
     * @return Collection<int, Genre>
     */
    public function getGenres(): Collection
    {
        return $this->genres;
    }

    public function addGenre(Genre $genre): static
    {
        if (!$this->genres->contains($genre)) {
            $this->genres->add($genre);
        }

        return $this;
    }

    public function removeGenre(Genre $genre): static
    {
        $this->genres->removeElement($genre);

        return $this;
    }
}
