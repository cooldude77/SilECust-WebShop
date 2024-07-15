<?php
// src/Controller/TaxController.php
namespace App\Controller\MasterData\Price\Tax;

// ...
use App\Entity\TaxBaseProduct;
use App\Form\MasterData\Price\Tax\TaxProductCreateForm;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PriceProductTaxController extends AbstractController
{
    #[Route('/product/{id}/base/tax', name: 'create_tax')]
    public function createBaseTaxRateForProduct($id, EntityManagerInterface $entityManager, ProductRepository $productRepository, Request $request): Response
    {
        $type = new TaxBaseProduct();
        $type->setProduct($productRepository->findOneBy(['id' => $id]));

        $form = $this->createForm(TaxProductCreateForm::class, $type);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // perform some action...
            $entityManager->persist($form->getData());
            $entityManager->flush();

            return $this->redirectToRoute('admin/price/tax/success_create.html.twig');
        }
        return $this->render('admin/price/tax/base_product/create.html.twig', ['form' => $form]);
    }


}