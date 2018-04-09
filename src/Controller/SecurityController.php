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
     * @Route("/lost_password", name="lost_password")
     */
    public function adressMailVerification(Request $request,\Swift_Mailer $mailer)
    {
        $report="";
        $email = $request->get('email');
        if (!empty($email)){
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository("App:User")->findOneBy(['email' => $email]);
            if (!empty($user)){
                $report = "nous avons envoyer un mail a cette adresse";
                //generate token and send email
                $token = hash('sha256', $user->getEmail().rand(0, 999));
                $user->setConfirmationToken($token);
                //creating message with token
                $message = (new \Swift_Message('Hello Email'))
                    ->setFrom('saidi.tayeb@hotmail.com')
                    ->setTo($user->getEmail())
                    ->setBody($this->renderView(
                        'mail/confirm_email.html.twig', [
                        'token' => $token,
                    ]),
                        'text/html'
                    );
                // Send message with token
                $mailer->send($message);
            } else {
                $report = "cette adress mail n'existe pas";
            }
        }
        return $this->render('adressMailVerification.html.twig', ['report'=>$report]);
    }

    /**
     * @Route("/reset_password/{token}", name="confirmMail")
     */
    public function confirmEmailAction($token, UserPasswordEncoderInterface $passwordEncoder, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository("App:User")->findOneBy(['confirmationToken' => $token]);
        if (!empty($user) && ($request->get('_password') != '') && ($request->get('_password') == $request->get('_password_repeated')))
        {
            $plainPassword = $request->get('_password');
            $password = $passwordEncoder->encodePassword($user, $plainPassword);
            $user->setPassword($password);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('confirmMail');
        }
        return $this->render('resetPassword.html.twig');
    }

//    /**
//     * @Route("/reset_password/reset", name="reset_password")
//     */
//    public function resetPassword(Request $request, UserPasswordEncoderInterface $passwordEncoder)
//    {
//        //read token, search the adress related with it.
//
//        $em = $this->getDoctrine()->getManager();
//        $user = $em->getRepository("App:User")->find('1');
//        if (!empty($user) && ($request->get('_password') != '') && ($request->get('_password') == $request->get('_password_repeated')))
//        {
//            $plainPassword = $request->get('_password');
//            $password = $passwordEncoder->encodePassword($user, $plainPassword);
//            $user->setPassword($password);
//            $em = $this->getDoctrine()->getManager();
//            $em->persist($user);
//            $em->flush();
//            return $this->redirectToRoute('resetPassword');
//        }
//        return $this->render('resetPassword.html.twig');
//    }


}