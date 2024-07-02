<?php
// src/Controller/ProductController.php
namespace App\Controller\Security\External\Credentials;

// ...
use App\Exception\Security\User\Customer\UserNotAssociatedWithACustomerException;
use App\Exception\Security\User\UserNotLoggedInException;
use App\Service\Security\User\Customer\CustomerFromUserFinder;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginManagementController extends AbstractController
{


    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render(
            'security/external/user/login/page/login_page.html.twig', ['last_username' =>
                                                                           $lastUsername,
                                                                       'error' => $error,]
        );
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new LogicException(
            'This method can be blank - it will be intercepted by the logout key on your firewall.'
        );
    }


    #[Route(path: '/where/to', name: 'user_where_to_go_after_login')]
    public function whereToAfterLogin(CustomerFromUserFinder $customerFromUserFinder
    ): Response {

        try {
            $customerFromUserFinder->getLoggedInCustomer();
            return $this->redirectToRoute('home');

        } catch (UserNotLoggedInException $e) {
            return new Response("Not Authorized", 403);
        } catch (UserNotAssociatedWithACustomerException) {
            return $this->redirectToRoute('admin_panel');
        }
    }
}