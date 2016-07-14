<?php

namespace App\FormationBundle\Service;

use App\FormationBundle\Service\Interfaces\GenericManagerInterface;
use App\FormationBundle\Repository\AbstractGenericRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

abstract class AbstractGenericManager implements GenericManagerInterface
{
    /**
     * @var AbstractGenericRepository $repository
     */
    protected $repository;

    /**
     * @var TokenStorageInterface $tokenStorage
     */
    protected $tokenStorage;

    public function setTokenStorage(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @inheritdoc
     */
    public function count($enabled = false) 
    {
        return $this->repository->count($enabled);
    }
    
    /**
     * @inheritdoc
     */
    public function remove($entity)
    {
        $this->repository->remove($entity);
    }

    /**
     * @inheritdoc
     */
    public function all($result = "object", $maxResults = null, $orderby = '', $dir = 'ASC')
    {
        return $this->repository->findAllByEntity($result, $maxResults, $orderby, $dir);
    }

    /**
     * @inheritdoc
     */
    public function find($entity)
    {
        return $this->repository->find($entity);
    }

    /**
     * @inheritdoc
     */
    public function save($entity, $persist = false, $flush = true)
    {
        return $this->repository->save($entity, $persist, $flush);
    }

    public function getPagination($request, $page, $route, $maxPerPage, $count = null)
    {
        $pageCount = null === $count ? ceil($this->count(true) / $maxPerPage) : ceil($count / $maxPerPage);

        return [
            'page' => $page,
            'route' => $route,
            'pages_count' => $pageCount,
            'route_params' => $request,
        ];
    }
}