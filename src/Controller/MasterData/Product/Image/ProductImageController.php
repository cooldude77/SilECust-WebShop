<?php

namespace Silecust\WebShop\Controller\MasterData\Product\Image;

use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Silecust\WebShop\Controller\Common\Utility\CommonUtility;
use Silecust\WebShop\Entity\ProductImage;
use Silecust\WebShop\Form\MasterData\Product\Image\DTO\ProductImageDTO;
use Silecust\WebShop\Form\MasterData\Product\Image\Form\ProductImageCreateForm;
use Silecust\WebShop\Form\MasterData\Product\Image\Form\ProductImageEditForm;
use Silecust\WebShop\Repository\ProductImageRepository;
use Silecust\WebShop\Repository\ProductRepository;
use Silecust\WebShop\Service\Common\Image\SystemImage;
use Silecust\WebShop\Service\Component\UI\Search\SearchEntityInterface;
use Silecust\WebShop\Service\MasterData\Product\Image\Mapper\ProductImageDTOMapper;
use Silecust\WebShop\Service\MasterData\Product\Image\ProductImageOperation;
use Silecust\WebShop\Service\MasterData\Product\Image\Provider\ProductDirectoryImagePathProvider;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 *
 */
class ProductImageController extends EnhancedAbstractController
{
    /**
     * @param int $id
     * @param EntityManagerInterface $entityManager
     * @param \Silecust\WebShop\Service\MasterData\Product\Image\ProductImageOperation $productImageOperation
     * @param \Silecust\WebShop\Repository\ProductRepository $productRepository
     * @param ProductImageDTOMapper $productImageDTOMapper
     * @param CommonUtility $commonUtility
     * @param Request $request
     *
     * @return Response
     */
    #[Route('/admin/product/{id}/image/create', name: 'sc_admin_product_file_image_create')]
    public function create(int                   $id, EntityManagerInterface $entityManager,
                           ProductImageOperation $productImageOperation,
                           ProductRepository     $productRepository,
                           ProductImageDTOMapper $productImageDTOMapper, CommonUtility $commonUtility,
                           Request               $request
    ): Response
    {
        $product = $productRepository->find(['id' => $id]);

        // Todo : validate if product exists

        $productImageDTO = new ProductImageDTO();
        $productImageDTO->productId = $id;

        $form = $this->createForm(ProductImageCreateForm::class, $productImageDTO, ['validation_groups' => 'create']);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var ProductImageDTO $productImageDTO */
            $productImageDTO = $form->getData();

            $productImage = $productImageDTOMapper->mapDtoToEntityForCreate($productImageDTO);
            $productImageOperation->createOrReplace(
                $productImage, $productImageDTO->getUploadedFile()
            );


            $entityManager->persist($productImage);
            $entityManager->flush();

            $id = $productImage->getId();

            $this->addFlash(
                'success', "Product file image created successfully"
            );

            return new Response(
                serialize(
                    ['id' => $id, 'message' => "Product file image  created successfully"]
                ), 200
            );
        }

        return $this->render('@SilecustWebShop/master_data/product/image/product_image_create.html.twig', ['form' =>
                $form]
        );
    }

    /**
     * @param int $id
     * @param EntityManagerInterface $entityManager
     * @param ProductImageRepository $productImageRepository
     * @param ProductImageDTOMapper $productImageDTOMapper
     * @param ProductImageOperation $productImageService
     * @param Request $request
     *
     * @return Response
     *
     * id is ProductImage id
     */
    #[Route('/admin/product/image/{id}/edit', name: 'sc_admin_product_file_image_edit')]
    public function edit(
        int                    $id,
        EntityManagerInterface $entityManager,
        ProductImageRepository $productImageRepository,
        ProductImageDTOMapper  $productImageDTOMapper,
        ProductImageOperation  $productImageService, Request $request
    ): Response
    {

        $this->setContentHeading($request, 'Edit product image');

        $productImage = $productImageRepository->find($id);

        $productImageDTO = $productImageDTOMapper->mapEntityToDtoForEdit(
            $productImage
        );
        $form = $this->createForm(ProductImageEditForm::class, $productImageDTO, ['validation_groups' => 'edit']);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var ProductImageDTO $productImageDTO */
            $productImageDTO = $form->getData();

            $productImage = $productImageDTOMapper->mapDtoToEntityForEdit(
                $form->getData(), $productImage
            );

            $productImageService->createOrReplace(
                $productImage, $productImageDTO->getUploadedFile()
            );

            $entityManager->persist($productImage);
            $entityManager->flush();


            $this->addFlash(
                'success', "Product file image updated successfully"
            );

            return new Response(
                serialize(
                    ['id' => $id, 'message' => "Product file image  updated successfully"]
                ), 200
            );
        }

        return $this->render(
            '@SilecustWebShop/master_data/product/image/product_image_edit.html.twig',
            ['form' => $form, 'entity' => $productImage]
        );

    }


    /**
     * @throws \Doctrine\ORM\Query\QueryException
     */
    #[Route('/admin/product/{id}/image/list', name: 'sc_admin_product_file_image_list')]
    public function list(int                    $id,
                         ProductImageRepository $productImageRepository,
                         PaginatorInterface     $paginator,
                         SearchEntityInterface  $searchEntity,
                         Request                $request
    ):
    Response
    {
        $this->setContentHeading($request, 'Product Files');


        $listGrid = ['title' => "Product Files",
            'function' => 'product_file_image',
            'link_id' => 'id-product-image-file',
            'id' => $id,
            'columns' => [
                [
                    'label' => 'Your fileName',
                    'propertyName' => 'yourFileName',
                    'action' => 'display',
                ],
                [
                    'label' => 'FileName', 'propertyName' => 'name'
                ],
            ],
            'createButtonConfig' => [
                'link_id' => ' id-create-file-image',
                'function' => 'product_file_image',
                'id' => $id,
                'anchorText' => 'Product File'
            ]
        ];

        $query = $searchEntity->getQueryForSelect($request, $productImageRepository, ['yourFileName', 'name']);

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render(
            '@SilecustWebShop/admin/ui/panel/section/content/list/list_paginated.html.twig',
            ['pagination' => $pagination, 'listGrid' => $listGrid, 'request' => $request]
        );
    }

    /**
     *
     * Fetch is to display image standalone ( call by URL at the top )
     *
     * @param int $id
     * @param ProductImageRepository $productImageRepository
     * @param ProductDirectoryImagePathProvider $productDirectoryImagePathProvider
     *
     * @return Response
     */
    #[Route('/admin/product/image/{id}/fetch', name: 'sc_admin_product_file_image_fetch')]
    public function fetch(int                               $id, ProductImageRepository $productImageRepository,
                          ProductDirectoryImagePathProvider $productDirectoryImagePathProvider
    ): Response
    {

        /** @var ProductImage $productImage */
        $productImage = $productImageRepository->findOneBy(['id' => $id]);
        $path = $productDirectoryImagePathProvider->getFullPhysicalPathForFileByName(
            $productImage->getProduct(), $productImage->getFile()->getName()
        );

        $file = file_get_contents($path);

        $headers = array('Content-Type' => mime_content_type($productImage->getFile()),
            'Content-Disposition' => 'inline; filename="' . $productImage->getFile()
                    ->getName() . '"');
        return new Response($file, 200, $headers);

    }

    /**
     * @param ProductImageRepository $productImageRepository
     * @param int $id
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return Response
     */
    #[Route('/admin/product/image/{$id}/display/', name: 'sc_admin_product_file_image_display')]
    public function display(ProductImageRepository $productImageRepository, int $id, Request $request): Response
    {
        $this->setContentHeading($request, 'Display product image');


        $productImage = $productImageRepository->findOneBy(['id' => $id]);
        if (!$productImage) {
            throw $this->createNotFoundException('No Product Image found for file id ' . $id);
        }
        $entity = ['id' => $productImage->getId(),
            'name' => $productImage->getFile()->getName(),
            'yourFileName' => $productImage->getFile()->getYourFileName()];

        $displayParams = ['title' => 'ProductImage',
            'editButtonLinkText' => 'Edit',
            'link_id' => 'id-edit-file',
            'fields' => [['label' => 'Your Name',
                'propertyName' => 'yourFileName',
                'link_id' => 'id-display-image-file'],
                ['label' => 'Name', 'propertyName' => 'name'],
            ]];

        return $this->render(
            '@SilecustWebShop/master_data/product/image/product_image_display.html.twig',
            ['request' => $request, 'entity' => $entity, 'params' => $displayParams]
        );

    }


    /**
     * @param int $id from ProductImage->getId()
     * @param ProductImageRepository $productImageRepository
     * @param ProductDirectoryImagePathProvider $productDirectoryImagePathProvider
     * @param \Silecust\WebShop\Service\Common\Image\SystemImage $systemImage
     * @return Response
     *
     * To be displayed in img tag
     */
    #[Route('product/image/img-tag/{id}', name: 'sc_product_image_file_for_img_tag')]
    public function getFileContentsById(int                               $id, ProductImageRepository $productImageRepository,
                                        ProductDirectoryImagePathProvider $productDirectoryImagePathProvider,
                                        SystemImage                       $systemImage
    ): Response
    {

        /** @var ProductImage $productImage */
        $productImage = $productImageRepository->findOneBy(['id' => $id]);

        if ($productImage != null) {
            $path = $productDirectoryImagePathProvider->getFullPhysicalPathForFileByName(
                $productImage->getProduct(), $productImage->getFile()->getName()
            );
        }
        if (!isset($path) || !file_exists($path)) {
            $path = $systemImage->getNoImageForProductPath();
        }


        return new BinaryFileResponse($path);

    }

}