<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Form\QuestionType;
use App\Form\SearchRequestsType;
use App\Repository\ContactRepository;
use App\Service\ContactRequestFileGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/contact')]
class ContactController extends AbstractController
{
    #[Route('/', name: 'app_contact_form')]
    public function index(
        Request $request,
        EntityManagerInterface $entityManager,
        ContactRequestFileGenerator $contactRequestFileGenerator,
    ): Response {
        $contact = new Contact();
        if ($this->getUser()) {
            $contact->setEmail($this->getUser()->getEmail());
        }
        $contact->setAuthor($this->getUser());
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($contact);
            $entityManager->flush();

            $contactRequestFileGenerator->generateJsonFile($contact);
            $this->addFlash('success', 'Your contact request was submitted successfully!');

            return $this->redirectToRoute('app_contact_success', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('contact/contact_form.html.twig', [
            'contact' => $contact,
            'form' => $form,
        ]);
    }

    #[Route('/success', name: 'app_contact_success')]
    public function success(): Response
    {
        return $this->render('contact/contact_success.html.twig');
    }

    #[Route('/edit/{id}', name: 'app_contact_edit')]
    public function editContact(
        Request $request, Contact $contact, EntityManagerInterface $entityManager,
        ContactRequestFileGenerator $contactRequestFileGenerator
    ): Response
    {
        if (!$this->getUser()) {
            $this->addFlash('danger', 'You must be logged in to edit a contact request!');
            return $this->redirectToRoute('app_login');
        }
        $form = $this->createForm(QuestionType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact->setQuestion($form->get('question')->getData());
            $contact->setIsEdited(true);

            $entityManager->persist($contact);
            $entityManager->flush();

            $contactRequestFileGenerator->generateJsonFile($contact);

            $this->addFlash('success', 'Your contact request was updated successfully!');

            return $this->redirectToRoute('app_contact_request', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('contact/contact_edit.html.twig', [
            'contact' => $contact,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/contact-request', name: 'app_contact_request')]
    public function contactRequest(Request $request, ContactRepository $contactRepository): Response
    {
        if ($this->getUser()) {
            return $this->render('contact/search_requests.html.twig', [
                'contacts' => $contactRepository->findBy(['author' => $this->getUser()]),
            ]);
        }

        $form = $this->createForm(SearchRequestsType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')->getData();

            return $this->redirectToRoute('app_contact_request', ['email' => $email]);
        }

        return $this->render('contact/search_requests.html.twig', [
            'form' => $form->createView(),
            'contacts' => $request->get('email') ? $contactRepository->findBy(['email' => $request->get('email')]) : [],
        ]);
    }
}