<?php

namespace App\FormationBundle\Repository\Interfaces;

use App\FormationBundle\Entity\Media;
use App\FormationBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;


interface MediaRepositoryInterface
{
    /**
     * Get a media from an id, with votes hydrated
     *
     * @param integer $id
     * @return Media
     */
    public function getHydratedMediaById($id);

    /**
     * Get a random media
     *
     * @return Media
     */
    public function getRandomMedia();

    /**
     * Get 5 top medias
     * @return ArrayCollection
     */
    public function getTopsMedia();

    /**
     * Get 5 flop medias
     * @return ArrayCollection
     */
    public function getFlopsMedia();

    /**
     * Get a media User hasn't voted for yet
     *
     * @param User $user
     * @return Media|null
     */
    public function getNewMediaForUser(User $user);
}
