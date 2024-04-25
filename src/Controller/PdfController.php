<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Snappy\Pdf;
use App\Repository\BilanFinancierRepository;


class PdfController extends AbstractController
{
    #[Route('/generate-pdf', name: 'generate_pdf', methods: ['GET'])]
public function generatePdf(Pdf $pdf, BilanFinancierRepository $bilanFinancierRepository): Response
{
    // Récupérer le dernier bilan financier
    $bilanFinancier = $bilanFinancierRepository->findOneBy([], ['dateDebut' => 'DESC']);

    // Vérifier si un bilan financier est trouvé
    if (!$bilanFinancier) {
        throw new \RuntimeException('Aucun bilan financier trouvé.');
    }


    // Utiliser la date de début du bilan financier pour déterminer le mois et l'année
    $month = $bilanFinancier->getDateDebut()->format('n');
    $year = $bilanFinancier->getDateDebut()->format('Y');

    // Générer le contenu HTML du PDF en utilisant la vue Twig
    $html = $this->renderView('bilan_financier/pdf.html.twig', [
        'month' => $month,
        'year' => $year,
        
        'revenusAbonnements' => $bilanFinancier->getRevenusAbonnements(),
        'revenusProduits' => $bilanFinancier->getRevenusProduits(),
        'depenses' => $bilanFinancier->getDepenses(),
        'profit' => $bilanFinancier->getProfit(),
        // Ajoutez d'autres données nécessaires pour le rapport financier
    ]);

    // Convertir le contenu HTML en PDF
    $pdfContent = $pdf->getOutputFromHtml($html);

    // Retourner une réponse avec le PDF en tant que pièce jointe
    return new Response(
        $pdfContent,
        Response::HTTP_OK,
        [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="rapport_financier.pdf"',
        ]
    );
}
}