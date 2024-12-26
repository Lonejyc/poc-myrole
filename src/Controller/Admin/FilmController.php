<?php

namespace App\Controller\Admin;

use App\Entity\Film;
use App\Form\FilmType;
use App\Repository\FilmRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/films', name: 'admin.film.')]
#[IsGranted('ROLE_ADMIN')]
class FilmController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(FilmRepository $repository, Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $films = $repository->paginateRecipes($page);
        return $this->render('admin/film/index.html.twig', [
            'films' => $films
        ]);
    }

    #[Route('/{id}', name: 'edit', requirements: ['id' => Requirement::DIGITS], methods: ['GET', 'POST'])]
    public function edit(Film $film, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(FilmType::class, $film);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Film modifiée avec succès');
            return $this->redirectToRoute('admin.film.index');
        }
        return $this->render('admin/film/edit.html.twig', [
            'film' => $film,
            'formContent' => $form
        ]);
    }

    #[Route('/create', name: 'create')]
    public function create(Request $request, EntityManagerInterface $em)
    {
        $recipe = new Film();
        $form = $this->createForm(FilmType::class, $recipe);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($recipe);
            $em->flush();
            $this->addFlash('success', 'Film créée avec succès');
            return $this->redirectToRoute('admin.film.index');
        }
        return $this->render('admin/film/create.html.twig', [
            'formContent' => $form
        ]);
    }

    #[Route('/{id}', name: 'delete', requirements: ['id' => Requirement::DIGITS], methods: ['DELETE'])]
    public function delete(Film $film, EntityManagerInterface $em)
    {
        $em->remove($film);
        $em->flush();
        $this->addFlash('success', 'Film supprimée avec succès');
        return $this->redirectToRoute('admin.film.index');
    }
}
