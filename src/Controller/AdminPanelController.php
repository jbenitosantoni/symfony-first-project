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
            } elseif ($precio == 0){
                return new Response("El precio no puede ser 0");
            }else {
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
     * @Route("admin/pedidos/cliente/{id}", name="pedidosCliente")
     */
    public function pedidosCliente($id, PedidosRepository $repositoryPedidos) {
        $pedidos = $repositoryPedidos->getPedidosCliente($id);
        if(count($pedidos) == 0) {
            $url = $this->generateUrl('formPedido', array('id'=> $id));
            return new Response("Este cliente no tiene pedidos <a href='$url'>click aqui para generar pedido</a>");
        } else {

        return $this->render('adminPanel/pedidosCliente.html.twig', [
            'pedidos' => $pedidos
        ]);
        }
    }

    /**
     * @Route("admin/buscar", name="buscarInstagram")
     * @param Request $request
     * @param ClienteRepository $repository
     * @return Response
     */
    public function buscarInstagram(Request $request, ClienteRepository $repository) {
        $instagram = dump($request->query->get('instagram'));
            $cliente = $repository->buscarPorInstagram($instagram);
            if ($cliente == null) {
                $url = $this->generateUrl('indexPanelAdmin');
                return new Response("El cliente con Instagram $instagram no existe, ¿has puesto la @? <br> Vuelve al panel <a href='$url'>Cick Aqui</a>");
            }
            return $this->render('adminPanel/cliente.html.twig', [
                'cliente' => $cliente
            ]);
    }
    /**
     * @Route("admin/generarCliente", name="formNuevoCliente")
     */
    public function formGenerarCliente() {
        return $this->render('adminPanel/generarCliente.html.twig');
    }
    /**
     * @Route("admin/generarCliente/new", name="nuevoCliente")
     */
    public function generarCliente(Request $request, ClienteRepository $repository) {
        if ($request->isMethod('post')) {
            $nombre = $request->get('nombre');
            $apellidos = $request->get('apellidos');
            $direccion = $request->get('direccion');
            $email = $request->get('email');
            $instagram = $request->get('instagram');
            if (!preg_match('/^@.*$/', $instagram)) {
                $url = $this->generateUrl('formNuevoCliente');
                Return new Response('El instagram debe empezar con @ para volver a crear el usuario click <a href="$url">aqui</a>');
            } elseif (!preg_match('/\b[\w\.-]+@[\w\.-]+\.\w{2,4}\b/', $email)){
                Return new Response('El formato del email es incorrecto para volver a crear el usuario click <a href="$url">aqui</a>');
            } else {
                if ($repository->validateEmail($email) != null && $repository->validateInstagram($instagram) != null){
            $cliente = $repository->nuevoCliente($nombre, $apellidos, $direccion, $email, $instagram);
            return $this->mostrarTodosClientes($repository);
                } else {
                    $url = $this->generateUrl('formNuevoCliente');
                    return new Response("El email o el instagram ya existen click <a href='$url'>aquí</a> para volver ");
                }
        }
    } else {
            $url = $this->generateUrl('indexPanelAdmin');
            return new Response("Que haces aqui?? <br> Vuelve al panel <a href='$url'>Cick Aqui</a>");
        }
    }
}