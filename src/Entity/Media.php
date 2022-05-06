<?php

namespace App\Entity;

use App\Repository\MediaRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MediaRepository::class)]
class Media
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $title;

    #[ORM\Column(type: 'string', length: 255)]
    private $type;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $uploadedPath;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $streamedPath;

    #[ORM\ManyToOne(targetEntity: Trick::class, inversedBy: 'media')]
    private $trick;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getUploadedPath(): ?string
    {
        return $this->uploadedPath;
    }

    public function setUploadedPath(?string $uploadedPath): self
    {
        $this->uploadedPath = $uploadedPath;

        return $this;
    }

    public function getStreamedPath(): ?string
    {
        return $this->streamedPath;
    }

    public function setStreamedPath(?string $streamedPath): self
    {
        $this->streamedPath = $streamedPath;

        return $this;
    }

    public function getTrick(): ?Trick
    {
        return $this->trick;
    }

    public function setTrick(?Trick $trick): self
    {
        $this->trick = $trick;

        return $this;
    }
}
