<?php

namespace Silecust\WebShop\Controller\MasterData\Product\Image;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Silecust\WebShop\Entity\Product;
use Silecust\WebShop\Entity\ProductImage;
use Silecust\WebShop\Form\MasterData\Product\Image\DTO\ProductImageDTO;
use Silecust\WebShop\Form\MasterData\Product\Image\Form\ProductImageCreateForm;
use Silecust\WebShop\Form\MasterData\Product\Image\Form\ProductImageEditForm;
use Silecust\WebShop\Repository\ProductImageRepository;
use Silecust\WebShop\Repository\ProductRepository;
use Silecust\WebShop\Service\Component\UI\Search\SearchEntityInterface;
use Silecust\WebShop\Service\MasterData\Product\Image\Mapper\ProductImageDTOMapper;
use Silecust\WebShop\Service\MasterData\Product\Image\ProductImageFileOperation;
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
     * @param \Silecust\WebShop\Entity\ProductImage $productImage
     * @param EntityManagerInterface $entityManager
     * @param ProductImageDTOMapper $productImageDTOMapper
     * @param ProductImageFileOperation $productImageService
     * @param Request $request
     *
     * @return Response
     *
     * id is ProductImage ID
     * @throws \Exception
     */
    #[Route('/admin/product/image/{id}/edit', name: 'sc_admin_product_file_image_edit')]
    public function edit(ProductImage              $productImage,
                         EntityManagerInterface    $entityManager,
                         ProductImageDTOMapper     $productImageDTOMapper,
                         ProductImageFileOperation $productImageService, Request $request
    ): Response
    {
        $this->setContentHeading($request, 'Edit product image');

        $productImageDTO = $productImageDTOMapper->mapEntityToDtoForEdit(
            $productImage
        );
        $form = $this->createForm(ProductImageEditForm::class, $productImageDTO);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var ProductImageDTO $productImageDTO */
            $productImageDTO = $form->getData();

            $productImage = $productImageDTOMapper->mapDtoToEntityForEdit(
                $form->getData(), $productImage
            );

            $productImageService->createOrUpdateFileAndEntity(
                $productImage, $productImageDTO->getUploadedFile()
            );

            $entityManager->persist($productImage);
            $entityManager->flush();


            $this->addFlash(
                'success', "Product file image updated successfully"
            );

            return new Response(
                serialize(
                    ['id' => $productImage->getId(), 'message' => "Product file image  updated successfully"]
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
    public function list(Product                $product,
                         ProductRepository      $productRepository,
                         ProductImageRepository $productImageRepository,
                         PaginatorInterface     $paginator,
                         SearchEntityInterface  $searchEntity,
                         Request                $request
    ):
    Response
    {
        $this->setContentHeading($request, 'Product Images');


        $listGrid = ['title' => "Product Files",
            'function' => 'product_file_image',
            'link_id' => 'id-product-image-file',
            'id' => $product->getId(),
            'columns' => [
                [
                    'label' => 'Your fileName',
                    'propertyName' => 'yourFileName',
                    'action' => 'display',
                ],
                [
                    'label' => 'FileName',
                    'propertyName' => 'name'
                ],
            ],
            'createButtonConfig' => [
                'link_id' => ' id-create-file-image',
                'function' => 'product_file_image',
                'id' => $product->getId(),
                'anchorText' => 'Product File'
            ]
        ];

        $query = $searchEntity->getQueryForSelect($request, $productImageRepository, ['yourFileName', 'name'],
            Criteria::create()->where(Criteria::expr()->eq('product', $product)));

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
     * @param \Silecust\WebShop\Entity\Product $product
     * @param EntityManagerInterface $entityManager
     * @param \Silecust\WebShop\Service\MasterData\Product\Image\ProductImageFileOperation $productImageOperation
     * @param ProductImageDTOMapper $productImageDTOMapper
     * @param Request $request
     *
     * @return Response
     * @throws \Exception
     */
    #[Route('/admin/product/{id}/image/create', name: 'sc_admin_product_file_image_create')]
    public function create(Product                   $product,
                           EntityManagerInterface    $entityManager,
                           ProductImageFileOperation $productImageOperation,
                           ProductImageDTOMapper     $productImageDTOMapper,
                           Request                   $request
    ): Response
    {

        // Todo : validate if product exists

        $productImageDTO = new ProductImageDTO();
        $productImageDTO->productId = $product->getId();

        $form = $this->createForm(ProductImageCreateForm::class, $productImageDTO);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var ProductImageDTO $productImageDTO */
            $productImageDTO = $form->getData();

            $productImage = $productImageDTOMapper->mapDtoToEntityForCreate($productImageDTO);
            $productImageOperation->createOrUpdateFileAndEntity(
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

        return $this->render(
            '@SilecustWebShop/master_data/product/image/product_image_create.html.twig', ['form' => $form]
        );
    }

    /**
     *
     * Fetch is to display image standalone ( call by URL at the top )
     *
     * @param \Silecust\WebShop\Entity\ProductImage $productImage
     * @param ProductDirectoryImagePathProvider $productDirectoryImagePathProvider
     *
     * @return Response
     */
    #[Route('/admin/product/image/{id}/fetch', name: 'sc_admin_product_file_image_fetch')]
    public function fetch(
        ProductImage                      $productImage,
        ProductDirectoryImagePathProvider $productDirectoryImagePathProvider
    ): Response
    {

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
     * @param \Silecust\WebShop\Entity\ProductImage $productImage
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return Response
     */
    #[Route('/admin/product/image/{$id}/display/', name: 'sc_admin_product_file_image_display')]
    public function display(ProductImage $productImage,
                            Request      $request): Response
    {
        $this->setContentHeading($request, 'Display product image');


        $entity = ['id' => $productImage->getId(),
            'name' => $productImage->getFile()->getName(),
            'yourFileName' => $productImage->getFile()->getYourFileName()];

        $displayParams = ['title' => 'ProductImage',
            'editButtonLinkText' => 'Edit',
            'link_id' => 'id-edit-link',
            'fields' => [
                ['label' => 'Your Name',
                    'propertyName' => 'yourFileName',
                    'link_id' => 'id-display-image-file'],
                ['label' => 'Name',
                    'propertyName' => 'name',
                    'link_id' => 'id-name'],
            ]];

        return $this->render(
            '@SilecustWebShop/master_data/product/image/product_image_display.html.twig',
            ['request' => $request, 'entity' => $entity, 'params' => $displayParams]
        );

    }


    /**
     * @param \Silecust\WebShop\Entity\ProductImage $productImage
     * @param ProductDirectoryImagePathProvider $productDirectoryImagePathProvider
     *
     * @return Response
     *
     * To be displayed in img tag
     */
    #[Route('product/image/img-tag/{id}', name: 'sc_product_image_file_for_img_tag')]
    public function getFileContentsById(ProductImage                      $productImage,
                                        ProductDirectoryImagePathProvider $productDirectoryImagePathProvider
    ): Response
    {

        $path = $productDirectoryImagePathProvider->getFullPhysicalPathForFileByName(
            $productImage->getProduct(), $productImage->getFile()->getName()
        );

        return new BinaryFileResponse($path);

    }

}