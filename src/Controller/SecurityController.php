<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Security\UserResetPassword;
use App\Form\Security\UserRegistration;
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
            echo  $request->get('username');
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('home');
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
    public function logout()
    {
    }
    /**
     * @Route("/reset_password", name="reset_password")
     */
    public function adressMailVerification(Request $request)
    {
        $message="";
        $email = $request->get('email');
        if (!empty($email)){
            $em = $this->getDoctrine()->getManager();
            $search = $em->getRepository("App:User")->findBy(['email' => $email]);

            if (!empty($search)){
                $message = "nous avons envoyer un mail a cette adresse";
            } else {
                $message = "cette adress mail n'existe pas";
            }
        }
        return $this->render('adressMailVerification.html.twig', ['message'=>$message]);
    }
    /**
     * @Route("/reset_password/token", name="reset_password")
     */
    public function resetPassword(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        //read token, search the adress related with it.
        $em = $this->getDoctrine()->getManager();
        $search = $em->getRepository("App:User")->findBy(['email' => 'tayeb.saidi@hetic.net']);
        $user = new User();

        if (!empty($user) && ($request->get('_password') == $request->get('_password_repeated')))
        {
            $plainPassword = $request->get('_password');
            $password = $passwordEncoder->encodePassword($user, $plainPassword);
            $user->setPassword($password);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->render('home.html.twig');
        }return $this->render('resetPassword.html.twig');
    }
}
