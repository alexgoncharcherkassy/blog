<?php
/**
 * Created by PhpStorm.
 * User: device
 * Date: 29.01.16
 * Time: 17:07
 */

namespace AppBundle\Service;


use AppBundle\Entity\Post;

class ImageManagerServices
{

    protected function getUploadRootDir()
    {
        return __DIR__ . '/../../../web/' . $this->getUploadDir();
    }

    /**
     * @return string
     */
    protected function getUploadDir()
    {
        return 'img/blog';
    }

    public function upload(Post $post)
    {
        $randPrefix = mt_rand(1, 9999);
        $post->getBlogImage()->move(
            $this->getUploadRootDir(),
            $randPrefix . '-' . $post->getBlogImage()->getClientOriginalName()
        );
        $post->setPathImage($this->getUploadDir() . '/' . $randPrefix . '-' . $post->getBlogImage()->getClientOriginalName());
        $post->setNameImage($randPrefix . '-' . $post->getBlogImage()->getClientOriginalName());
        $post->setBlogImage(null);

        return;
    }
}