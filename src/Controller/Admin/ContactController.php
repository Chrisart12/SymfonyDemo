<?php

namespace App\Controller\Admin;

use App\Entity\Contact;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function index(Request $request, EntityManagerInterface $entityManagerInterface, MailerInterface $mailer): Response
    {
        $contact = new Contact();

        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
        
            // $entityManagerInterface->persist($contact);
            // $entityManagerInterface->flush();

            $email = (new TemplatedEmail())
                ->from( $contact->getEmail())
                ->to($contact->getService())
                // ->to('issaissifou@hotmail.com')
                ->subject('Demande de contact')
                // ->text('Sending emails is fun again!')
                ->htmlTemplate('emails/contact.html.twig')
                ->context([
                    'contact' => $contact,
                ]);

                try {
                    $mailer->send($email);
                } catch (TransportExceptionInterface $e) {
                    return new Response($e->getMessage());
                }


            // Ajout de message flash
            $this->addFlash(
                'success',
                'Votre message a été envoyé.'
            );

            return $this->redirectToRoute('contact');
        }

        
        return $this->render('contact/index.html.twig', [
            'contactForm' => $form,
        ]);
    }
}
