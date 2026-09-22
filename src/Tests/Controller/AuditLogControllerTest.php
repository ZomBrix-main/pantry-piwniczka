<?php

namespace App\Tests\Controller;

use App\Entity\AuditLog;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class AuditLogControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;

    /** @var EntityRepository<AuditLog> */
    private EntityRepository $auditLogRepository;
    private string $path = '/audit/log/crud/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->auditLogRepository = $this->manager->getRepository(AuditLog::class);

        foreach ($this->auditLogRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('AuditLog index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first()->text());
    }

    public function testNew(): void
    {
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'audit_log[description]' => 'Testing',
            'audit_log[createdAt]' => 'Testing',
            'audit_log[actionType]' => 'Testing',
            'audit_log[jars]' => 'Testing',
            'audit_log[emptyJarStats]' => 'Testing',
        ]);

        self::assertResponseRedirects('/audit/log/crud');

        self::assertSame(1, $this->auditLogRepository->count([]));

        $this->markTestIncomplete('This test was generated');
    }

    public function testShow(): void
    {
        $fixture = new AuditLog();
        $fixture->setDescription('My Title');
        $fixture->setCreatedAt('My Title');
        $fixture->setActionType('My Title');
        $fixture->setJars('My Title');
        $fixture->setEmptyJarStats('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('AuditLog');

        // Use assertions to check that the properties are properly displayed.
        $this->markTestIncomplete('This test was generated');
    }

    public function testEdit(): void
    {
        $fixture = new AuditLog();
        $fixture->setDescription('Value');
        $fixture->setCreatedAt('Value');
        $fixture->setActionType('Value');
        $fixture->setJars('Value');
        $fixture->setEmptyJarStats('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'audit_log[description]' => 'Something New',
            'audit_log[createdAt]' => 'Something New',
            'audit_log[actionType]' => 'Something New',
            'audit_log[jars]' => 'Something New',
            'audit_log[emptyJarStats]' => 'Something New',
        ]);

        self::assertResponseRedirects('/audit/log/crud');

        $fixture = $this->auditLogRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getDescription());
        self::assertSame('Something New', $fixture[0]->getCreatedAt());
        self::assertSame('Something New', $fixture[0]->getActionType());
        self::assertSame('Something New', $fixture[0]->getJars());
        self::assertSame('Something New', $fixture[0]->getEmptyJarStats());

        $this->markTestIncomplete('This test was generated');
    }

    public function testRemove(): void
    {
        $fixture = new AuditLog();
        $fixture->setDescription('Value');
        $fixture->setCreatedAt('Value');
        $fixture->setActionType('Value');
        $fixture->setJars('Value');
        $fixture->setEmptyJarStats('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/audit/log/crud');
        self::assertSame(0, $this->auditLogRepository->count([]));

        $this->markTestIncomplete('This test was generated');
    }
}
