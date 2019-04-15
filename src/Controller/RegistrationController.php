<?php

namespace App\Controller;

use App\Entity\Registration;
use App\Form\RegistrationType;
use App\Repository\RegistrationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/registration")
 */
class RegistrationController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @Route("/", name="registration_index", methods={"GET"})
     */
    public function index(RegistrationRepository $registrationRepository): Response
    {
        if ($this->security->isGranted('ROLE_ADMIN') || $this->security->isGranted('ROLE_LEADER') || $this->security->isGranted('ROLE_STUDENT')){
        return $this->render('registration/index.html.twig', [
            'registrations' => $registrationRepository->findAll(),
        ]);
        } else {
            return $this->redirectToRoute('default');
        }
    }

    /**
     * @Route("/new", name="registration_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        if ($this->security->isGranted('ROLE_ADMIN') || $this->security->isGranted('ROLE_LEADER')){
        $registration = new Registration();
        $form = $this->createForm(RegistrationType::class, $registration);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($registration);
            $entityManager->flush();

            return $this->redirectToRoute('registration_index');
        }

        return $this->render('registration/new.html.twig', [
            'registration' => $registration,
            'form' => $form->createView(),
        ]);
        } else {
            return $this->redirectToRoute('default');
        }
    }

    /**
     * @Route("/{id}", name="registration_show", methods={"GET"})
     */
    public function show(Registration $registration): Response
    {
        if ($this->security->isGranted('ROLE_ADMIN') || $this->security->isGranted('ROLE_LEADER') || $this->security->isGranted('ROLE_STUDENT')){
        return $this->render('registration/show.html.twig', [
            'registration' => $registration,
        ]);
        } else {
            return $this->redirectToRoute('default');
        }
    }

    /**
     * @Route("/{id}/edit", name="registration_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Registration $registration): Response
    {
        if ($this->security->isGranted('ROLE_ADMIN') || $this->security->isGranted('ROLE_LEADER')){
        $form = $this->createForm(RegistrationType::class, $registration);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('registration_index', [
                'id' => $registration->getId(),
            ]);
        }

        return $this->render('registration/edit.html.twig', [
            'registration' => $registration,
            'form' => $form->createView(),
        ]);
        } else {
            return $this->redirectToRoute('default');
        }
    }

    /**
     * @Route("/{id}", name="registration_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Registration $registration): Response
    {
        if ($this->security->isGranted('ROLE_ADMIN') || $this->security->isGranted('ROLE_LEADER')){
        if ($this->isCsrfTokenValid('delete'.$registration->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($registration);
            $entityManager->flush();
        }

        return $this->redirectToRoute('registration_index');
        } else {
            return $this->redirectToRoute('default');
        }
    }
}
