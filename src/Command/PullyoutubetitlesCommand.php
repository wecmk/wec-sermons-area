<?php

namespace App\Command;

use App\Repository\EventRepository;
use App\Repository\UserRepository;
use App\Services\Google\YouTubeVideoMetadataService;
use Doctrine\ORM\EntityManagerInterface;
use Google_Service_YouTube_Video;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

#[AsCommand(
    name: 'app:pullyoutubetitlescommand',
    description: 'Add a short description for your command.',
)]
class PullyoutubetitlesCommand extends Command
{

    public function __construct(private readonly EntityManagerInterface $entityManager, private readonly EventRepository $eventRepository, TokenStorageInterface $tokenStorage, UserRepository $userRepository, \Symfony\Bundle\SecurityBundle\Security $security, private readonly YouTubeVideoMetadataService $youTubeVideoMetadataService, string $name = null)
    {
        parent::__construct($name);
        $user = $userRepository->findOneBy(['username' => 'samuel']);
        $tokenStorage->setToken(new UsernamePasswordToken($user, 'main', $user->getRoles()));
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $items = $this->eventRepository->findAll();

        foreach ($items as $event) {
            $youtube = $this->youTubeVideoMetadataService->googleServiceYouTube();

            if ($event->getYouTubeLink() == null || $event->getYouTubeLink() == "") {//|| $event->getDate()->format("U") > date('U', strtotime("10 April 2021"))) {
                continue;
            }

            if (str_contains($event->getYouTubeLink(), "youtube.com")) {
                $stringParts = explode("=", $event->getYouTubeLink());
                if (count($stringParts) != 2) {
                    continue;
                }
            }

            if (str_contains($event->getYouTubeLink(), "youtu.be")) {
                $string = str_replace("//", "", $event->getYouTubeLink());
                $stringParts = explode("/", $string);
                if (count($stringParts) != 2) {
                    continue;
                }
            }

            // Call the API's videos.list method to retrieve the video resource.
            $listResponse = $youtube->videos->listVideos(
                "snippet,status",
                ['id' => $stringParts[1]]
            );
            $io->info("Searched for videos. Count of videos: " . $listResponse->count());

            $video = null;

            // If $listResponse is empty, the specified video was not found.
            $io->warning('asdf');

            if (empty($listResponse)) {
                $io->warning(sprintf('Can\'t find a video with video id: %s', $stringParts[1]));
            } elseif ($listResponse->count() > 0) {
                /* @var Google_Service_YouTube_Video $video */
                $video = $listResponse->getItems()[0];
                $capItems = $youtube->captions->listCaptions('snippet', $video->getId());
                $io->warning('asdf');
                foreach ($capItems as $caption) {
                    $name = $caption->getSnippet()->getName();
                    $io->info($name);
                }
                if (count($capItems) == 0) {
                    $io->info("no captions found for " . $video->getId());
                }
                $io->info($event->getLegacyId() . "|" . $video->getSnippet()->getTitle());
                $youtubeTitle = str_replace('| Allan Huxtable', "", $video->getSnippet()->getTitle());
                $event->setTitle($youtubeTitle);
                $this->entityManager->persist($event);
            } else {
                $io->warning("No results found");
            }
        }
        $this->entityManager->flush();


        return Command::SUCCESS;
    }
}
