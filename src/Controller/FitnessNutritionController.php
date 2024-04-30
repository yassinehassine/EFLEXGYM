<?php
use App\Service\FitnessNutritionApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class FitnessNutritionController extends AbstractController
{
    public function fitnessData(FitnessNutritionApiService $apiService): Response
    {
        $fitnessData = $apiService->fetchFitnessData();

        return $this->render('fitness_data.html.twig', [
            'fitnessData' => $fitnessData,
        ]);
    }

    public function nutritionData(FitnessNutritionApiService $apiService): Response
    {
        $nutritionData = $apiService->fetchNutritionData();

        return $this->render('nutrition_data.html.twig', [
            'nutritionData' => $nutritionData,
        ]);
    }
}