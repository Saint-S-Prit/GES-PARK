<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccidentController extends AbstractController
{
    /**
     * @Route("/accident", name="accident")
     */
    public function index(): Response
    {
        return $this->render('accident/index.html.twig', [
            'controller_name' => 'AccidentController',
        ]);
    }
}
