<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Form\UserRoleType;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class UsersController
 * @package App\Controller\Api
 * @Route("/api/users")
 */
class UsersController extends Controller
{
    /**
     * @Rest\Delete("/delete/{id}", name="users_delete")
     * @Rest\View()
     * @ParamConverter("user", class="App:User")
     * @param User $user
     * @return array
     */
    public function delete(User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        return [
            'success' => true
        ];
    }

    /**
     * @Rest\Post("/edit_role/{id}", name="users_edit_role")
     * @Rest\View()
     * @ParamConverter("user", class="App:User")
     * @param User $user
     * @return User|\Symfony\Component\Form\FormInterface
     */
    public function edit_role(Request $request, User $user)
    {
        $form = $this->createForm(UserRoleType::class, $user);
        $form->submit($request->request->all());

        if($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return [
                'roles' => $user->getRoles()
            ];
        }

        return $form;
    }
}
