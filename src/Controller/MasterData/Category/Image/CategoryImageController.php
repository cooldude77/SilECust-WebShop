<?php

namespace Silecust\WebShop\Controller\MasterData\Category\Image;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Silecust\WebShop\Entity\Category;
use Silecust\WebShop\Entity\CategoryImage;
use Silecust\WebShop\Form\MasterData\Category\Image\DTO\CategoryImageDTO;
use Silecust\WebShop\Form\MasterData\Category\Image\Form\CategoryImageCreateForm;
use Silecust\WebShop\Form\MasterData\Category\Image\Form\CategoryImageEditForm;
use Silecust\WebShop\Repository\CategoryImageRepository;
use Silecust\WebShop\Repository\CategoryRepository;
use Silecust\WebShop\Service\Component\UI\Search\SearchEntityInterface;
use Silecust\WebShop\Service\MasterData\Category\Image\CategoryImageOperation;
use Silecust\WebShop\Service\MasterData\Category\Image\Mapper\CategoryImageDTOMapper;
use Silecust\WebShop\Service\MasterData\Category\Image\Provider\CategoryDirectoryImagePathProvider;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 *
 */
class CategoryImageController extends EnhancedAbstractController
{
    /**
     * @param \Silecust\WebShop\Entity\CategoryImage $categoryImage
     * @param EntityManagerInterface $entityManager
     * @param CategoryImageDTOMapper $categoryImageDTOMapper
     * @param CategoryImageOperation $categoryImageService
     * @param Request $request
     *
     * @return Response
     *
     * id is CategoryImage ID
     */
    #[Route('/admin/category/image/{id}/edit', name: 'sc_admin_category_file_image_edit')]
    public function edit(CategoryImage          $categoryImage,
                         EntityManagerInterface $entityManager,
                         CategoryImageDTOMapper $categoryImageDTOMapper,
                         CategoryImageOperation $categoryImageService, Request $request
    ): Response
    {
        $this->setContentHeading($request, 'Edit category image');

        $categoryImageDTO = $categoryImageDTOMapper->mapEntityToDtoForEdit(
            $categoryImage
        );
        $form = $this->createForm(CategoryImageEditForm::class, $categoryImageDTO);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var CategoryImageDTO $categoryImageDTO */
            $categoryImageDTO = $form->getData();

            $categoryImage = $categoryImageDTOMapper->mapDtoToEntityForEdit(
                $form->getData(), $categoryImage
            );

            $categoryImageService->createOrReplaceFileAndUpdateEntity(
                $categoryImage, $categoryImageDTO->getUploadedFile()
            );

            $entityManager->persist($categoryImage);
            $entityManager->flush();


            $this->addFlash(
                'success', "Category file image updated successfully"
            );

            return new Response(
                serialize(
                    ['id' => $categoryImage->getId(), 'message' => "Category file image  updated successfully"]
                ), 200
            );
        }

        return $this->render(
            '@SilecustWebShop/master_data/category/image/category_image_edit.html.twig',
            ['form' => $form, 'entity' => $categoryImage]
        );

    }

    /**
     * @throws \Doctrine\ORM\Query\QueryException
     */
    #[Route('/admin/category/{id}/image/list', name: 'sc_admin_category_file_image_list')]
    public function list(Category                $category,
                         CategoryRepository      $categoryRepository,
                         CategoryImageRepository $categoryImageRepository,
                         PaginatorInterface      $paginator,
                         SearchEntityInterface   $searchEntity,
                         Request                 $request
    ):
    Response
    {
        $this->setContentHeading($request, 'Category Images');


        $listGrid = ['title' => "Category Files",
            'function' => 'category_file_image',
            'link_id' => 'id-category-image-file',
            'id' => $category->getId(),
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
                'function' => 'category_file_image',
                'id' => $category->getId(),
                'anchorText' => 'Category File'
            ]
        ];

        $query = $searchEntity->getQueryForSelect($request, $categoryImageRepository, ['yourFileName', 'name'],
            Criteria::create()->where(Criteria::expr()->eq('category', $category)));

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
     * @param \Silecust\WebShop\Entity\Category $category
     * @param EntityManagerInterface $entityManager
     * @param \Silecust\WebShop\Service\MasterData\Category\Image\CategoryImageOperation $categoryImageOperation
     * @param CategoryImageDTOMapper $categoryImageDTOMapper
     * @param Request $request
     *
     * @return Response
     */
    #[Route('/admin/category/{id}/image/create', name: 'sc_admin_category_file_image_create')]
    public function create(Category               $category,
                           EntityManagerInterface $entityManager,
                           CategoryImageOperation $categoryImageOperation,
                           CategoryImageDTOMapper $categoryImageDTOMapper,
                           Request                $request
    ): Response
    {

        // Todo : validate if category exists

        $categoryImageDTO = new CategoryImageDTO();
        $categoryImageDTO->categoryId = $category->getId();

        $form = $this->createForm(CategoryImageCreateForm::class, $categoryImageDTO);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var CategoryImageDTO $categoryImageDTO */
            $categoryImageDTO = $form->getData();

            $categoryImage = $categoryImageDTOMapper->mapDtoToEntityForCreate($categoryImageDTO);
            $categoryImageOperation->createOrReplaceFileAndUpdateEntity(
                $categoryImage, $categoryImageDTO->getUploadedFile()
            );


            $entityManager->persist($categoryImage);
            $entityManager->flush();

            $id = $categoryImage->getId();

            $this->addFlash(
                'success', "Category file image created successfully"
            );

            return new Response(
                serialize(
                    ['id' => $id, 'message' => "Category file image  created successfully"]
                ), 200
            );
        }

        return $this->render(
            '@SilecustWebShop/master_data/category/image/category_image_create.html.twig', ['form' => $form]
        );
    }

    /**
     *
     * Fetch is to display image standalone ( call by URL at the top )
     *
     * @param \Silecust\WebShop\Entity\CategoryImage $categoryImage
     * @param CategoryDirectoryImagePathProvider $categoryDirectoryImagePathProvider
     *
     * @return Response
     */
    #[Route('/admin/category/image/{id}/fetch', name: 'sc_admin_category_file_image_fetch')]
    public function fetch(
        CategoryImage                      $categoryImage,
        CategoryDirectoryImagePathProvider $categoryDirectoryImagePathProvider
    ): Response
    {

        $path = $categoryDirectoryImagePathProvider->getFullPhysicalPathForFileByName(
            $categoryImage->getCategory(), $categoryImage->getFile()->getName()
        );

        $file = file_get_contents($path);

        $headers = array('Content-Type' => mime_content_type($categoryImage->getFile()),
            'Content-Disposition' => 'inline; filename="' . $categoryImage->getFile()
                    ->getName() . '"');
        return new Response($file, 200, $headers);

    }

    /**
     * @param \Silecust\WebShop\Entity\CategoryImage $categoryImage
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return Response
     */
    #[Route('/admin/category/image/{$id}/display/', name: 'sc_admin_category_file_image_display')]
    public function display(CategoryImage $categoryImage,
                            Request       $request): Response
    {
        $this->setContentHeading($request, 'Display category image');


        $entity = ['id' => $categoryImage->getId(),
            'name' => $categoryImage->getFile()->getName(),
            'yourFileName' => $categoryImage->getFile()->getYourFileName()];

        $displayParams = ['title' => 'CategoryImage',
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
            '@SilecustWebShop/master_data/category/image/category_image_display.html.twig',
            ['request' => $request, 'entity' => $entity, 'params' => $displayParams]
        );

    }


    /**
     * @param \Silecust\WebShop\Entity\CategoryImage $categoryImage
     * @param CategoryDirectoryImagePathProvider $categoryDirectoryImagePathProvider
     *
     * @return Response
     *
     * To be displayed in img tag
     */
    #[Route('category/image/img-tag/{id}', name: 'sc_category_image_file_for_img_tag')]
    public function getFileContentsById(CategoryImage                      $categoryImage,
                                        CategoryDirectoryImagePathProvider $categoryDirectoryImagePathProvider
    ): Response
    {

        $path = $categoryDirectoryImagePathProvider->getFullPhysicalPathForFileByName(
            $categoryImage->getCategory(), $categoryImage->getFile()->getName()
        );

        return new BinaryFileResponse($path);

    }

}