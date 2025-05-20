<?php

namespace Silecust\WebShop\Controller\Admin\Settings\System;

use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Silecust\WebShop\Form\Admin\Settings\System\LogoForm;
use Silecust\WebShop\Form\Common\File\DTO\FileDTO;
use Silecust\WebShop\Service\Admin\Employee\Settings\System\Settings;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SettingsSystemController extends EnhancedAbstractController
{

    public function __construct(private readonly EventDispatcherInterface $eventDispatcher,
                                private readonly Settings                 $settings)
    {
        parent::__construct($eventDispatcher);
    }

    #[Route('/settings/system', 'sc_admin_settings_system')]
    public function list(Request $request, Settings $settingsService): Response
    {
        $logoForm = $this->saveLogo($request);

        return $this->render('@SilecustWebShop/admin/employee/settings/system/settings_system.html.twig',
            ['logoForm' => $logoForm]);
    }

    /**
     * @param FileDTO $fileDTO
     * @return FormInterface
     * @throws ORMException
     * @throws OptimisticLockException
     */
    private function saveLogo(Request $request): FormInterface
    {
        $logoForm = $this->createForm(LogoForm::class);
        $logoForm->handleRequest($request);

        if ($logoForm->isSubmitted() && $logoForm->isValid()) {
            try {
                $data = $logoForm->getData()['fileDTO'];
                $this->settings->saveLogo($data);
                $this->addFlash("Success", "Logo Saved");
            } catch (OptimisticLockException|ORMException $e) {
                $this->addFlash("Error", "Database Error");
            }

        }
        return $logoForm;
    }

}