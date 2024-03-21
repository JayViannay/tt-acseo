<?php

namespace App\Tests\Service;

use App\Entity\Contact;
use App\Service\ContactFileGenerator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ContactFileGeneratorTest extends TestCase
{
    private $serializer;
    private $params;
    private $contactFileGenerator;
    private $testStoragePath;

    protected function setUp(): void
    {
        // Create mocks for SerializerInterface and ParameterBagInterface
        $this->serializer = $this->createMock(SerializerInterface::class);
        $this->params = $this->createMock(ParameterBagInterface::class);
        // TODO: Set the test storage path 
        $this->testStoragePath = '/path/to/test/storage';
    
        // Create an instance of ContactFileGenerator with the mocks and test parameters
        $this->contactFileGenerator = new ContactFileGenerator($this->serializer, $this->params);
    }

    protected function tearDown(): void
    {
        // Clean up the test directory
        array_map('unlink', glob($this->testStoragePath."/*.json"));
    }

    public function testGenerateJsonFile()
    {
        $contact = new Contact();
        $contact->setFullname('Jane Doe');
        $contact->setEmail('janedoe@gmail.com');
        $contact->setQuestion('Hi, I need help with something.');
        $fileName = $this->contactFileGenerator->generateJsonFile($contact);
        $contact->setJsonFile($fileName);
        
        $this->assertNotEmpty($this->testStoragePath . '/');
        $this->assertEquals(1, count(glob($this->testStoragePath."/*.json")));
        $this->assertFileExists($this->testStoragePath . '/' . $contact->getJsonFile());
    }

    public function testRemoveJsonFile()
    {
        $contact = new Contact();
        $contact->setFullname('Jane Doe');
        $contact->setEmail('janedoe@gmail.com');
        $contact->setQuestion('Hi, I need help with something.');
        $fileName = $this->contactFileGenerator->generateJsonFile($contact);
        $contact->setJsonFile($fileName);

        $this->contactFileGenerator->removeJsonFile($contact);

        $this->assertEquals(1, count(glob($this->testStoragePath."/*.json")));
        $this->assertFileDoesNotExist($this->testStoragePath . '/' . $contact->getJsonFile());
    }
        
}
