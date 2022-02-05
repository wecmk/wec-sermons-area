<?php

namespace App\Command;

use App\Entity\Event;
use App\Entity\Series;
use App\Repository\EventRepository;
use App\Repository\SeriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class DatestampSeriesCommand extends Command
{
    protected static $defaultName = 'app:datestampseries';
    protected static $defaultDescription = 'Sets the start and end dates for series';

    private EntityManagerInterface $entityManager;
    private SeriesRepository $seriesRepository;
    private EventRepository $eventRepository;

    public function __construct(EntityManagerInterface $entityManager, SeriesRepository $seriesRepository, EventRepository $eventRepository, String $name = null)
    {
        parent::__construct($name);
        $this->entityManager = $entityManager;
        $this->seriesRepository = $seriesRepository;
        $this->eventRepository = $eventRepository;
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

            if (is_array($startingEventList) && count($startingEventList) > 0) {
                $series->setStartDate($startingEventList[0]->getDate());
            }

            if ($series->getComplete() && is_array($endingEventList) && count($endingEventList) > 0) {
                $series->setEndDate($endingEventList[0]->getDate());
            }

            if ($series->getEndDate() != null && $series->getEndDate()->getTimestamp() < strtotime("-2 year", time())) {
                $series->setComplete(true);
            }

            $series->setName(trim($series->getName()));

            $seriesSpeakers = array_map(function (Event $object) {
                return trim($object->getSpeaker());
            }, $this->eventRepository->findBySeries($series));

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
