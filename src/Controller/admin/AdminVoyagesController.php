<?php

namespace App\Controller\admin;

use App\Form\VisiteType;
use App\Repository\VisiteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Visite;

class AdminVoyagesController extends AbstractController {
    
    /**
     * 
     * @var VisiteRepository
     */
    private $repository;
    
    /**
     * 
     * @var EntityManagerInterface
     */
    private $entityManager;
    
    /**
     * 
     * @param VisiteRepository $repository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(VisiteRepository $repository, EntityManagerInterface $entityManager) {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }
    
    #[Route('/admin', name: 'admin.voyages')]
    public function index(): Response {
        $visites = $this->repository->findAllOrderBy('datecreation', 'DESC');
        return $this->render("admin/admin.voyages.html.twig", [
            'visites' => $visites
        ]);
    }
    
    #[Route('/admin/edit/{id}', name: 'admin.voyage.edit')]
    public function edit(int $id, Request $request): Response{
        $visite = $this->repository->find($id);
        if (!$visite) {
            return $this->redirectToRoute('admin.voyages');
        }
        
        $formVisite = $this->createForm(VisiteType::class, $visite);
        $formVisite->handleRequest($request);
        
        if ($formVisite->isSubmitted() && $formVisite->isValid()) {
            $this->entityManager->persist($visite);
            $this->entityManager->flush();
            return $this->redirectToRoute('admin.voyages');
        }
        
        return $this->render("admin/admin.voyage.edit.html.twig", [
            'visite' => $visite,
            'formvisite' => $formVisite->createView()
        ]);
    }
    
    #[Route('/admin/suppr/{id}', name: 'admin.voyage.suppr')]
    public function suppr(int $id): Response{
        $visite = $this->repository->find($id);
        if ($visite) {
            $this->repository->remove($visite);
        }
        return $this->redirectToRoute('admin.voyages');
    }
    #[Route('/admin/ajout', name: 'admin.voyage.ajout')]
    public function ajout(Request $request): Response{
        $visite = new Visite();
        $formVisite = $this->createForm(VisiteType::class, $visite);
        $formVisite->handleRequest($request);
        
        if ($formVisite->isSubmitted() && $formVisite->isValid()) {
            $this->entityManager->persist($visite);
            $this->entityManager->flush();
            return $this->redirectToRoute('admin.voyages');
        }

        return $this->render("admin/admin.voyage.ajout.html.twig", [
            'visite' => $visite,
            'formvisite' => $formVisite->createView()
        ]);
    }
}
