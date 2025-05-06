<?php

namespace Silecust\WebShop\Controller\MasterData\Category\Image;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Silecust\WebShop\Controller\Common\Utility\CommonUtility;
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
     * @param int $id
     * @param EntityManagerInterface $entityManager
     * @param CategoryImageDTOMapper $categoryImageDTOMapper
     * @param CommonUtility $commonUtility
     * @param Request $request
     *
     * @return Response
     */
    #[Route('/admin/category/{id}/image/create', name: 'sc_admin_category_file_image_create')]
    public function create(int                    $id, EntityManagerInterface $entityManager,
                           CategoryImageOperation $categoryImageOperation,
                           CategoryRepository     $categoryRepository,
                           CategoryImageDTOMapper $categoryImageDTOMapper, CommonUtility $commonUtility,
                           Request                $request
    ): Response
    {
        $category = $categoryRepository->find(['id' => $id]);

        // Todo : validate if category exists

        $categoryImageDTO = new CategoryImageDTO();
        $categoryImageDTO->categoryId = $id;

        $form = $this->createForm(CategoryImageCreateForm::class, $categoryImageDTO);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var CategoryImageDTO $categoryImageDTO */
            $categoryImageDTO = $form->getData();

            $categoryImage = $categoryImageDTOMapper->mapDtoToEntityForCreate($categoryImageDTO);
            $categoryImageOperation->createOrReplace(
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
     * @param int $id
     * @param EntityManagerInterface $entityManager
     * @param CategoryImageRepository $categoryImageRepository
     * @param CategoryImageDTOMapper $categoryImageDTOMapper
     * @param CategoryImageOperation $categoryImageService
     * @param Request $request
     *
     * @return Response
     *
     * id is CategoryImage Id
     */
    #[Route('/admin/category/image/{id}/edit', name: 'sc_admin_category_file_image_edit')]
    public function edit(int                     $id, EntityManagerInterface $entityManager,
                         CategoryImageRepository $categoryImageRepository,
                         CategoryImageDTOMapper  $categoryImageDTOMapper,
                         CategoryImageOperation  $categoryImageService, Request $request
    ): Response
    {
        $categoryImage = $categoryImageRepository->find($id);

        $categoryImageDTO = $categoryImageDTOMapper->mapEntityToDto(
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

            $categoryImageService->createOrReplace(
                $categoryImage, $categoryImageDTO->getUploadedFile()
            );

            $entityManager->persist($categoryImage);
            $entityManager->flush();


            $this->addFlash(
                'success', "Category file image updated successfully"
            );

            return new Response(
                serialize(
                    ['id' => $id, 'message' => "Category file image  updated successfully"]
                ), 200
            );
        }

        return $this->render(
            '@SilecustWebShop/master_data/category/image/category_image_edit.html.twig',
            ['form' => $form, 'entity' => $categoryImage]
        );

    }

    #[Route('/admin/category/{id}/image/list', name: 'sc_admin_category_file_image_list')]
    public function list(int                     $id,
                         CategoryRepository      $categoryRepository,
                         CategoryImageRepository $categoryImageRepository,
                         PaginatorInterface      $paginator,
                         SearchEntityInterface   $searchEntity,
                         Request                 $request
    ):
    Response
    {
        $this->setContentHeading($request, 'Category Images');

        $category = $categoryRepository->find($id);

        $listGrid = ['title' => "Category Files",
            'function' => 'category_file_image',
            'link_id' => 'id-category-image-file',
            'columns' => [['label' => 'Your fileName',
                'propertyName' => 'yourFileName',
                'action' => 'display',],
                ['label' => 'FileName', 'propertyName' => 'name'],],
            'createButtonConfig' => ['link_id' => ' id-create-file-image',
                'function' => 'category_file_image',
                'id' => $id,
                'anchorText' => 'Category File']];

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
     *
     * Fetch is to display image standalone ( call by URL at the top )
     *
     * @param int $id
     * @param CategoryImageRepository $categoryImageRepository
     * @param CategoryDirectoryImagePathProvider $categoryDirectoryImagePathProvider
     *
     * @return Response
     */
    #[Route('/admin/category/image/{id}/fetch', name: 'sc_admin_category_file_image_fetch')]
    public function fetch(int                                $id, CategoryImageRepository $categoryImageRepository,
                          CategoryDirectoryImagePathProvider $categoryDirectoryImagePathProvider
    ): Response
    {

        /** @var CategoryImage $categoryImage */
        $categoryImage = $categoryImageRepository->findOneBy(['id' => $id]);
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
     * @param CategoryImageRepository $categoryImageRepository
     * @param int $id
     *
     * @return Response
     */
    #[Route('/admin/category/image/{$id}/display/', name: 'sc_admin_category_file_image_display')]
    public function display(CategoryImageRepository $categoryImageRepository, int $id, Request $request): Response
    {
        $categoryImage = $categoryImageRepository->findOneBy(['id' => $id]);
        if (!$categoryImage) {
            throw $this->createNotFoundException('No Category Image found for file id ' . $id);
        }
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
     * @param int $id from CategoryImage->getId()
     * @param CategoryImageRepository $categoryImageRepository
     * @param CategoryDirectoryImagePathProvider $categoryDirectoryImagePathProvider
     *
     * @return Response
     *
     * To be displayed in img tag
     */
    #[Route('category/image/img-tag/{id}', name: 'sc_category_image_file_for_img_tag')]
    public function getFileContentsById(int                                $id, CategoryImageRepository $categoryImageRepository,
                                        CategoryDirectoryImagePathProvider $categoryDirectoryImagePathProvider
    ): Response
    {

        /** @var CategoryImage $categoryImage */
        $categoryImage = $categoryImageRepository->findOneBy(['id' => $id]);
        $path = $categoryDirectoryImagePathProvider->getFullPhysicalPathForFileByName(
            $categoryImage->getCategory(), $categoryImage->getFile()->getName()
        );

        return new BinaryFileResponse($path);

    }

}