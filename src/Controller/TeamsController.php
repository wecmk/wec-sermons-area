<?php

namespace App\Controller;

use App\Form\TeamsType;
use App\Repository\TeamsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/teams')]
class TeamsController extends AbstractController
{
    #[Route(path: '/', name: 'teams_index', methods: ['GET'])]
    public function index(TeamsRepository $teamsRepository): Response
    {
        return $this->render('teams/index.html.twig', [
            'teams' => $teamsRepository->findBy([], ["name" => "ASC"]),
        ]);
    }

    #[Route(path: '/new', name: 'teams_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $team = new Teams();
        $form = $this->createForm(TeamsType::class, $team);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form['imageFile']->getData();
            $fileContentsAsBase64 = base64_encode(file_get_contents($uploadedFile->getRealPath()));
            $team->setImageAsBase64($fileContentsAsBase64);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($team);
            $entityManager->flush();

            return $this->redirectToRoute('teams_index');
        }

        return $this->render('teams/new.html.twig', [
            'team' => $team,
            'form' => $form,
        ]);
    }

    #[Route(path: '/{id}', name: 'teams_show', methods: ['GET'])]
    public function show(Teams $team): Response
    {
        return $this->render('teams/show.html.twig', [
            'team' => $team,
        ]);
    }

    #[Route(path: '/{id}/edit', name: 'teams_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Teams $team): Response
    {
        $form = $this->createForm(TeamsType::class, $team);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('teams_index');
        }

        return $this->render('teams/edit.html.twig', [
            'team' => $team,
            'form' => $form,
        ]);
    }

    #[Route(path: '/{id}', name: 'teams_delete', methods: ['DELETE'])]
    public function delete(Request $request, Teams $team): Response
    {
        if ($this->isCsrfTokenValid('delete'.$team->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($team);
            $entityManager->flush();
        }

        return $this->redirectToRoute('teams_index');
    }
}
