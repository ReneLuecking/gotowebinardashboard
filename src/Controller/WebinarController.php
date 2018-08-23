<?php


namespace App\Controller;


use App\Entity\Webinar;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WebinarController extends AbstractController
{
    /**
     * @Route("/webinar/", name="webinar_index")
     *
     * @return Response
     */
    public function index()
    {
        $organizers = $this->getDoctrine()
            ->getRepository(Webinar::class)
            ->findAll();

        return $this->render('organizer/index.html.twig', array(
            'organizers' => $organizers,
        ));
    }
}
