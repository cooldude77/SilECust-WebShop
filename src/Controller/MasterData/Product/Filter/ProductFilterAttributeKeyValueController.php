<?php

namespace App\Controller\MasterData\Product\Filter;

use App\Form\MasterData\Product\Filter\ProductFilterMultipleForm;
use App\Repository\CategoryRepository;
use App\Service\MasterData\Product\Filter\ProductFilterMapper;
use App\Service\MasterData\Product\Filter\Provider\ProductFilterProviderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductFilterAttributeKeyValueController extends AbstractController
{

    #[Route('/xyz', 'prod_filter')]
    public function filter(
        ProductFilterProviderInterface $productFilterProvider,
        CategoryRepository             $categoryRepository,
        ProductFilterMapper            $productFilterMapper,
        Request                        $request): Response
    {

        $availableFilters = $productFilterProvider->getList($categoryRepository->find(1));

        $formattedFilters = $productFilterMapper->format($availableFilters);

        $form = $this->createForm(ProductFilterMultipleForm::class, $formattedFilters);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
        }
        return $this->render('master_data/product/filter/filter.html.twig', ['form' => $form]);
    }
}