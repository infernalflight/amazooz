<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 *
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    public function getBooksByAuthor2()
    {
        $dql = "SELECT book 
                FROM App\Entity\Book book 
                WHERE book.title LIKE :title AND book.author LIKE :author
                ORDER BY book.nbPage DESC";

        $query = $this->getEntityManager()->createQuery($dql);

        $query->setMaxResults(1);

        $query->setParameter('title', '%ti%');
        $query->setParameter('author', '%au%');

        dd($query->getResult());
    }

    public function getBooksByAuthor($author = null, $title = null)
    {
        $query = $this->createQueryBuilder('book');
        $query->orderBy('book.nbPage', 'DESC');

        if (!is_null($author)) {
            $query->andWhere('book.author LIKE :author')
                ->setParameter('author', '%' . $author . '%');
        }

        if (!is_null($title)) {
            $query->andWhere('book.title LIKE :title')
                ->setParameter('title', '%' . $title . '%');
        }


        $query->

        $query->setMaxResults(1);

        $query->setParameter('title', '%ti%');
        $query->setParameter('author', '%au%');

        dd($query->getQuery()->getResult());
    }

    public function findBooksWithCategory() {
        return $this->createQueryBuilder('b')
            ->addOrderBy('b.title', 'ASC')
            ->leftJoin('b.category', 'category')
            ->addSelect('category')
            ->setFirstResult(10)
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();
    }


}
