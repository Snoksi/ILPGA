<?php
namespace App\Controller\Admin;
use App\Form\Security\UserRoles;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\Security\UserEdit;
use Symfony\Component\HttpFoundation\Request;
/**
 * Class AdminDashboardController.
 *
 * @Route("/")
 */
class AdminDashboardController extends Controller
{
    /**
     * @Route("/", name="admin_dash")
     *
     * @return Response
     */
    public function index(): Response
    {
        $user = $this->getUser();
        return $this->render('home.html.twig');
    }

    /**
     * @Route("/users", name="user_search")
     */
    public function searchAction() {
        $loggedUser = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $usersInfo = $em->getRepository("App:User")->findAll();
        return $this->render('users.html.twig', ['usersInfo'=>$usersInfo,'loggedUser'=>$loggedUser]
        );
    }
    /**
     * @Route("/users/{id}", name="users_show")
     */
    public function show($id,Request $request) {
        $em = $this->getDoctrine()->getManager();
        $usersInfo = $em->getRepository("App:User")->find($id);
        $form = $this->createForm(UserRoles::class, $usersInfo);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($usersInfo);
            $em->flush();
            return $this->render('home.html.twig');
        }
//        $ageplayer = $age->playerAge($playerDetails->getDob());
        return $this->render('show.html.twig', ['usersInfo'=>$usersInfo, 'form'=>$form->createView()]
        );
    }
}