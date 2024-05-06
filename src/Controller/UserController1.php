<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Security;
use Google\Client as GoogleClient;
use GuzzleHttp\Client as GuzzleClient;



#[Route('/profile')]
class UserController1 extends AbstractController
{




    private $passwordHasher;
    private $entityManager;

    private $security;
    public function __construct(UserPasswordHasherInterface $passwordHasher, Security $security, EntityManagerInterface $entityManager)
    {
        $this->passwordHasher = $passwordHasher;
        $this->security = $security;
        $this->entityManager = $entityManager;
    }


    #[Route('/{userId}', name: 'profile_user_show', methods: ['GET'])]
    public function show1($userId): Response
    {

        $httpClient = new GuzzleClient();

        // Fetch weather data from OpenWeatherMap API
        $city = 'Tunis'; // Replace with the city you want to get the weather for
        $apiKey = 'e7e933b8ee14963f05103ef05c85201c';
        $response = $httpClient->request('GET', 'https://api.openweathermap.org/data/2.5/weather', [
            'query' => [
                'q' => $city,
                'appid' => $apiKey,
                'units' => 'metric', // Use 'metric' for Celsius, 'imperial' for Fahrenheit
            ],
        ]);
        $weatherData = json_decode($response->getBody()->getContents(), true);

        // Fetch user information based on the user ID
        $user = $this->entityManager->getRepository(User::class)->find($userId);

        // Create Guzzle HTTP client


        // Render the profile page with user information and weather data
        return $this->render('profile.html.twig', [
            'user' => $user,
            'weatherData' => $weatherData,

        ]);
    }

    #[Route('/{userId}/edit', name: 'user_edit', methods: ['GET', 'POST'])]
    public function edit2(Request $request, User $user,int $userId, EntityManagerInterface $entityManager): Response
    {
        $user = $this->entityManager->getRepository(User::class)->find($userId);

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $photoFile */
            $photoFile = $form->get('photoFile')->getData();

            if ($photoFile) {
                $newFilename = uniqid().'.'.$photoFile->guessExtension();

                try {
                    $photoFile->move(
                        $this->getParameter('photos_directory'), // Define this parameter in services.yaml
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle file upload error...
                }

                $user->setPhoto($newFilename);
            }




            $user->setPassword(
                $this->passwordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $entityManager->flush();

            return $this->redirectToRoute('profile_user_show', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('editprofile.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }






}



