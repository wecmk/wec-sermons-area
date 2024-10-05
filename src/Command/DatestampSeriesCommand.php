<?php

namespace App\Command;

use App\Entity\Event;
use App\Entity\Series;
use App\Repository\EventRepository;
use App\Repository\SeriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:datestampseries',
    description: 'Sets the start and end dates for series.',
)]
class DatestampSeriesCommand extends Command
{

    public function __construct(private readonly EntityManagerInterface $entityManager, private readonly SeriesRepository $seriesRepository, private readonly EventRepository $eventRepository, String $name = null)
    {
        parent::__construct($name);
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        foreach ($this->seriesRepository->findAll() as $series) {
            $io->info($series->getName());

            $startingEventList = $this->eventRepository->findBySeries($series, "ASC", 1);
            $endingEventList = $this->eventRepository->findBySeries($series, "DESC", 1);

            if (is_array($startingEventList) && $startingEventList !== []) {
                $series->setStartDate($startingEventList[0]->getDate());
            }

            if ($series->getComplete() && is_array($endingEventList) && $endingEventList !== []) {
                $series->setEndDate($endingEventList[0]->getDate());
            }

            if ($series->getEndDate() != null && $series->getEndDate()->getTimestamp() < strtotime("-2 year", time())) {
                $series->setComplete(true);
            }

            $series->setName(trim((string) $series->getName()));

            $seriesSpeakers = array_map(fn(Event $object) => trim((string) $object->getSpeaker()), $this->eventRepository->findBySeries($series));

            $seriesSpeakers = array_unique($seriesSpeakers);

            $uniqueSpeakerCount = count($seriesSpeakers);
            if ($uniqueSpeakerCount < 3) {
                $io->success("Set Author: " . $seriesSpeakers[0]);
                $series->setAuthor($seriesSpeakers[0]);
            } elseif ($uniqueSpeakerCount >= 3) {
                $io->warning("Speaker Count > 3 " . implode("|", $seriesSpeakers));
            }


            $this->entityManager->persist($series);
        }
        $this->entityManager->flush();

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
