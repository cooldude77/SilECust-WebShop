<?php
// src/Controller/ProductController.php
namespace Silecust\WebShop\Controller\Security\External\Credentials\Login;

// ...
use Silecust\WebShop\Exception\Security\User\UserNotAuthorized;
use Silecust\WebShop\Exception\Security\User\UserNotLoggedInException;
use Silecust\WebShop\Service\Security\User\Customer\CustomerFromUserFinder;
use Silecust\WebShop\Service\Security\User\Employee\EmployeeFromUserFinder;
use LogicException;
use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginManagementController extends EnhancedAbstractController
{


    #[Route(path: '/login', name: 'sc_app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render(
            '@SilecustWebShop/security/external/user/login/page/login_page.html.twig', ['last_username' =>
                                                                           $lastUsername,
                                                                       'error' => $error,]
        );
    }

    #[Route(path: '/logout', name: 'sc_app_logout')]
    public function logout(): void
    {
        throw new LogicException(
            'This method can be blank - it will be intercepted by the logout key on your firewall.'
        );
    }


    /**
     * @throws UserNotAuthorized
     * @throws UserNotLoggedInException
     */
    #[Route(path: '/where/to', name: 'sc_user_where_to_go_after_login')]
    public function whereToAfterLogin(CustomerFromUserFinder $customerFromUserFinder,
        EmployeeFromUserFinder $employeeFromUserFinder,
    Security $security
    ): Response
    {

        if($security->getUser() == null)
            throw new UserNotLoggedInException();

        if ($customerFromUserFinder->isLoggedInUserAlsoACustomer()) {
            return $this->redirectToRoute('sc_home');
        } elseif ($employeeFromUserFinder->isLoggedInUserAlsoAEmployee()) {
            return $this->redirectToRoute('sc_admin_panel', ['_function' => 'dashboard']);
        }

        throw new UserNotAuthorized($security->getUser());
    }
}