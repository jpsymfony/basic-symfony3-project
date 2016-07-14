<?php

namespace App\FormationBundle\Tests\Unit\Service;

use App\FormationBundle\Entity\Media;
use App\FormationBundle\Entity\User;
use App\FormationBundle\Entity\Vote;
use App\FormationBundle\Repository\AbstractGenericRepository;
use App\FormationBundle\Repository\Interfaces\GenericRepositoryInterface;
use App\FormationBundle\Repository\MediaRepository;
use App\FormationBundle\Service\AbstractGenericManager;
use App\FormationBundle\Service\MediaManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class AbstractGenericManagerTest extends \PHPUnit_Framework_TestCase
{
    protected $repository;
    protected $manager;

    public function setUp()
    {
        $this->repository = $this->getMock(GenericRepositoryInterface::class);
        $this->manager = new Manager($this->repository);
    }

    public function testCount()
    {
        $this->repository
            ->expects($this->once())
            ->method('count')
            ->with(true)
            ->willReturn(1);

        $this->assertEquals(1, $this->manager->count(true));
    }

    /**
     * @dataProvider entityProvider
     */
    public function testRemove($entity)
    {
        $this->repository
            ->expects($this->once())
            ->method('remove')
            ->with($entity);

        $this->manager->remove($entity);
    }

    public function entityProvider()
    {
        return [
            [new User()],
            [new Vote()],
            [new Media()],
        ];
    }

    public function testAll()
    {
        $this->repository
            ->expects($this->once())
            ->method('findAllByEntity')
            ->with('object', null, '', 'ASC');

        $this->manager->all('object', null, '', 'ASC');
    }

    /**
     * @dataProvider entityProvider
     */
    public function testFind($entity)
    {
        $this->repository
            ->expects($this->once())
            ->method('find')
            ->with($entity);

        $this->manager->find($entity);
    }

    /**
     * @dataProvider entityProvider
     */
    public function testSave($entity)
    {
        $this->repository
            ->expects($this->once())
            ->method('save')
            ->with($entity, true, true);

        $this->manager->save($entity, true, true);
    }

    /**
     * @dataProvider getPaginationProvider
     */
    public function testGetPagination($expected, $nbCountFunctionCall, $maxPerPage, $count)
    {
        $mockManager = $this->getMock(Manager::class, ['count'], [$this->repository]);
        $mockManager
            ->expects($this->exactly($nbCountFunctionCall))
            ->method('count')
            ->with(true)
            ->willReturn(30);

        $this->assertEquals($expected, $mockManager->getPagination([], 1, 'some_route', $maxPerPage, $count));
    }

    public function getPaginationprovider()
    {
        return [
            [
                [
                    'page' => 1,
                    'route' => 'some_route',
                    'pages_count' => ceil(30 / 5),
                    'route_params' => [],
                ],
                1, 5, null],
            [
                [
                'page' => 1,
                'route' => 'some_route',
                'pages_count' => ceil(25 / 5),
                'route_params' => [],
                ],
                0, 5, 25],
        ];
    }
}

class Manager extends AbstractGenericManager
{
    public function __construct(GenericRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }
}