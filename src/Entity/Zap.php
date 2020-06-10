<?php

namespace App\Entity;

use App\Repository\ZapRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ZapRepository::class)
 */
class Zap
{
    use PublishableTrait, ViewableTrait;

    const TYPE_LONG = 'long';
    const TYPE_SHORT = 'short';

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
    private $subtitle;

    /**
     * @ORM\Column(type="text")
     */
    private $shortDescription;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $thumbnail;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="integer")
     */
    private $duration;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $link;

    public static $typeValues = [
        self::TYPE_SHORT => 'zap.type.short',
        self::TYPE_LONG => 'zap.type.long',
    ];


    public function __construct($data = null)
    {
        $this->setCreatedAt(new \DateTime());

        if ($data) {
            $this->setTitle($data['title'] ?? null);
            $this->setSubtitle($data['subtitle']);
            $this->setShortDescription($data['short_description']);
            $this->setThumbnail($data['thumbnail']);
            $this->setType($data['type']);
            $this->setDuration($data['duration']);
            $this->setLikes((int) $data['likes']);
            $this->setLink($data['link']);
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

    public function __toString()
    {
        return $this->getTitle();
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

    public function getSubtitle(): ?string
    {
        return $this->subtitle;
    }

    public function setSubtitle(string $subtitle): self
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    public function setShortDescription(string $shortDescription): self
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }

    public function getThumbnail(): ?string
    {
        return $this->thumbnail;
    }

    public function setThumbnail(string $thumbnail): self
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        if (in_array($type, array_keys(self::$typeValues))) {
            $this->type = $type;
        }

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(string $link): self
    {
        $this->link = $link;

        return $this;
    }
}
