<?php

namespace App\Service;

use App\Entity\Contact;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ContactFileGenerator
{
    private $serializer;
    private $params;

    public function __construct(SerializerInterface $serializer, ParameterBagInterface $params)
    {
        $this->serializer = $serializer;
        $this->params = $params;
    }

    public function generateJsonFile(Contact $contact): string 
    {
        // Convert Contact object to JSON
        $jsonData = $this->serializer->serialize($contact, 'json', ['groups' => 'contact']);
    
        // Generate a unique file name
        $fileName = uniqid().'.json';
        
        // Path to directory where file is stored (outside public folder)
        $storagePath = $this->params->get('kernel.project_dir').'/var/contact_requests';
    
        // Check if the directory exists, if not create it
        if (!file_exists($storagePath)) {
            mkdir($storagePath, 0777, true);
        }
    
        // Save JSON file
        file_put_contents($storagePath.'/'.$fileName, $jsonData);

        return $fileName;
    }
    
    public function removeJsonFile(Contact $contact): void
    {
        if ($contact->getJsonFile()) {
            $filePath = $this->getJsonFilePath($contact);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
    }

    private function getJsonFilePath(Contact $contact): string
    {
        return $this->params->get('kernel.project_dir').'/var/contact_requests/'.$contact->getJsonFile();
    }
}