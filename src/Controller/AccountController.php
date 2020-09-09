<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ResetPasswordType;
use App\Form\VerifyPasswordType;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Form\UserType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{

    /**
     * @Route("/user/reset/{user}", name="password_reset", methods={"GET","POST"})
     * @param User $user
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     * @throws Exception
     */
    public function reset(
        User $user,
        Request $request,
        UserPasswordEncoderInterface $encoder
    ): Response {
        $form = $this->createForm(ResetPasswordType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $user->getPassword();
            $encoded = $encoder->encodePassword($user, $plainPassword);

            $user->setPassword($encoded);

            $manager = $this->getDoctrine()->getManager();

            $manager->persist($user);
            $manager->flush();

            try {
                $mail = new PHPMailer(true);

                /*Enable verbose debug output*/
                $mail->SMTPDebug = SMTP::DEBUG_SERVER;
                /* Tells PHPMailer to use SMTP. */
                $mail->isSMTP();
                /* SMTP server address. */
                $mail->Host = $this->getParameter('mail_server');
                /* Use SMTP authentication. */
                $mail->SMTPAuth = true;
                /* SMTP authentication username. */
                $mail->Username = $this->getParameter('mail_from');
                /* SMTP authentication password. */
                $mail->Password = $this->getParameter('mail_password');
                /* Set the encryption system. */
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                /* Set the SMTP port. */
                $mail->Port = 587;
                $mail->setFrom($this->getParameter('mail_from'));
                $mail->addAddress($user->getEmail());
                $mail->isHTML(true);
                $mail->Subject = utf8_decode('Nemea On Board : modification du mot de passe');
                $firstname = $user->getFirstname();
                $lastname = $user->getLastname();
                $mail->Body = utf8_decode("Bonjour $firstname $lastname, <br>
                                          Votre mot de passe d'accès à <i>Nemea On Board</i> a été modifié. <br> 
                                          Si vous n'avez pas effectué ou demandé ce changement, 
                                          contactez le service Ressources Humaines.<br><br>
                                          Message automatique envoyé depuis <i>Nemea On Board</i>");

                /* Disable some SSL checks. */
                $mail->SMTPOptions = array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                );

                $mail->send();
                $this->addFlash(
                    'primary',
                    'Mot de passe modifié avec succès !'
                );
            } catch (Exception $exception) {
                $this->addFlash(
                    'primary',
                    'Mot de passe modifié avec succès !'
                );
            }

            return new RedirectResponse($this->generateUrl('profile', [
                'user' => $user->getId(),
            ]));
        }
        return $this->render('security/reset.html.twig', [
            'form' => $form->createView(),
            'User' => $user,
        ]);
    }

    /**
     * @Route("/user/verify/{user}", name="password_verify", methods={"GET","POST"})
     * @param User $user
     * @param Request $request
     * @return Response
     */
    public function verify(User $user, Request $request): Response
    {
        $hash = $user->getPassword();
        $form = $this->createForm(VerifyPasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (password_verify($form['password']->getData(), $hash)) {
                return new RedirectResponse($this->generateUrl('password_reset', [
                    'user' => $user->getId(),
                ]));
            } else {
                $this->addFlash('danger', "Le mot de passe n'est pas bon !");
            }
        }

        return $this->render('security/verify.html.twig', [
            'form' => $form->createView(),
            'User' => $user,
        ]);
    }
}
