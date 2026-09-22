<?php

namespace App\Controller;

use App\Entity\EmptyJarStat;
use App\Form\EmptyJarStatType;
use App\Repository\EmptyJarStatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/empty/jar/stat/crud')]
final class EmptyJarStatCrudController extends AbstractController
{
    #[Route(name: 'app_empty_jar_stat_crud_index', methods: ['GET'])]
    public function index(EmptyJarStatRepository $emptyJarStatRepository): Response
    {
        return $this->render('empty_jar_stat_crud/index.html.twig', [
            'empty_jar_stats' => $emptyJarStatRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_empty_jar_stat_crud_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $emptyJarStat = new EmptyJarStat();
        $form = $this->createForm(EmptyJarStatType::class, $emptyJarStat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($emptyJarStat);
            $entityManager->flush();

            return $this->redirectToRoute('app_empty_jar_stat_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('empty_jar_stat_crud/new.html.twig', [
            'empty_jar_stat' => $emptyJarStat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_empty_jar_stat_crud_show', methods: ['GET'])]
    public function show(EmptyJarStat $emptyJarStat): Response
    {
        return $this->render('empty_jar_stat_crud/show.html.twig', [
            'empty_jar_stat' => $emptyJarStat,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_empty_jar_stat_crud_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EmptyJarStat $emptyJarStat, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EmptyJarStatType::class, $emptyJarStat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_empty_jar_stat_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('empty_jar_stat_crud/edit.html.twig', [
            'empty_jar_stat' => $emptyJarStat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_empty_jar_stat_crud_delete', methods: ['POST'])]
    public function delete(Request $request, EmptyJarStat $emptyJarStat, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$emptyJarStat->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($emptyJarStat);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_empty_jar_stat_crud_index', [], Response::HTTP_SEE_OTHER);
    }
}
