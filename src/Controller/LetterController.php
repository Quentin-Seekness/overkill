<?php

namespace App\Controller;

use DateTime;
use Knp\Snappy\Pdf;
use App\Entity\Letter;
use App\Form\LetterType;
use App\Service\isDeOrD;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
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

            // we instanciate our service as it needs an argument letter (so no injection possible)
            $isDeOrD = new isDeOrD($letter);
            //we use our service to determine wether we'll use a "de" or a "d' " in front of the company and the job name
            $isDeOrD->setCompanyDStatus();
            $isDeOrD->setJobDStatus();

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

    /**
     *  Will use wkhtmltopdf and snappyBundle to convert the html letter into pdf
     * 
     * @Route("/letter/download/{id}", name="letter_download")
     */
    public function letterToPdf(Letter $letter, Pdf $pdf)
    {
        $companyName =  $letter->getCompanyName();

        $html = $this->renderView('letter/letter.html.twig', [
            'letter' => $letter,
        ]);

        return new PdfResponse($pdf->getOutputFromHtml($html, [
            'page-size' => 'A4',
            'encoding' => 'UTF-8',
            'margin-top' => '0mm',
            'margin-bottom' => '0mm',
            'margin-left' => '0mm',
            'margin-right' => '0mm',
            'zoom' => '2',
            'bypass-proxy-for' => false,
        ]), 'Cover_letter_' . $companyName . '.pdf');

    }
}
