<?php
// src/Controller/ProductController.php
namespace Silecust\WebShop\Controller\MasterData\Product\Type;

// ...
use Silecust\WebShop\Form\MasterData\Product\Type\DTO\ProductTypeDTO;
use Silecust\WebShop\Form\MasterData\Product\Type\ProductTypeCreateForm;
use Silecust\WebShop\Form\MasterData\Product\Type\ProductTypeUpdateForm;
use Silecust\WebShop\Repository\ProductTypeRepository;
use Silecust\WebShop\Service\MasterData\Product\Type\ProductTypeDTOMapper;
use Doctrine\ORM\EntityManagerInterface;
use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductTypeController extends EnhancedAbstractController
{
    #[Route('/admin/product/type/create', name: 'product_type_create')]
    public function create(ProductTypeDTOMapper $mapper, EntityManagerInterface $entityManager,
        Request $request
    ): Response {
        $productTypeDTO = new ProductTypeDTO();

        $form = $this->createForm(ProductTypeCreateForm::class, $productTypeDTO);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $productType = $mapper->mapDtoToEntityForCreate($form->getData());

            $entityManager->persist($productType);
            $entityManager->flush();


            $id = $productType->getId();

            $this->addFlash(
                'success', "Product Type updated successfully"
            );

            return new Response(
                serialize(
                    ['id' => $id, 'message' => "Product Type created successfully"]
                ), 200
            );
        }

        return $this->render(
            'admin/ui/panel/section/content/create/create.html.twig', ['form' => $form]
        );

    }


  #[Route('/admin/product/type/{id}/edit', name: 'product_type_edit')]
    public function edit(
        int $id,
        ProductTypeDTOMapper $mapper, EntityManagerInterface $entityManager,
        ProductTypeRepository $productTypeRepository,
        Request $request
    ): Response {
        $productTypeDTO = new ProductTypeDTO();

        $productTypeEntity =  $productTypeRepository->find($id);

        $form = $this->createForm(ProductTypeUpdateForm::class, $productTypeDTO);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $productTypeEntity = $mapper->mapDtoToEntityForUpdate($form->getData(),$productTypeEntity);

            $entityManager->persist($productTypeEntity);
            $entityManager->flush();

            $this->addFlash(
                'success', "Product Type updated successfully"
            );

            return new Response(
                serialize(
                    ['id' => $id, 'message' => "Product Type created successfully"]
                ), 200
            );
        }

        return $this->render(
            'admin/ui/panel/section/content/create/create.html.twig', ['form' => $form]
        );

    }


    #[Route('/admin/product/type/list', name: 'product_type_list')]
    public function list(ProductTypeRepository $productTypeRepository,Request $request): Response
    {

        $listGrid = ['title' => 'ProductType',
                     'link_id'=>'id-product-type',
                     'columns' => [['label' => 'Name',
                                    'propertyName' => 'name',
                                    'action' => 'display'],
                                   ['label' => 'Description',
                                    'propertyName' => 'description'],],
                     'createButtonConfig' => [
                         'link_id'=>' id-create-product-type',
                         'function' => 'productType',
                                              'anchorText' => 'Create ProductType']];

        $productTypes = $productTypeRepository->findAll();
        return $this->render(
            'admin/ui/panel/section/content/list/list.html.twig',
            ['request' => $request,'entities' => $productTypes, 'listGrid' => $listGrid]
        );
    }

}