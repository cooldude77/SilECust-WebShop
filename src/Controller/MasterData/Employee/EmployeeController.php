<?php
// src/controller/employeeController.php
namespace App\Controller\MasterData\Employee;

// ...
use App\Entity\User;
use App\Form\MasterData\Employee\EmployeeCreateForm;
use App\Form\MasterData\Employee\EmployeeEditForm;
use App\Form\MasterData\Employee\DTO\EmployeeDTO;
use App\Repository\EmployeeRepository;
use App\Service\MasterData\Employee\Mapper\EmployeeDTOMapper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class EmployeeController extends AbstractController
{

    #[\Symfony\Component\Routing\Attribute\Route('/employee/create', 'employee_create')]
    public function create(EmployeeDTOMapper $employeeDTOMapper,
        EntityManagerInterface $entityManager, Request $request,
    UserPasswordHasherInterface $userPasswordHasher
    ): Response {
        $employeeDTO = new EmployeeDTO();
        $form = $this->createForm(
            EmployeeCreateForm::class, $employeeDTO
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $employeeEntity = $employeeDTOMapper->mapToEntityForCreate($form->getData());

            // perform some action...
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

        $formErrors = $form->getErrors(true);
        return $this->render('master_data/employee/employee_create.html.twig', ['form' => $form]);
    }


    #[Route('/employee/{id}/edit', name: 'employee_edit')]
    public function edit(EntityManagerInterface $entityManager,
        EmployeeRepository $employeeRepository, EmployeeDTOMapper $employeeDTOMapper,
        Request $request, int $id
    ): Response {
        $employee = $employeeRepository->find($id);


        if (!$employee) {
            throw $this->createNotFoundException('No Employee found for id ' . $id);
        }

        $employeeDTO = new EmployeeDTO();
        $employeeDTO->id = $id;

        $form = $this->createForm(EmployeeEditForm::class, $employeeDTO);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $employee = $employeeDTOMapper->mapToEntityForEdit($form, $employee);
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

        return $this->render('master_data/employee/employee_edit.html.twig', ['form' => $form]);
    }

    #[Route('/employee/{id}/display', name: 'employee_display')]
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
            'master_data/employee/employee_display.html.twig',
            ['entity' => $employee, 'params' => $displayParams]
        );

    }

    #[\Symfony\Component\Routing\Attribute\Route('/employee/list', name: 'employee_list')]
    public function list(EmployeeRepository $employeeRepository): Response
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

        $employees = $employeeRepository->findAll();
        return $this->render(
            'admin/ui/panel/section/content/list/list.html.twig',
            ['entities' => $employees, 'listGrid' => $listGrid]
        );
    }
}