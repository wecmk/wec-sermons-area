<?php

namespace App\Controller;

use App\Repository\EventRepository;
use App\Repository\UserRepository;
use App\Services\Google\GoogleCredentials;
use App\Services\Google\YouTubeVideoMetadataService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Google_Client;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Client\Provider\GoogleClient;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class GoogleController extends AbstractController
{
    /**
     * Link to this controller to start the "connect" process
     *
     * @Route("/connect/google", name="connect_google_start")
     */
    public function connectAction(ClientRegistry $clientRegistry)
    {
        return $clientRegistry
            ->getClient('google_main') // key used in config/packages/knpu_oauth2_client.yaml
            ->redirect(['scope' => "https://www.googleapis.com/auth/youtube"]);
    }

    /**
     * After going to Google, you're redirected back here
     * because this is the "redirect_route" you configured
     * in config/packages/knpu_oauth2_client.yaml
     *
     * @Route("/connect/google/check", name="connect_google_check")
     * @param Request $request
     * @param LoggerInterface $logger
     * @param ClientRegistry $clientRegistry
     * @param Security $security
     * @param EntityManager $entityManager
     * @param UserRepository $userRepository
     * @throws IdentityProviderException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function connectCheckAction(Request $request, LoggerInterface $logger, ClientRegistry $clientRegistry, GoogleCredentials $googleCredentials, EntityManagerInterface $entityManager, UserRepository $userRepository)
    {
        // ** if you want to *authenticate* the user, then
        // leave this method blank and create a Guard authenticator
        // (read below)

        /** @var GoogleClient $client */
        $client = $clientRegistry->getClient('google_main');

        try {
            // Fixes bug where calling the $client more than once invalidates the reference
            $json = json_encode($client->getAccessToken());
            $obj = json_decode($json, true);

            $accessToken = $obj['access_token'];
            $idToken = $obj['id_token'];
            $refreshToken = $obj['refresh_token'] ?? null;
            $expires = $obj['expires'];

            $accessTokenObj = new AccessToken($obj);

            $user = $client->fetchUserFromToken($accessTokenObj);

            if ($user->getName() != "Wolverton Evangelical Church") {
                throw new \Exception("Google Account User not expected (expected 'Wolverton Evangelical Church', not" . $user->getName());
            }

            $googleCredentials->setAccessToken($accessToken, true);
            if ($refreshToken != null) {
                $googleCredentials->setRefreshToken($refreshToken, true);
            }
            $googleCredentials->setExpires($expires, true);
            $googleCredentials->persist();

            // Todo: store refresh token and access token and expires time in User entity
            // todo: update login logic
            //      if user expired/not exist
            //          google auth
            //      else
            //          continue as usual
            // Todo: Update YouTube videos as expected
            //       do not update if before 4 April 2021
            //      Unlisted
            //      Title
        } catch (IdentityProviderException $e) {
            // something went wrong!
            // probably you should return the reason to the user
            $logger->error($e->getMessage(), $e->getTrace());
            throw $e;
            var_dump("error: " . $e->getMessage());
            die;
        } catch (MissingAuthorizationCodeException $e) {
            throw $e;
        } catch (OptimisticLockException $e) {
            throw $e;
        } catch (ORMException $e) {
            throw $e;
        }

        return $this->redirectToRoute('members_area_home');
    }

    /**
     *
     * @Route("/youtube/{id}", name="youtube")
     * @param Request $request
     * @param YouTubeVideoMetadataService $youTubeVideoMetadataService
     * @param EventRepository $eventRepository
     * @param int $id
     * @return Response
     */
    public function youTubeAction(Request $request, YouTubeVideoMetadataService $youTubeVideoMetadataService, EventRepository $eventRepository, int $id): Response
    {
        $event = $eventRepository->findOneBy(['shortId' => $id]);
        if ($event == null) {
            throw $this->createNotFoundException("shortId not found");
        }
        return new Response($youTubeVideoMetadataService->updateVideo($event)->getSnippet()->getTitle(), 200);
    }
}
