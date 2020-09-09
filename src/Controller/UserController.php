<?php

namespace App\Controller;

use App\Entity\ChecklistItem;
use App\Entity\IntegrationStep;
use App\Entity\User;
use App\Entity\UserSearch;
use App\Form\UserSearchType;
use App\Form\UserType;
use App\Form\UserTypeChecklist;
use App\Entity\Role;
use App\Repository\AppointmentRepository;
use App\Repository\IntegrationStepRepository;
use App\Repository\ResidenceRepository;
use App\Repository\UserRepository;
use App\Service\TimelineService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     * @return Response
     */
    public function adminPage(): Response
    {
        return $this->render('admin.html.twig');
    }

    /**
     * @Route("/profile/{user}", name="profile")
     * @param User $user
     * @param TimelineService $timelineService
     * @param AppointmentRepository $appointmentRepository
     * @param Request $request
     * @return Response
     */
    public function profile(
        User $user,
        TimelineService $timelineService,
        AppointmentRepository $appointmentRepository,
        Request $request
    ): Response {
        //Checklist progress bar
        $totalItems = count($this->getDoctrine()->getRepository(ChecklistItem::class)->findAll());
        $userItems = count($user->getChecklistItems());

        $percentChecklist = ($userItems * 100) / $totalItems;

        //Integration progress bar
        $steps = $this->getDoctrine()->getRepository(IntegrationStep::class)->findAll();
        $totalSteps = count($steps);
        $startDate = $user->getStartDate();
        $statuses = $timelineService->generate($steps, $startDate);
        if (in_array('completed', $statuses)) {
            $completedSteps = (array_count_values($statuses)['completed']);
        } else {
            $completedSteps = 0;
        }


        $percentIntegration = ($completedSteps * 100) / $totalSteps;

        $appointments = [];
        if (in_array('ROLE_MANAGER', $user->getRoles())) {
            $appointments = $appointmentRepository->findBy(['partner' => $user->getId()]);
        } elseif (in_array('ROLE_COLLABORATOR', $user->getRoles())) {
            $appointments = $appointmentRepository->findBy(['user' => $user->getId()]);
        }

        usort($appointments, function ($a, $b) {
            return ($a->getDate()) <=> ($b->getDate());
        });

        $session = $request->getSession();

        $session->set('from', $user->getId());

        return $this->render('user/profile.html.twig', [
            'user' => $user,
            'percentChecklist' => $percentChecklist,
            'percentIntegration' => $percentIntegration,
            'appointments' => $appointments,
        ]);
    }

    /**
     * @Route("/{user}/collaborators", name="manager_show", methods={"GET"})
     * @param User $user
     * @return Response
     */
    public function showCollaborators(User $user): Response
    {
        return $this->render('manager/collaborator.html.twig', [
            'collaborators' => $user->getCollaborators(),
        ]);
    }

    /**
     * @Route("/manager/collaborator/{user}", name="collaborator_checklist", methods={"GET"})
     * @param User $user
     * @return Response
     */
    public function showCollaboratorChecklist(User $user): Response
    {
        $form = $this->createForm(UserTypeChecklist::class, $user);

        $totalItems = count($this->getDoctrine()->getRepository(ChecklistItem::class)->findAll());
        $userItems = count($user->getChecklistItems());

        $percent = ($userItems * 100) / $totalItems;

        return $this->render('checklist.html.twig', [
            'collaborator' => $user,
            'form' => $form->createView(),
            'percent' => $percent,
        ]);
    }


    /**
     * @Route("/admin/index", name="user_index", methods={"GET"})
     * @param UserRepository $userRepository
     * @return Response
     */
    public function index(UserRepository $userRepository, Request $request): Response
    {
        $search = new UserSearch();

        $form = $this->createForm(UserSearchType::class, $search);
        $form->handleRequest($request);

        $users = $userRepository->searchUser($search);

        return $this->render('user/index.html.twig', [
            'users' => $users,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/new", name="user_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($user->getRole()->getIdentifier() === 'collab') {
                $user->setRoles(['ROLE_COLLABORATOR']);
            }
            if ($user->getRole()->getIdentifier() === 'manager') {
                $user->setRoles(['ROLE_MANAGER']);
            }
            if ($user->getRole()->getIdentifier() === 'admin') {
                $user->setRoles(['ROLE_ADMIN']);
            }

            $plainPassword = $user->getPassword();
            $encoded = $passwordEncoder->encodePassword($user, $plainPassword);
            $user->setPassword($encoded);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash(
                'primary',
                'Utilisateur ajouté'
            );

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/{id}", name="user_show", methods={"GET"})
     * @param User $user
     * @return Response
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/admin/{id}/edit", name="user_edit", methods={"GET","POST"})
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user, ['password_disabled' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($user->getRole()->getIdentifier() === 'collab') {
                $user->setRoles(['ROLE_COLLABORATOR']);
            }
            if ($user->getRole()->getIdentifier() === 'manager') {
                $user->setRoles(['ROLE_MANAGER']);
            }
            if ($user->getRole()->getIdentifier() === 'admin') {
                $user->setRoles(['ROLE_ADMIN']);
            }

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'primary',
                'Modification prise en compte'
            );

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/{id}", name="user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();

            $this->addFlash(
                'primary',
                'Utilisateur supprimé'
            );
        }

        return $this->redirectToRoute('user_index');
    }
}
