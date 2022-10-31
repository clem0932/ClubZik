<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Instrument;
use App\Entity\Local;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\InstrumentRepository;
use App\Form\InstrumentType;
use Symfony\Component\HttpFoundation\Request;

class InstrumentController extends AbstractController
{
    /**
     * @Route("/", name = "home", methods="GET")
     */
    public function indexAction()
    {
        return $this->render('/home/index.html.twig');
    }

    /**
     * Lists all todo entities.
     *
     * @Route("/list", name = "instrument_list", methods="GET")
     */
    public function listAction(ManagerRegistry $doctrine): Response
    {
        $entityManager= $doctrine->getManager();
        $instruments = $entityManager->getRepository(Instrument::class)->findAll();

        dump($instruments);

        return $this->render('instrument/index.html.twig',
            [ 'instruments' => $instruments ]
            );
    }


    /**
     * Finds and displays a todo entity.
     *
     * @Route("/{id}", name="instrument_show", requirements={ "id": "\d+"}, methods="GET")
     */
    public function showAction(Instrument $instrument): Response
    {
        return $this->render('instrument/show.html.twig',
     [ 'instrument' => $instrument ]
     );
    }


    /**
     * @Route("/new", name="instrument_new", methods={"GET", "POST"})
     */
    public function new(Request $request, InstrumentRepository $instrumentRepository): Response
    {
        $instrument = new Instrument();
        $form = $this->createForm(InstrumentType::class, $instrument);
        $form->handleRequest($request); 

        if ($form->isSubmitted() && $form->isValid()) {
            $instrumentRepository->add($instrument, true);

            $this->addFlash('message', 'bien ajouté !');
            return $this->redirectToRoute('instrument_list', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('instrument/new.html.twig', [
            'instrument' => $instrument,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="instrument_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Instrument $instrument, InstrumentRepository $instrumentRepository): Response
    {
        $form = $this->createForm(InstrumentType::class, $instrument);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $instrumentRepository->add($instrument, true);

            return $this->redirectToRoute('instrument_list', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('instrument/edit.html.twig', [
            'instrument' => $instrument,
            'form' => $form,
        ]);
    }


    /**
     * @Route("/{id}", name="instrument_delete", methods={"POST"})
     */
    public function delete(Request $request, Instrument $instrument, InstrumentRepository $instrumentRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$instrument->getId(), $request->request->get('_token'))) {
            $intrumentRepository->remove($instrument, true);
        }

        return $this->redirectToRoute('instrument_list', [], Response::HTTP_SEE_OTHER);
    }
}
