<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\InscriptionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription", name="security_inscription")
     */
    public function inscription(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder, MailerInterface $mail)
    {
        $user = new User();

        $form = $this->createForm(InscriptionType::class, $user);

        $form -> handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $hash = $encoder->encodePassword($user,$user->getPassword());

            $user->setPassword($hash);

            // on génère le token
            $user->setActivationToken(md5(uniqid()));

            $em->persist($user);
            $em->flush();

            $email = (new TemplatedEmail())
            -> from('didier@rolland-dev.fr')
            -> to($form->get('email')->getData())
            ->subject('Activation de votre compte')
            ->html(
                $this->renderView('security/activation.html.twig',[
                'token'=>$user->getActivationToken()])
                );
                
            $mail->send($email);

            $this->addFlash('message', 'Un mail de validation vous a été envoyé');

            // envoi mail inscription a l'admin

            $email = (new TemplatedEmail())
            -> from('didier@rolland-dev.fr')
            -> to('didier@rolland-de.fr')
            ->subject('Nouvelle inscription')
            ->htmlTemplate('cv/message2.html.twig') 
            ->context([
                'email'=> $form->get('email')->getData(),
                'prenom'=>'CGU ok',
                'nom'=>'',
                'message'=>'',
            ]);
            $mail->send($email);

            return $this->redirectToRoute('login');
        }

        return $this->render('security/inscription.html.twig', [
            'form' => $form->createView()
        ]);  
    }

    /**
     * @Route("activation/{token}", name="active")
     */
    public function activation($token){

        $repo = $this->getDoctrine()->getRepository(User::class);

        // Verif si user a ce token
        $user = $repo->findOneBy(['activation_token'=> $token]);

        // si aucun user
        if(!$user){
            // erreur 404
            throw $this->createNotFoundException('Pas d\'utilisateur connu');
        }
        // on supp le token
        $user->setActivationToken(null);
        $entityManager= $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        // envoi flash compte ok
        $this->addFlash('message','Votre compte est bien validé');

        // retour accueil
        return $this-> redirectToRoute('accueil');
    }

    /**
     * @Route("connexion", name="security_login")
     */
    public function login(){

        $repo = $this->getDoctrine()->getRepository(User::class);
        $users = $repo->findAll();

            return $this->render('security/connexion.html.twig',[
                'users'=> $users,
            ]);
            
            
    }

    /**
     * @Route("deconnexion", name="security_logout")
     */
    public function logout()
    {
      
    }
}
