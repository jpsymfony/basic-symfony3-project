<?php

namespace App\FormationBundle\Service;

use App\FormationBundle\Entity\Media;
use App\FormationBundle\Entity\User;
use App\FormationBundle\Entity\Vote;
use App\FormationBundle\Repository\Interfaces\GenericRepositoryInterface;

class VoteManager extends AbstractGenericManager
{
    public function __construct(GenericRepositoryInterface $voteRepository)
    {
        $this->repository = $voteRepository;
    }

    /**
     * Get a new vote object for current user and given media
     *
     * @param Media $media
     * @return Vote|null
     */
    public function getNewVote(Media $media)
    {
        $user = $this->tokenStorage->getToken()->getUser();

        if (!$user instanceof User) {
            return null;
        }

        $vote = new Vote();
        $vote->setUser($user);
        $vote->setMedia($media);

        return $vote;
    }

    /**
     * Save a vote and update the average score
     *
     * @param Vote $vote
     */
    public function saveVote(Vote $vote)
    {
        $media = $vote->getMedia();
        $media->addVote($vote);

        $this->save($vote, true, true);
    }
}