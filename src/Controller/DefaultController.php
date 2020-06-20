<?php


namespace App\Controller;


use App\Entity\Cliente;
use App\Entity\Pedidos;
use App\Entity\User;
use App\Repository\PedidosRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class DefaultController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function homePage() {
        return $this->render('index.html.twig');
    }

    /**
     * @Route("/add")
     * @param EntityManagerInterface $em
     * @param UserPasswordEncoderInterface $encoder
     */
    public function addPedido(EntityManagerInterface $em) {
        $pedido = new Pedidos();
        $cliente = new Cliente();
        $date = date('Y-m-d H:i:s');
        $cliente->setEmail('javibs69@gmail.com');
        $cliente->setNombre('Javier');
        $cliente->setDireccion('Test');
        $cliente->setApellidos('Benito');
        $cliente->setInstagram('@fluexetine');
        $cliente->setFechaCreacion(new \DateTime());
        $pedido->setFechaCreacion(new \DateTime());
        $pedido->setIdCliente($cliente);
        $pedido->setArticulos('Mada');
        $pedido->setDevuelto(0);
        $pedido->setEnviado(0);
        $pedido->setPrecioFinal(222);
        $pedido->setRecibido(0);
        $em->persist($cliente);
        $em->persist($pedido);

        $em->flush();
    }
//    public function addUser(EntityManagerInterface $em, UserPasswordEncoderInterface $encoder) {
//        $user = new User();
//        $user->setEmail('');
//        $password = $encoder->encodePassword($user, '');
//        $user->setPassword($password);
//        $user->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
//
//        $em->persist($user);
//        $em->flush();
//    }
}