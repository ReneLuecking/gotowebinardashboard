<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class DashboardController extends AbstractController
{
    /**
     * @Route("/", name="home")
     *
     * @return Response
     */
    public function home() {
        $number = rand(0, 100);

        return $this->render('dashboard/home.html.twig', array(
            'number' => $number,
        ));
    }
}
