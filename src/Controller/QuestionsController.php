<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\QuestionsAndAnswersSeries;
use App\Entity\QuestionsAndAnswers;
use App\Form\PublishQuestionType;

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

        $entities = $em->getRepository(QuestionsAndAnswersSeries::class)->findAll();

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
        $questionSeries = $em->getRepository(QuestionsAndAnswersSeries::class)->find($id);

        if (!$questionSeries) {
            throw $this->createNotFoundException('Unable to find QuestionSeries entity.');
        }

        $questions = $this->getDoctrine()->getRepository(QuestionsAndAnswers::class)
                ->createQueryBuilder('es')
                ->where('es.QuestionsAndAnswersSeries = :id')
                ->setParameter('id', $id)
                ->orderBy('es.number', 'ASC')
                ->getQuery()
                ->getResult();

        return $this->render('questions/show.html.twig', [
                    'questions' => $questions,
                    'questionSeries' => $questionSeries,
        ]);
    }

    /**
     * Sets the current QuestionSeries
     *
     * @Route("/{id}/current", name="set_current", methods={"POST"})
     */
    public function setSeriesCurrent(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        /* @var $entity QuestionSeries */
        $entity = $em->getRepository(QuestionsAndAnswersSeries::class)->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find QuestionSeries entity.');
        }

        foreach ($em->getRepository(QuestionsAndAnswersSeries::class)->findAll() as $obj) {
            if ($entity->getId() != $obj->getId()) {
                $obj->setCurrent(false);
            } else {
                $obj->setCurrent(true);
            }
        }

        $em->flush();
        return $this->redirect($this->generateUrl('questions_list_by_id', array('id' => $id)));
    }

    /**
     * Publishes the Question on publish immediately
     *
     * @Route("/publish/{id}", name="publish_now", methods={"POST"})
     */
    public function setQuestionPublishDateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository(QuestionsAndAnswers::class)->find($id);
        $publishDate = $request->request->get('publishdate');

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Question entity.');
        }
        if (!$publishDate) {
            throw new \UnexpectedValueException('publishdate value expected');
        }

        $entity->setPublishDate(new \DateTime($publishDate));
        $em->persist($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('questions_list_by_id', array('id' => $entity->getQuestionsAndAnswersSeries()->getId())));
    }

    /**
     * Displays a form to edit the publish Date of a Question entity.
     *
     * @Route("/edit/{id}", name="edit", methods={"GET"})
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository(QuestionQA::class)->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Question entity.');
        }

        $editForm = $this->createPublishQuestionForm($entity);

        return $this->render('questions/edit.html.twig', [
            'question' => $entity,
            'edit_form' => $editForm->createView(),
        ]);
    }

    /**
     * Edits an existing Question entity.
     *
     * @Route("/schedule/{id}", name="schedule", methods={"GET", "POST", "PUT"})
     */
    public function scheduleQuestionAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository(QuestionsAndAnswers::class)->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Question entity.');
        }

        $editForm = $this->createPublishQuestionForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            if ($editForm['scheduleSubsequentQuestions']->getData()) {
                $em->flush();

                $startDate = $entity->getPublishDate();
                foreach ($em->getRepository(QuestionsAndAnswers::class)->findBy(
                    array('questionsAndAnswersSeries' => $entity->getQuestionsAndAnswersSeries()),
                    array('id' => 'ASC')
                )
                        as $question) {
                    if ($question->getNumber() >= $entity->getNumber()) {
                        // Hack: Doctrine detects changes by reference.
                        // You must create a new object (DateTime) in order for it to be updated
                        $startDateOffSet = $startDate;
                        $dateToInsert = new \DateTime();
                        $dateToInsert->setTimestamp($startDateOffSet->format('U'));
                        $question->setPublishDate($dateToInsert);

                        // Now add 1 week for offset
                        $startDate->modify('+1 week');
                    }
                }
            }

            $em->flush();

            return $this->redirect($this->generateUrl('questions_list_by_id', array('id' => $entity->getNumber())));
        }

        return $this->render('questions/edit.html.twig', [
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
        ]);
    }

    /**
     * Creates a form to publish a Question entity.
     *
     * @param Question $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createPublishQuestionForm(QuestionQA $entity)
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(PublishQuestionType::class, $entity, array(
            'method' => 'PUT',
            'action' => $this->generateUrl('questions_schedule', array('id' => $entity->getId()))
        ));
        return $form;
    }
}
