<?php

namespace App\Controller;

use DateTime;
use Knp\Snappy\Pdf;
use App\Entity\Letter;
use App\Form\LetterType;
use App\Repository\LetterRepository;
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
     * @Route("/", name="letter_add", methods={"GET", "POST"})
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

            return $this->redirectToRoute('letter_read', [
                'id' => $letter->getId(),
            ]);
        }

        // as long as the form is not submitted or valid we display the create view
        return $this->render('letter/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/letter/browse", name="letter_browse", methods={"GET"})
     */
    public function browse(LetterRepository $letterRepository): Response
    {
        $letters = $letterRepository->findAll();

        return $this->render('letter/browse.html.twig', [
            'letters' => $letters,
        ]);
    }

    /**
     * @Route("/letter/{id}", name="letter_read", methods={"GET"})
     */
    public function read(Letter $letter): Response
    {

        return $this->render('letter/read.html.twig', [
            'letter' => $letter,
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

    /**
     * @Route("/letter/delete/{id}", name="letter_delete", methods={"DELETE", "POST"})
     */
    public function delete(Letter $letter, Request $request, EntityManagerInterface $entityManager): Response
    {
        // we recover the token from the form
        $submittedToken = $request->request->get('token');

        // 'delete-challenge' is the same value used in the template to generate the token
        if (! $this->isCsrfTokenValid('delete-letter', $submittedToken)) {
            // We send an error an 403
            throw $this->createAccessDeniedException('Are you token to me !??!??');
        }

        $entityManager->remove($letter);
        $entityManager->flush();

        return $this->redirectToRoute('letter_browse');
    }
}
