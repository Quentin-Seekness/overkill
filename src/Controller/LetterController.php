<?php

namespace App\Controller;

use App\Entity\Letter;
use App\Form\LetterType;
use ContainerEvtxBZr\getKnpSnappy_PdfService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\SnappyBundle\DependencyInjection\KnpSnappyExtension;
use Knp\Bundle\SnappyBundle\KnpSnappyBundle;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LetterController extends AbstractController
{
    /**
     * @Route("/letter/add", name="letter_add", methods={"GET", "POST"})
     */
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        // instanciation of a new letter object
        $letter = new Letter();
        // creation of the form giving it the correct entity it must be associate with
        $form = $this->createForm(LetterType::class, $letter);
        // we give the data from the request to the form
        $form->handleRequest($request);

        // is the form submitted and valid ?
        if($form->isSubmitted() && $form->isValid()){
            // we give the new new letter object a datetime
            $letter->setCreatedAt(new DateTime());
            // the manager will save the new entity
            $entityManager->persist($letter);
            $entityManager->flush();

            return $this->render('letter/read.html.twig', [
                'letter' => $letter,
            ]);
        }

        // as long as the form is not submitted or valid we display the create view
        return $this->render('letter/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
