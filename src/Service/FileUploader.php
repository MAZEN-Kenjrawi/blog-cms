<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Psr\Log\LoggerInterface;

class FileUploader
{
    private $upload_dir;

    private $logger;

    private $clean_name = false;

    private $type;

    public function __construct(LoggerInterface $logger, string $upload_dir)
    {
        $this->logger = $logger;
        $this->upload_dir = $upload_dir;
    }

    public function upload(UploadedFile $file, $file_name = '')
    {
        if (empty($file_name)) {
            $file_name = $file->getClientOriginalName();
        }

        if ($this->clean_name) {
            $file_name = md5(uniqid()) . '.' . $file->guessExtension();
        }

        try {
            $file->move($this->getUploadDirectory($this->type), $file_name);
        } catch (FileException $e) {
            $this->logger->error('failed to upload file: ' . $e->getMessage());
            throw new FileException('Failed to upload file');
        }

        return $this->type . '/' . $file_name;
    }

    public function isValid($file)
    {
        return true;
    }

    public function getUploadDirectory(string $append_path = ''): string
    {
        return $this->upload_dir . $append_path;
    }

    public function setUploadDirectory(string $upload_dir): self
    {
        $this->upload_dir = $upload_dir;

        return $this;
    }

    public function cleanName(bool $clean_name): self
    {
        $this->clean_name = $clean_name;

        return $this;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }
}
