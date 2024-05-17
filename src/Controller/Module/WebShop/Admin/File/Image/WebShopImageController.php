<?php
// src/Controller/WebShopController.php
namespace App\Controller\Module\WebShop\Admin\File\Image;

// ...

use App\Form\Module\WebShop\Admin\File\DTO\WebShopFileImageDTO;
use App\Form\Module\WebShop\Admin\File\Form\WebShopFileImageCreateForm;
use App\Service\Module\WebShop\Admin\File\WebShopFileImageService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WebShopImageController extends AbstractController
{
    #[Route('/shop/{id}/file/image/create', name: 'create_webShop_image')]
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
            return $this->redirectToRoute('common/file/success_create.html.twig');


        }

            return $this->render('module/web_shop/admin/file/image/create.html.twig', ['form' => $form]);
    }


}