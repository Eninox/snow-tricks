<?php

namespace App\Entity;

use App\Repository\MediaRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: MediaRepository::class)]
class Media
{
    const TYPE_PICTURE = 'Image';
    const TYPE_VIDEO_UPLOADED = 'Vidéo téléchargée';
    const TYPE_VIDEO_STREAMED = 'Vidéo streamée';

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

    // This attribute is not use by Doctrine, juste for VichUploaderBundle to store the file
    #[Vich\UploadableField(mapping: 'trick_media', fileNameProperty: 'uploadedPath')]
    private ?File $mediaFile = null;

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

    // setter for the media file which use VichUploaderBundle
    public function setMediaFile(?File $uploadedPath): void
    {
        $this->mediaFile = $uploadedPath;

        if (null !== $uploadedPath) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->createdAt = new \DateTimeImmutable();
        }
    }

    // getter for the media file which use VichUploaderBundle
    public function getMediaFile(): ?File
    {
        return $this->mediaFile;
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

    public function getSrcVideo()
    {
        // if the streamPath contains a youtube link
        if (str_contains($this->getStreamedPath(), 'youtube')) {
            // parse the youtube link to get the video id
            $parseUrl = parse_url($this->getStreamedPath(), PHP_URL_QUERY);
            // explode the query string to get the video id
            parse_str($parseUrl, $params);

            return 'https://www.youtube.com/embed/' . $params['v'];
        }

        if (str_contains($this->getStreamedPath(), 'vimeo')) {
            $parseUrl = parse_url($this->getStreamedPath(), PHP_URL_QUERY);
            parse_str($parseUrl, $params);

            return 'https://player.vimeo.com/video/' . $params['id'];
        }
    }
}
