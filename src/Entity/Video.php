<?php

namespace App\Entity;

use App\Entity\traits\EntityIdTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VideoRepository")
 */
class Video
{
    use EntityIdTrait;

    const YOUTUBE = 0;
    const DAILYMOTION = 1;
    const VIMEO = 2;

    /**
     * Foreign key
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Figure", inversedBy="videos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $figure;

    /**
     * Video's string of characters used in the URL in order to access it.
     *
     * @ORM\Column(type="string", length=25)
     */
    private $videoId;

    /**
     * Platform : YouTube, Vimeo or DailyMotion
     *
     * @ORM\Column(type="string", length=8)
     */
    private $platform;

    public function getVideoId(): ?string
    {
        return $this->videoId;
    }

    public function setVideoId(string $videoId): self
    {
        $this->videoId = $videoId;

        return $this;
    }

    public function getPlatform(): ?string
    {
        if ($this->platform == self::YOUTUBE) {
            return 'youtube';
        } elseif ($this->platform == self::DAILYMOTION) {
            return 'dailymotion';
        } elseif ($this->platform == self::VIMEO) {
            return 'vimeo';
        }

        return null;
    }

    public function setPlatform(string $platform): self
    {
        if (strtolower($platform) == 'youtube') {
            $this->platform = self::YOUTUBE;
        } elseif (strtolower($platform) == 'dailymotion') {
            $this->platform = self::DAILYMOTION;
        } elseif (strtolower($platform) == 'vimeo') {
            $this->platform = self::VIMEO;
        }

        return $this;
    }

    public function getFigure(): Figure
    {
        return $this->figure;
    }

    public function setFigure(?Figure $figure): self
    {
        $this->figure = $figure;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl(): ?string
    {
        if ($this->platform == self::YOUTUBE) {
            return 'https://www.youtube.com/embed/'.$this->videoId;
        } elseif ($this->platform == self::VIMEO) {
            return 'https://player.vimeo.com/video/'.$this->videoId;
        } elseif ($this->platform == self::DAILYMOTION) {
            return 'https://www.dailymotion.com/embed/video/'.$this->videoId;
        }

        return null;
    }
}
