<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class UsersController
 * @package App\Controller
 * @Route("/users")
 */
class UsersController extends Controller
{
    /**
     * @Route("/", name="users_index")
     * @Route("/search/{query}", name="users_search")
     */
    public function index(Request $request, UserRepository $userRepository, $query = null)
    {
        $page = $request->query->getInt('page', 1);
        if($page == 0) $page = 1;

        if($query !== null) $users = $userRepository->search($query, $page);
        else $users = $userRepository->getPage($page);


        return $this->render('users/index.html.twig', [
            'page' => $page,
            'totalPages' => ceil(count($users) / 20),
            'users' => $users
        ]);
    }

    /**
     * @Route("/edit/{id}", name="users_edit")
     * @ParamConverter(name="user", class="App:User")
     * @param Request $request
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function edit(Request $request, User $user){
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Modifications enregistrées !');
            return $this->redirectToRoute('users_index');
        }
        return $this->render('users/edit.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }
}
