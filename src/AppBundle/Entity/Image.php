<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * Image
 *
 * @ORM\Table(name="image")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\ImageRepository")
 */
class Image
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
     * @Assert\File(
     *              maxSize = "3M",
     *              mimeTypes = {"image/*"}
     *              )
     */
    private $blogImage;

    /**
     * @var string
     *
     * @ORM\Column(name="nameImage", type="string", length=255)
     */
    private $nameImage;

    /**
     * @var string
     *
     * @ORM\Column(name="pathImage", type="string", length=255)
     */
    private $pathImage;


    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Post", inversedBy="images")
     */
    private $post;


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
     * Set blogImage
     *
     * @param string $blogImage
     *
     * @return Image
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
     * @return Image
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
     * @return Image
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

    /**
     * @return mixed
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * @param mixed $post
     */
    public function setPost(Post $post = null)
    {
        $this->post = $post;
    }

    public function getAbsolutePath()
    {
        return null === $this->pathImage
            ? null
            : $this->getUploadRootDir().'/'.$this->pathImage;
    }

    public function getWebPath()
    {
        return null === $this->pathImage
            ? null
            : $this->getUploadDir().'/'.$this->pathImage;
    }

    protected function getUploadRootDir()
    {
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        return 'img/blog';
    }

    public function uploadImage($role)
    {
        if ($role == 'new') {
            if (null === $this->getBlogImage()) {
                $this->pathImage = $this->getUploadDir().'/default.jpg';
                $this->nameImage = 'default.jpg';
                return;
            }
        }
        if ($role == 'edit') {
            if (null === $this->getBlogImage()) {
                return;
            }
        }
        $randPrefix = mt_rand(1, 9999);
        $this->getBlogImage()->move(
            $this->getUploadRootDir(),
            $randPrefix.'-'.$this->getBlogImage()->getClientOriginalName()
        );
        $this->pathImage = $this->getUploadDir().'/'.$randPrefix.'-'.$this->getBlogImage()->getClientOriginalName();
        $this->nameImage = $randPrefix.'-'.$this->getBlogImage()->getClientOriginalName();
        $this->blogImage = null;
    }

}
