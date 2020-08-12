<?php

namespace App\Service;

use App\Entity\Video;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use phpDocumentor\Reflection\Types\This;

/**
 * Class VideoPlatformParser
 *
 * Service to parse urls from famous VOD platforms.
 * use parseUrl($url) to proceed, and retrieve your results with getVideoId() and getWebsite()
 * retrieve results as an associative array with getResults()
 *
 * @author Nayte
 */
class VideoPlatformParser
{
    /** @var string|null */
    private $videoId;

    /** @var string|null */
    private $website;

    /** @var string|null */
    private $parsedUrl;

    /** @var bool */
    private $hasParsedRight;

    /**
     * Parse Youtube, Dailymotion or Vimeo URL.
     *
     * @param string|null $aUrl URL you want to parse
     * @return bool if url was valid or not.
     */
    public function parseUrl(?string $aUrl): bool
    {
        $this->videoId = $this->website = null;
        $this->parsedUrl = $aUrl;

        if (!$aUrl) {
            return false;
        } elseif (strpos($aUrl, 'youtube') !== false
            || strpos($aUrl, 'youtu.be') !== false) {
            // youtube
            if (preg_match('/(?:https?:\/\/)?(?:youtu\.be\/|(?:www\.|m\.)?youtube\.com\/(?:watch|v|embed)(?:\.php)?(?:\?.*v=|\/))([a-zA-Z0-9\-_]+)/', $aUrl, $id)) {
                $this->videoId = $id[1];
                $this->website = 'youtube';
            }
        } elseif (strpos($aUrl, 'vimeo') !== false) {
            // vimeo
            if (preg_match('/https:\/\/vimeo.com\/([\w-]+)/', $aUrl, $id)) {
                $this->videoId = $id[1];
                $this->website = 'vimeo';
            }
        } elseif (strpos($aUrl, 'dailymotion') !== false) {
            // dailymotion
            if (preg_match('/(.+)dailymotion.com\/video\/([\w-]+)/', $aUrl, $id)) {
                $this->videoId = $id[2];
                $this->website = 'dailymotion';
            }
        } elseif (strpos($aUrl, 'dai.ly') !== false) {
            // dailymotion
            if (preg_match('/(.+)dai.ly\/([\w-]+)/', $aUrl, $id)) {
                $this->videoId = $id[2];
                $this->website = 'dailymotion';
            }
        }

        $this->hasParsedRight = boolval($this->website);

        return $this->hasParsedRight;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function getVideoId(): ?string
    {
        return $this->videoId;
    }

    /**
     * Parsed information as an array :
     *
     * $result = [
     *      'website' as youtube, vimeo or dailymotion
     *      'videoId' as the string of video id
     * ];
     *
     * @return string[]|null
     */
    public function getResults(): array
    {
        return [
            'website' => $this->website,
            'videoId' => $this->videoId,
        ];
    }

    public function hasParsedRight(): bool
    {
        return $this->hasParsedRight;
    }
}
