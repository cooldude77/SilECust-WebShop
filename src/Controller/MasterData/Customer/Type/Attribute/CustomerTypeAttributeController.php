<?php
// src/Controller/CustomerController.php
namespace App\Controller\MasterData\Customer\Type\Attribute;

// ...
use App\Entity\CustomerType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CustomerTypeAttributeController extends AbstractController
{
    #[Route('/admin/customer/type/attribute/create', name: 'create_customer_type_attribute')]
    public function createCustomer(EntityManagerInterface $entityManager): Response
    {
        $customer = new CustomerType();

        return new Response('Saved new customer with id ' . $customer->getId());
    }


}