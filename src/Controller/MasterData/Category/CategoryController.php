<?php
// src/Controller/LuckyController.php
namespace App\Controller\MasterData\Category;

use App\Form\MasterData\Category\CategoryCreateForm;
use App\Form\MasterData\Category\CategoryEditForm;
use App\Form\MasterData\Category\DTO\CategoryDTO;
use App\Repository\CategoryRepository;
use App\Service\MasterData\Category\Mapper\CategoryDTOMapper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CategoryController extends AbstractController
{

    #[Route('/category/create', 'category_create')]
    public function create(CategoryDTOMapper $categoryDTOMapper,
        EntityManagerInterface $entityManager,
        Request $request,
        ValidatorInterface $validator
    ): Response {
        $categoryDTO = new CategoryDTO();
        $form = $this->createForm(CategoryCreateForm::class, $categoryDTO);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $categoryEntity = $categoryDTOMapper->mapToEntityForCreate($form);

            // todo:
           $errors =  $validator->validate($categoryEntity);

            // perform some action...
            $entityManager->persist($categoryEntity);
            $entityManager->flush();

            $this->addFlash('success', "Category created successfully");
            return new Response(
                serialize(
                    ['id' => $categoryEntity->getId(), 'message' => "Category created successfully"]
                ), 200
            );
        }

        return $this->render(
            'master_data/category/category_create.html.twig', ['form' => $form]
        );
    }


    #[\Symfony\Component\Routing\Annotation\Route('/category/{id}/edit', name: 'category_edit')]
    public function edit(EntityManagerInterface $entityManager,
        CategoryRepository $categoryRepository, CategoryDTOMapper $categoryDTOMapper,
        Request $request, int $id
    ): Response {
        $category = $categoryRepository->find($id);


        if (!$category) {
            throw $this->createNotFoundException(
                'No category found for id ' . $id
            );
        }
        $categoryDTO = new CategoryDTO();
        $categoryDTO->id = $id;

        $form = $this->createForm(CategoryEditForm::class, $categoryDTO);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();
            $category = $categoryDTOMapper->mapToEntityForEdit($form, $category);


            // perform some action...
            $entityManager->persist($category);
            $entityManager->flush();

            $this->addFlash('success', "Category created successfully");
            return new Response(
                serialize(
                    ['id' => $category->getId(), 'message' => "Category created successfully"]
                ), 200
            );
        }

        return $this->render(
            'master_data/category/category_edit.html.twig', ['form' => $form]
        );
    }

    #[Route('/category/{id}/display', name: 'category_display')]
    public function display(CategoryRepository $categoryRepository, int $id): Response
    {
        $category = $categoryRepository->find($id);
        if (!$category) {
            throw $this->createNotFoundException(
                'No category found for id ' . $id
            );
        }

        $displayParams = ['title' => 'Category',
                          'editButtonLinkText' => 'Edit',
                          'link_id' => 'id-category',
                          'fields' => [['label' => 'Name',
                                        'propertyName' => 'name',
                                        'link_id' => 'id-display-category',],
                                       ['label' => 'Description',
                                        'propertyName' => 'description'],]];

        return $this->render(
            'master_data/category/category_display.html.twig',
            ['entity' => $category, 'params' => $displayParams]
        );

    }

    #[Route('/category/list', name: 'category_list')]
    public function list(CategoryRepository $categoryRepository): Response
    {

        $listGrid = ['title' => 'Category',
                     'link_id' => 'id-category',
                     'columns' => [['label' => 'Name',
                                    'propertyName' => 'name',
                                    'action' => 'display',],
                                   ['label' => 'Description',
                                    'propertyName' => 'description'],],
                     'createButtonConfig' => ['link_id' => 'id-create-category',
                                              'function' => 'category',
                                              'anchorText' => 'Create Category']];

        $categories = $categoryRepository->findAll();
        return $this->render(
            'admin/ui/panel/section/content/list/list.html.twig',
            ['entities' => $categories, 'listGrid' => $listGrid]
        );
    }
}