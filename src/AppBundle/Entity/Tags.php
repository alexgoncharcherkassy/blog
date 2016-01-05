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
 * @ORM\Entity(repositoryClass="AppBundle\Entity\TagsRepository")
 */
class Tags
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
     *
     * @ORM\Column(name="tagName", type="string", length=255)
     */
    private $tagName;

    /**
     * @var string
     * @Gedmo\Slug(fields={"tagName"})
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     */
    private $slug;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Post", mappedBy="tags")
     */
    private $posts;



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
     * @return Tags
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
     * @return Tags
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
     * @return mixed
     */


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

    public function getPosts()
    {
        return $this->posts;
    }


}
