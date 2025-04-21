<?php
// src/controller/employeeController.php
namespace Silecust\WebShop\Controller\MasterData\Employee;

// ...
use Knp\Component\Pager\PaginatorInterface;
use Silecust\WebShop\Form\MasterData\Employee\DTO\EmployeeDTO;
use Silecust\WebShop\Form\MasterData\Employee\EmployeeCreateForm;
use Silecust\WebShop\Form\MasterData\Employee\EmployeeEditForm;
use Silecust\WebShop\Repository\EmployeeRepository;
use Silecust\WebShop\Service\Component\UI\Search\SearchEntityInterface;
use Silecust\WebShop\Service\MasterData\Employee\Mapper\EmployeeDTOMapper;
use Doctrine\ORM\EntityManagerInterface;
use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EmployeeController extends EnhancedAbstractController
{

    #[Route('/admin/employee/create', 'employee_create')]
    public function create(EmployeeDTOMapper      $employeeDTOMapper,
                           EntityManagerInterface $entityManager,
                           Request                $request,
                           ValidatorInterface     $validator
    ): Response
    {
        $employeeDTO = new EmployeeDTO();

        $form = $this->createForm(EmployeeCreateForm::class, $employeeDTO);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var EmployeeDTO $data */
            $data = $form->getData();

            $employeeEntity = $employeeDTOMapper->mapToEntityForCreate($data);

            // todo:
            $errors = $validator->validate($employeeEntity);

            if (count($errors) == 0) {   // perform some action...
                $entityManager->persist($employeeEntity);
                $entityManager->flush();


                $id = $employeeEntity->getId();

                $this->addFlash(
                    'success', "Employee created successfully"
                );

                return new Response(
                    serialize(
                        ['id' => $id, 'message' => "Employee created successfully"]
                    ), 200
                );
            }
        }
        return $this->render('@SilecustWebShop/master_data/employee/employee_create.html.twig', ['form' => $form]);
    }


    #[Route('/admin/employee/{id}/edit', name: 'sc_admin_employee_edit')]
    public function edit(EntityManagerInterface $entityManager,
                         EmployeeRepository     $employeeRepository,
                         EmployeeDTOMapper      $employeeDTOMapper,
                         Request                $request, int $id,
                         ValidatorInterface     $validator
    ): Response
    {
        $employee = $employeeRepository->find($id);


        if (!$employee) {
            throw $this->createNotFoundException('No Employee found for id ' . $id);
        }


        $form = $this->createForm(EmployeeEditForm::class, $employeeDTOMapper->mapToDTOFromEntity($employee));


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var EmployeeDTO $data */
            $data = $form->getData();

            $employee = $employeeDTOMapper->mapToEntityForEdit($data);
            // todo:
            $errors = $validator->validate($employee);

            if (count($errors) == 0) {
                // perform some action...
                $entityManager->persist($employee);
                $entityManager->flush();

                $id = $employee->getId();

                $this->addFlash(
                    'success', "Employee updated successfully"
                );

                return new Response(
                    serialize(
                        ['id' => $id, 'message' => "Employee updated successfully"]
                    ), 200
                );
            }
        }
        return $this->render('@SilecustWebShop/master_data/employee/employee_edit.html.twig', ['form' => $form]);
    }

    #[Route('/admin/employee/{id}/display', name: 'sc_admin_employee_display')]
    public function display(EmployeeRepository $employeeRepository, int $id): Response
    {
        $employee = $employeeRepository->find($id);
        if (!$employee) {
            throw $this->createNotFoundException('No Employee found for id ' . $id);
        }

        $displayParams = ['title' => 'Employee',
            'link_id' => 'id-employee',
            'editButtonLinkText' => 'Edit',
            'fields' => [['label' => 'First Name',
                'propertyName' => 'firstName',
                'link_id' => 'id-display-employee'],
                ['label' => 'Last Name',
                    'propertyName' => 'lastName'],]];

        return $this->render(
            '@SilecustWebShop/master_data/employee/employee_display.html.twig',
            ['entity' => $employee, 'params' => $displayParams]
        );

    }

    #[Route('/admin/employee/list', name: 'sc_admin_employee_list')]
    public function list(EmployeeRepository $employeeRepository,
                         PaginatorInterface    $paginator,
                         SearchEntityInterface $searchEntity,
                         Request               $request): Response
    {

        $listGrid = ['title' => 'Employee',
            'link_id' => 'id-employee',
            'columns' => [['label' => 'Name',
                'propertyName' => 'firstName',
                'action' => 'display',],
            ],
            'createButtonConfig' => ['link_id' => ' id-create-Employee',
                'function' => 'employee',
                'anchorText' => 'create Employee']];

        $query = $searchEntity->getQueryForSelect($request, $productRepository);

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
}