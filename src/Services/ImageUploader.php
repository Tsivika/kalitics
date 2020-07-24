<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class ImageUploader.
 */
class ImageUploader
{
    /**
     * @var string
     */
    private $targetDirectory;

    /**
     * ImageUploader constructor.
     *
     * @param string $targetDirectory
     */
    public function __construct(string $targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;

        if (!is_dir($this->targetDirectory)) {
            mkdir($this->targetDirectory, 0777, true);
            chmod($this->targetDirectory, 0777);
        }
    }

    /**
     * @param UploadedFile $file
     *
     * @return string
     */
    public function upload(UploadedFile $file): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {
            return '';
        }

        return $fileName;
    }

    /**
     * Remove uploaded file.
     *
     * @param string $path
     */
    public function removeFileUploaded(string $path)
    {
        try {
            if ($path) {
                unlink($path);
            }
        } catch (\Exception $e) {
            //nothing yet
        }
    }

    /**
     * @return string
     */
    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }

    /**
     * @return string
     */
    public static function generateUniqueFileName(): string
    {
        return md5(uniqid('', true));
    }
}
