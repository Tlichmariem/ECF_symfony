<?php

namespace App\Controller;

use App\Entity\Livre;
use App\Form\LivreType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LivreController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/livres', name: 'livre_index')]
    public function index(Request $request): Response
    {
        // Recherche par titre ou auteur
        $search = $request->query->get('search');
        $livres = $this->entityManager->getRepository(Livre::class)
            ->createQueryBuilder('l')
            ->where('l.titre LIKE :search OR l.auteur LIKE :search')
            ->setParameter('search', '%' . $search . '%')
            ->getQuery()
            ->getResult();

        // Rendu de la vue avec la liste des livres et la recherche
        return $this->render('livre/index.html.twig', [
            'livres' => $livres,
            'search' => $search,
        ]);
    }

    #[Route('/livres/ajouter', name: 'livre_ajouter')]
    public function ajouter(Request $request): Response
    {
        // Créer un nouvel objet Livre
        $livre = new Livre();
        
        // Créer le formulaire
        $form = $this->createForm(LivreType::class, $livre);

        // Traiter la requête
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrer l'objet Livre dans la base de données
            $this->entityManager->persist($livre);
            $this->entityManager->flush();

            // Redirection vers la liste des livres après ajout
            return $this->redirectToRoute('livre_index');
        }

        // Rendu de la vue pour ajouter un livre
        return $this->render('livre/ajouter.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/livre/edit/{id}', name: 'livre_edit')]
    public function edit(Request $request, Livre $livre): Response
    {
        // Créer le formulaire pour modifier un livre
        $form = $this->createForm(LivreType::class, $livre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Mettre à jour le livre dans la base de données
            $this->entityManager->flush();

            // Redirection vers la liste des livres après modification
            return $this->redirectToRoute('livre_index');
        }

        return $this->render('livre/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/livre/delete/{id}', name: 'livre_delete')]
    public function delete(Livre $livre): Response
    {
        // Supprimer le livre de la base de données
        $this->entityManager->remove($livre);
        $this->entityManager->flush();

        // Redirection vers la liste des livres après suppression
        return $this->redirectToRoute('livre_index');
    }
}
