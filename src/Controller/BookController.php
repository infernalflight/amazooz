<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BookController extends AbstractController
{

    #[Route('/book/create', name:'book_create')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $book = new Book();

        $form = $this->createForm(BookType::class, $book);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($book);

            $em->flush();

            $this->addFlash('success', 'Un livre a été enregistré');

            return $this->redirectToRoute('book_list');

        }


        return $this->render('book/edit.html.twig', [
            'book_form' => $form
        ]);

    }

    #[Route('/book/list', name: 'book_list')]
    public function list(): Response
    {
        return new Response('Ceci est la liste');
    }


}
