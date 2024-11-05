<?php

namespace App\Controller;

use App\Entity\Tristan;
use App\Form\TristanType;
use App\Repository\TristanRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/tristan')]
final class TristanController extends AbstractController
{
    #[Route(name: 'app_tristan_index', methods: ['GET'])]
    public function index(TristanRepository $tristanRepository): Response
    {
        return $this->render('tristan/index.html.twig', [
            'tristans' => $tristanRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_tristan_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $tristan = new Tristan();
        $form = $this->createForm(TristanType::class, $tristan);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($tristan);
            $entityManager->flush();

            return $this->redirectToRoute('app_tristan_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tristan/new.html.twig', [
            'tristan' => $tristan,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tristan_show', methods: ['GET'])]
    public function show(Tristan $tristan): Response
    {
        return $this->render('tristan/show.html.twig', [
            'tristan' => $tristan,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_tristan_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Tristan $tristan, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TristanType::class, $tristan);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_tristan_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tristan/edit.html.twig', [
            'tristan' => $tristan,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tristan_delete', methods: ['POST'])]
    public function delete(Request $request, Tristan $tristan, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tristan->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($tristan);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_tristan_index', [], Response::HTTP_SEE_OTHER);
    }
}
