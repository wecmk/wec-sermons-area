<?php

namespace App\Command;

use App\Repository\EventRepository;
use App\Repository\SeriesRepository;
use App\Services\Google\GoogleCredentials;
use Doctrine\ORM\EntityManagerInterface;
use Google_Client;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Client\Provider\GoogleClient;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class RenewOAuth2Command extends Command
{
    protected static $defaultName = 'app:renewoauth2';
    protected static $defaultDescription = 'Renews the oauth2 tokens';

    private LoggerInterface $logger;
    private string $OAUTH_GOOGLE_CLIENT_ID;
    private string $OAUTH_GOOGLE_CLIENT_SECRET;
    private EntityManagerInterface $entityManager;
    private ClientRegistry $clientRegistry;
    private GoogleCredentials $googleCredentials;
    private Google_Client $client;


    public function __construct(LoggerInterface $logger, EntityManagerInterface $entityManager, ClientRegistry $clientRegistry, GoogleCredentials $googleCredentials, string $OAUTH_GOOGLE_CLIENT_ID, string $OAUTH_GOOGLE_CLIENT_SECRET, String $name = null)
    {
        parent::__construct($name);
        $this->logger = $logger;
        $this->entityManager = $entityManager;
        $this->clientRegistry = $clientRegistry;
        $this->googleCredentials = $googleCredentials;
        $this->OAUTH_GOOGLE_CLIENT_ID = $OAUTH_GOOGLE_CLIENT_ID;
        $this->OAUTH_GOOGLE_CLIENT_SECRET = $OAUTH_GOOGLE_CLIENT_SECRET;
        $config = [
            "client_id" => $OAUTH_GOOGLE_CLIENT_ID,
            "client_secret" => $OAUTH_GOOGLE_CLIENT_SECRET,
            "access_type" => "offline",
            "api_format_v2" => true,
        ];
        $this->client = new Google_Client($config);
        $this->client->setLogger($this->logger);

        $this->googleCredentials = $googleCredentials;

        $this->google_Service_YouTube = null;
    }

    protected function configure(): void
    {
    }

    /**
     * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $accessKeys = [];
        $this->logger->debug("Loaded key");
        $accessKeys['expires_in'] = time() - $this->googleCredentials->getExpires();
        $accessKeys['access_token'] = $this->googleCredentials->getAccessToken();
        if ($this->googleCredentials->getRefreshToken() != null) {
            $accessKeys['refresh_token'] = $this->googleCredentials->getRefreshToken();
        }
        // refresh the token
        $this->logger->warning("OAuth2.0 token expired");
        $this->client->refreshToken($accessKeys['refresh_token']);

        $obj = $this->client->fetchAccessTokenWithRefreshToken($this->client->getRefreshToken());

        $this->logger->info(print_r($obj, true));

        $accessToken = $obj['access_token'];
        $idToken = $obj['id_token'];
        $refreshToken = $obj['refresh_token'] ?? null;
        $expires = time() + intval($obj['expires_in']);

        $this->googleCredentials->setAccessToken($accessToken, true);
        if ($refreshToken != null) {
            $this->googleCredentials->setRefreshToken($refreshToken, true);
        }
        $this->googleCredentials->setExpires($expires, true);
        $this->googleCredentials->persist();

        return Command::SUCCESS;
    }
}
