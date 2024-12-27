<?php

namespace App\Controller\Admin;

use App\Entity\EmployeeGroup;
use App\Form\EmployeeGroupType;
use App\Repository\EmployeeGroupRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/employee-group', name: 'admin.employee_group.')]
#[IsGranted('ROLE_ADMIN')]
class EmployeeGroupController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(EmployeeGroupRepository $repository, Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $employees = $repository->paginateRecipes($page);
        return $this->render('admin/employee/index.html.twig', [
            'employees' => $employees
        ]);
    }

    #[Route('/{id}', name: 'edit', requirements: ['id' => Requirement::DIGITS], methods: ['GET', 'POST'])]
    public function edit(EmployeeGroup $eg, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(EmployeeGroupType::class, $eg);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', "Groupe d'employée modifiée avec succès");
            return $this->redirectToRoute('admin.employee_group.index');
        }
        return $this->render('admin/employee/edit.html.twig', [
            'employee' => $eg,
            'formContent' => $form,
        ]);
    }

    #[Route('/create', name: 'create')]
    public function create(Request $request, EntityManagerInterface $em)
    {
        $eg = new EmployeeGroup();
        $form = $this->createForm(EmployeeGroupType::class, $eg);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($eg);
            $em->flush();
            $this->addFlash('success', "Groupe d'emplyée créée avec succès");
            return $this->redirectToRoute('admin.employee_group.index');
        }
        return $this->render('admin/employee/create.html.twig', [
            'formContent' => $form
        ]);
    }

    #[Route('/{id}', name: 'delete', requirements: ['id' => Requirement::DIGITS], methods: ['DELETE'])]
    public function delete(EmployeeGroup $eg, EntityManagerInterface $em)
    {
        $em->remove($eg);
        $em->flush();
        $this->addFlash('success', "Groupe d'employée supprimée avec succès");
        return $this->redirectToRoute('admin.employee_group.index');
    }
}
