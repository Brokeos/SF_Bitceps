<?php

namespace App\Controller;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Lesson;
use App\Entity\Tarif;
use App\Form\ChangePasswordType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */
class UserController extends AbstractController
{

    /**
     * @Route("/participation/add/{lesson}", name="user.participation.add")
     * @param Lesson $lesson
     * @param Request $request
     * @return RedirectResponse
     */
    public function addParticipation(Lesson $lesson, Request $request)
    {
        $user = $this->getUser();
        $userParticipations = $user->getParticipations()->toArray();
        if (!in_array($lesson, $userParticipations))
        {
            $user->addParticipation($lesson);
            $this->getDoctrine()->getManager()->flush();
        }
        $response = $request->headers->get('referer') ?? $this->generateUrl('planning');
        return new RedirectResponse($response);
    }

    /**
     * @Route("/participation/del/{lesson}", name="user.participation.del")
     * @param Lesson $lesson
     * @param Request $request
     * @return RedirectResponse
     */
    public function removeParticipation(Lesson $lesson, Request $request)
    {
        $user = $this->getUser();
        $user->removeParticipation($lesson);
        $this->getDoctrine()->getManager()->flush();
        $response = $request->headers->get('referer') ?? $this->generateUrl('planning');
        return new RedirectResponse($response);
    }

    /**
     * @Route("/profile", name="user.profile")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     */
    public function profile(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $form = $this->createForm(ChangePasswordType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $user = $this->getUser();
            $password = $passwordEncoder->encodePassword($user, $form->getData()['password']);

            $user->setPassword($password);
            $this->getDoctrine()->getManager()->flush();

            return $this->render('user/index.html.twig', [
                'success' => true,
                'form' => $form->createView()
            ]);
        }

        return $this->render('user/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/checkout/{tarif}", name="user.checkout")
     * @param Tarif $tarif
     * @param Request $request
     * @return Response
     */
    public function checkout(Tarif $tarif, Request $request)
    {
        $response = new Response();
        if ($request->isMethod("POST"))
        {
            if ($this->isCsrfTokenValid('checkout' . $tarif->getId(), $request->request->get('_token')))
            {
                $this->getUser()->setSubscription($tarif);
                $this->getDoctrine()->getManager()->flush();
                $response = $this->render('home/checkout.html.twig', [
                    'success' => true
                ]);
                $response->headers->set('Refresh', '3; url='. $this->generateUrl('user.profile'));
            }
        }
        else {
            $response = $this->render('home/checkout.html.twig', [
                'tarif' => $tarif
            ]);
        }
        return $response;
    }

    /**
     * @Route("/cancelsub", name="user.cancelsub")
     */
    public function cancelSub()
    {
        $user = $this->getUser();
        $user->setSubscription(null);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('user.profile');
    }

}
