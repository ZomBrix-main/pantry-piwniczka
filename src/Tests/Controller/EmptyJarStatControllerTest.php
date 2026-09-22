<?php

namespace App\Tests\Controller;

use App\Entity\EmptyJarStat;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class EmptyJarStatControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;

    /** @var EntityRepository<EmptyJarStat> */
    private EntityRepository $emptyJarStatRepository;
    private string $path = '/empty/jar/stat/crud/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->emptyJarStatRepository = $this->manager->getRepository(EmptyJarStat::class);

        foreach ($this->emptyJarStatRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('EmptyJarStat index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first()->text());
    }

    public function testNew(): void
    {
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'empty_jar_stat[type]' => 'Testing',
            'empty_jar_stat[quantity]' => 'Testing',
        ]);

        self::assertResponseRedirects('/empty/jar/stat/crud');

        self::assertSame(1, $this->emptyJarStatRepository->count([]));

        $this->markTestIncomplete('This test was generated');
    }

    public function testShow(): void
    {
        $fixture = new EmptyJarStat();
        $fixture->setType('My Title');
        $fixture->setQuantity('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('EmptyJarStat');

        // Use assertions to check that the properties are properly displayed.
        $this->markTestIncomplete('This test was generated');
    }

    public function testEdit(): void
    {
        $fixture = new EmptyJarStat();
        $fixture->setType('Value');
        $fixture->setQuantity('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'empty_jar_stat[type]' => 'Something New',
            'empty_jar_stat[quantity]' => 'Something New',
        ]);

        self::assertResponseRedirects('/empty/jar/stat/crud');

        $fixture = $this->emptyJarStatRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getType());
        self::assertSame('Something New', $fixture[0]->getQuantity());

        $this->markTestIncomplete('This test was generated');
    }

    public function testRemove(): void
    {
        $fixture = new EmptyJarStat();
        $fixture->setType('Value');
        $fixture->setQuantity('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/empty/jar/stat/crud');
        self::assertSame(0, $this->emptyJarStatRepository->count([]));

        $this->markTestIncomplete('This test was generated');
    }
}
