<?php

namespace App\Entity;

use App\Model\Rating;
use App\Repository\MovieRepository;
use App\Validator\Constraints\MovieSlugFormat;
use App\Validator\Constraints\ValidPoster;
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
    #[MovieSlugFormat()]
    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[Assert\NotNull()]
    #[Assert\Length(min: 2)]
    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[Assert\NotNull()]
    #[Assert\Length(min: 4)]
    #[ValidPoster]
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

    #[ORM\Column(length: 8, enumType: Rating::class, options: ['default' => 'G'])]
    private ?Rating $rated = Rating::GeneralAudiences;

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

    public function getRated(): ?Rating
    {
        return $this->rated;
    }

    public function setRated(Rating $rated): static
    {
        $this->rated = $rated;

        return $this;
    }
}
