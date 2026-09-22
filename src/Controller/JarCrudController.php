<?php

namespace App\Controller;

use App\Entity\Jar;
use App\Form\JarType;
use App\Repository\JarRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/jar/crud')]
final class JarCrudController extends AbstractController
{
    #[Route(name: 'app_jar_crud_index', methods: ['GET'])]
    public function index(JarRepository $jarRepository): Response
    {
        return $this->render('jar_crud/index.html.twig', [
            'jars' => $jarRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_jar_crud_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $jar = new Jar();
        $form = $this->createForm(JarType::class, $jar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($jar);
            $entityManager->flush();

            $this->addFlash('success_modal_title', 'Dodano nowy słoik!');
            $this->addFlash('success_modal_body', 'Słoik o nazwie: ' . $jar->getContent() . ' otrzymał przypisany systemowy ID: ' . $jar->getId() . '. Naklej ten numer na słoik!');

            return $this->redirectToRoute('app_jar_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('jar_crud/new.html.twig', [
            'jar' => $jar,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_jar_crud_show', methods: ['GET'])]
    public function show(Jar $jar): Response
    {
        return $this->render('jar_crud/show.html.twig', [
            'jar' => $jar,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_jar_crud_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Jar $jar, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(JarType::class, $jar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_jar_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('jar_crud/edit.html.twig', [
            'jar' => $jar,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_jar_crud_delete', methods: ['POST'])]
    public function delete(Request $request, Jar $jar, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$jar->getId(), $request->getPayload()->getString('_token'))) {
            $jar->setStatus('zużyty');
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_jar_crud_index', [], Response::HTTP_SEE_OTHER);
    }
}
