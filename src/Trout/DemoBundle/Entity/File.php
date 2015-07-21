<?php

namespace Trout\DemoBundle\Entity;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * File
 *
 * @ORM\Table(name="trout_file")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class File
{
    const BASE_FOLDER = 'files';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $id;

    /**
     * Physical filename in local storage
     * @Assert\File(maxSize="6000000")
     */
    private $file;

    /**
     * Path to file in local storage
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $path;

    /**
     * Size of the file (in bytes)
     * @ORM\Column(type="integer")
     */
    public $filesize;

    /**
     * Original filename
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    public $name;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return mixed
     */
    public function getFilesize()
    {
        return $this->filesize;
    }

    /**
     * @param $filesize
     */
    public function setFilesize($filesize)
    {
        $this->filesize = $filesize;
    }

    /**
     * Get pat to this file in local storage
     * @param array $scale
     * @return null
     */
    public function getWebPath($scale = array())
    {
        if (empty($this->path)) {
            return null;
        }

        return $this->getUploadDir().'/'.$this->path;
    }


    /**
     * Get root directory for uploads
     * @return string
     */
    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        $dir = __DIR__.'/../../../../app/'.$this->getUploadDir();
        $fs = new Filesystem();
        if (!$fs->exists($dir)) {
            try {
                $fs->mkdir($dir);
            } catch (IOException $e) {
                echo "An error occurred while creating the directory ".$dir;
            }
        }
        return $dir;
    }

    /**
     * Get the relative path from webroot to the upload directory
     * @return string
     */
    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        $env = isset($_SERVER['ELMO_ENV']) ? $_SERVER['ELMO_ENV'] : '.';

        return self::BASE_FOLDER.'/'.$env.'/'.$this->getSubfolder();
    }

    /**
     * Get the subfolder where this file is located.
     * @return string
     */
    protected function getSubfolder()
    {
        // Derive the path from the first 4 characters of the filename
        $str = $this->path;
        $dir1 = substr($str, 0, 2);
        $dir2 = substr($str, 2, 2);
        return $dir1.'/'.$dir2;
    }

    /**
     * Return the path for display according to the file path
     * @param string $path
     * @return string
     */
    public static function getDisplayDir($path)
    {
        $dir1 = substr($path, 0, 2);
        $dir2 = substr($path, 2, 2);
        $env = isset($_SERVER['ELMO_ENV']) ? $_SERVER['ELMO_ENV'] : '.';
        $src = '/files/'.$env.'/'.$dir1.'/'.$dir2.'/'.$path;
        return $src;
    }


    /**
     * Sets file.
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }

    /**
     * Get file.
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Before upload, generate filepath, get filename, filesize
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->getFile()) {
            if (empty($this->path)) {
                $filename = sha1(uniqid(mt_rand(), true));
                $this->path = $filename.'.'.$this->getFile()->guessExtension();
            }

            if($this->getFile() instanceof UploadedFile){
                $this->filesize = $this->getFile()->getClientSize();
                $this->name = $this->getFile()->getClientOriginalName();
            }
        }
    }

    /**
     * On upload, move the file to its new home.
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->getFile()) {
            return;
        }

        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->getFile()->move($this->getUploadRootDir(), $this->path);


        $this->file = null;
        return;
    }


    /**
     * Get absolute path of the file in local storage
     * @return null|string
     */
    public function getAbsolutePath()
    {
        return null === $this->path
            ? null
            : $this->getUploadRootDir().'/'.$this->path;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Fetch the filename without the extension
     * @return string
     */
    public function getNameWithoutExtension()
    {
        $extension = pathinfo($this->getName(), PATHINFO_EXTENSION);
        return substr($this->name, 0, -(strlen($extension)+1));
    }

    /**
     * @param $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}