<?php

namespace App\Test\Controller;

use App\Entity\BilanFinancier;
use App\Repository\BilanFinancierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BilanFinancierControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private BilanFinancierRepository $repository;
    private string $path = '/bilan/financier/';
    private EntityManagerInterface $manager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(BilanFinancier::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('BilanFinancier index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'bilan_financier[dateDebut]' => 'Testing',
            'bilan_financier[dateFin]' => 'Testing',
            'bilan_financier[salairesCoachs]' => 'Testing',
            'bilan_financier[prixLocation]' => 'Testing',
            'bilan_financier[revenusAbonnements]' => 'Testing',
            'bilan_financier[revenusProduits]' => 'Testing',
            'bilan_financier[depenses]' => 'Testing',
            'bilan_financier[profit]' => 'Testing',
        ]);

        self::assertResponseRedirects('/bilan/financier/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new BilanFinancier();
        $fixture->setDateDebut('My Title');
        $fixture->setDateFin('My Title');
        $fixture->setSalairesCoachs('My Title');
        $fixture->setPrixLocation('My Title');
        $fixture->setRevenusAbonnements('My Title');
        $fixture->setRevenusProduits('My Title');
        $fixture->setDepenses('My Title');
        $fixture->setProfit('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('BilanFinancier');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new BilanFinancier();
        $fixture->setDateDebut('My Title');
        $fixture->setDateFin('My Title');
        $fixture->setSalairesCoachs('My Title');
        $fixture->setPrixLocation('My Title');
        $fixture->setRevenusAbonnements('My Title');
        $fixture->setRevenusProduits('My Title');
        $fixture->setDepenses('My Title');
        $fixture->setProfit('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'bilan_financier[dateDebut]' => 'Something New',
            'bilan_financier[dateFin]' => 'Something New',
            'bilan_financier[salairesCoachs]' => 'Something New',
            'bilan_financier[prixLocation]' => 'Something New',
            'bilan_financier[revenusAbonnements]' => 'Something New',
            'bilan_financier[revenusProduits]' => 'Something New',
            'bilan_financier[depenses]' => 'Something New',
            'bilan_financier[profit]' => 'Something New',
        ]);

        self::assertResponseRedirects('/bilan/financier/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getDateDebut());
        self::assertSame('Something New', $fixture[0]->getDateFin());
        self::assertSame('Something New', $fixture[0]->getSalairesCoachs());
        self::assertSame('Something New', $fixture[0]->getPrixLocation());
        self::assertSame('Something New', $fixture[0]->getRevenusAbonnements());
        self::assertSame('Something New', $fixture[0]->getRevenusProduits());
        self::assertSame('Something New', $fixture[0]->getDepenses());
        self::assertSame('Something New', $fixture[0]->getProfit());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new BilanFinancier();
        $fixture->setDateDebut('My Title');
        $fixture->setDateFin('My Title');
        $fixture->setSalairesCoachs('My Title');
        $fixture->setPrixLocation('My Title');
        $fixture->setRevenusAbonnements('My Title');
        $fixture->setRevenusProduits('My Title');
        $fixture->setDepenses('My Title');
        $fixture->setProfit('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/bilan/financier/');
    }
}
