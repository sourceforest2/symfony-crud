<?php

namespace App\Controller;

use App\Entity\JobOffer;
use App\Form\JobOfferType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class JobOfferController extends AbstractController
{
    /**
     * @Route("/", name="offers")
     */
    public function index(): Response
    {

        $entityManager = $this->getDoctrine()->getManager();
        $jobOffers = $entityManager->getRepository(JobOffer::class)->findAll();

        return $this->render('index.html.twig', [
            'jobOffers' => $jobOffers,
        ]);

    }

    /**
     * @Route("/create", name="create", methods={"GET", "POST"})
     */
    public function create(Request $request): Response
    {
        $jobOffer = new JobOffer();

        // Handle form submission
        $form = $this->createForm(JobOfferType::class, $jobOffer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Save the job offer to the database
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($jobOffer);
            $entityManager->flush();

            return $this->redirectToRoute('read', ['id' => $jobOffer->getId()]);
        }

        return $this->render('create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="read", methods={"GET"})
     */
    public function read($id): Response
    {
        $jobOffer = $this->getDoctrine()->getRepository(JobOffer::class);
        $jobOffer = $jobOffer->find($id);

        if (!$jobOffer) {
            throw $this->createNotFoundException(
                'No JobOffer for id: ' . $id
            );
        }
        return $this->render(
            'read.html.twig',
            array('jobOffer' => $jobOffer)
        );
    }

    /**
     * @Route("update/{id}", name="update", methods={"GET", "POST"})
     */
    public function update(Request $request, int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $jobOffer = $entityManager->getRepository(JobOffer::class)->find($id);
    
        if (!$jobOffer) {
            throw $this->createNotFoundException('Job Offer not found.');
        }
    
        $form = $this->createForm(JobOfferType::class, $jobOffer);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Save the updated $jobOffer to the database
            $entityManager->flush();
    
            return $this->redirectToRoute('read', ['id' => $jobOffer->getId()]);
        }
    
        return $this->render('update.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete", methods={"DELETE"})
     */
    public function delete(Request $request, $id): JsonResponse
    {
        $entityManager=$this->getDoctrine()->getManager(); 
        $jobOffer=$entityManager->getRepository(jobOffer::class); 
        $jobOffer=$jobOffer->find($id); 
        $entityManager->remove($jobOffer); 
        $entityManager->flush(); 

        return new JsonResponse(['message' => 'Job offer deleted successfully']);
    }


}
