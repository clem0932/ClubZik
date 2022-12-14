<?php

namespace App\Controller;

use App\Entity\Instrument;
use App\Entity\Owner;
use App\Form\OwnerType;
use App\Repository\InstrumentRepository;
use App\Repository\OwnerRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/owner")
 */
class OwnerController extends AbstractController
{
    /**
     * @Route("/", name="app_owner_index", methods={"GET"})
     */
    public function index(OwnerRepository $ownerRepository): Response
    {
        return $this->render('owner/index.html.twig', [
            'owners' => $ownerRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_owner_new", methods={"GET", "POST"})
     * @IsGranted("ROLE_USER")
     */
    function new (Request $request, OwnerRepository $ownerRepository): Response {
        $owner = new Owner();
        $form = $this->createForm(OwnerType::class, $owner);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ownerRepository->add($owner, true);
            $this->addFlash('success', $owner->getName() . ' (id=' . $owner->getId() . ') a bien été ajouté !');

            return $this->redirectToRoute('app_owner_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('owner/new.html.twig', [
            'owner' => $owner,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_owner_show", methods={"GET"})
     */
    public function show(Owner $owner): Response
    {
        return $this->render('owner/show.html.twig', [
            'owner' => $owner,
        ]);
    }

    /**
     * @Route("/{id}/instrument", name="app_owner_instrument_show", methods={"GET"})
     */
    public function showInstrument(Owner $owner, InstrumentRepository $instrumentRepo): Response
    {
        $instruments = [];
        foreach ($instrumentRepo->findAll() as $instrument) {
            if ($instrument->getOwner() === $owner) {
                $instruments[] = $instrument;
            }
        }
        return $this->render('owner/show_instrument.html.twig', [
            'instruments' => $instruments,
            'owner' => $owner,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_owner_edit", methods={"GET", "POST"})
     * @IsGranted("ROLE_USER")
     */
    public function edit(Request $request, Owner $owner, OwnerRepository $ownerRepository): Response
    {
        $form = $this->createForm(OwnerType::class, $owner);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ownerRepository->add($owner, true);
            $this->addFlash('success', $owner->getName() . ' (id=' . $owner->getId() . ') a bien été modifié !');

            return $this->redirectToRoute('app_owner_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('owner/edit.html.twig', [
            'owner' => $owner,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_owner_delete", methods={"POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Owner $owner, OwnerRepository $ownerRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $owner->getId(), $request->request->get('_token'))) {
            $this->addFlash('success', $owner->getName() . ' (id=' . $owner->getId() . ') a bien été supprimé !');
            $ownerRepository->remove($owner, true);
        }

        return $this->redirectToRoute('app_owner_index', [], Response::HTTP_SEE_OTHER);
    }
}
