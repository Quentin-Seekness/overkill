<?php

namespace App\Controller;

use App\Entity\Letter;
use App\Form\LetterType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

// bundle for pdf generation
use Dompdf\Dompdf;
use Dompdf\Options;

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

    /**
     * @Route("/letter/download/{id}", name="letter_download")
     */
    public function letterToPdf(Letter $letter)
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->set('isHtml5ParserEnabled', true);
        $pdfOptions->set('isRemoteEnabled', true);
        //utile ?
        $pdfOptions->set('isJavascriptEnabled', true);
        $pdfOptions->set('isFontSubsettingEnabled', true);
        
        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('letter/letter.html.twig', [
            'letter' => $letter,
        ]);
        //! TEST chargement css 
        // $html .= '<link type="text/css" href="/perso/overkill/public/css/app.css" rel="stylesheet" />';
        $html .= '<img src="../../../../images/letter_bg01.jpg">';

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);
        
        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => false
        ]);

        // dd($dompdf);
        die;
    }
}
