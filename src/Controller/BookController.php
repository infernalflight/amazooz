<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use App\Service\Censurator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/book', name:'book')]
#[IsGranted('ROLE_USER')]
class BookController extends AbstractController
{
    #[Route('/create', name:'_create')]
    #[IsGranted('ROLE_CONTRIB')]
    public function create(Request $request, EntityManagerInterface $em, SluggerInterface $slugger, Censurator $censurator): Response
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

            $purifiedTitle = $censurator->purify($book->getTitle());
            $book->setTitle($purifiedTitle);

            $em->persist($book);
            $em->flush();

            $this->addFlash('success', 'Un livre a été enregistré');
            return $this->redirectToRoute('book_list');

        }

        return $this->render('book/edit.html.twig', [
            'book_form' => $form
        ]);
    }

    #[Route('/update/{id}', name:'_update', requirements: ['id' => '\d+'])]
    #[IsGranted('ROLE_ADMIN')]
    public function update(Book $book, Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('picture_file')->getData() instanceof UploadedFile) {
                $pictureFile = $form->get('picture_file')->getData();
                $fileName = $slugger->slug($book->getTitle()) . '-' . uniqid() . '.' . $pictureFile->guessExtension();
                $pictureFile->move($this->getParameter('picture_dir'), $fileName);

                if (!empty($book->getPicture())) {
                    $picturePath = $this->getParameter('picture_dir') . '/' . $book->getPicture();
                    if (file_exists($picturePath)) {
                        unlink($picturePath);
                    }
                }

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

    #[Route('/list', name: '_list')]
    public function list(BookRepository $bookRepository): Response
    {
        $books = $bookRepository->findBooksWithCategory();

        return $this->render('book/index.html.twig', [
            'books' => $books
        ]);
    }

    #[Route('/detail/{id}', name: '_detail', requirements: ['id' => '\d+'])]
    public function detail(Book $book): Response
    {
        return $this->render('book/detail.html.twig', [
            'book' => $book
        ]);

    }


}
