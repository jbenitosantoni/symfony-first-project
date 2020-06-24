<?php


namespace App\Controller;

use App\Entity\Cliente;
use App\Form\NewClientType;
use App\Repository\ClienteRepository;
use App\Repository\PedidosRepository;
use Knp\Component\Pager\PaginatorInterface;
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
            $trackings = $repository->uniqueTracking($tracking);
            if (count($trackings) == 0 || (count($trackings) == 1 && $trackings[0]['id'] == $id)) {
            $repository->modifyPedido($tracking, $enviado, $recibido, $devuelto, $id);
            return $this->redirect("/admin/pedido/$id");
        } else {
            return new Response("El tracking ya existe");
            }
        } else {
            $url = $this->generateUrl('indexPanelAdmin');
            return new Response("Que haces aqui?? <br> Vuelve al panel <a href='$url'>Cick Aqui</a>");
        }
    }

    /**
     * @Route("/admin/pedidos", name="todosPedidos")
     * @param PedidosRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function mostrarTodosPedidos(PedidosRepository $repository, PaginatorInterface $paginator, Request $request) {
        $allPedidosQuery = $repository->getAllPedidos();
        $pedidos = $paginator->paginate(
            $allPedidosQuery,
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('adminPanel/pedidos.html.twig', [
            'pedidos' => $pedidos
        ]);
    }

    /**
     * @Route("/admin/clientes", name="clientes")
     * @param ClienteRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function mostrarTodosClientes(ClienteRepository $repository, PaginatorInterface $paginator, Request $request) {
        $allClientesQuery = $repository->getAllClientes();
        $clientes = $paginator->paginate(
            $allClientesQuery,
            $request->query->getInt('page', 1),
            10
        );
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
            $submittedToken = $request->request->get('token');
            $articulos = $request->get('articulos');
            $precio = $request->get('precio');
            $idCliente = $request->get('idCliente');
            if(!preg_match("/^\d{0,8}(\.\d{1,4})?$/",$precio)) {
                return new Response("El precio debe ser del formato xx.xx, solo puede tener dos decimales");
            } elseif ($precio == 0){
                return new Response("El precio no puede ser 0");
            }else {
                if ($this->isCsrfTokenValid('delete-item', $submittedToken)) {
            $cliente = $repositoryCliente->getCliente($idCliente);
            $repositoryPedidos->generarPedido($articulos, $precio, $cliente);
            return $this->redirect($this->generateUrl('clientes'));
                }
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
     * @Route("admin/generarCliente", name="nuevoCliente")
     * @param Request $request
     * @param ClienteRepository $repository
     * @return Response
     */
    public function generarCliente(Request $request,ClienteRepository $repository) {
        $form = $this->createForm(NewClientType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $repository->nuevoCliente($formData);
            return $this->redirect($this->generateUrl('clientes'));
        }
        return $this->render('adminPanel/newClient.html.twig', [
            'form' => $form,
            'form' => $form->createView()
        ]);
    }
}