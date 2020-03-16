<?php

namespace App\Controller;

use App\Entity\Lesson;
use App\Entity\Tarif;
use App\Entity\Trainer;
use App\Form\LessonType;
use App\Form\TarifType;
use App\Form\TrainerType;
use App\Helpers\Dates;
use App\Repository\LessonRepository;
use App\Repository\TarifRepository;
use App\Repository\TrainerRepository;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminController
 * @package App\Controller
 * @Route("/admin")
 * @IsGranted("ROLE_ADMIN")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="admin.home")
     */
    public function index()
    {
        return $this->redirectToRoute('admin.trainers');
    }

    /**
     * @Route("/trainers", name="admin.trainers")
     * @param TrainerRepository $trainerRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function trainers(TrainerRepository $trainerRepository)
    {
        $trainers = $trainerRepository->findAll();

        return $this->render('admin/trainers/index.html.twig',[
           'trainers' => $trainers
        ]);
    }

    /**
     * @Route("/trainers/edit/{trainer}", name="admin.trainers.edit")
     * @param Trainer $trainer
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editTrainer(Trainer $trainer, Request $request)
    {
        $form = $this->createForm(TrainerType::class, $trainer);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin.trainers');
        }

        return $this->render('admin/trainers/edit.html.twig',[
           'trainer' => $trainer,
           'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/planning", name="admin.planning")
     * @param LessonRepository $lessonRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function planning(LessonRepository $lessonRepository)
    {
        $days = Dates::$days;
        $lessons = $lessonRepository->findAll();
        $groupedLessons = Dates::groupLessonsByDays($lessons);

        return $this->render('admin/planning/index.html.twig', [
            'days' => $days,
            'groupedLessons' => $groupedLessons,
        ]);
    }

    /**
     * @Route("/planning/add", name="admin.planning.add")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addLesson(Request $request)
    {
        $lesson = new Lesson();
        $form = $this->createForm(LessonType::class, $lesson);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();

            $em->persist($lesson);
            $em->flush();

            return $this->redirectToRoute('admin.planning');
        }

        return $this->render('admin/planning/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/planning/edit/{lesson}", name="admin.planning.edit")
     * @param Lesson $lesson
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editLesson(Lesson $lesson, Request $request)
    {
        $form = $this->createForm(LessonType::class, $lesson);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();

            $em->persist($lesson);
            $em->flush();

            return $this->redirectToRoute('admin.planning');
        }

        return $this->render('admin/planning/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/planning/del/{lesson}", name="admin.planning.del")
     * @param Lesson $lesson
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delLesson(Lesson $lesson)
    {
        $em = $this->getDoctrine()->getManager();

        $em->remove($lesson);
        $em->flush();

        return $this->redirectToRoute('admin.planning');
    }

    /**
     * @Route("/planning/{trainer}", name="admin.planning.trainer")
     * @param Trainer $trainer
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function planningTrainer(Trainer $trainer)
    {
        $days = Dates::$days;
        $groupedLessons = Dates::groupLessonsByDays($trainer->getLessons()->toArray());

        return $this->render('admin/planning/index.html.twig', [
            'days' => $days,
            'groupedLessons' => $groupedLessons,
            'trainer' => $trainer
        ]);
    }

    /**
     * @Route("/tarifs", name="admin.tarifs")
     * @param TarifRepository $tarifRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function tarifs(TarifRepository $tarifRepository)
    {
        $tarifs = $tarifRepository->findAll();

        return $this->render('admin/tarifs/index.html.twig', [
           'tarifs' => $tarifs
        ]);
    }

    /**
     * @Route("/tarifs/edit/{tarif}", name="admin.tarifs.edit")
     * @param Tarif $tarif
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editTarif(Tarif $tarif, Request $request)
    {
        $form = $this->createForm(TarifType::class, $tarif);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin.tarifs');
        }

        return $this->render('admin/tarifs/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/users", name="admin.users")
     */
    public function users(UserRepository $userRepository)
    {
        $users = $userRepository->findAll();

        return $this->render('admin/users/index.html.twig',[
            'users' => $users
        ]);
    }

    /**
     * @Route("/participations", name="admin.participations")
     * @param LessonRepository $lessonRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function participations(LessonRepository $lessonRepository)
    {
        $days = Dates::$days;
        $lessons = $lessonRepository->findAll();
        $groupedLessons = Dates::groupLessonsByDays($lessons);

        return $this->render('admin/participations/index.html.twig', [
           'groupedLessons' => $groupedLessons,
            'days' => $days
        ]);
    }

    /**
     * @Route("/participations/{lesson}", name="admin.participations.lesson")
     * @param Lesson $lesson
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function participationsLesson(Lesson $lesson)
    {
        return $this->render('admin/participations/lesson.html.twig', [
           'lesson' => $lesson
        ]);
    }

}
