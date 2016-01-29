<?php

namespace AppBundle\Entity;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Post
 *
 * @ORM\Table(name="post")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PostRepository")
 */
class Post
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
     * @Assert\Length(min="5", minMessage="This field can not be less than 5 characters")
     *
     * @ORM\Column(name="titlePost", type="string", length=255)
     */
    private $titlePost;

    /**
     * @var string
     * @Assert\NotBlank(message="This field can not be empty")
     * @Assert\Length(min="30", minMessage="This field can not be less than 30 characters")
     *
     * @ORM\Column(name="textPost", type="text")
     */
    private $textPost;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     */
    private $slug;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updateAt", type="datetime")
     */
    private $updateAt;

    /**
     * @var string
     *
     * @ORM\Column(name="newTags", type="string", length=255, nullable=true)
     */
    private $newTags;

    /**
     * @var string
     *
     * @ORM\Column(name="newCategory", type="string", length=255, nullable=true)
     */
    private $newCategory;

    /**
     * @var float
     *
     * @ORM\Column(name="rating", type="float")
     */
    private $rating;

    /**
     * @var string
     * @Assert\File(
     *              maxSize = "3M",
     *              mimeTypes = {"image/*"},
     *              maxSizeMessage = "The file is too large ({{ size }}).Allowed maximum size is {{ limit }}",
     *              mimeTypesMessage = "The mime type of the file is invalid ({{ type }}). Allowed mime types are {{ types }}"
     *              )
     */
    private $blogImage;

    /**
     * @var string
     *
     * @ORM\Column(name="nameImage", type="string", length=255, nullable=true)
     */
    private $nameImage;

    /**
     * @var string
     *
     * @ORM\Column(name="pathImage", type="string", length=255, nullable=true)
     */
    private $pathImage;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Comment", mappedBy="post")
     */
    private $comments;


    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Tag", inversedBy="posts")
     */
    private $tags;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Category", inversedBy="posts")
     */
    private $category;

    /**
     *
     */
    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }


    /**
     * @param Comment $comment
     */
    public function addComment(Comment $comment)
    {
        //   $comment->setPost($this);
        $this->comments[] = $comment;

        return $this;
    }

    /**
     * @param Comment $comment
     */
    public function removeComment(Comment $comment)
    {
        $this->comments->removeElement($comment);
    }

    /**
     * @return mixed
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param Tag $tags
     */
    public function addTag(Tag $tags)
    {

        $this->tags[] = $tags;

        return $this;
    }

    /**
     * @param Tag $tags
     */
    public function removeTag(Tag $tags)
    {
        $this->tags->removeElement($tags);
    }


    /**
     * @return ArrayCollection
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param Tag $tags
     */
    public function setTags(Tag $tags = null)
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        if ($this->category === null) {
            $this->category = array('slug' => '#', 'categoryName' => 'Without category');

            return $this->category;
        }
        return $this->category;
    }

    /**
     * @param Category $category
     */
    public function setCategory(Category $category = null)
    {
        $this->category = $category;
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
     * Set titlePost
     *
     * @param string $titlePost
     *
     * @return Post
     */
    public function setTitlePost($titlePost)
    {
        $this->titlePost = $titlePost;

        return $this;
    }

    /**
     * Get titlePost
     *
     * @return string
     */
    public function getTitlePost()
    {
        return $this->titlePost;
    }

    /**
     * Set textPost
     *
     * @param string $textPost
     *
     * @return Post
     */
    public function setTextPost($textPost)
    {
        $this->textPost = $textPost;

        return $this;
    }

    /**
     * Get textPost
     *
     * @return string
     */
    public function getTextPost()
    {
        return $this->textPost;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Post
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Post
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updateAt
     *
     * @param \DateTime $updateAt
     *
     * @return Post
     */
    public function setUpdateAt($updateAt)
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    /**
     * Get updateAt
     *
     * @return \DateTime
     */
    public function getUpdateAt()
    {
        return $this->updateAt;
    }

    /**
     * Set newTags
     *
     * @param string $newTags
     *
     * @return Post
     */
    public function setNewTags($newTags)
    {
        $this->newTags = $newTags;

        return $this;
    }

    /**
     * Get newTags
     *
     * @return string
     */
    public function getNewTags()
    {
        return $this->newTags;
    }

    /**
     * Set newCategory
     *
     * @param string $newCategory
     *
     * @return Post
     */
    public function setNewCategory($newCategory)
    {
        $this->newCategory = $newCategory;

        return $this;
    }

    /**
     * Get newCategory
     *
     * @return string
     */
    public function getNewCategory()
    {
        return $this->newCategory;
    }

    /**
     * @return float
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @param float $rating
     */
    public function setRating($rating)
    {
        $this->rating = $rating;
    }


    /**
     * Set blogImage
     *
     * @param string $blogImage
     *
     */
    public function setBlogImage(UploadedFile $file = null)
    {
        $this->blogImage = $file;
    }

    /**
     * Get blogImage
     *
     * @return string
     */
    public function getBlogImage()
    {
        return $this->blogImage;
    }

    /**
     * Set nameImage
     *
     * @param string $nameImage
     *
     */
    public function setNameImage($nameImage)
    {
        $this->nameImage = $nameImage;

        return $this;
    }

    /**
     * Get nameImage
     *
     * @return string
     */
    public function getNameImage()
    {
        return $this->nameImage;
    }

    /**
     * Set pathImage
     *
     * @param string $pathImage
     *
     */
    public function setPathImage($pathImage)
    {
        $this->pathImage = $pathImage;

        return $this;
    }

    /**
     * Get pathImage
     *
     * @return string
     */
    public function getPathImage()
    {
        return $this->pathImage;
    }

}
