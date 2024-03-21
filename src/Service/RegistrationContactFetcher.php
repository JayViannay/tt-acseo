<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;

class RegistrationContactFetcher
{
    private $contactRepository;
    private $entityManager;

    public function __construct(ContactRepository $contactRepository, EntityManagerInterface $entityManager)
    {
        $this->contactRepository = $contactRepository;
        $this->entityManager = $entityManager;
    }

    public function fetchContact(string $email, User $user)
    {
        $contactsUser = $this->contactRepository->findBy(['email' => $email]);
        foreach ($contactsUser as $contact) {
            $this->setOwner($contact, $user);
            $this->entityManager->persist($contact);
        }
        $this->entityManager->flush();
    }

    public function setOwner($contact, $user)
    {
        $contact->setAuthor($user);
    }
}