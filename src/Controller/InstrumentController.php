<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Instrument;
use App\Entity\Local;
use Doctrine\Persistence\ManagerRegistry;

class InstrumentController extends AbstractController
{
    /**
     * @Route("/instrument_twig", name="app_instrument")
     */
    public function index(): Response
    {
        return $this->render('instrument/index.html.twig', [
            'controller_name' => 'InstrumentController',
        ]);
    }


    /**
     * Lists all todo entities.
     *
     * @Route("/list", name = "instrument_list", methods="GET")
     * @Route("/index", name="instrument_index", methods="GET")
     */
    public function listAction(ManagerRegistry $doctrine)
    {
        $htmlpage = '<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Instruments list!</title>
    </head>
    <body>
        <h1>Instrument list</h1>
        <p>Here are all your Instruments:</p>
        <ul>';
        
        $entityManager= $doctrine->getManager();
        $instruments = $entityManager->getRepository(Instrument::class)->findAll();
        foreach($instruments as $instrument) {
           $htmlpage .= '<li>
            <a href="/instrument?id='.$instrument->getid().'">'.$instrument->getName().'</a></li>';
         }
        $htmlpage .= '</ul>';

        $htmlpage .= '</body></html>';
        
        return new Response(
            $htmlpage,
            Response::HTTP_OK,
            array('content-type' => 'text/html')
            );
    }


    /**
     * Finds and displays a todo entity.
     *
     * @Route("/instrument", name="instrument_show", requirements={ "id": "\d+"}, methods="GET")
     */
    public function showAction(ManagerRegistry $doctrine)
    {
        $htmlpage = '<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Instruments list!</title>
    </head>
    <body>
        <h1>Instrument</h1>
        <p>Here are your Instrument:</p>
        <ul>';
        
        $entityManager= $doctrine->getManager();
        $instruments = $entityManager->getRepository(Instrument::class)->findAll();
        foreach($instruments as $instrument) {
            if (isset($_GET['id']) && $instrument->getId() == $_GET['id']) {
                $htmlpage .= '<p>'.$instrument->getName().' ; '.$instrument->getLieu()->getName().' ; '.$instrument->getOwner()->getName().'</p>';
            }
         }
        $htmlpage .= '<a href="/list">Retour</a>';
        $htmlpage .= '</body></html>';
        
        return new Response(
            $htmlpage,
            Response::HTTP_OK,
            array('content-type' => 'text/html')
            );
    }
}
