<?php

namespace App\Repository;

use App\Entity\QuestionsAndAnswers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method QuestionsAndAnswers|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuestionsAndAnswers|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuestionsAndAnswers[]    findAll()
 * @method QuestionsAndAnswers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuestionsAndAnswersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuestionsAndAnswers::class);
    }
}
