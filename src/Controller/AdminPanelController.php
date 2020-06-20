<?php


namespace App\Controller;

use App\Entity\Cliente;
use App\Repository\ClienteRepository;
use App\Repository\PedidosRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminPanelController extends AbstractController
{
    /**
     * @Route("/admin/index", name="indexPanelAdmin")
     * @param PedidosRepository $repository
     * @return Response
     */
    public function adminPanelIndex(PedidosRepository $repository) {
        $pedidosSinEnviar = $repository->getPedidosSinEnviar();
        return $this->render('adminPanel/index.html.twig', [
            'pedidos' => $pedidosSinEnviar
        ]);
    }

    /**
     * @Route("/admin/pedido/{id}", name="verPedido")
     * @param $id
     * @param PedidosRepository $repository
     * @return Response
     */
    public function pedido($id, PedidosRepository $repository) {
        $pedido = $repository->getPedido($id);
        $pedido['FechaCreacion'] = $pedido['FechaCreacion']->format('Y-m-d H:i:s');
        if ($pedido['FechaRecibido'] != null) {
            $pedido['FechaRecibido'] = $pedido['FechaRecibido']->format('Y-m-d H:i:s');
        }

        return $this->render('adminPanel/pedido.html.twig', [
            'pedido' => $pedido
        ]);
    }

    /**
     * @Route("/admin/modificar/pedido", name="modifyOrder")
     * @param Request $request
     * @param PedidosRepository $repository
     */
    public function modifyOrder(Request $request, PedidosRepository $repository){
        if ($request->isMethod('post')) {
            $tracking = $request->get('tracking');
            $enviado = $request->get('enviado');
            $recibido = $request->get('recibido');
            $devuelto = $request->get('devuelto');
            $id = $request->get('id');
            $repository->modifyPedido($tracking, $enviado, $recibido, $devuelto, $id);

            $pedido = $repository->getPedido($id);
            $pedido['FechaCreacion'] = $pedido['FechaCreacion']->format('Y-m-d H:i:s');
            if ($pedido['FechaRecibido'] != null) {
                $pedido['FechaRecibido'] = $pedido['FechaRecibido']->format('Y-m-d H:i:s');
            }
            return $this->render('adminPanel/pedido.html.twig', [
                'pedido' => $pedido
            ]);
        } else {
            $url = $this->generateUrl('indexPanelAdmin');
            return new Response("Que haces aqui?? <br> Vuelve al panel <a href='$url'>Cick Aqui</a>");
        }
    }

    /**
     * @Route("/admin/pedidos", name="todosPedidos")
     * @param PedidosRepository $repository
     * @return Response
     */
    public function mostrarTodosPedidos(PedidosRepository $repository) {
        $pedidos = $repository->getAllPedidos();
        return $this->render('adminPanel/pedidos.html.twig', [
            'pedidos' => $pedidos
        ]);
    }

    /**
     * @Route("/admin/clientes", name="clientes")
     * @param ClienteRepository $repository
     * @return Response
     */
    public function mostrarTodosClientes(ClienteRepository $repository) {
        $clientes = $repository->getAllClientes();
        return $this->render('adminPanel/clientes.html.twig', [
            'clientes' => $clientes
        ]);
    }

    /**
     * @Route("/admin/generarPedido/cliente/{id}", name="formPedido")
     * @param $id
     * @return Response
     */
    public function forumGenerarPedido($id) {
        return $this->render('adminPanel/generarPedido.html.twig', [
            'idCliente' => $id]);
    }
    /**
     * @Route("/admin/generarPedido/new", name="generarPedido")
     */
    public function generarPedido(Request $request, PedidosRepository $repositoryPedidos, ClienteRepository $repositoryCliente) {
        if ($request->isMethod('post')) {
            $articulos = $request->get('articulos');
            $precio = $request->get('precio');
            $idCliente = $request->get('idCliente');
            if(!preg_match("/^\d{0,8}(\.\d{1,4})?$/",$precio)) {
                return new Response("El precio debe ser del formato xx.xx, solo puede tener dos decimales");
            } else {
            $cliente = $repositoryCliente->getCliente($idCliente);
            $repositoryPedidos->generarPedido($articulos, $precio, $cliente);
            return $this->redirect($this->generateUrl('clientes'));
            }
        } else {
            $url = $this->generateUrl('indexPanelAdmin');
            return new Response("Que haces aqui?? <br> Vuelve al panel <a href='$url'>Cick Aqui</a>");
        }
    }
    /**
     * @Route("pedidos/cliente/{id}", name="pedidosCliente")
     */
    public function pedidosCliente($id, PedidosRepository $repositoryPedidos) {
        $pedidos = $repositoryPedidos->getPedidosCliente($id);
        if(count($pedidos) == 0) {
            $url = $this->generateUrl('formPedido', array('id'=> $id));
            return new Response("Este cliente no tiene pedidos para volver <a href='$url'>click aqui para generar pedido</a>");
        } else {

        return $this->render('adminPanel/pedidosCliente.html.twig', [
            'pedidos' => $pedidos
        ]);
        }
    }
}