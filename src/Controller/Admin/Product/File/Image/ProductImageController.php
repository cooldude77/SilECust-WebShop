<?php
// src/Controller/ProductController.php
namespace App\Controller\Admin\Product\File\Image;

// ...
use App\Form\Admin\Product\File\DTO\ProductFileDTO;
use App\Form\Admin\Product\File\DTO\ProductImageFileDTO;
use App\Form\Admin\Product\File\Form\ProductFileCreateForm;
use App\Form\Admin\Product\File\Form\ProductFileImageCreateForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductImageController extends AbstractController
{
    #[Route('/product/{id}/file/image/create', name: 'create_product_image')]
    public function createProductImage(EntityManagerInterface $entityManager, Request $request): Response
    {
        $productImageFileDTO = new ProductImageFileDTO();

        $form = $this->createForm(ProductFileImageCreateForm::class, $productImageFileDTO);
        // $productFileDTO = new ProductFileDTO();

        //   $form = $this->createForm(ProductFileCreateForm::class, $productFileDTO);

        $form->handleRequest($request);

        $data = $form->getData();

        return $this->render('admin/product/create.html.twig', ['form' => $form]);
    }


}