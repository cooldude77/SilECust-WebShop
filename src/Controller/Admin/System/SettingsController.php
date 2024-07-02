<?php

namespace App\Controller\Admin\System;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SettingsController extends AbstractController
{


    #[Route('/system/settings', 'system_settings')]
    public function list(): Response
    {
        return $this->render('admin/employee/settings/settings_list.html.twig');
    }

    public function logoForm(): Response
    {
        $form = $this->createFormBuilder()->create('logo', FileType::class)->getForm();


        return $this->render('admin/ui/panel/section/content/edit/edit.html.twig', ['form' => $form]
        );

    }


}