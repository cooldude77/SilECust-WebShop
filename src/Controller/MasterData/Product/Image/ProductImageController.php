<?php

namespace App\Controller\MasterData\Product\Image;

use App\Controller\Common\Utility\CommonUtility;
use App\Controller\MasterData\Product\Image\ListObject\ProductImageObject;
use App\Entity\ProductImage;
use App\Form\MasterData\Product\Image\DTO\ProductImageDTO;
use App\Form\MasterData\Product\Image\Form\ProductImageCreateForm;
use App\Form\MasterData\Product\Image\Form\ProductImageEditForm;
use App\Repository\ProductImageRepository;
use App\Repository\ProductRepository;
use App\Service\Common\Image\SystemImage;
use App\Service\MasterData\Product\Image\Mapper\ProductImageDTOMapper;
use App\Service\MasterData\Product\Image\ProductImageOperation;
use App\Service\MasterData\Product\Image\Provider\ProductDirectoryImagePathProvider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 *
 */
class ProductImageController extends AbstractController
{
    /**
     * @param int                    $id
     * @param EntityManagerInterface $entityManager
     * @param ProductImageDTOMapper  $productImageDTOMapper
     * @param CommonUtility          $commonUtility
     * @param Request                $request
     *
     * @return Response
     */
    #[Route('/product/{id}/image/create', name: 'product_file_image_create')]
    public function create(int $id, EntityManagerInterface $entityManager,
        ProductImageOperation $productImageOperation,
        ProductRepository $productRepository,
        ProductImageDTOMapper $productImageDTOMapper, CommonUtility $commonUtility,
        Request $request
    ): Response {
        $product = $productRepository->find(['id' => $id]);

        // Todo : validate if product exists

        $productImageDTO = new ProductImageDTO();
        $productImageDTO->productId = $id;

        $form = $this->createForm(ProductImageCreateForm::class, $productImageDTO);

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

        return $this->render('master_data/product/image/create.html.twig', ['form' => $form]);
    }

    /**
     * @param int                    $id
     * @param EntityManagerInterface $entityManager
     * @param ProductImageRepository $productImageRepository
     * @param ProductImageDTOMapper  $productImageDTOMapper
     * @param ProductImageOperation  $productImageService
     * @param Request                $request
     *
     * @return Response
     *
     * id is ProductImage Id
     */
    #[\Symfony\Component\Routing\Attribute\Route('/product/image/{id}/edit', name: 'product_file_image_edit')]
    public function edit(int $id, EntityManagerInterface $entityManager,
        ProductImageRepository $productImageRepository,
        ProductImageDTOMapper $productImageDTOMapper,
        ProductImageOperation $productImageService, Request $request
    ): Response {
        $productImage = $productImageRepository->find($id);

        $productImageDTO = $productImageDTOMapper->mapEntityToDto(
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
            'master_data/product/image/product_image_edit.html.twig',
            ['form' => $form, 'entity' => $productImage]
        );

    }

    #[Route('/product/{id}/image/list', name: 'product_create_file_image_list')]
    public function list(int $id, ProductRepository $productRepository,
        ProductImageRepository $productImageRepository
    ):
    Response {


        $productImages = $productImageRepository->findBy(['product' => $productRepository->find
        (
            $id
        )]);

        $entities = [];
        if ($productImages != null) {
            /** @var ProductImage $productImage */
            foreach ($productImages as $productImage) {
                $f = new ProductImageObject();
                $f->id = $productImage->getId();
                $f->yourFileName = $productImage->getFile()->getYourFileName();
                $f->name = $productImage->getFile()->getName();
                $entities[] = $f;
            }
        }

        $listGrid = ['title' => "Product Files",
                     'function' => 'product_file_image',
                     'link_id' => 'id-product-image-file',
                     'columns' => [['label' => 'Your fileName',
                                    'propertyName' => 'yourFileName',
                                    'action' => 'display',],
                                   ['label' => 'FileName', 'propertyName' => 'name'],],
                     'createButtonConfig' => ['link_id' => ' id-create-file-image',
                                              'function' => 'product_file_image',
                                              'id' => $id,
                                              'anchorText' => 'Product File']];

        return $this->render(
            'admin/ui/panel/section/content/list/list.html.twig',
            ['entities' => $entities, 'listGrid' => $listGrid]
        );

    }

    /**
     *
     * Fetch is to display image standalone ( call by URL at the top )
     *
     * @param int                               $id
     * @param ProductImageRepository            $productImageRepository
     * @param ProductDirectoryImagePathProvider $productDirectoryImagePathProvider
     *
     * @return Response
     */
    #[\Symfony\Component\Routing\Attribute\Route('/product/image/{id}/fetch', name: 'product_file_image_fetch')]
    public function fetch(int $id, ProductImageRepository $productImageRepository,
        ProductDirectoryImagePathProvider $productDirectoryImagePathProvider
    ): Response {

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
     * @param int                    $id
     *
     * @return Response
     */
    #[\Symfony\Component\Routing\Attribute\Route('/product/image/{$id}/display/', name: 'product_file_image_display')]
    public function display(ProductImageRepository $productImageRepository, int $id): Response
    {
        $productImage = $productImageRepository->findOneBy(['id' => $id]);
        if (!$productImage) {
            throw $this->createNotFoundException('No Product Image found for file id ' . $id);
        }
        $entity = ['id' => $productImage->getId(),
                   'name' => $productImage->getProductFile()->getFile()->getName(),
                   'yourFileName' => $productImage->getProductFile()->getFile()->getYourFileName(),
                   'productImageType' => $productImage->getProductImageType()->getDescription()];

        $displayParams = ['title' => 'ProductImage',
                          'editButtonLinkText' => 'Edit',
                          'fields' => [['label' => 'Your Name',
                                        'propertyName' => 'yourFileName',
                                        'link_id' => 'id-display-image-file'],
                                       ['label' => 'Name', 'propertyName' => 'name'],
                                       ['label' => 'Image File Type',
                                        'propertyName' => 'productImageType']]];

        return $this->render(
            'master_data/product/image/product_image_display.html.twig',
            ['entity' => $entity, 'params' => $displayParams]
        );

    }


    /**
     * @param int                               $id from ProductImage->getId()
     * @param ProductImageRepository            $productImageRepository
     * @param ProductDirectoryImagePathProvider $productDirectoryImagePathProvider
     *
     * @return Response
     *
     * To be displayed in img tag
     */
    #[\Symfony\Component\Routing\Attribute\Route('product/image/img-tag/{id}', name: 'product_image_file_for_img_tag')]
    public function getFileContentsById(int $id, ProductImageRepository $productImageRepository,
        ProductDirectoryImagePathProvider $productDirectoryImagePathProvider,
        SystemImage $systemImage
    ): Response {

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