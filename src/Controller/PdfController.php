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
        // Récupérer les données nécessaires pour le rapport financier du mois et de l'année actuels
        $bilanFinancier = $bilanFinancierRepository->findOneByMonth(date('n'), date('Y'));

        // Générer le contenu HTML du PDF en utilisant la vue Twig
        $html = $this->renderView('bilan_financier/pdf.html.twig', [
            'nbAdherents' => $bilanFinancier->getNbAdherents(),
            'nbCoachs' => $bilanFinancier->getNbCoachs(),
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


