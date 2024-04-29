<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class mapController extends AbstractController
{
    #[Route('/map', name: 'app_map')]
    public function index(): Response
    {
        return $this->render('map.html.twig');
    }

    #[Route('/search', name: 'app_search')]
    public function search(Request $request): Response
    {
        // Handle search logic here
        $query = $request->query->get('query'); // Get the search query from the request

        // Perform search operation based on the query
        // You can use any geocoding service or implement your own search logic here

        // Example: Return a response with search results
        $results = [];

        // Check if the query matches Tunisia, Ariana, Mornag, or any other predefined location
        if (strcasecmp($query, 'Tunisia') === 0) {
            // Add coordinates for Tunisia
            $results[] = ['name' => 'Tunisia', 'lat' => 34.0479, 'lon' => 100.6197];
        } elseif (strcasecmp($query, 'Ariana') === 0) {
            // Add coordinates for Ariana
            $results[] = ['name' => 'Ariana', 'lat' => 36.8625, 'lon' => 10.1956];
        } elseif (strcasecmp($query, 'Mornag') === 0) {
            // Add coordinates for Mornag
            $results[] = ['name' => 'Mornag', 'lat' => 36.7006, 'lon' => 10.3378];
        }
        // Add other locations as needed

        return $this->json([
            'query' => $query,
            'results' => $results,
        ]);
    }
}
