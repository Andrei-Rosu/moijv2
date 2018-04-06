<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 * @UniqueEntity("title")
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, unique=true)
     * @Assert\Length(min=3, max=50)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(min=15, max=65000)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(groups={"insertion"})
     * @Assert\Image(maxSize="2M", minWidth="200", minHeight="200")
     * @var object
     */

    private $image;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="products")
     * @var User owner
     */

    private $owner;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Tag", inversedBy="products", cascade="persist")
     * @var Collection
     */

    private $tags;
    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    /**
     * @return User
     */

    public function getOwner(): User
    {
        return $this->owner;
    }

    /**
     * @param User $owner
     * @return Product
     */
    public function setOwner(User $owner): Product
    {
        $this->owner = $owner;
        return $this;
    }



    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }


    public function getImage()
    {
        return $this->image;
    }


    public function setImage($image)
    {
        $this->image = $image;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag($tag)
    {
        if ($this->tags->contains($tag)){
            return;
        }
        $this->tags->add($tag);
        $tag->getProducts()->add($this);
    }

    /**
     * @param Collection $tags
     */
    public function setTags(Collection $tags)
    {
        $this->tags = $tags;
        return $this;
    }


}
