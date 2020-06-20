<?php

namespace App\Entity;

use App\Repository\ClienteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ClienteRepository::class)
 */
class Cliente
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $Nombre;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $Apellidos;

    /**
     * @ORM\Column(type="text")
     */
    private $Direccion;

    /**
     * @ORM\Column(type="string", length=70)
     */
    private $Email;

    /**
     * @ORM\Column(type="string", length=70)
     */
    private $Instagram;

    /**
     * @ORM\Column(type="datetime")
     */
    private $FechaCreacion;

    /**
     * @ORM\OneToMany(targetEntity=Pedidos::class, mappedBy="IdCliente")
     */
    private $pedidos;

    /**
     * @ORM\OneToMany(targetEntity=Factura::class, mappedBy="IdCliente")
     */
    private $facturas;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $pedidosId = [];

    public function __construct()
    {
        $this->pedidos = new ArrayCollection();
        $this->facturas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->Nombre;
    }

    public function setNombre(string $Nombre): self
    {
        $this->Nombre = $Nombre;

        return $this;
    }

    public function getApellidos(): ?string
    {
        return $this->Apellidos;
    }

    public function setApellidos(string $Apellidos): self
    {
        $this->Apellidos = $Apellidos;

        return $this;
    }

    public function getDireccion(): ?string
    {
        return $this->Direccion;
    }

    public function setDireccion(string $Direccion): self
    {
        $this->Direccion = $Direccion;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->Email;
    }

    public function setEmail(string $Email): self
    {
        $this->Email = $Email;

        return $this;
    }

    public function getInstagram(): ?string
    {
        return $this->Instagram;
    }

    public function setInstagram(string $Instagram): self
    {
        $this->Instagram = $Instagram;

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

    /**
     * @return Collection|Pedidos[]
     */
    public function getPedidos(): Collection
    {
        return $this->pedidos;
    }

    public function addPedido(Pedidos $pedido): self
    {
        if (!$this->pedidos->contains($pedido)) {
            $this->pedidos[] = $pedido;
            $pedido->setIdCliente($this);
        }

        return $this;
    }

    public function removePedido(Pedidos $pedido): self
    {
        if ($this->pedidos->contains($pedido)) {
            $this->pedidos->removeElement($pedido);
            // set the owning side to null (unless already changed)
            if ($pedido->getIdCliente() === $this) {
                $pedido->setIdCliente(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Factura[]
     */
    public function getFacturas(): Collection
    {
        return $this->facturas;
    }

    public function addFactura(Factura $factura): self
    {
        if (!$this->facturas->contains($factura)) {
            $this->facturas[] = $factura;
            $factura->setIdCliente($this);
        }

        return $this;
    }

    public function removeFactura(Factura $factura): self
    {
        if ($this->facturas->contains($factura)) {
            $this->facturas->removeElement($factura);
            // set the owning side to null (unless already changed)
            if ($factura->getIdCliente() === $this) {
                $factura->setIdCliente(null);
            }
        }

        return $this;
    }

    public function getPedidosId(): ?array
    {
        return $this->pedidosId;
    }

    public function setPedidosId(?array $pedidosId): self
    {
        $this->pedidosId = $pedidosId;

        return $this;
    }
}
