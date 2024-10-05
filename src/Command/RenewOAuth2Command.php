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
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:renewoauth2',
    description: 'Renews the oauth2 tokens.',
)]
class RenewOAuth2Command extends Command
{
    /**
     * @var null
     */
    public $google_Service_YouTube = null;
    private readonly Google_Client $client;


    public function __construct(private readonly LoggerInterface $logger, private readonly EntityManagerInterface $entityManager, private readonly ClientRegistry $clientRegistry, private GoogleCredentials $googleCredentials, private readonly string $OAUTH_GOOGLE_CLIENT_ID, private readonly string $OAUTH_GOOGLE_CLIENT_SECRET, String $name = null)
    {
        parent::__construct($name);
        $config = [
            "client_id" => $this->OAUTH_GOOGLE_CLIENT_ID,
            "client_secret" => $this->OAUTH_GOOGLE_CLIENT_SECRET,
            "access_type" => "offline",
            "api_format_v2" => true,
        ];
        $this->client = new Google_Client($config);
        $this->client->setLogger($this->logger);

        $this->googleCredentials = $this->googleCredentials;
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
