<?php

namespace App\Entity;

use App\Repository\PedidosRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping as ORM;
use http\Env\Response;


/**
 * @ORM\Entity(repositoryClass=PedidosRepository::class)
 */
class Pedidos
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $PrecioFinal;

    /**
     * @ORM\Column(type="text")
     */
    private $Articulos;

    /**
     * @ORM\Column(type="boolean")
     */
    private $Enviado;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $Tracking;

    /**
     * @ORM\Column(type="boolean")
     */
    private $Devuelto;

    /**
     * @ORM\Column(type="boolean")
     */
    private $Recibido;

    /**
     * @ORM\Column(type="datetime")
     */
    private $FechaCreacion;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $FechaRecibido;

    /**
     * @ORM\ManyToOne(targetEntity=Cliente::class, inversedBy="pedidos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $IdCliente;

    /**
     * @ORM\OneToOne(targetEntity=Factura::class, mappedBy="IdPedido", cascade={"persist", "remove"})
     */
    private $factura;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrecioFinal(): ?string
    {
        return $this->PrecioFinal;
    }

    public function setPrecioFinal(string $PrecioFinal): self
    {
        $this->PrecioFinal = $PrecioFinal;

        return $this;
    }

    public function getArticulos(): ?string
    {
        return $this->Articulos;
    }

    public function setArticulos(string $Articulos): self
    {
        $this->Articulos = $Articulos;

        return $this;
    }

    public function getEnviado(): ?bool
    {
        return $this->Enviado;
    }

    public function setEnviado(bool $Enviado): self
    {
        $this->Enviado = $Enviado;

        return $this;
    }

    public function getTracking(): ?string
    {
        return $this->Tracking;
    }

    public function setTracking(?string $Tracking): self
    {
        $this->Tracking = $Tracking;

        return $this;
    }

    public function getDevuelto(): ?bool
    {
        return $this->Devuelto;
    }

    public function setDevuelto(bool $Devuelto): self
    {
        $this->Devuelto = $Devuelto;

        return $this;
    }

    public function getRecibido(): ?bool
    {
        return $this->Recibido;
    }

    public function setRecibido(bool $Recibido): self
    {
        $this->Recibido = $Recibido;

        return $this;
    }

    public function getFechaCreacion(): ?\DateTimeInterface
    {
        return $this->FechaCreacion;
    }

    public function setFechaCreacion(\DateTimeInterface $FechaCreacion): self
    {
        $this->FechaCreacion = $FechaCreacion;

        return $this;
    }

    public function getFechaRecibido(): ?\DateTimeInterface
    {
        return $this->FechaRecibido;
    }

    public function setFechaRecibido(?\DateTimeInterface $FechaRecibido): self
    {
        $this->FechaRecibido = $FechaRecibido;

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

    public function getFactura(): ?Factura
    {
        return $this->factura;
    }

    public function setFactura(Factura $factura): self
    {
        $this->factura = $factura;

        // set the owning side of the relation if necessary
        if ($factura->getIdPedido() !== $this) {
            $factura->setIdPedido($this);
        }

        return $this;
    }
}
