<?php
// src/Controller/ProductController.php
namespace App\Controller\MasterData\Product\File\Image;

// ...
use App\Entity\ProductImageFile;
use App\Form\MasterData\Product\File\DTO\ProductFileImageDTO;
use App\Form\MasterData\Product\File\Form\ProductFileImageCreateForm;
use App\Repository\ProductImageFileRepository;
use App\Service\MasterData\Product\File\Image\ProductFileImageService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductImageController extends AbstractController
{
    #[Route('/product/{id}/file/image/create', name: 'product_create_file_image')]
    public function create(EntityManagerInterface $entityManager,
        ProductFileImageService $productFileImageService,
        Request $request
    ): Response {
        $productImageFileDTO = new ProductFileImageDTO();

        $form = $this->createForm(
            ProductFileImageCreateForm::class,
            $productImageFileDTO
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $productImageEntity = $productFileImageService->mapFormDTO($data);
            $productFileImageService->moveFile($data);

            $entityManager->persist($productImageEntity);
            $entityManager->flush();
            return $this->redirectToRoute('common/file/success_create.html.twig');


        }

        return $this->render(
            'master_data/product/file/image/create.html.twig',
            ['form' => $form]
        );
    }
}
