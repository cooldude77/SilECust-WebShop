<?php
// src/Controller/ProductController.php
namespace App\Controller\MasterData\Product\Attribute;

// ...
use App\Entity\ProductAttributeKey;
use App\Form\MasterData\Product\Attribute\DTO\ProductAttributeKeyDTO;
use App\Form\MasterData\Product\Attribute\ProductAttributeKeyCreateForm;
use App\Form\MasterData\Product\Attribute\ProductAttributeKeyEditForm;
use App\Repository\ProductAttributeKeyRepository;
use App\Repository\ProductRepository;
use App\Service\MasterData\Product\Attribute\ProductAttributeKeyDTOMapper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductAttributeKeyController extends AbstractController
{
    #[Route('/admin/product/attribute/key/create', name: 'sc_route_admin_product_attribute_key_create')]
    public function create(ProductAttributeKeyDTOMapper $mapper, EntityManagerInterface $entityManager,
                           Request                      $request
    ): Response
    {
        $productAttributeKeyDTO = new ProductAttributeKeyDTO();

        $form = $this->createForm(ProductAttributeKeyCreateForm::class, $productAttributeKeyDTO);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $productAttributeKey = $mapper->mapDtoToEntity($form->getData());

            $entityManager->persist($productAttributeKey);
            $entityManager->flush();

            $this->addFlash(
                'success', "Product created successfully"
            );

            $id = $productAttributeKey->getId();
            $this->addFlash(
                'success', "Product Attribute Key created successfully"
            );

            return new Response(
                serialize(
                    ['id' => $id, 'message' => "Product Attribute Key created successfully"]
                ), 200
            );
        }

        return $this->render(
            'admin/ui/panel/section/content/create/create.html.twig', ['form' => $form]
        );

    }

    #[Route('/admin/product/attribute/{id}/display', name: 'sc_route_admin_product_attribute_key_display')]
    public function display(ProductRepository $productRepository, ProductAttributeKey $productAttributeKey, Request $request): Response
    {

        $displayParams = ['title' => 'Product Attribute Key',
            'link_id' => 'id-product-attribute-key',
            'editButtonLinkText' => 'Edit',
            'fields' => [['label' => 'Name',
                'propertyName' => 'name',
                'link_id' => 'id-display-product-attribute-key'],
                ['label' => 'Description',
                    'propertyName' => 'description'],]];

        return $this->render(
            'master_data/product/attribute/key/product_attribute_key_display.html.twig',
            ['request' => $request, 'entity' => $productAttributeKey, 'params' => $displayParams]
        );

    }

    #[Route('/admin/product/attribute/key/{id}/edit', name: 'sc_route_admin_product_attribute_key_edit')]
    public function edit(ProductAttributeKey    $productAttributeKey, ProductAttributeKeyDTOMapper $mapper,
                         EntityManagerInterface $entityManager, Request $request
    ): Response
    {
        $productAttributeKeyDTO = $mapper->mapDtoFromEntityForEdit($productAttributeKey);

        $form = $this->createForm(ProductAttributeKeyEditForm::class, $productAttributeKeyDTO);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $productAttributeKeyEntity = $mapper->mapDtoToEntityForEdit($form->getData());

            $entityManager->persist($productAttributeKeyEntity);
            $entityManager->flush();

            $this->addFlash(
                'success', "Product Attribute Key Value updated successfully"
            );

            return new Response(
                serialize(
                    ['id' => $productAttributeKeyEntity->getId(), 'message' => "Product Attribute Key Value updated successfully"]
                ), 200
            );
        }

        return $this->render(
            'admin/ui/panel/section/content/edit/edit.html.twig', ['form' => $form]
        );

    }


    #[Route('/admin/product/attribute/key/list', name: 'sc_route_admin_product_attribute_key_list')]
    public function list(ProductAttributeKeyRepository $productAttributeKeyRepository, Request $request): Response
    {

        $listGrid = ['title' => 'Product Attribute Key',
            'link_id' => 'id-product-attribute',
            'columns' => [['label' => 'Name',
                'propertyName' => 'name',
                'action' => 'display'],
                ['label' => 'Description',
                    'propertyName' => 'description'],],
            'createButtonConfig' => ['link_id' => 'id-create-sc_route_admin_product_attribute',
                'function' => 'product_attribute_key',
                'anchorText' => 'Create Product Attribute Key']];

        $productAttributeKeys = $productAttributeKeyRepository->findAll();
        return $this->render(
            'admin/ui/panel/section/content/list/list.html.twig',
            ['request' => $request, 'entities' => $productAttributeKeys, 'listGrid' => $listGrid]
        );
    }

}