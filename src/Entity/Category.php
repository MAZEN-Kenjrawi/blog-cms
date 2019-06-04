<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 */
class Category
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $title;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $slug;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $meta_title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $meta_description;

    /**
     * @Assert\Choice(
     *     choices = { "en", "ar" },
     *     message = "Choose a valid language ['EN', 'AR']."
     * )
     * @ORM\Column(type="string", length=10, options={"default" : "en"}, nullable=false)
     */
    private $lang;

    /**
     * @ORM\Column(type="integer", options={"default" : 1, "unsigned"=true}, nullable=true)
     */
    private $sort;

    /**
     * @Assert\Choice(
     *     choices = { false, true },
     *     message = "Choose a valid status."
     * )
     * @ORM\Column(type="smallint", options={"default": 0, "unsigned"=true}, nullable=false)
     */
    private $active;

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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getMetaTitle(): ?string
    {
        return $this->meta_title;
    }

    public function setMetaTitle(string $meta_title): self
    {
        $this->meta_title = $meta_title;

        return $this;
    }

    public function getMetaDescription(): ?string
    {
        return $this->meta_description;
    }

    public function setMetaDescription(?string $meta_description): self
    {
        $this->meta_description = $meta_description;

        return $this;
    }

    public function getLang(): ?string
    {
        return $this->lang;
    }

    public function setLang(string $lang): self
    {
        $this->lang = $lang;

        return $this;
    }

    public function getSort(): ?int
    {
        return $this->sort;
    }

    public function setSort(int $sort): self
    {
        $this->sort = $sort;

        return $this;
    }

    public function getActive(): ?bool
    {
        return (bool) $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }
}
