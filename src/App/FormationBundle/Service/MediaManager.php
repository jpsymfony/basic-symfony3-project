<?php

namespace App\FormationBundle\Service;

use App\FormationBundle\Entity\Media;
use App\FormationBundle\Entity\User;
use App\FormationBundle\Repository\Interfaces\GenericRepositoryInterface;

class MediaManager extends AbstractGenericManager
{
    public function __construct(GenericRepositoryInterface $mediaRepository)
    {
        $this->repository = $mediaRepository;
    }

    /**
     * Get the next media to display depending on the user
     *
     * @return Media|null
     */
    public function getNextMedia()
    {
        $user = $this->tokenStorage->getToken()->getUser();

        if ($user instanceof User) {
            $media = $this->repository->getNewMediaForUser($user);

            if ($media instanceof Media) {
                return $media;
            }
        }

        return $this->repository->getRandomMedia();
    }

    /**
     * Get a media object from an id, with votes hydrated
     *
     * @param $id
     * @return Media
     */
    public function getMedia($id)
    {
        $media = $this->repository->getHydratedMediaById($id);

        return $media instanceof Media ? $media : null;
    }
}