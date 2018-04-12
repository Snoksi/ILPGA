<?php
namespace App\Controller\Admin;

use App\Form\Security\UserRoles;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
/**
 * Class AdminDashboardController.
 *
 * @Route("/")
 */
class AdminDashboardController extends Controller
{
    /**
     * @Route("/users", name="admin_dash")
     *
     * @return Response
     */
    public function index(): Response
    {

        return $this->redirectToRoute('tests_index', ['id'=>null]);
    }

}