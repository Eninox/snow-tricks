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
    #[Vich\UploadableField(mapping: 'trick_media_picture', fileNameProperty: 'uploadedPath')]
    private ?File $pictureFile = null;

    // This attribute is not use by Doctrine, juste for VichUploaderBundle to store the file
    #[Vich\UploadableField(mapping: 'trick_media_video', fileNameProperty: 'uploadedPath')]
    private ?File $videoFile = null;

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

    // setter for the picture file which use VichUploaderBundle
    public function setPictureFile(?File $uploadedPath): void
    {
        $this->pictureFile = $uploadedPath;

//        if (null !== $mainPicture) {
//            // It is required that at least one field changes if you are using doctrine
//            // otherwise the event listeners won't be called and the file is lost
//            $this->createdAt = new \DateTimeImmutable();
//        }
    }

    // getter for the picture file which use VichUploaderBundle
    public function getPictureFile(): ?File
    {
        return $this->pictureFile;
    }

    // setter for the video file which use VichUploaderBundle
    public function setVideoFile(?File $uploadedPath): void
    {
        $this->videoFile = $uploadedPath;

//        if (null !== $mainPicture) {
//            // It is required that at least one field changes if you are using doctrine
//            // otherwise the event listeners won't be called and the file is lost
//            $this->createdAt = new \DateTimeImmutable();
//        }
    }

    // getter for the video file which use VichUploaderBundle
    public function getVideoFile(): ?File
    {
        return $this->videoFile;
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
