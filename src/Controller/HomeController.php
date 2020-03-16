<?php

namespace App\Controller;

use App\Repository\TarifRepository;
use App\Repository\TrainerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param TrainerRepository $trainerRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(TrainerRepository $trainerRepository)
    {

        $trainers = $trainerRepository->findAll();

        return $this->render('home/index.html.twig', [
            'trainers' => $trainers
        ]);
    }

    /**
     * @Route("/tarifs", name="tarifs")
     * @param TarifRepository $tarifRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function tarifs(TarifRepository $tarifRepository)
    {
        $tarifs = $tarifRepository->findAll();

        return $this->render('home/tarifs.html.twig', [
           'tarifs' => $tarifs
        ]);
    }

}
