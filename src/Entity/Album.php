<?php

namespace App\Entity;

use App\Repository\AlbumRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AlbumRepository::class)]
class Album
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read'])]
    private ?int $id = null;

    
    #[ORM\Column(length: 100)]
    #[Groups(['read','create','update'])]
    #[Assert\NotBlank(groups: ['create'])]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $year = null;

    /**
     * @var Collection<int, Track>
     */
    #[ORM\OneToMany(targetEntity: Track::class, mappedBy: 'album')]
    private Collection $album_id;

    /**
     * @var Collection<int, Artist>
     */
    #[ORM\ManyToMany(targetEntity: Artist::class, mappedBy: 'artist_id')]
    private Collection $artists;

    /**
     * @var Collection<int, Artist>
     */
    #[ORM\ManyToMany(targetEntity: Artist::class, inversedBy: 'albums')]
    private Collection $artiste_id;

    #[ORM\Column]
    private ?int $artist_id = null;

    public function __construct()
    {
        $this->album_id = new ArrayCollection();
        $this->artists = new ArrayCollection();
        $this->artiste_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): static
    {
        $this->year = $year;

        return $this;
    }

    /**
     * @return Collection<int, Track>
     */
    public function getAlbumId(): Collection
    {
        return $this->album_id;
    }

    public function addAlbumId(Track $albumId): static
    {
        if (!$this->album_id->contains($albumId)) {
            $this->album_id->add($albumId);
            $albumId->setAlbum($this);
        }

        return $this;
    }

    public function removeAlbumId(Track $albumId): static
    {
        if ($this->album_id->removeElement($albumId)) {
            // set the owning side to null (unless already changed)
            if ($albumId->getAlbum() === $this) {
                $albumId->setAlbum(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Artist>
     */
    public function getArtists(): Collection
    {
        return $this->artists;
    }

    public function addArtist(Artist $artist): static
    {
        if (!$this->artists->contains($artist)) {
            $this->artists->add($artist);
            $artist->addArtistId($this);
        }

        return $this;
    }

    public function removeArtist(Artist $artist): static
    {
        if ($this->artists->removeElement($artist)) {
            $artist->removeArtistId($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Artist>
     */
    public function getArtisteId(): Collection
    {
        return $this->artiste_id;
    }

    public function addArtisteId(Artist $artisteId): static
    {
        if (!$this->artiste_id->contains($artisteId)) {
            $this->artiste_id->add($artisteId);
        }

        return $this;
    }

    public function removeArtisteId(Artist $artisteId): static
    {
        $this->artiste_id->removeElement($artisteId);

        return $this;
    }

    public function getArtistId(): ?int
    {
        return $this->artist_id;
    }

    public function setArtistId(int $artist_id): static
    {
        $this->artist_id = $artist_id;

        return $this;
    }
}
