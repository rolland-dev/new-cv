<?php

namespace App\Controller;

use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CvController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function index()
    {
        return $this->render('cv/index.html.twig', [
            'controller_name' => 'CvController'
        ]);
    }

    /**
     * @Route("/presentation", name="presentation")
     */
    public function presentation()
    {
        return $this->render('cv/presentation.html.twig');
    }

     /**
     * @Route("/competences", name="competences")
     */
    public function competences()
    {
        return $this->render('cv/competences.html.twig');
    }

    /**
     * @Route("/formations", name="formations")
     */
    public function formations()
    {
        return $this->render('cv/formations.html.twig');
    }

    /**
     * @Route("/projet", name="projet")
     */
    public function projet()
    {
        return $this->render('cv/projet.html.twig');
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact(Request $request, EntityManagerInterface $em,  MailerInterface $mail)
    {

        $form = $this->createForm(ContactType::class);

        $form -> handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = (new TemplatedEmail())
            -> from('didier@rolland-dev.fr')
            -> to($form->get('email')->getData())
            ->subject('Demande diverse')
            ->htmlTemplate('cv/message.html.twig') 
            ->context([
                'mail'=> $form->get('email')->getData(),
                'prenom'=>$form->get('prenom')->getData(),
                'nom'=>$form->get('nom')->getData(),
                'message'=>$form->get('demande')->getData(),
            ]);
                
            $mail->send($email);

            $this->addFlash('message', 'Votre demande a été envoyé');
            return $this->redirectToRoute('accueil');
        }


        return $this->render('cv/contact.html.twig',[
            'formContact' => $form->createView()
        ]);
    }


}
