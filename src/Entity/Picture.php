<?php

namespace App\Entity;

use App\Entity\traits\EntityIdTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PictureRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Picture
{
    use EntityIdTrait;

    const UPLOAD_DIR = 'uploads/img';

    const UPLOAD_ROOT_DIR = __DIR__.'/../../public/'.self::UPLOAD_DIR;

    /**
     * Figure's post this picture is related to.
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Figure", inversedBy="pictures")
     * @ORM\JoinColumn(nullable=false)
     */
    private $figure;

    /**
     * Extension of the file as the user originally uploaded.
     *
     * @ORM\Column(type="string", length=50)
     */
    private $extension;

    /**
     * Alternative text associated to the img markup. Originally the name of the file the user uploaded.
     *
     * @ORM\Column(type="string", length=50)
     */
    private $alt;

    /**
     * @var UploadedFile $file
     */
    private $file;

    public function getFigure(): ?Figure
    {
        return $this->figure;
    }

    public function setFigure(?Figure $figure): self
    {
        $this->figure = $figure;

        return $this;
    }

    public function getExtension(): ?string
    {
        return $this->extension;
    }

    public function setExtension(string $extension): self
    {
        $this->extension = $extension;

        return $this;
    }

    public function getAlt(): ?string
    {
        return $this->alt;
    }

    public function setAlt(string $alt): self
    {
        $this->alt = $alt;

        return $this;
    }

    public function getWebPath(): string
    {
        return self::UPLOAD_DIR.'/'.$this->id.'.'.$this->extension;
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    public function setFile(UploadedFile $file): self
    {
        $this->file = $file;

        return $this;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function parseFilename(): void
    {
        if (!$this->file) return;

        $this->alt = explode('.', $this->file->getClientOriginalName())[0];
        $this->extension = $this->file->getClientOriginalExtension();
    }

    /**
     * @ORM\PostPersist
     * @ORM\PostUpdate
     */
    public function sortFile(): void
    {
        if (!$this->file) return;

        $this->file->move(self::UPLOAD_ROOT_DIR, $this->id.'.'.$this->extension);
    }
}
