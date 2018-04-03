<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\User\UserRegistration;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{
    /**
     * @param Request                      $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     *
     * @Route("/register", name="user_registration")
     *
     * @return Response
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(UserRegistration::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $user->setRoles(['ROLE_USER']);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->render('home.html.twig');
        }
        return $this->render(
            'register.html.twig',
            ['form' => $form->createView()]
        );
    }
    /**
     * @param Request             $request
     * @param AuthenticationUtils $authUtils
     * @Route("/login", name="user_login")
     *
     * @return Response
     */
    public function login(AuthenticationUtils $authUtils): Response
    {
        $error = $authUtils->getLastAuthenticationError();
        $lastUsername = $authUtils->getLastUsername();
        return $this->render(
            'login.html.twig',
            [
                'last_username' => $lastUsername,
                'error' => $error,
            ]
        );
    }
    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction()
    {
    }
}
