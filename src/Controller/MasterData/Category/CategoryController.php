<?php
// src/Controller/LuckyController.php
namespace Silecust\WebShop\Controller\MasterData\Category;

use Silecust\WebShop\Form\MasterData\Category\CategoryCreateForm;
use Silecust\WebShop\Form\MasterData\Category\CategoryEditForm;
use Silecust\WebShop\Form\MasterData\Category\DTO\CategoryDTO;
use Silecust\WebShop\Repository\CategoryRepository;
use Silecust\WebShop\Service\MasterData\Category\Mapper\CategoryDTOMapper;
use Doctrine\ORM\EntityManagerInterface;
use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CategoryController extends EnhancedAbstractController
{

    #[Route('/admin/category/create', 'sc_route_admin_category_create')]
    public function create(CategoryDTOMapper      $categoryDTOMapper,
                           EntityManagerInterface $entityManager,
                           Request                $request,
                           ValidatorInterface              $validator
    ): Response
    {
        $categoryDTO = new CategoryDTO();
        $form = $this->createForm(CategoryCreateForm::class, $categoryDTO);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $categoryEntity = $categoryDTOMapper->mapToEntityForCreate($form->getData());

            // todo:
            $errors = $validator->validate( $categoryEntity);

            if (count($errors) == 0) {
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
        }

        return $this->render(
            '@SilecustWebShop/master_data/category/category_create.html.twig', ['form' => $form]
        );
    }


    #[Route('/admin/category/{id}/edit', name: 'sc_route_admin_category_edit')]
    public function edit(EntityManagerInterface $entityManager,
                         CategoryRepository     $categoryRepository,
                         CategoryDTOMapper      $categoryDTOMapper,
                         Request                $request, int $id,
                         ValidatorInterface     $validator
    ): Response
    {
        $category = $categoryRepository->find($id);


        if (!$category) {
            throw $this->createNotFoundException(
                'No category found for id ' . $id
            );
        }
        $categoryDTO = $categoryDTOMapper->mapToDtoFromEntity($category);

        $form = $this->createForm(CategoryEditForm::class, $categoryDTO);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();
            $category = $categoryDTOMapper->mapToEntityForEdit($data);

            $errors = $validator->validate($category);

            if (count($errors) == 0) {
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
        }
        return $this->render(
            '@SilecustWebShop/master_data/category/category_edit.html.twig', ['form' => $form]
        );
    }


    #[Route('/admin/category/{id}/display', name: 'sc_route_admin_category_display')]
    public function display(CategoryRepository $categoryRepository, int $id, Request $request): Response
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
            '@SilecustWebShop/master_data/category/category_display.html.twig',
            ['request' => $request, 'entity' => $category, 'params' => $displayParams]
        );

    }

    #[Route('/admin/category/list', name: 'sc_route_admin_category_list')]
    public function list(CategoryRepository $categoryRepository, Request $request): Response
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
            '@SilecustWebShop/admin/ui/panel/section/content/list/list.html.twig',
            ['request' => $request, 'entities' => $categories, 'listGrid' => $listGrid]
        );
    }
}