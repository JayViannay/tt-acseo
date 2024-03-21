<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'app_admin_index')]
    public function index(ContactRepository $contactRepository, Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $maxPage = ceil($contactRepository->count([]) / 10);
        return $this->render('admin/index.html.twig', [
            'contacts' => $contactRepository->paginateContacts($request),
            'maxPage' => $maxPage,
            'page' => $page,
        ]);
    }

    #[Route('/reply-contact/{id}', name: 'app_admin_contact_reply')]
    public function reply(Contact $contact, EntityManagerInterface $entityManager): Response
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $contact->setReply($_POST['reply']);
            $contact->setUpdatedAt(new \DateTimeImmutable());
            $contact->setIsArchived(true);

            $entityManager->persist($contact);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_index');
        }

        return $this->render('admin/contact_reply.html.twig', [
            'contact' => $contact,
        ]);
    }

    #[Route('/contact/{id}', name: 'app_admin_contact_show')]
    public function show(Contact $contact): Response
    {
        return $this->render('admin/contact_show.html.twig', [
            'contact' => $contact,
        ]);
    }

    #[Route('/archive-contact/{id}', name: 'app_admin_contact_archive')]
    public function archive(Contact $contact, EntityManagerInterface $entityManager): Response
    {
        $contact->setIsArchived(true);
        $entityManager->persist($contact);
        $entityManager->flush();

        return $this->redirectToRoute('app_admin_index');
    }

    #[Route('/archived-contact', name: 'app_admin_contact_archived')]
    public function archived(Request $request, ContactRepository $contactRepository): Response
    {
        $page = $request->query->getInt('page', 1);
        $maxPage = ceil($contactRepository->count([]) / 10);

        return $this->render('admin/contact_archived.html.twig', [
            'contacts' => $contactRepository->paginateContactsArchived($request),
            'maxPage' => $maxPage,
            'page' => $page,
        ]);
    }
}
