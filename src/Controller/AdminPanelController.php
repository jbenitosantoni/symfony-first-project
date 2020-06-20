<?php


namespace App\Controller;

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
     * @Route("/admin/pedido/{id}")
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
            return new Response("Que haces aqui?? <br> Vuelve al panel <a href='../../admin/index'>Cick Aqui</a>");
        }

    }
}