<?php

namespace App\Controller;

use App\Entity\SuiviProgre;
use App\Form\SuiviProgreType;
use App\Repository\ProgrammePersonnaliseRepository;
use App\Repository\SuiviProgreRepository;
use App\Service\PDFGenerator;
use App\Service\RecipeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Twilio\Rest\Client;

#[Route('/suivi/progre')]
class SuiviProgreController extends AbstractController
{
    private ProgrammePersonnaliseRepository $ppr;
    public function __construct(ProgrammePersonnaliseRepository $p)
    {
        $this->ppr = $p;
    }


    public function getIMC($poids, $taille)
    {
        return $poids / pow($taille / 100, 2);
    }

    public function getIMG($poids, $taille, $age, $sexe)
    {
        return (1.2 * $this->getIMC($poids, $taille)) + (0.23 * $age) - (10.8 * ($sexe == "homme" ? 1 : 0)) - 5.4;
    }
    public function determineProgramme($imc, $img)
    {
        $imcEvaluation = "";
        $imgEvaluation = "";

        // Évaluation de l'IMC
        if ($imc < 20) {
            $imcEvaluation = "faible";
        } else if ($imc < 25) {
            $imcEvaluation = "normal";
        } else {
            $imcEvaluation = "élevé";
        }

        // Évaluation de l'IMG
        if ($img < 15) {
            $imgEvaluation = "faible";
        } else if ($img < 20) {
            $imgEvaluation = "normal";
        } else {
            $imgEvaluation = "élevé";
        }
        if ($imcEvaluation == "faible" && $imgEvaluation == "faible") {
            return 3;
        } else if ($imcEvaluation == "normal" && $imgEvaluation == "normal") {
            return 2;
        } else {
            return 1;
        }
    }
    public function getEvaluation($poids, $taille, $age, $sexe)
    {
        $imc = $this->getIMC($poids, $taille);
        $img = $this->getIMG($poids, $taille, $age, $sexe);
        $imc = number_format($imc, 1); //number_format to specify the number after the comma
        $img = number_format($img, 1);
        $imcEvaluation = "";
        $imgEvaluation = "";

        // Évaluation de l'IMC
        if ($imc < 20) {
            $imcEvaluation = "faible";
        } else if ($imc < 25) {
            $imcEvaluation = "normal";
        } else {
            $imcEvaluation = "élevé";
        }

        // Évaluation de l'IMG
        if ($img < 15) {
            $imgEvaluation = "faible";
        } else if ($img < 20) {
            $imgEvaluation = "normal";
        } else {
            $imgEvaluation = "élevé";
        }

        // Construction du message d'évaluation
        $message = "Votre IMC est " . $imc . " (" . $imcEvaluation . ") \n votre IMG est " . $img . " (" . $imgEvaluation . ").\n ";

        // Ajouter le message final
        $message .= "Vous devez ";
        if ($imcEvaluation == "faible" || $imgEvaluation == "faible") {
            $message .= "prendre de la masse";
        } else if ($imcEvaluation == "normal" || $imgEvaluation == "normal") {
            $message .= "vous mettre en forme";
        } else {
            $message .= "perdre du poids";
        }
        return $message;
    }





    // #########################################################################################################################################
    // ####################################################     ROUTES      ####################################################################
    // #########################################################################################################################################

    #[Route('/', name: 'app_suivi_progre_index', methods: ['GET'])]
    public function index(SuiviProgreRepository $suiviProgreRepository): Response
    {
        return $this->render('suivi_progre/index.html.twig', [
            'suivi_progres' => $suiviProgreRepository->findAll(),
        ]);
    }
// here in this route you need to pass the user to the field idUser using
    // $suiviProgre-setIduser($connectedUser);
    #[Route('/new', name: 'app_suivi_progre_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $suiviProgre = new SuiviProgre();
        $form = $this->createForm(SuiviProgreType::class, $suiviProgre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $suiviProgre-setIduser($connectedUser);
            $entityManager->persist($suiviProgre);
            $entityManager->flush();

            // Now $suiviProgre has an ID generated by the database
            $suiviProgreId = $suiviProgre->getId();

            // Redirect to the 'show' route for the newly created entity
            return $this->redirectToRoute('app_suivi_progre_show', ['id' => $suiviProgreId], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('suivi_progre/new.html.twig', [
            'suivi_progre' => $suiviProgre,
            'form' => $form,
        ]);
    }
    #[Route('/chartimc', name: 'imc_chart')]
    public function imcChart(SuiviProgreRepository $suiviProgreRepository): Response
    {
        // Retrieve IMC data for all users from your database
        $s = $suiviProgreRepository->findAll();

        // Define IMC thresholds for high, low, and normal categories
        $highThreshold = 25;
        $lowThreshold = 18.5;

        // Initialize counters for each IMC category
        $highCount = 0;
        $lowCount = 0;
        $normalCount = 0;

        // Calculate IMC categories for each user
        foreach ($s as $suivi) {
            $poids = $suivi->getPoids();
            $taille = $suivi->getTaille();
            $imc = number_format($this->getIMC($poids, $taille), 1);

            if ($imc >= $highThreshold) {
                $highCount++;
            } elseif ($imc <= $lowThreshold) {
                $lowCount++;
            } else {
                $normalCount++;
            }
        }

        // Prepare chart data
        $chartData = [
            'labels' => ['High IMC', 'Low IMC', 'Normal IMC'],
            'datasets' => [
                [
                    'label' => 'Number of Users',
                    'backgroundColor' => ['#ff4d4d', '#4d94ff', '#66ff66'],
                    'data' => [$highCount, $lowCount, $normalCount],
                ],
            ],
        ];

        // Render chart data as JSON response
        return $this->json($chartData);
    }

    #[Route('/chartimg', name: 'img_chart')]
    public function imgChart(SuiviProgreRepository $suiviProgreRepository): Response
    {
        // Retrieve IMC data for all users from your database
        $s = $suiviProgreRepository->findAll();

        // Define IMC thresholds for high, low, and normal categories
        $highThreshold = 20;
        $lowThreshold = 15;

        // Initialize counters for each IMC category
        $highCount = 0;
        $lowCount = 0;
        $normalCount = 0;

        // Calculate IMC categories for each user
        foreach ($s as $suivi) {
            $poids = $suivi->getPoids();
            $taille = $suivi->getTaille();
            $age = $suivi->getAge();
            $sexe = $suivi->getSexe();
            $img = number_format($this->getIMG($poids, $taille, $age, $sexe), 1);

            if ($img >= $highThreshold) {
                $highCount++;
            } elseif ($img <= $lowThreshold) {
                $lowCount++;
            } else {
                $normalCount++;
            }
        }

        // Prepare chart data
        $chartDataIMG = [
            'labels' => ['High IMG', 'Low IMG', 'Normal IMG'],
            'datasets' => [
                [
                    'label' => 'Number of Users',
                    'backgroundColor' => ['#ff4d4d', '#4d94ff', '#66ff66'],
                    'data' => [$highCount, $lowCount, $normalCount],
                ],
            ],
        ];

        // Render chart data as JSON response
        return $this->json($chartDataIMG);
    }
    #[Route('/generate-pdf/{id}', name: 'generate_pdf', methods: ['GET'])]
    public function generatePdf(PDFGenerator $pdfGenerator, SuiviProgre $suiviProgre): Response
    {
        $poids = $suiviProgre->getPoids();
        $taille = $suiviProgre->getTaille();
        $age = $suiviProgre->getAge();
        $sexe = $suiviProgre->getSexe();
        $imc = $this->getIMC($poids, $taille);
        $img = $this->getIMG($poids, $taille, $age, $sexe);

        $evaluationMessage = $this->getEvaluation($poids, $taille, $age, $sexe);
        $programme = $this->determineProgramme($imc, $img);
        $programmes = $this->ppr->findAll();
        $programmeToFollow = null;

        foreach ($programmes as $programmeEntity) {
            if ($programmeEntity->getId() == $programme) {
                $programmeToFollow = $programmeEntity;
                break;
            }
        }
        // Data to pass to the Twig template
        $data = [
            'suivi_progre' => $suiviProgre,
            'evaluation_message' => $evaluationMessage,
            'programmeToFollow' => $programmeToFollow,
        ];

        // Generate PDF from template
        $pdfContent = $pdfGenerator->generatePdfFromTemplate('suivi_progre/pdf.html.twig', $data);

        // Send PDF as response
        $response = new Response($pdfContent);
        $response->headers->set('Content-Type', 'application/pdf');

        return $response;
    }

    #[Route('/{id}', name: 'app_suivi_progre_show', methods: ['GET'])]
    public function show(SuiviProgre $suiviProgre): Response
    {
        $poids = $suiviProgre->getPoids();
        $taille = $suiviProgre->getTaille();
        $age = $suiviProgre->getAge();
        $sexe = $suiviProgre->getSexe();
        $imc = $this->getIMC($poids, $taille);
        $img = $this->getIMG($poids, $taille, $age, $sexe);

        $evaluationMessage = $this->getEvaluation($poids, $taille, $age, $sexe);
        $programme = $this->determineProgramme($imc, $img);
        $programmes = $this->ppr->findAll();
        $programmeToFollow = null;

        foreach ($programmes as $programmeEntity) {
            if ($programmeEntity->getId() == $programme) {
                $programmeToFollow = $programmeEntity;
                break;
            }
        }

        return $this->render('suivi_progre/show.html.twig', [
            'suivi_progre' => $suiviProgre,
            'evaluation_message' => $evaluationMessage,
            'programmeToFollow' => $programmeToFollow,
        ]);
    }
    #[Route('/{id}/message', name: 'app_message', methods: ['GET', 'POST'])]
    public function message(SuiviProgre $suiviProgre,  Request $request, EntityManagerInterface $entityManager): Response
    {
        $poids = $suiviProgre->getPoids();
        $taille = $suiviProgre->getTaille();
        $age = $suiviProgre->getAge();
        $sexe = $suiviProgre->getSexe();
        $imc = $this->getIMC($poids, $taille);
        $img = $this->getIMG($poids, $taille, $age, $sexe);

        $evaluationMessage = $this->getEvaluation($poids, $taille, $age, $sexe);
        $programme = $this->determineProgramme($imc, $img);
        $programmes = $this->ppr->findAll();
        $programmeToFollow = null;

        foreach ($programmes as $programmeEntity) {
            if ($programmeEntity->getId() == $programme) {
                $programmeToFollow = $programmeEntity;
                break;
            }
        }
        $sid    = $_ENV['T_ID'];
        $token  = $_ENV['T_TOKEN'];
        // $sid    = "AC3bc1b157f7c8c059c45a31af7f8897bf";
        // $token  = "0571fd2f680903635921880a05727794";
        $twilio = new Client($sid, $token);

        $message = $twilio->messages
            ->create(
                "whatsapp:+21655897711", // to
                array(
                    "from" => "whatsapp:+14155238886",
                    "body" => $evaluationMessage
                )
            );

        return $this->redirectToRoute('app_suivi_progre_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/back/{id}', name: 'app_suivi_progre_showBack', methods: ['GET'])]
    public function showBack(SuiviProgre $suiviProgre): Response
    {
        $poids = $suiviProgre->getPoids();
        $taille = $suiviProgre->getTaille();
        $age = $suiviProgre->getAge();
        $sexe = $suiviProgre->getSexe();
        $imc = $this->getIMC($poids, $taille);
        $img = $this->getIMG($poids, $taille, $age, $sexe);

        $evaluationMessage = $this->getEvaluation($poids, $taille, $age, $sexe);
        $programme = $this->determineProgramme($imc, $img);
        $programmes = $this->ppr->findAll();
        $programmeToFollow = null;

        foreach ($programmes as $programmeEntity) {
            if ($programmeEntity->getId() == $programme) {
                $programmeToFollow = $programmeEntity;
                break;
            }
        }

        return $this->render('suivi_progre/showBack.html.twig', [
            'suivi_progre' => $suiviProgre,
            'evaluation_message' => $evaluationMessage,
            'programmeToFollow' => $programmeToFollow
        ]);
    }
    #[Route('/{id}/edit', name: 'app_suivi_progre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SuiviProgre $suiviProgre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SuiviProgreType::class, $suiviProgre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_suivi_progre_show', ['id' => $suiviProgre->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('suivi_progre/edit.html.twig', [
            'suivi_progre' => $suiviProgre,
            'form' => $form,
        ]);
    }

    #[Route('/back/{id}/edit', name: 'app_suivi_progre_editBack', methods: ['GET', 'POST'])]
    public function editBack(Request $request, SuiviProgre $suiviProgre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SuiviProgreType::class, $suiviProgre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_suivi_progre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('suivi_progre/editBack.html.twig', [
            'suivi_progre' => $suiviProgre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_suivi_progre_delete', methods: ['POST'])]
    public function delete(Request $request, SuiviProgre $suiviProgre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $suiviProgre->getId(), $request->request->get('_token'))) {
            $entityManager->remove($suiviProgre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_suivi_progre_index', [], Response::HTTP_SEE_OTHER);
    }
}
