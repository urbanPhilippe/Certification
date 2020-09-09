<?php

namespace App\Controller;

use App\Entity\Document;
use App\Form\DocumentType;
use App\Repository\DocumentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/document/admin")
 */
class DocumentController extends AbstractController
{
    /**
     * @Route("/text", name="document_index_texts", methods={"GET"})
     */
    public function indexTexts(DocumentRepository $documentRepository): Response
    {
        $texts = $documentRepository->findBy(['type' => Document::TEXT]);

        return $this->render('document/index_texts.html.twig', [
            'texts' => $texts,
        ]);
    }

    /**
     * @Route("/image", name="document_index_images", methods={"GET"})
     */
    public function indexImages(DocumentRepository $documentRepository): Response
    {
        $images = $documentRepository->findBy(['type' => Document::IMAGE]);

        return $this->render('document/index_images.html.twig', [
            'images' => $images,
        ]);
    }

    /**
     * @Route("/new/text", name="document_new_text", methods={"GET","POST"})
     */
    public function newText(Request $request): Response
    {
        $document = new Document();
        $form = $this->createForm(DocumentType::class, $document);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $document->setType(Document::TEXT);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($document);
            $entityManager->flush();

            $this->addFlash(
                'primary',
                'Document ajouté'
            );

            return $this->redirectToRoute('document_index_texts');
        }

        return $this->render('document/new_text.html.twig', [
            'document' => $document,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/new/image", name="document_new_image", methods={"GET","POST"})
     */
    public function newImage(Request $request): Response
    {
        $document = new Document();
        $form = $this->createForm(DocumentType::class, $document);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $document->setType(Document::IMAGE);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($document);
            $entityManager->flush();

            $this->addFlash(
                'primary',
                'Image ajoutée'
            );

            return $this->redirectToRoute('document_index_images');
        }

        return $this->render('document/new_image.html.twig', [
            'document' => $document,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/text/{id}", name="document_show_text", methods={"GET"})
     */
    public function showText(Document $document): Response
    {
        return $this->render('document/show_text.html.twig', [
            'document' => $document,
        ]);
    }

    /**
     * @Route("/image/{id}", name="document_show_image", methods={"GET"})
     */
    public function showImage(Document $document): Response
    {
        return $this->render('document/show_image.html.twig', [
            'document' => $document,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="document_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Document $document): Response
    {
        $form = $this->createForm(DocumentType::class, $document);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('document_index');
        }

        return $this->render('document/edit.html.twig', [
            'document' => $document,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/text/{id}", name="document_delete_text", methods={"DELETE"})
     */
    public function deleteText(Request $request, Document $document): Response
    {
        if ($this->isCsrfTokenValid('delete'.$document->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($document);
            $entityManager->flush();

            $this->addFlash(
                'primary',
                'Document supprimé'
            );
        }

        return $this->redirectToRoute('document_index_texts');
    }

    /**
     * @Route("/image/{id}", name="document_delete_image", methods={"DELETE"})
     */
    public function deleteImage(Request $request, Document $document): Response
    {
        if ($this->isCsrfTokenValid('delete'.$document->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($document);
            $entityManager->flush();

            $this->addFlash(
                'primary',
                'Image supprimée'
            );
        }

        return $this->redirectToRoute('document_index_images');
    }
}
