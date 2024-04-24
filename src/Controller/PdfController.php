<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Snappy\Pdf;


class PdfController extends AbstractController
{
    #[Route('/pdf', name: 'app_pdf')]
    public function index(): Response
    {
        return $this->render('pdf/index.html.twig', [
            'controller_name' => 'PdfController',
        ]);
    }

    #[Route('/generate_Pdf', name: 'app_pdf', methods: ['GET'])]
    public function generatePdf(Pdf $pdf)
    {
        $html = $this->renderView('bilan_financier/pdf.html.twig', [
            'name' => 'John Doe',
        ]);

        return new Response(
            $pdf->getOutputFromHtml($html),
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="example.pdf"',
            ]
        );
    }
}
