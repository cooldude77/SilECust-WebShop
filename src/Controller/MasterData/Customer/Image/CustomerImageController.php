<?php

namespace Silecust\WebShop\Controller\MasterData\Customer\Image;

use Silecust\WebShop\Controller\Common\Utility\CommonUtility;
use Silecust\WebShop\Controller\MasterData\Customer\Image\ListObject\CustomerImageObject;
use Silecust\WebShop\Entity\CustomerImage;
use Silecust\WebShop\Form\MasterData\Customer\Image\DTO\CustomerImageDTO;
use Silecust\WebShop\Form\MasterData\Customer\Image\Form\CustomerImageCreateForm;
use Silecust\WebShop\Form\MasterData\Customer\Image\Form\CustomerImageEditForm;
use Silecust\WebShop\Repository\CustomerImageRepository;
use Silecust\WebShop\Repository\CustomerRepository;
use Silecust\WebShop\Service\Common\Image\SystemImage;
use Silecust\WebShop\Service\MasterData\Customer\Image\Mapper\CustomerImageDTOMapper;
use Silecust\WebShop\Service\MasterData\Customer\Image\CustomerImageOperation;
use Silecust\WebShop\Service\MasterData\Customer\Image\Provider\CustomerDirectoryImagePathProvider;
use Doctrine\ORM\EntityManagerInterface;
use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 *
 */
class CustomerImageController extends EnhancedAbstractController
{
    /**
     * @param int $id
     * @param EntityManagerInterface $entityManager
     * @param CustomerImageDTOMapper $customerImageDTOMapper
     * @param CommonUtility $commonUtility
     * @param Request $request
     *
     * @return Response
     */
    #[Route('/admin/customer/{id}/image/create', name: 'sc_admin_customer_file_image_create')]
    public function create(int                   $id, EntityManagerInterface $entityManager,
                           CustomerImageOperation $customerImageOperation,
                           CustomerRepository     $customerRepository,
                           CustomerImageDTOMapper $customerImageDTOMapper, CommonUtility $commonUtility,
                           Request               $request
    ): Response
    {
        $customer = $customerRepository->find(['id' => $id]);

        // Todo : validate if customer exists

        $customerImageDTO = new CustomerImageDTO();
        $customerImageDTO->customerId = $id;

        $form = $this->createForm(CustomerImageCreateForm::class, $customerImageDTO);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var CustomerImageDTO $customerImageDTO */
            $customerImageDTO = $form->getData();

            $customerImage = $customerImageDTOMapper->mapDtoToEntityForCreate($customerImageDTO);
            $customerImageOperation->createOrReplace(
                $customerImage, $customerImageDTO->getUploadedFile()
            );


            $entityManager->persist($customerImage);
            $entityManager->flush();

            $id = $customerImage->getId();

            $this->addFlash(
                'success', "Customer file image created successfully"
            );

            return new Response(
                serialize(
                    ['id' => $id, 'message' => "Customer file image  created successfully"]
                ), 200
            );
        }

        return $this->render('@SilecustWebShop/master_data/customer/image/customer_image_create.html.twig', ['form' =>
                $form]
        );
    }

    /**
     * @param int $id
     * @param EntityManagerInterface $entityManager
     * @param CustomerImageRepository $customerImageRepository
     * @param CustomerImageDTOMapper $customerImageDTOMapper
     * @param CustomerImageOperation $customerImageService
     * @param Request $request
     *
     * @return Response
     *
     * id is CustomerImage Id
     */
    #[\Symfony\Component\Routing\Attribute\Route('/admin/customer/image/{id}/edit', name: 'sc_admin_customer_file_image_edit')]
    public function edit(int                    $id, EntityManagerInterface $entityManager,
                         CustomerImageRepository $customerImageRepository,
                         CustomerImageDTOMapper  $customerImageDTOMapper,
                         CustomerImageOperation  $customerImageService, Request $request
    ): Response
    {
        $customerImage = $customerImageRepository->find($id);

        $customerImageDTO = $customerImageDTOMapper->mapEntityToDto(
            $customerImage
        );
        $form = $this->createForm(CustomerImageEditForm::class, $customerImageDTO);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var CustomerImageDTO $customerImageDTO */
            $customerImageDTO = $form->getData();

            $customerImage = $customerImageDTOMapper->mapDtoToEntityForEdit(
                $form->getData(), $customerImage
            );

            $customerImageService->createOrReplace(
                $customerImage, $customerImageDTO->getUploadedFile()
            );

            $entityManager->persist($customerImage);
            $entityManager->flush();


            $this->addFlash(
                'success', "Customer file image updated successfully"
            );

            return new Response(
                serialize(
                    ['id' => $id, 'message' => "Customer file image  updated successfully"]
                ), 200
            );
        }

        return $this->render(
            '@SilecustWebShop/master_data/customer/image/customer_image_edit.html.twig',
            ['form' => $form, 'entity' => $customerImage]
        );

    }

    #[Route('/admin/customer/{id}/image/list', name: 'sc_admin_customer_create_file_image_list')]
    public function list(int                    $id, CustomerRepository $customerRepository,
                         CustomerImageRepository $customerImageRepository,
                         Request                $request
    ):
    Response
    {


        $customerImages = $customerImageRepository->findBy(['customer' => $customerRepository->find
        (
            $id
        )]);

        $entities = [];
        if ($customerImages != null) {
            /** @var CustomerImage $customerImage */
            foreach ($customerImages as $customerImage) {
                $f = new CustomerImageObject();
                $f->id = $customerImage->getId();
                $f->yourFileName = $customerImage->getFile()->getYourFileName();
                $f->name = $customerImage->getFile()->getName();
                $entities[] = $f;
            }
        }

        $listGrid = ['title' => "Customer Files",
            'function' => 'customer_file_image',
            'link_id' => 'id-customer-image-file',
            'columns' => [['label' => 'Your fileName',
                'propertyName' => 'yourFileName',
                'action' => 'display',],
                ['label' => 'FileName', 'propertyName' => 'name'],],
            'createButtonConfig' => ['link_id' => ' id-create-file-image',
                'function' => 'customer_file_image',
                'id' => $id,
                'anchorText' => 'Customer File']];

        return $this->render(
            '@SilecustWebShop/admin/ui/panel/section/content/list/list.html.twig',
            ['request' => $request, 'entities' => $entities, 'listGrid' => $listGrid]
        );

    }

    /**
     *
     * Fetch is to display image standalone ( call by URL at the top )
     *
     * @param int $id
     * @param CustomerImageRepository $customerImageRepository
     * @param CustomerDirectoryImagePathProvider $customerDirectoryImagePathProvider
     *
     * @return Response
     */
    #[\Symfony\Component\Routing\Attribute\Route('/admin/customer/image/{id}/fetch', name: 'sc_admin_customer_file_image_fetch')]
    public function fetch(int                               $id, CustomerImageRepository $customerImageRepository,
                          CustomerDirectoryImagePathProvider $customerDirectoryImagePathProvider
    ): Response
    {

        /** @var CustomerImage $customerImage */
        $customerImage = $customerImageRepository->findOneBy(['id' => $id]);
        $path = $customerDirectoryImagePathProvider->getFullPhysicalPathForFileByName(
            $customerImage->getCustomer(), $customerImage->getFile()->getName()
        );

        $file = file_get_contents($path);

        $headers = array('Content-Type' => mime_content_type($customerImage->getFile()),
            'Content-Disposition' => 'inline; filename="' . $customerImage->getFile()
                    ->getName() . '"');
        return new Response($file, 200, $headers);

    }

    /**
     * @param CustomerImageRepository $customerImageRepository
     * @param int $id
     *
     * @return Response
     */
    #[\Symfony\Component\Routing\Attribute\Route('/admin/customer/image/{$id}/display/', name: 'sc_admin_customer_file_image_display')]
    public function display(CustomerImageRepository $customerImageRepository, int $id, Request $request): Response
    {
        $customerImage = $customerImageRepository->findOneBy(['id' => $id]);
        if (!$customerImage) {
            throw $this->createNotFoundException('No Customer Image found for file id ' . $id);
        }
        $entity = ['id' => $customerImage->getId(),
            'name' => $customerImage->getFile()->getName(),
            'yourFileName' => $customerImage->getFile()->getYourFileName()];

        $displayParams = ['title' => 'CustomerImage',
            'editButtonLinkText' => 'Edit',
            'link_id' => 'id-edit-file',
            'fields' => [['label' => 'Your Name',
                'propertyName' => 'yourFileName',
                'link_id' => 'id-display-image-file'],
                ['label' => 'Name', 'propertyName' => 'name'],
            ]];

        return $this->render(
            '@SilecustWebShop/master_data/customer/image/customer_image_display.html.twig',
            ['request' => $request, 'entity' => $entity, 'params' => $displayParams]
        );

    }


    /**
     * @param int $id from CustomerImage->getId()
     * @param CustomerImageRepository $customerImageRepository
     * @param CustomerDirectoryImagePathProvider $customerDirectoryImagePathProvider
     *
     * @return Response
     *
     * To be displayed in img tag
     */
    #[\Symfony\Component\Routing\Attribute\Route('customer/image/img-tag/{id}', name: 'sc_customer_image_file_for_img_tag')]
    public function getFileContentsById(int                               $id, CustomerImageRepository $customerImageRepository,
                                        CustomerDirectoryImagePathProvider $customerDirectoryImagePathProvider,
                                        SystemImage                       $systemImage
    ): Response
    {

        /** @var CustomerImage $customerImage */
        $customerImage = $customerImageRepository->findOneBy(['id' => $id]);

        if ($customerImage != null) {
            $path = $customerDirectoryImagePathProvider->getFullPhysicalPathForFileByName(
                $customerImage->getCustomer(), $customerImage->getFile()->getName()
            );
        }
        if (!isset($path) || !file_exists($path)) {
            $path = $systemImage->getNoImageForCustomerPath();
        }


        return new BinaryFileResponse($path);

    }

}