<?php

namespace App\Repository;

use App\Entity\Cliente;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method Cliente|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cliente|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cliente[]    findAll()
 * @method Cliente[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClienteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cliente::class);
    }

    public function getAllClientes() {
        return $this->createQueryBuilder('cliente')->select('cliente.id', 'cliente.Nombre', 'cliente.Apellidos', 'cliente.Direccion', 'cliente.Email', 'cliente.Instagram')->orderBy('cliente.id')->getQuery()->getResult();
    }

    public function getCliente($id) {
        try {
            return $this->createQueryBuilder('cliente')->where("cliente.id = $id")->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
        }
    }
    public function buscarPorInstagram($ig) {
        $qb = $this->createQueryBuilder('cliente');
        $ig = $qb->expr()->literal($ig);
        try {
            return $this->createQueryBuilder('cliente')->where("cliente.Instagram = $ig")->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
        }
    }

    public function nuevoCliente($form) {
        $em = $this->getEntityManager();
        try {
            $em->persist($form);
        } catch (ORMException $e) {
        }
        try {
            $em->flush();
        } catch (OptimisticLockException $e) {
        } catch (ORMException $e) {
        }
    }
    public function validateInstagram($instagram) {
        try {
            return $this->createQueryBuilder('cliente')->select('cliente.Instagram')->where('cliente.Instagram = :instagram')->setParameter('instagram', $instagram)->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
        }
    }
    // /**
    //  * @return Cliente[] Returns an array of Cliente objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Cliente
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
