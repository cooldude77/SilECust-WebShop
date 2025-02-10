<?php

namespace App\Tests;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MyTestCase extends WebTestCase
{
    protected function setUp(): void
    {
        require_once __DIR__ . '/TestKernel.php';

        $kernel = new TestKernel('test', true);
        $kernel->boot();

        $container = $kernel->getContainer();
        // $this->handler = $container->get('my_own.handling.handler');

        /** @var EntityManager $entityManager */
        $entityManager = $this->getContainer()->get('doctrine')->getManager();

        $conn = $entityManager->getConnection();
        $y = $conn->isConnected();
        $x = $entityManager->getRepository(Category::class)->find(1);


    }

    public function testHandle()
    {
        $a = 1;
        $this->assertEquals(1, $a);
    }
}