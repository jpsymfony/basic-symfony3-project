<?php

namespace App\FormationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use FOS\UserBundle\Model\User as FOSUser;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="App\FormationBundle\Repository\UserRepository")
 */
class User extends FOSUser
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

//    /**
//     * @var string
//     *
//     * @ORM\Column(name="username", type="string", length=255)
//     * @Assert\NotBlank()
//     * @Assert\Length(min="8")
//     */
//    private $username;
//
//    /**
//     * @var string
//     *
//     * @ORM\Column(name="email", type="string", length=255, unique=true)
//     * @Assert\NotBlank()
//     * @Assert\Email()
//     */
//    private $email;
//
//    /**
//     * @var string
//     *
//     * @ORM\Column(name="password", type="string", length=255)
//     *  @Assert\NotBlank()
//     *  @Assert\Length(min="8")
//     */
//    private $password;

    /**
     * persist => when the user form is submitted, the votes are persisted
     * orphanRemoval=true => when a user is deleted, associated votes are removed from database
     * @ORM\OneToMany(targetEntity="App\FormationBundle\Entity\Vote", mappedBy="user", cascade={"persist"}, orphanRemoval=true)
     */
    private $votes;

    public function __construct()
    {
        parent::__construct();
        $this->votes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

//    /**
//     * Set username
//     *
//     * @param string $username
//     *
//     * @return User
//     */
//    public function setUsername($username)
//    {
//        $this->username = $username;
//
//        return $this;
//    }
//
//    /**
//     * Get username
//     *
//     * @return string
//     */
//    public function getUsername()
//    {
//        return $this->username;
//    }
//
//    /**
//     * Set email
//     *
//     * @param string $email
//     *
//     * @return User
//     */
//    public function setEmail($email)
//    {
//        $this->email = $email;
//
//        return $this;
//    }
//
//    /**
//     * Get email
//     *
//     * @return string
//     */
//    public function getEmail()
//    {
//        return $this->email;
//    }
//
//    /**
//     * Set password
//     *
//     * @param string $password
//     *
//     * @return User
//     */
//    public function setPassword($password)
//    {
//        $this->password = $password;
//
//        return $this;
//    }
//
//    /**
//     * Get password
//     *
//     * @return string
//     */
//    public function getPassword()
//    {
//        return $this->password;
//    }


    /**
     * Add vote
     *
     * @param \App\FormationBundle\Entity\User $vote
     *
     * @return User
     */
    public function addVote(\App\FormationBundle\Entity\User $vote)
    {
        $this->votes[] = $vote;

        return $this;
    }

    /**
     * Remove vote
     *
     * @param \App\FormationBundle\Entity\User $vote
     */
    public function removeVote(\App\FormationBundle\Entity\User $vote)
    {
        $this->votes->removeElement($vote);
    }

    /**
     * Get votes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVotes()
    {
        return $this->votes;
    }
}
