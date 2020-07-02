<?php


namespace App\Entity;


class FaussePicture
{
    /**
     * @param mixed $file
     */
    /*
    public function setFile($file): self
    {
        $this->file = $file;

        if ($this->path) {
            $this->tempFileName = $this->path;
        }

        $this->extension = $this->alt = null;

        return $this;
    }
    */

    /**
     * @ORM\PreRemove
     */
    public function preRemoveFile(): void
    {
        $this->tempFileName = $this->id.'.'.$this->extension;
    }

    /**
     * @ORM\PostRemove
     */
    public function removeFile(): void
    {
        if (file_exists(self::UPLOAD_ROOT_DIR.'/'.$this->tempFileName)) {
            unlink(self::UPLOAD_ROOT_DIR.'/'.$this->tempFileName);
        }
    }
}