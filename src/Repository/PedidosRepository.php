<?php

namespace App\Repository;

use App\Entity\Cliente;
use App\Entity\Pedidos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method Pedidos|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pedidos|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pedidos[]    findAll()
 * @method Pedidos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PedidosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pedidos::class);
    }
    public function getPedidosSinEnviar() : ?array {
        $pedidos =  $this->createQueryBuilder('pedidos')->andWhere('pedidos.Enviado = 0')->select('pedidos.id', 'pedidos.FechaCreacion', 'pedidos.PrecioFinal', 'c.Nombre', 'c.Apellidos', 'c.Instagram', 'c.Email')->innerJoin('pedidos.IdCliente', 'c')->getQuery()->getResult();
        for ($i = 0; $i < count($pedidos); $i++) {
            $pedidos[$i]['FechaCreacion'] = $pedidos[$i]['FechaCreacion']->format('Y-m-d H:i:s');
        }
        return $pedidos;
    }

    public function getPedido($id) {
        try {
            return $this->createQueryBuilder('pedidos')->where("pedidos.id = $id")->select('c.Nombre', 'c.Apellidos', 'c.Instagram', 'c.Direccion', 'c.Email', 'pedidos.PrecioFinal', 'pedidos.FechaCreacion', 'pedidos.FechaRecibido', 'pedidos.Devuelto', 'pedidos.Recibido', 'pedidos.Tracking', 'pedidos.Enviado', 'pedidos.Articulos', 'pedidos.id')->innerJoin('pedidos.IdCliente', 'c')->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
        }
    }

    public function modifyPedido($tracking , $enviado, $recibido, $devuelto, $id) {
        $qb = $this->createQueryBuilder('pedidos');
        return $qb->update()->set('pedidos.Tracking', $qb->expr()->literal($tracking))->set('pedidos.Enviado', $enviado)->set('pedidos.Recibido', $recibido)->set('pedidos.Devuelto', $devuelto)->where("pedidos.id = :pedidoId")->setParameter('pedidoId', $id)->getQuery()->execute();
    }
    // /**
    //  * @return Pedidos[] Returns an array of Pedidos objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Pedidos
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
