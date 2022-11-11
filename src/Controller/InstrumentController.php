<?php

namespace App\Controller;

use App\Entity\Instrument;
use App\Form\InstrumentType;
use App\Repository\InstrumentRepository;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InstrumentController extends AbstractController
{
    /**
     * Lists all todo entities.
     *
     * @Route("/instrument/list", name = "instrument_list", methods="GET")
     */
    public function listAction(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $instruments = $entityManager->getRepository(Instrument::class)->findAll();

        dump($instruments);

        return $this->render('instrument/index.html.twig',
            ['instruments' => $instruments]
        );
    }

    /**
     * Finds and displays a todo entity.
     *
     * @Route("/instrument/{id}", name="instrument_show", requirements={ "id": "\d+"}, methods="GET")
     */
    public function showAction(Instrument $instrument): Response
    {
        return $this->render('instrument/show.html.twig',
            ['instrument' => $instrument]
        );
    }

    /**
     * @Route("/instrument/new", name="instrument_new", methods={"GET", "POST"})
     * @IsGranted("ROLE_USER")
     */
    function new (Request $request, InstrumentRepository $instrumentRepository): Response {
        $instrument = new Instrument();
        $form = $this->createForm(InstrumentType::class, $instrument);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imagefile = $instrument->getImageFile();

            if ($imagefile) {
                $mimetype = $imagefile->getMimeType();
                //$instrument->setContentType($mimetype);
            }

            $instrumentRepository->add($instrument, true);

            $this->addFlash('success', $instrument->getName() . ' (id=' . $instrument->getId() . ') a bien été ajouté !');
            return $this->redirectToRoute('instrument_list', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('instrument/new.html.twig', [
            'instrument' => $instrument,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/instrument/{id}/edit", name="instrument_edit", methods={"GET", "POST"})
     * @IsGranted("ROLE_USER")
     */
    public function edit(Request $request, Instrument $instrument, InstrumentRepository $instrumentRepository): Response
    {
        $form = $this->createForm(InstrumentType::class, $instrument);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imagefile = $instrument->getImageFile();

            if ($imagefile) {
                $mimetype = $imagefile->getMimeType();
                //$instrument->setContentType($mimetype);
            }

            $instrumentRepository->add($instrument, true);

            $this->addFlash('success', $instrument->getName() . ' (id=' . $instrument->getId() . ') a bien été modifié !');
            return $this->redirectToRoute('instrument_list', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('instrument/edit.html.twig', [
            'instrument' => $instrument,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/instrument/{id}", name="instrument_delete", methods={"POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Instrument $instrument, InstrumentRepository $instrumentRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $instrument->getId(), $request->request->get('_token'))) {
            $this->addFlash('success', $instrument->getName() . ' (id=' . $instrument->getId() . ') a bien été supprimé !');
            $intrumentRepository->remove($instrument, true);

        }

        return $this->redirectToRoute('instrument_list', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * Mark a task as current priority in the user's session
     *
     * @Route("/instrument/mark/{id}", name="instrument_mark", requirements={ "id": "\d+"}, methods="GET")
     */
    public function markAction(Request $request, Instrument $instrument): Response
    {
        $reserved = $request->getSession()->get('reserved');
        if (!is_array($reserved)) {
            $reserved = array();
        }
        $id = $instrument->getId();
        if (!in_array($id, $reserved)) {
            $reserved[] = $id;
        } else {
            $reserved = array_diff($reserved, array($id));
        }
        $request->getSession()->set('reserved', $reserved);
        return $this->redirectToRoute('instrument_list');
    }
}
