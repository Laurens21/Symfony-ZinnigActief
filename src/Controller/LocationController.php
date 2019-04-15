<?php

namespace App\Controller;

use App\Entity\Location;
use App\Form\LocationType;
use App\Repository\LocationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/location")
 */
class LocationController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @Route("/", name="location_index", methods={"GET"})
     */
    public function index(LocationRepository $locationRepository): Response
    {
        if ($this->security->isGranted('ROLE_ADMIN') || $this->security->isGranted('ROLE_LEADER') || $this->security->isGranted('ROLE_STUDENT')){
        return $this->render('location/index.html.twig', [
            'locations' => $locationRepository->findAll(),
        ]);
        } else {
            return $this->redirectToRoute('default');
        }
    }

    /**
     * @Route("/new", name="location_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        if ($this->security->isGranted('ROLE_ADMIN')){
        $location = new Location();
        $form = $this->createForm(LocationType::class, $location);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($location);
            $entityManager->flush();

            return $this->redirectToRoute('location_index');
        }

        return $this->render('location/new.html.twig', [
            'location' => $location,
            'form' => $form->createView(),
        ]);
        } else {
            return $this->redirectToRoute('default');
        }
    }

    /**
     * @Route("/{id}", name="location_show", methods={"GET"})
     */
    public function show(Location $location): Response
    {
        if ($this->security->isGranted('ROLE_ADMIN') || $this->security->isGranted('ROLE_LEADER') || $this->security->isGranted('ROLE_STUDENT')){
        return $this->render('location/show.html.twig', [
            'location' => $location,
        ]);
        } else {
            return $this->redirectToRoute('default');
        }
    }

    /**
     * @Route("/{id}/edit", name="location_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Location $location): Response
    {
        if ($this->security->isGranted('ROLE_ADMIN')){

        $form = $this->createForm(LocationType::class, $location);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('location_index', [
                'id' => $location->getId(),
            ]);
        }

        return $this->render('location/edit.html.twig', [
            'location' => $location,
            'form' => $form->createView(),
        ]);
        } else {
            return $this->redirectToRoute('default');
        }
    }

    /**
     * @Route("/{id}", name="location_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Location $location): Response
    {
        if ($this->security->isGranted('ROLE_ADMIN')){
        if ($this->isCsrfTokenValid('delete'.$location->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($location);
            $entityManager->flush();
        }

        return $this->redirectToRoute('location_index');
        } else {
        return $this->redirectToRoute('default');
        }
    }
}
