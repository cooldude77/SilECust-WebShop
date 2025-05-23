<?php
// src/Controller/FileController.php
namespace Silecust\WebShop\Controller\Common\File;

// ...
use Knp\Component\Pager\PaginatorInterface;
use Silecust\WebShop\Entity\File;
use Silecust\WebShop\Form\Common\File\DTO\FileDTO;
use Silecust\WebShop\Form\Common\File\FileCreateForm;
use Silecust\WebShop\Form\Common\File\FileEditForm;
use Silecust\WebShop\Form\Common\File\Mapper\FileDTOMapper;
use Silecust\WebShop\Repository\FileRepository;
use Silecust\WebShop\Service\Common\File\FilePhysicalOperation;
use Silecust\WebShop\Service\Common\File\Provider\FileDirectoryPathProvider;
use Doctrine\ORM\EntityManagerInterface;
use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Silecust\WebShop\Service\Component\UI\Search\SearchEntityInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 *  Image guidelines :
 *
 *  Carousel :
 */
class FileController extends EnhancedAbstractController
{
    /**
     * @param EntityManagerInterface    $entityManager
     * @param FileDTOMapper             $fileDTOMapper
     * @param FilePhysicalOperation     $filePhysicalOperation
     * @param FileDirectoryPathProvider $directoryPathProvider
     * @param Request                   $request
     *
     * @return Response
     */
    #[Route('/file/create', name: 'sc_file_create')]
    public function create(EntityManagerInterface $entityManager, FileDTOMapper $fileDTOMapper,
        FilePhysicalOperation $filePhysicalOperation,
        FileDirectoryPathProvider $directoryPathProvider, Request $request
    ): Response {
        $fileFormDTO = new FileDTO();
        $form = $this->createForm(FileCreateForm::class, $fileFormDTO);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $fileEntity = $fileDTOMapper->mapToFileEntityForCreate($form->getData());
            $filePhysicalOperation->createOrReplaceFile(
                $fileFormDTO->uploadedFile, $fileEntity->getName(),
                $directoryPathProvider->getBaseFolderPath()
            );

            $entityManager->persist($fileEntity);
            $entityManager->flush();

            if ($request->get('_redirect_upon_success_url')) {
                $this->addFlash('success', "Category created successfully");

                $id = $fileEntity->getId();
                $success_url = $request->get('_redirect_upon_success_url') . "&id={$id}";

                return $this->redirect($success_url);
            }


            return $this->render(
                '/common/miscellaneous/success/create.html.twig',
                ['message' => 'File successfully created']
            );
        }

        return $this->render('@SilecustWebShop/common/file/create.html.twig', ['form' => $form]);
    }

    /**
     * @param int                       $id
     * @param EntityManagerInterface    $entityManager
     * @param FileRepository            $fileRepository
     * @param FileDTOMapper             $fileDTOMapper
     * @param FilePhysicalOperation     $filePhysicalOperation
     * @param FileDirectoryPathProvider $directoryPathProvider
     * @param Request                   $request
     *
     * @return Response
     */
    #[Route('/file/edit/{id}', name: 'sc_file_edit')]
    public function edit(int $id, EntityManagerInterface $entityManager,
        FileRepository $fileRepository, FileDTOMapper $fileDTOMapper,
        FilePhysicalOperation $filePhysicalOperation,
        FileDirectoryPathProvider $directoryPathProvider, Request $request
    ): Response {
        $fileEntity = $fileRepository->findOneBy(['id' => $id]);

        $fileFormDTO = $fileDTOMapper->mapEntityToFileDto($fileEntity);
        $form = $this->createForm(FileEditForm::class, $fileFormDTO);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $fileEntity = $fileDTOMapper->mapToFileEntityForEdit($form->getData(), $fileEntity);
            $filePhysicalOperation->createOrReplaceFile(
                $fileFormDTO->uploadedFile, $fileEntity->getName(),
                $directoryPathProvider->getBaseFolderPath()
            );

            $entityManager->persist($fileEntity);
            $entityManager->flush();

            if ($request->get('_redirect_upon_success_url')) {
                $this->addFlash('success', "Updated created successfully");

                $id = $fileEntity->getId();
                $success_url = $request->get('_redirect_upon_success_url') . "&id={$id}";

                return $this->redirect($success_url);
            }

            return $this->render(
                '/common/miscellaneous/success/create.html.twig',
                ['message' => 'File successfully created']
            );
        }

        return $this->render(
            'common/file/edit.html.twig', ['form' => $form, 'entity' => $fileEntity]
        );
    }


    /**
     * @param FileRepository $fileRepository
     *
     * @return Response
     */
    #[Route('/file/list', name: 'sc_file_list')]
    public function list(FileRepository $fileRepository,
                         PaginatorInterface    $paginator,
                         SearchEntityInterface $searchEntity,
                         Request               $request): Response
    {

        $files = $fileRepository->findAll();

        $listGrid = ['title' => "Files",
                     'function' => 'file',
                     'link_id' => 'id-file',
                     'columns' => [['label' => 'Your fileName',
                                    'propertyName' => 'yourFileName',
                                    'action' => 'display'],
                                   ['label' => 'FileName', 'propertyName' => 'name'],],
                     'createButtonConfig' => ['function' => 'file',
                                              'anchorText' => 'File',
                                              'link_id' => 'id-file']];

        $query = $searchEntity->getQueryForSelect($request, $fileRepository,
        ['name']);

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
     * @param FileRepository $fileRepository
     * @param int            $id
     *
     * @return Response
     */
    #[Route('/file/display/{id}', name: 'sc_file_display')]
    public function display(FileRepository $fileRepository, int $id): Response
    {
        $file = $fileRepository->find($id);
        if (!$file) {
            throw $this->createNotFoundException('No file found for id ' . $id);
        }

        $displayParams = ['title' => 'File',
                          'editButtonLinkText' => 'Edit',
                          'link_id'=>'id-file',
                          'fields' => [['label' => 'Your File Name',
                                        'link_id' => 'id-display-file',
                                        'propertyName' => 'yourFileName'],
                                       ['label' => 'Name', 'propertyName' => 'name'],]];

        return $this->render(
            'common/file/display.html.twig', ['entity' => $file, 'params' => $displayParams]
        );

    }

    /**
     * @param int                       $id
     * @param FileRepository            $fileRepository
     * @param FileDirectoryPathProvider $directoryPathProvider
     * @param FilePhysicalOperation     $fileService
     *
     * @return Response
     *
     * To be used to fetch image with just URL
     */
    #[Route('/file/fetch/{id}', name: 'sc_file_fetch')]
    public function fetch(int $id, FileRepository $fileRepository,
        FileDirectoryPathProvider $directoryPathProvider, FilePhysicalOperation $fileService
    ): Response {
        $fileEntity = $fileRepository->findOneBy(['id' => $id]);
        $path = $directoryPathProvider->getFullPathForImageFile($fileEntity->getName());

        $file = file_get_contents($path);

        $headers = array('Content-Type' => mime_content_type( $file),
                         'Content-Disposition' => 'inline; filename="'
                             . $fileEntity->getName()
                             . '"');
        return new Response($file, 200, $headers);

    }

    /**
     * @param int                       $id
     * @param FileRepository            $fileRepository
     * @param FileDirectoryPathProvider $directoryPathProvider
     *
     * @return Response
     *
     * To be used in IMG tag (in display and edit templates)
     */
    #[Route('/file/path/{id}', name: 'sc_image_file_for_img_tag')]
    public function getFileContentsById(int $id, FileRepository $fileRepository,
        FileDirectoryPathProvider $directoryPathProvider
    ): Response {

        /** @var File $fileEntity */
        $fileEntity = $fileRepository->findOneBy(['id' => $id]);
        $path = $directoryPathProvider->getFullPathForImageFile($fileEntity->getName());

        return new BinaryFileResponse($path);

    }

}