<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\InstrumentRepository;
use App\Repository\ReservationRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReservationController extends AbstractController
{
    /**
     * @Route("/", name = "home", methods="GET")
     */
    public function index(ReservationRepository $reservationRepo)
    {
        $reservations = $reservationRepo->findBy(['user' => $this->getUser()]);
        return $this->render('/reservation/index.html.twig', [
            'reservations' => $reservations,
        ]);
    }

    /**
     * @Route("/reservation/list", name = "app_reservation_list", methods="GET")
     * @IsGranted("ROLE_USER")
     */
    public function listAction(ReservationRepository $reservationRepo)
    {
        return $this->render('/reservation/list.html.twig', [
            'reservations' => $reservationRepo->findAll(),
        ]);
    }

    /**
     * @Route("/reservation/new", name="app_reservation_new", methods={"GET", "POST"})
     * @IsGranted("ROLE_USER")
     */
    function new (Request $request, ReservationRepository $ReservationRepo, InstrumentRepository $instrumentRepo): Response {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        $inReservation = [];
        $inReservationIds = $request->getSession()->get('reserved');
        if (isset($inReservationIds)) {
            foreach ($inReservationIds as $id) {
                if ($instrumentRepo->findOneBy(['id' => $id]) != null) {
                    $instrumentName = $instrumentRepo->findOneBy(['id' => $id])->getName();
                    array_push($inReservation, $instrumentName);
                }
            }
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $reservation->setUser($this->getUser());
            foreach ($inReservationIds as $id) {
                if ($instrumentRepo->findOneBy(['id' => $id]) != null) {
                    $reservation->addInstrument($instrumentRepo->findOneBy(['id' => $id]));
                }
            }
            $ReservationRepo->add($reservation, true);
            $request->getSession()->set('reserved', []);
            $this->addFlash('success', $reservation->getName() . ' (id=' . $reservation->getId() . ') a bien été pris en compte !');

            return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservation/new.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
            'inReservation' => $inReservation,
        ]);
    }

    /**
     * @Route("/reservation/{id}", name="app_reservation_delete", methods={"POST"})
     * @IsGranted("ROLE_USER")
     */
    public function delete(Request $request, Reservation $reservation, ReservationRepository $reservationRepository, InstrumentRepository $instrumentRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $reservation->getId(), $request->request->get('_token'))) {
            if ($this->getUser() === $reservation->getUser() || $this->isGranted('ROLE_ADMIN')) {
                $instruments = $instrumentRepository->findBy(['reservation' => $reservation->getId()]);
                foreach ($instruments as $instrument) {
                    $instrumentRepository->remove($instrument, true);
                    $instrument->setReservation(null);
                    $instrumentRepository->add($instrument, true);
                }
                $reservationRepository->remove($reservation, true);
                $this->addFlash('success', $reservation->getName() . ' (id=' . $reservation->getId() . ') a bien été supprimé !');
            } else {
                $this->addFlash('error', 'Vous ne pouvez pas supprimer une réservation qui ne vous appartient pas !');
            }
        }

        return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
    }
}
