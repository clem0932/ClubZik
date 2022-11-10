<?php

namespace App\Controller;

use App\Entity\Local;
use App\Form\LocalType;
use App\Repository\InstrumentRepository;
use App\Repository\LocalRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/local")
 */
class LocalController extends AbstractController
{
    /**
     * @Route("/", name="app_local_index", methods={"GET"})
     */
    public function index(LocalRepository $localRepository): Response
    {
        return $this->render('local/index.html.twig', [
            'locals' => $localRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_local_new", methods={"GET", "POST"})
     * @IsGranted("ROLE_USER")
     */
    function new (Request $request, LocalRepository $localRepository): Response {
        $local = new Local();
        $form = $this->createForm(LocalType::class, $local);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $localRepository->add($local, true);

            return $this->redirectToRoute('app_local_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('local/new.html.twig', [
            'local' => $local,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_local_show", methods={"GET"})
     */
    public function show(Local $local): Response
    {
        return $this->render('local/show.html.twig', [
            'local' => $local,
        ]);
    }

    /**
     * @Route("/{id}/instrument", name="app_local_instrument_show", methods={"GET"})
     */
    public function showInstrument(Local $local, InstrumentRepository $instrumentRepo): Response
    {
        $instruments = [];
        foreach ($instrumentRepo->findAll() as $instrument) {
            if ($instrument->getLieu() === $local) {
                $instruments[] = $instrument;
            }
        }
        return $this->render('local/show_instrument.html.twig', [
            'instruments' => $instruments,
            'local' => $local,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_local_edit", methods={"GET", "POST"})
     * @IsGranted("ROLE_USER")
     */
    public function edit(Request $request, Local $local, LocalRepository $localRepository): Response
    {
        $form = $this->createForm(LocalType::class, $local);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $localRepository->add($local, true);

            return $this->redirectToRoute('app_local_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('local/edit.html.twig', [
            'local' => $local,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_local_delete", methods={"POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Local $local, LocalRepository $localRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $local->getId(), $request->request->get('_token'))) {
            $localRepository->remove($local, true);
        }

        return $this->redirectToRoute('app_local_index', [], Response::HTTP_SEE_OTHER);
    }
}
