<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Tags
 *
 * @ORM\Table(name="tags")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TagRepository")
 */
class Tag implements \JsonSerializable
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @Assert\NotBlank(message="This field can not be empty")
     * @Assert\Length(min="3", minMessage="This field can not be less than 3 characters")
     *
     * @ORM\Column(name="tagName", type="string", length=255)
     */
    private $tagName;

    /**
     * @var string
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     */
    private $slug;

    /**
     *
     * @ORM\Column(name="weightTag" , type="integer", nullable=true)
     */
    private $weightTag;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Post", mappedBy="tags")
     */
    private $posts;

    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'tagName' => $this->getTagName()
        ];
    }

    /**
     *
     */
    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set tagName
     *
     * @param string $tagName
     *
     * @return Tag
     */
    public function setTagName($tagName)
    {
        $this->tagName = $tagName;

        return $this;
    }

    /**
     * Get tagName
     *
     * @return string
     */
    public function getTagName()
    {
        return $this->tagName;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Tag
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param Post $posts
     */
    public function addPost(Post $posts)
    {
        $this->posts[] = $posts;

        return $this;
    }

    /**
     * @param Post $posts
     */
    public function removePost(Post $posts)
    {
        $this->posts->removeElement($posts);
    }

    /**
     * @return ArrayCollection
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * @return mixed
     */
    public function getWeightTag()
    {
        return $this->weightTag;
    }

    /**
     * @param mixed $weightTag
     */
    public function setWeightTag($weightTag)
    {
        $this->weightTag = $weightTag;
    }

    /*function __toString()
    {
        return $this->getTagName();
    }*/


}
