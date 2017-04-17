<?php

namespace AppBundle\Service;

use AppBundle\Entity\Media;
use Cloudinary\Uploader;
use Doctrine\ORM\EntityManager;

class Cloudinary
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * Cloudinary constructor.
     * @param EntityManager $em
     * @param array $cloudinaryConfig
     */
    public function __construct(EntityManager $em, array $cloudinaryConfig)
    {
        $cloudinary = [];

        array_walk_recursive($cloudinaryConfig, function ($a, $b) use (&$cloudinary) {
            $cloudinary[$b] = $a;
        });

        \Cloudinary::config($cloudinary);

        /** @var EntityManager em */
        $this->em = $em;
    }

    /**
     * @param $path
     * @return Media|null
     */
    public function upload($path)
    {
        $uploaded = Uploader::upload($path);

        $media = new Media();
        $media->setEtag($uploaded['etag'] ?? '')
            ->setHeight($uploaded['height'] ?? 0)
            ->setWidth($uploaded['width'] ?? 0)
            ->setUrl($uploaded['url'] ?? '')
            ->setPublicId($uploaded['public_id']);
        $this->em->persist($media);
        $this->em->flush();

        return $media;
    }
}
