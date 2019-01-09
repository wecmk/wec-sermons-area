<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\QuestionSeries;
use App\Entity\QuestionQA;

/**
 * @Route("/questions", name="questions_")
 */
class QuestionsController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository(QuestionSeries::class)->findAll();

        return $this->render('questions/index.html.twig', [
            'entities' => $entities,
        ]);
    }
    
    /**
     * Finds and displays a list of Questions based on QuestionSeries id.
     *
     * @Route("/{id}", name="list_by_id")
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $questionSeries = $em->getRepository(QuestionSeries::class)->find($id);

        if (!$questionSeries) {
            throw $this->createNotFoundException('Unable to find QuestionSeries entity.');
        }

        $questions = $this->getDoctrine()->getRepository(QuestionQA::class)
            ->createQueryBuilder('es')
            ->where('es.questionSeries = :id')
            ->setParameter('id', $id)
            ->orderBy('es.Number', 'ASC')
            ->getQuery()
            ->getResult();

        //$questionSeriesSetCurrentForm = $this->createSetCurrentSeriesForm($questionSeries);

        return $this->render('questions/show.html.twig', [
            'questions'      => $questions,
            'questionSeries' => $questionSeries,
            //'questionSeriesSetCurrentForm' => $questionSeriesSetCurrentForm->createView(),
        ]);
    }
}
