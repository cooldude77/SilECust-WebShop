<?php

namespace App\Controller\MasterData\Category\Image;

use App\Controller\Common\Utility\CommonUtility;
use App\Controller\MasterData\Category\Image\ListObject\CategoryImageObject;
use App\Entity\CategoryImage;
use App\Form\MasterData\Category\File\DTO\CategoryImageDTO;
use App\Form\MasterData\Category\File\Image\Form\CategoryImageCreateForm;
use App\Form\MasterData\Category\File\Image\Form\CategoryImageEditForm;
use App\Repository\CategoryImageRepository;
use App\Repository\CategoryRepository;
use App\Service\MasterData\Category\File\Image\CategoryImageOperation;
use App\Service\MasterData\Category\File\Image\Mapper\CategoryImageDTOMapper;
use App\Service\MasterData\Category\File\Provider\CategoryDirectoryImagePathProvider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 *
 */
class CategoryImageController extends AbstractController
{
    /**
     * @param int                    $id
     * @param EntityManagerInterface $entityManager
     * @param CategoryImageDTOMapper $categoryImageDTOMapper
     * @param CommonUtility          $commonUtility
     * @param Request                $request
     *
     * @return Response
     */
    #[Route('/category/{id}/image/create', name: 'category_file_image_create')]
    public function create(int $id, EntityManagerInterface $entityManager,
        CategoryImageOperation $categoryImageOperation,
        CategoryRepository $categoryRepository,
        CategoryImageDTOMapper $categoryImageDTOMapper, CommonUtility $commonUtility,
        Request $request
    ): Response {
        $category = $categoryRepository->find(['id' => $id]);

        // Todo : validate if category exists

        $categoryImageDTO = new CategoryImageDTO();
        $categoryImageDTO->categoryId = $id;

        $form = $this->createForm(CategoryImageCreateForm::class, $categoryImageDTO);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $categoryImage = $categoryImageDTOMapper->mapDtoToEntityForCreate($data);
            $categoryImageOperation->createOrReplace($data);


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

        return $this->render('master_data/category/image/create.html.twig', ['form' => $form]);
    }

    /**
     * @param int                     $id
     * @param EntityManagerInterface  $entityManager
     * @param CategoryImageRepository $categoryImageRepository
     * @param CategoryImageDTOMapper  $categoryImageDTOMapper
     * @param CategoryImageOperation  $categoryImageService
     * @param Request                 $request
     *
     * @return Response
     *
     * id is CategoryImage Id
     */
    #[\Symfony\Component\Routing\Attribute\Route('/category/image/{id}/edit', name: 'category_file_image_edit')]
    public function edit(int $id, EntityManagerInterface $entityManager,
        CategoryImageRepository $categoryImageRepository,
        CategoryImageDTOMapper $categoryImageDTOMapper,
        CategoryImageOperation $categoryImageService, Request $request
    ): Response {
        $categoryImageEntity = $categoryImageRepository->findOneBy(['id' => $id]);

        $categoryImageFormDTO = $categoryImageDTOMapper->mapEntityToDto(
            $categoryImageEntity
        );
        $form = $this->createForm(CategoryImageEditForm::class, $categoryImageFormDTO);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $categoryImageEntity = $categoryImageDTOMapper->mapDtoToEntityForEdit(
                $form->getData(), $categoryImageEntity
            );

            $categoryImageService->createOrReplace($data);

            $entityManager->persist($categoryImageEntity);
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
            'master_data/category/image/category_image_edit.html.twig',
            ['form' => $form, 'entity' => $categoryImageEntity]
        );

    }

    #[Route('/category/{id}/image/list', name: 'category_create_file_image_list')]
    public function list(int $id, CategoryRepository $categoryRepository,
        CategoryImageRepository $categoryImageRepository
    ):
    Response {


        $categoryImages = $categoryImageRepository->findBy(['category' => $categoryRepository->find
        (
            $id
        )]);

        $entities = [];
        if ($categoryImages != null) {
            /** @var CategoryImage $categoryImage */
            foreach ($categoryImages as $categoryImage) {
                $f = new CategoryImageObject();
                $f->id = $categoryImage->getId();
                $f->yourFileName = $categoryImage->getFile()->getYourFileName();
                $f->name = $categoryImage->getFile()->getName();
                $entities[] = $f;
            }
        }

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

        return $this->render(
            'admin/ui/panel/section/content/list/list.html.twig',
            ['entities' => $entities, 'listGrid' => $listGrid]
        );

    }

    /**
     *
     * Fetch is to display image standalone ( call by URL at the top )
     *
     * @param int                                $id
     * @param CategoryImageRepository            $categoryImageRepository
     * @param CategoryDirectoryImagePathProvider $categoryDirectoryImagePathProvider
     *
     * @return Response
     */
    #[\Symfony\Component\Routing\Attribute\Route('/category/image/{id}/fetch', name: 'category_file_image_fetch')]
    public function fetch(int $id, CategoryImageRepository $categoryImageRepository,
        CategoryDirectoryImagePathProvider $categoryDirectoryImagePathProvider
    ): Response {

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
     * @param int                     $id
     *
     * @return Response
     */
    #[\Symfony\Component\Routing\Attribute\Route('/category/image/{$id}/display/', name: 'category_file_image_display')]
    public function display(CategoryImageRepository $categoryImageRepository, int $id): Response
    {
        $categoryImage = $categoryImageRepository->findOneBy(['id' => $id]);
        if (!$categoryImage) {
            throw $this->createNotFoundException('No Category Image found for file id ' . $id);
        }
        $entity = ['id' => $categoryImage->getId(),
                   'name' => $categoryImage->getCategoryFile()->getFile()->getName(),
                   'yourFileName' => $categoryImage->getCategoryFile()->getFile()->getYourFileName(
                   ),
                   'categoryImageType' => $categoryImage->getCategoryImageType()->getDescription()];

        $displayParams = ['title' => 'CategoryImage',
                          'editButtonLinkText' => 'Edit',
                          'fields' => [['label' => 'Your Name',
                                        'propertyName' => 'yourFileName',
                                        'link_id' => 'id-display-image-file'],
                                       ['label' => 'Name', 'propertyName' => 'name'],
                                       ['label' => 'Image File Type',
                                        'propertyName' => 'categoryImageType']]];

        return $this->render(
            'master_data/category/image/category_image_display.html.twig',
            ['entity' => $entity, 'params' => $displayParams]
        );

    }


    /**
     * @param int                                $id from CategoryImage->getId()
     * @param CategoryImageRepository            $categoryImageRepository
     * @param CategoryDirectoryImagePathProvider $categoryDirectoryImagePathProvider
     *
     * @return Response
     *
     * To be displayed in img tag
     */
    #[\Symfony\Component\Routing\Attribute\Route('category/image/img-tag/{id}', name: 'category_image_file_for_img_tag')]
    public function getFileContentsById(int $id, CategoryImageRepository $categoryImageRepository,
        CategoryDirectoryImagePathProvider $categoryDirectoryImagePathProvider
    ): Response {

        /** @var CategoryImage $categoryImage */
        $categoryImage = $categoryImageRepository->findOneBy(['id' => $id]);
        $path = $categoryDirectoryImagePathProvider->getFullPhysicalPathForFileByName(
            $categoryImage->getCategory(), $categoryImage->getFile()->getName()
        );

        return new BinaryFileResponse($path);

    }

}