<?php

namespace App\Repository;

use App\Entity\Factura;
use App\Entity\Pedidos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
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
        $pedidos =  $this->createQueryBuilder('pedidos')->andWhere('pedidos.Enviado = 0')->andWhere('factura.Pagado = 1')->select('pedidos.id', 'pedidos.FechaCreacion', 'pedidos.PrecioFinal', 'c.Nombre', 'c.Apellidos', 'c.Instagram', 'c.Email', 'factura.FechaPago')->innerJoin('pedidos.IdCliente', 'c')->innerJoin('pedidos.factura', 'factura')->orderBy('pedidos.id')->getQuery()->getResult();
        for ($i = 0; $i < count($pedidos); $i++) {
            $pedidos[$i]['FechaCreacion'] = $pedidos[$i]['FechaCreacion']->format('Y-m-d H:i:s');
            if ($pedidos[$i]['FechaPago'] != null) {
                $pedidos[$i]['FechaPago'] = $pedidos[$i]['FechaPago']->format('Y-m-d H:i:s');
            }
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
        $dateTime = date('Y-m-d H:i:s');
        if ($recibido == 1 && $enviado == 1) {
            $qb->update()->set('pedidos.FechaRecibido', $qb->expr()->literal($dateTime))->set('pedidos.Recibido', $recibido)->where("pedidos.id = :pedidoId")->setParameter('pedidoId', $id)->getQuery()->execute();
        }
        if ($recibido == 0 && $enviado == 1) {
            $qb->update()->set('pedidos.FechaEnviado', $qb->expr()->literal($dateTime))->set('pedidos.Enviado', $enviado)->where("pedidos.id = :pedidoId")->setParameter('pedidoId', $id)->getQuery()->execute();
        }
        if ($enviado == 1 && $recibido == 0 && $devuelto == 1) {
            $qb->update()->set('pedidos.FechaDevuelto', $qb->expr()->literal($dateTime))->set('pedidos.Devuelto', $devuelto)->where("pedidos.id = :pedidoId")->setParameter('pedidoId', $id)->getQuery()->execute();
        }
        // Reglas Inserción
        if ($recibido == 1 && $enviado == 0) {
            return new Response("No puedes recibir el pedido sin antes haberlo enviado");
        } elseif ($enviado == 0 && $devuelto == 1) {
            return new Response("No puedes devolver un pedido sin antes haberlo enviado");
        }elseif ($recibido == 1 && $devuelto == 1){
            return new Response("No se aceptan devoluciones, si lo han recibido no lo pueden devolver");
        } else {
        return $qb->update()->set('pedidos.Tracking', $qb->expr()->literal($tracking))->where("pedidos.id = :pedidoId")->setParameter('pedidoId', $id)->getQuery()->execute();
        }
    }

    public function getAllPedidos(){
        $pedidos =  $this->createQueryBuilder('pedidos')->select('pedidos.id', 'pedidos.FechaCreacion', 'pedidos.PrecioFinal', 'pedidos.Enviado', 'pedidos.Devuelto', 'c.Nombre', 'c.Apellidos', 'c.Instagram', 'c.Email', 'factura.Pagado', 'factura.FechaPago')->innerJoin('pedidos.IdCliente', 'c')->innerJoin('pedidos.factura', 'factura')->orderBy('pedidos.id')->getQuery()->getResult();
        for ($i = 0; $i < count($pedidos); $i++) {
            $pedidos[$i]['FechaCreacion'] = $pedidos[$i]['FechaCreacion']->format('Y-m-d H:i:s');
            if ($pedidos[$i]['FechaPago'] != null) {
                $pedidos[$i]['FechaPago'] = $pedidos[$i]['FechaPago']->format('Y-m-d H:i:s');
            }
        }
        return $pedidos;
    }

    public function generarPedido($articulos, $precio, $cliente) {

            $em = $this->getEntityManager();
            $pedido = new Pedidos();
            $pedido->setPrecioFinal($precio);
            $pedido->setArticulos($articulos);
            $pedido->setEnviado(0);
            $pedido->setDevuelto(0);
            $pedido->setRecibido(0);
            $pedido->setFechaCreacion(new \DateTime());
            $pedido->setIdCliente($cliente);
            try {
                $em->persist($pedido);
            } catch (ORMException $e) {
            }
            try {
                $em->flush();
            } catch (OptimisticLockException $e) {
            } catch (ORMException $e) {
            }
            $factura = new Factura();
            $factura->setIdCliente($cliente);
            $factura->setPagado(0);
            $factura->setFecha(new \DateTime());
            $factura->setIdPedido($pedido);
            try {
                $em->persist($factura);
            } catch (ORMException $e) {
            }
            try {
                $em->flush();
            } catch (OptimisticLockException $e) {
            } catch (ORMException $e) {
            }
    }

    public function getPedidosCliente($idCliente) {
        return $this->createQueryBuilder('pedidos')->select('pedidos.id', 'pedidos.FechaCreacion', 'pedidos.PrecioFinal', 'pedidos.Enviado', 'pedidos.Devuelto', 'c.Nombre', 'c.Apellidos', 'c.Instagram', 'c.Email', 'factura.Pagado', 'factura.FechaPago')->innerJoin('pedidos.IdCliente', 'c')->innerJoin('pedidos.factura', 'factura')->orderBy('pedidos.id')->where("c.id = $idCliente")->getQuery()->getResult();
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
