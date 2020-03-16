<?php

namespace App\Controller;

use App\Entity\Trainer;
use App\Helpers\Dates;
use App\Repository\LessonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PlanningController extends AbstractController
{
    /**
     * @Route("/planning", name="planning")
     * @param LessonRepository $lessonRepository
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function index(LessonRepository $lessonRepository)
    {
        $date = new \DateTime('now');
        $dates = Dates::generateDates($date->format('W'), $date->format('Y'));
        $lessons = $lessonRepository->findAll();
        $groupedLessons = Dates::groupLessonsByDays($lessons);
        $participations = $this->getUser() != null ? $this->getUser()->getParticipations() : [];

        return $this->render('planning/index.html.twig', [
            'dates' => $dates,
            'groupedLessons' => $groupedLessons,
            'participations' => $participations
        ]);
    }

    /**
     * @Route("/planning/{trainer}", name="planning.trainer")
     * @param Trainer $trainer
     * @param LessonRepository $lessonRepository
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function showTrainer(Trainer $trainer,LessonRepository $lessonRepository)
    {
        $date = new \DateTime('now');
        $dates = Dates::generateDates($date->format('W'), $date->format('Y'));
        $lessons = $lessonRepository->findBy(['trainer' => $trainer]);
        $groupedLessons = Dates::groupLessonsByDays($lessons);
        $participations = $this->getUser() != null ? $this->getUser()->getParticipations() : [];

        return $this->render('planning/index.html.twig', [
            'trainer' => $trainer,
            'dates' => $dates,
            'groupedLessons' => $groupedLessons,
            'participations' => $participations
        ]);
    }

}
