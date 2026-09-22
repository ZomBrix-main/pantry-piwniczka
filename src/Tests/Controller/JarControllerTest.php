<?php

namespace App\Tests\Controller;

use App\Entity\Jar;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class JarControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;

    /** @var EntityRepository<Jar> */
    private EntityRepository $jarRepository;
    private string $path = '/jar/crud/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->jarRepository = $this->manager->getRepository(Jar::class);

        foreach ($this->jarRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Jar index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first()->text());
    }

    public function testNew(): void
    {
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'jar[content]' => 'Testing',
            'jar[createdAt]' => 'Testing',
            'jar[status]' => 'Testing',
            'jar[location]' => 'Testing',
            'jar[type]' => 'Testing',
            'jar[auditLogs]' => 'Testing',
        ]);

        self::assertResponseRedirects('/jar/crud');

        self::assertSame(1, $this->jarRepository->count([]));

        $this->markTestIncomplete('This test was generated');
    }

    public function testShow(): void
    {
        $fixture = new Jar();
        $fixture->setContent('My Title');
        $fixture->setCreatedAt('My Title');
        $fixture->setStatus('My Title');
        $fixture->setLocation('My Title');
        $fixture->setType('My Title');
        $fixture->setAuditLogs('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Jar');

        // Use assertions to check that the properties are properly displayed.
        $this->markTestIncomplete('This test was generated');
    }

    public function testEdit(): void
    {
        $fixture = new Jar();
        $fixture->setContent('Value');
        $fixture->setCreatedAt('Value');
        $fixture->setStatus('Value');
        $fixture->setLocation('Value');
        $fixture->setType('Value');
        $fixture->setAuditLogs('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'jar[content]' => 'Something New',
            'jar[createdAt]' => 'Something New',
            'jar[status]' => 'Something New',
            'jar[location]' => 'Something New',
            'jar[type]' => 'Something New',
            'jar[auditLogs]' => 'Something New',
        ]);

        self::assertResponseRedirects('/jar/crud');

        $fixture = $this->jarRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getContent());
        self::assertSame('Something New', $fixture[0]->getCreatedAt());
        self::assertSame('Something New', $fixture[0]->getStatus());
        self::assertSame('Something New', $fixture[0]->getLocation());
        self::assertSame('Something New', $fixture[0]->getType());
        self::assertSame('Something New', $fixture[0]->getAuditLogs());

        $this->markTestIncomplete('This test was generated');
    }

    public function testRemove(): void
    {
        $fixture = new Jar();
        $fixture->setContent('Value');
        $fixture->setCreatedAt('Value');
        $fixture->setStatus('Value');
        $fixture->setLocation('Value');
        $fixture->setType('Value');
        $fixture->setAuditLogs('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/jar/crud');
        self::assertSame(0, $this->jarRepository->count([]));

        $this->markTestIncomplete('This test was generated');
    }
}
