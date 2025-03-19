<?php
// src/Controller/CustomerController.php
namespace Silecust\WebShop\Controller\MasterData\Customer\Type\Attribute;

// ...
use Silecust\WebShop\Entity\CustomerType;
use Doctrine\ORM\EntityManagerInterface;
use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CustomerTypeAttributeController extends EnhancedAbstractController
{
    #[Route('/admin/customer/type/attribute/create', name: 'sc_admin_create_customer_type_attribute')]
    public function createCustomer(EntityManagerInterface $entityManager): Response
    {
        $customer = new CustomerType();

        return new Response('Saved new customer with id ' . $customer->getId());
    }


}