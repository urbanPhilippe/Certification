<?php

namespace App\Controller;

use App\Entity\ChecklistItem;
use App\Entity\User;
use App\Form\ChecklistItemType;
use App\Form\UserTypeChecklist;
use App\Repository\ChecklistItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/checklistitem", name="checklist_item_")
 */
class ChecklistItemController extends AbstractController
{
    /**
     * @Route("/checklist/{user}", name="checklist")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param User $user
     * @return Response
     */
    public function checklist(Request $request, EntityManagerInterface $entityManager, User $user): Response
    {
        $form = $this->createForm(UserTypeChecklist::class, $user, ['write_right' => true]);
        $form->handleRequest($request);

        $totalItems = count($this->getDoctrine()->getRepository(ChecklistItem::class)->findAll());
        $userItems = count($user->getChecklistItems());

        $percent = ($userItems * 100) / $totalItems;

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash(
                'primary',
                'Vos changements ont été sauvegardés !'
            );
            $entityManager->flush();
        }
        return $this->render('checklist.html.twig', [
            'form' => $form->createView(),
            'percent' => $percent,
        ]);
    }

    /**
     * @Route("/admin/index", name="index", methods={"GET"})
     * @param ChecklistItemRepository $checklistItemRepository
     * @return Response
     */
    public function index(ChecklistItemRepository $checklistItemRepository): Response
    {
        return $this->render('checklist_item/index.html.twig', [
            'checklist_items' => $checklistItemRepository->findAll(),
        ]);
    }

    /**
     * @Route("/admin/new", name="new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $checklistItem = new ChecklistItem();
        $form = $this->createForm(ChecklistItemType::class, $checklistItem);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($checklistItem);
            $entityManager->flush();

            $this->addFlash(
                'primary',
                'Modification prise en compte'
            );

            return $this->redirectToRoute('checklist_item_index');
        }

        return $this->render('checklist_item/new.html.twig', [
            'checklist_item' => $checklistItem,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/{id}", name="show", methods={"GET"})
     * @param ChecklistItem $checklistItem
     * @return Response
     */
    public function show(ChecklistItem $checklistItem): Response
    {
        return $this->render('checklist_item/show.html.twig', [
            'checklist_item' => $checklistItem,
        ]);
    }

    /**
     * @Route("/admin/{id}/edit", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param ChecklistItem $checklistItem
     * @return Response
     */
    public function edit(Request $request, ChecklistItem $checklistItem): Response
    {
        $form = $this->createForm(ChecklistItemType::class, $checklistItem);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'primary',
                'Modification prise en compte'
            );

            return $this->redirectToRoute('checklist_item_index');
        }

        return $this->render('checklist_item/edit.html.twig', [
            'checklist_item' => $checklistItem,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param ChecklistItem $checklistItem
     * @return Response
     */
    public function delete(Request $request, ChecklistItem $checklistItem): Response
    {
        if ($this->isCsrfTokenValid('delete'.$checklistItem->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($checklistItem);
            $entityManager->flush();

            $this->addFlash(
                'primary',
                'Modification prise en compte'
            );
        }

        return $this->redirectToRoute('checklist_item_index');
    }
}
