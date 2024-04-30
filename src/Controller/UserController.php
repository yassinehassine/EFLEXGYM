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



#[Route('/')]
class UserController extends AbstractController
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

    #[Route('/user', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/', name: 'app_user_front', methods: ['GET'])]
    public function index2(): Response
    {     $httpClient = new GuzzleClient();

        // Fetch weather data from OpenWeatherMap API
        $city = 'London'; // Replace with the city you want to get the weather for
        $apiKey = 'e7e933b8ee14963f05103ef05c85201c';
        $response = $httpClient->request('GET', 'https://api.openweathermap.org/data/2.5/weather', [
            'query' => [
                'q' => $city,
                'appid' => $apiKey,
                'units' => 'metric', // Use 'metric' for Celsius, 'imperial' for Fahrenheit
            ],
        ]);
        $weatherData = json_decode($response->getBody()->getContents(), true);


        return $this->render('index.html.twig', [
            'weatherData' => $weatherData,
        ]);
    }
    #[Route('/front', name: 'front', methods: ['GET'])]
    public function weather(): Response
    {     $httpClient = new GuzzleClient();

        // Fetch weather data from OpenWeatherMap API
        $city = 'London'; // Replace with the city you want to get the weather for
        $apiKey = 'e7e933b8ee14963f05103ef05c85201c';
        $response = $httpClient->request('GET', 'https://api.openweathermap.org/data/2.5/weather', [
            'query' => [
                'q' => $city,
                'appid' => $apiKey,
                'units' => 'metric', // Use 'metric' for Celsius, 'imperial' for Fahrenheit
            ],
        ]);
        $weatherData = json_decode($response->getBody()->getContents(), true);


        return $this->render('font.html.twig', [
            'weatherData' => $weatherData,
        ]);
    }



    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
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










            $user->setRoles($form->get('roles')->getData());
            

            
            $user->setPassword(
                $this->passwordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
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

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }




        ///////////*USER SECTION*////////////

    #[Route('/profile/{userId}', name: 'profile_user_show', methods: ['GET'])]
    public function show1($userId): Response
    {
        // Fetch user information based on the user ID
        $user = $this->entityManager->getRepository(User::class)->find($userId);

        // Create Guzzle HTTP client


        // Render the profile page with user information and weather data
        return $this->render('profile.html.twig', [
            'user' => $user,

        ]);
    }

    #[Route('/profile/{userId}/edit', name: 'user_edit', methods: ['GET', 'POST'])]
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



