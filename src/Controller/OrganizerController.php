<?php

namespace App\Controller;

use App\Entity\Organizer;
use App\Form\OrganizerType;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrganizerController extends AbstractController
{
    /**
     * @Route("/organizer/", name="organizer_index")
     *
     * @return Response
     */
    public function index()
    {
        $organizers = $this->getDoctrine()
            ->getRepository(Organizer::class)
            ->findAll();

        return $this->render('organizer/index.html.twig', array(
            'organizers' => $organizers,
        ));
    }

    /**
     * @Route("/organizer/add/", name="organizer_add")
     *
     * @param Request         $request
     * @param LoggerInterface $logger
     *
     * @return Response
     */
    public function add(Request $request, LoggerInterface $logger)
    {
        $organizer      = new Organizer();
        $responseKeyUrl = '';

        $form = $this->createForm(OrganizerType::class, $organizer);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Organizer $organizer */
            $organizer = $form->getData();

            if (empty($organizer->getResponsekey())) {
                $responseKeyUrl =
                    "https://api.getgo.com/oauth/v2/authorize?client_id={$organizer->getconsumerkey()}&response_type=code";
            } else {
                $this->requestFullOrganizer($organizer, $logger);

                return $this->redirectToRoute('organizer_index');
            }
        }

        return $this->render('organizer/add.html.twig', array(
            'form'           => $form->createView(),
            'responseKeyUrl' => $responseKeyUrl,
        ));
    }

    private function requestFullOrganizer(Organizer $organizer, LoggerInterface $logger)
    {
        $auth = base64_encode($organizer->getconsumerkey() . ':' . $organizer->getconsumersecret());

        $headers = array(
            'Authorization: Basic ' . $auth,
            'Accept: application/json',
            'Content-Type: application/x-www-form-urlencoded',
        );

        $data = http_build_query(array(
            'grant_type' => 'authorization_code',
            'code'       => $organizer->getResponsekey(),
        ));

        $curl = curl_init('https://api.getgo.com/oauth/v2/token');

        if (false === $curl) {
            $logger->error('Curl could not be initiated.');
            return;
        }

        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        if ($proxy = getenv('https_proxy')) {
            curl_setopt($curl, CURLOPT_PROXY, $proxy);

            if ($noproxy = getenv('no_proxy')) {
                curl_setopt($curl, CURLOPT_NOPROXY, $noproxy);
            }
        }

        $result     = curl_exec($curl);
        $httpCode   = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($curl);
        curl_close($curl);

        if ('2' === substr($httpCode, 0, 1)) {
            $result = json_decode($result);

            $organizer->setaccesstoken($result->access_token);
            $organizer->setexpiresin(time() + $result->expires_in);
            $organizer->setrefreshtoken($result->refresh_token);
            $organizer->setRefreshexpiresin(time() + 30 * 86400);
            $organizer->setorganizerkey($result->organizer_key);
            $organizer->setaccountkey($result->account_key);
            $organizer->setFirstname($result->firstName);
            $organizer->setLastname($result->lastName);
            $organizer->setEmail($result->email);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($organizer);
            $entityManager->flush();
        } else {
            $logger->error("Curl error (code: {$httpCode}): {$curl_error}");
        }
    }
}
