<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class BookController extends AbstractController
{

    #[Route('/book/create', name:'book_create')]
    public function create(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($form->get('picture_file')->getData() instanceof UploadedFile) {
                $pictureFile = $form->get('picture_file')->getData();
                $fileName = $slugger->slug($book->getTitle()) . '-' . uniqid() . '.' . $pictureFile->guessExtension();
                $pictureFile->move($this->getParameter('picture_dir'), $fileName);
                $book->setPicture($fileName);
            }

            $em->persist($book);
            $em->flush();

            $this->addFlash('success', 'Un livre a été enregistré');
            return $this->redirectToRoute('book_list');

        }


        return $this->render('book/edit.html.twig', [
            'book_form' => $form
        ]);
    }

    #[Route('/book/update/{id}', name:'book_update', requirements: ['id' => '\d+'])]
    public function update(int $id, Request $request, EntityManagerInterface $em, BookRepository $bookRepository, SluggerInterface $slugger): Response
    {
        $book = $bookRepository->find($id);
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($form->get('picture_file')->getData() instanceof UploadedFile) {
                $pictureFile = $form->get('picture_file')->getData();
                $fileName = $slugger->slug($book->getTitle()) . '-' . uniqid() . '.' . $pictureFile->guessExtension();
                $pictureFile->move($this->getParameter('picture_dir'), $fileName);
                $book->setPicture($fileName);
            }

            $em->persist($book);
            $em->flush();

            $this->addFlash('success', 'Un livre a été modifié');
            return $this->redirectToRoute('book_list');

        }

        return $this->render('book/edit.html.twig', [
            'book_form' => $form
        ]);

    }

    #[Route('/book/list', name: 'book_list')]
    public function list(BookRepository $bookRepository): Response
    {
        $books = $bookRepository->findAll();

        return $this->render('book/index.html.twig', [
            'books' => $books
        ]);
    }

    #[Route('/book/detail/{id}', name: 'book_detail', requirements: ['id' => '\d+'])]
    public function detail(int $id, BookRepository $bookRepository): Response
    {
        $book = $bookRepository->find($id);

        return $this->render('book/detail.html.twig', [
            'book' => $book
        ]);

    }


}
