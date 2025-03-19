<?php
// src/Controller/LuckyController.php
namespace Silecust\WebShop\Controller\Finance\Tax;

use Silecust\WebShop\Form\Finance\TaxSlab\DTO\TaxSlabDTO;
use Silecust\WebShop\Form\Finance\TaxSlab\TaxSlabCreateForm;
use Silecust\WebShop\Form\Finance\TaxSlab\TaxSlabEditForm;
use Silecust\WebShop\Repository\TaxSlabRepository;
use Silecust\WebShop\Service\Finance\TaxSlab\Mapper\TaxSlabDTOMapper;
use Doctrine\ORM\EntityManagerInterface;
use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TaxSlabController extends EnhancedAbstractController
{

    #[Route('/admin/tax-slab/create', name: 'sc_route_admin_tax_slab_create')]
    public function create(TaxSlabDTOMapper      $taxSlabDTOMapper,
                           EntityManagerInterface $entityManager,
                           Request                $request,
                           ValidatorInterface              $validator
    ): Response
    {
        $taxSlabDTO = new TaxSlabDTO();
        $form = $this->createForm(TaxSlabCreateForm::class, $taxSlabDTO);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $taxSlabEntity = $taxSlabDTOMapper->mapToEntityForCreate($form->getData());

            $errors = $validator->validate( $taxSlabEntity);

            if (count($errors) == 0) {
                // perform some action...
                $entityManager->persist($taxSlabEntity);
                $entityManager->flush();

                $this->addFlash('success', "TaxSlab created successfully");
                return new Response(
                    serialize(
                        ['id' => $taxSlabEntity->getId(), 'message' => "TaxSlab created successfully"]
                    ), 200
                );
            }
        }

        return $this->render(
            'finance/tax_slab/tax_slab_create.html.twig', ['form' => $form]
        );
    }


    #[Route('/admin/tax-slab/{id}/edit', name: 'sc_route_admin_tax_slab_edit')]
    public function edit(EntityManagerInterface $entityManager,
                         TaxSlabRepository     $taxSlabRepository,
                         TaxSlabDTOMapper      $taxSlabDTOMapper,
                         Request                $request, int $id,
                         ValidatorInterface     $validator
    ): Response
    {
        $taxSlab = $taxSlabRepository->find($id);


        if (!$taxSlab) {
            throw $this->createNotFoundException(
                'No taxSlab found for id ' . $id
            );
        }
        $taxSlabDTO = $taxSlabDTOMapper->mapToDtoFromEntity($taxSlab);

        $form = $this->createForm(TaxSlabEditForm::class, $taxSlabDTO);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();
            $taxSlab = $taxSlabDTOMapper->mapToEntityForEdit($data);

            $errors = $validator->validate($taxSlab);

            if (count($errors) == 0) {
                // perform some action...
                $entityManager->persist($taxSlab);
                $entityManager->flush();

                $this->addFlash('success', "TaxSlab created successfully");
                return new Response(
                    serialize(
                        ['id' => $taxSlab->getId(), 'message' => "TaxSlab created successfully"]
                    ), 200
                );
            }
        }
        return $this->render(
            'finance/tax_slab/tax_slab_edit.html.twig', ['form' => $form]
        );
    }


    #[Route('/admin/tax-slab/{id}/display', name: 'sc_route_admin_tax_slab_display')]
    public function display(TaxSlabRepository $taxSlabRepository, int $id, Request $request): Response
    {
        $taxSlab = $taxSlabRepository->find($id);
        if (!$taxSlab) {
            throw $this->createNotFoundException(
                'No taxSlab found for id ' . $id
            );
        }

        $displayParams = ['title' => 'TaxSlab',
            'editButtonLinkText' => 'Edit',
            'link_id' => 'id-taxSlab',
            'fields' => [['label' => 'Name',
                'propertyName' => 'name',
                'link_id' => 'id-display-taxSlab',],
                ['label' => 'Description',
                    'propertyName' => 'description'],]];

        return $this->render(
            'finance/tax_slab/tax_slab_display.html.twig',
            ['request' => $request, 'entity' => $taxSlab, 'params' => $displayParams]
        );

    }

    #[Route('/admin/tax-slab/list', name: 'sc_route_admin_tax_slab_list')]
    public function list(TaxSlabRepository $taxSlabRepository, Request $request): Response
    {

        $listGrid = ['title' => 'TaxSlab',
            'link_id' => 'id-taxSlab',
            'columns' => [['label' => 'Name',
                'propertyName' => 'name',
                'action' => 'display',],
                ['label' => 'Description',
                    'propertyName' => 'description'],],
            'createButtonConfig' => ['link_id' => 'id-create-taxSlab',
                'function' => 'taxSlab',
                'anchorText' => 'Create TaxSlab']];

        $categories = $taxSlabRepository->findAll();
        return $this->render(
            '@SilecustWebShop/admin/ui/panel/section/content/list/list.html.twig',
            ['request' => $request, 'entities' => $categories, 'listGrid' => $listGrid]
        );
    }
}