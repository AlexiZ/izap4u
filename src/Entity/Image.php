<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ImageRepository::class)
 */
class Image
{
    use PublishableTrait, ViewableTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $path;

    /**
     * @ORM\Column(type="integer")
     */
    private $viewCount;


    public function __construct($data = null)
    {
        $this->setCreatedAt(new \DateTime());

        if ($data) {
            $this->setTitle($data['title'] ?? null);
            $this->setPath($data['thumbnail']);
            $this->setLikes((int) $data['likes']);
            $this->setStatus($data['status']);
            $this->setViews((int) $data['views']);
            $this->setCreatedBy($data['created_by']);
            try {
                $this->setPublishedAt(new \DateTime((date('Y-m-d H:i:s', strtotime($data['published_at'] ?? 'one year ago')))));
                $this->setCreatedAt(new \DateTime((date('Y-m-d H:i:s', strtotime($data['created_at'] ?? 'now')))));
            } catch (\Exception $e) {}
        }

        return $this;
    }

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

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getViewCount(): ?int
    {
        return $this->viewCount;
    }

    public function setViewCount(int $viewCount): self
    {
        $this->viewCount = $viewCount;

        return $this;
    }
}
