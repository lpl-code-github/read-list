<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
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

    public function save(Book $entity, bool $flush = false): bool
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
        return true;
    }

    public function remove(Book $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function updateBookPageNum(Book $entity): string|int|null|float
    {
        $queryBuilder = $this->createQueryBuilder('b')
            ->update('App:Book','b')
            ->set('b.pageNum',$entity->getPageNum())
            ->andWhere('b.id =: id')
            ->setParameter('id', $entity->getId())
            ->andWhere('b.active =:active')
            ->setParameter("active",IS_NOT_DELETED)
            ->getQuery();
        return  $queryBuilder->execute();
    }

    /**
     * @return Paginator Returns an array of BookVo objects
     */
    public function findBookList(string $searchContent="",int $page=DEFAULT_PAGE): Paginator
    {

        $queryBuilder = $this->createQueryBuilder('b');
        // 分页偏移量
        $offset = ($page-1)*DEFAULT_SIZE;

        if ($searchContent !=""){
            $queryBuilder->andWhere('b.bookTitle LIKE :bookTitle')
                ->setParameter('bookTitle', '%'.$searchContent.'%')
                ->orWhere('b.bookAuthor LIKE :bookAuthor')
                ->setParameter('bookAuthor','%'.$searchContent.'%');
        }
        $queryBuilder->andWhere("b.active = :active")
            ->setParameter('active',IS_NOT_DELETED)
            ->andWhere('b.status = :status')
            ->setParameter("status",BOOK_VISIBLE)
            ->orderBy('b.createTime', 'DESC')
            ->setMaxResults(DEFAULT_SIZE)
            ->setFirstResult($offset)
            ->getQuery();


//        dd($queryBuilder);
        return new Paginator($queryBuilder);
    }


//    /**
//     * @return BookVo[] Returns an array of BookVo objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?BookVo
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
