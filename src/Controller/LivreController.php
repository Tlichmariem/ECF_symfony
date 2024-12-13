// src/Controller/LivreController.php
namespace App\Controller;

use App\Entity\Livre;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LivreController extends AbstractController
{
    #[Route('/livres', name: 'livre_index')]
    public function index(): Response
    {
        // Récupère tous les livres de la base de données
        $livres = $this->getDoctrine()
            ->getRepository(Livre::class)
            ->findAll();

        // Rendu de la vue avec la liste des livres
        return $this->render('livre/index.html.twig', [
            'livres' => $livres,
        ]);
    }
}
