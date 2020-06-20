<?php

namespace App\Entity;

use App\Repository\FacturaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FacturaRepository::class)
 */
class Factura
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $Pagado;

    /**
     * @ORM\Column(type="datetime")
     */
    private $Fecha;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $FechaPago;

    /**
     * @ORM\OneToOne(targetEntity=Pedidos::class, inversedBy="factura", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $IdPedido;

    /**
     * @ORM\ManyToOne(targetEntity=Cliente::class, inversedBy="facturas")
     * @ORM\JoinColumn(nullable=false)
     */
    private $IdCliente;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPagado(): ?bool
    {
        return $this->Pagado;
    }

    public function setPagado(bool $Pagado): self
    {
        $this->Pagado = $Pagado;

        return $this;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->Fecha;
    }

    public function setFecha(\DateTimeInterface $Fecha): self
    {
        $this->Fecha = $Fecha;

        return $this;
    }

    public function getFechaPago(): ?\DateTimeInterface
    {
        return $this->FechaPago;
    }

    public function setFechaPago(?\DateTimeInterface $FechaPago): self
    {
        $this->FechaPago = $FechaPago;

        return $this;
    }

    public function getIdPedido(): ?Pedidos
    {
        return $this->IdPedido;
    }

    public function setIdPedido(Pedidos $IdPedido): self
    {
        $this->IdPedido = $IdPedido;

        return $this;
    }

    public function getIdCliente(): ?Cliente
    {
        return $this->IdCliente;
    }

    public function setIdCliente(?Cliente $IdCliente): self
    {
        $this->IdCliente = $IdCliente;

        return $this;
    }
}
