<?php
// src/Controller/WebShopController.php
namespace Silecust\WebShop\Controller\Module\WebShop\Admin\File\Image;

// ...

use Silecust\WebShop\Form\Module\WebShop\Admin\File\DTO\WebShopFileImageDTO;
use Silecust\WebShop\Form\Module\WebShop\Admin\File\Form\WebShopFileImageCreateForm;
use Silecust\WebShop\Service\Module\WebShop\Admin\File\WebShopFileImageService;
use Doctrine\ORM\EntityManagerInterface;
use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WebShopImageController extends EnhancedAbstractController
{
    #[Route('/shop/{id}/file/image/create', name: 'sc_create_webShop_image')]
    public function createWebShopImage(EntityManagerInterface  $entityManager,
                                       WebShopFileImageService $webShopFileImageService,
                                       Request                 $request): Response
    {
        $webShopImageFileDTO = new WebShopFileImageDTO();

        $form = $this->createForm(WebShopFileImageCreateForm::class, $webShopImageFileDTO);
        // $webShopFileDTO = new WebShopFileDTO();

        //   $form = $this->createForm(WebShopFileCreateForm::class, $webShopFileDTO);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $webShopImageEntity = $webShopFileImageService->mapFormDTO($data);
            $webShopFileImageService->moveFile($data);

            $entityManager->persist($webShopImageEntity);
            $entityManager->flush();
            return $this->redirectToRoute('sc_common/file/success_create.html.twig');


        }

            return $this->render('@SilecustWebShop/module/web_shop/admin/file/image/create.html.twig', ['form' => $form]);
    }


}