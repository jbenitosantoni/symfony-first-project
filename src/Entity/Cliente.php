<?php

namespace App\Entity;

use App\Repository\ClienteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator as ClienteAssert;

/**
 * @ORM\Entity(repositoryClass=ClienteRepository::class)
 * @UniqueEntity(fields="Email", message="Introduce un Email que no haya sido usado antes")
 * @UniqueEntity(fields="Instagram", message="Introduce un Instagtram que no haya sido usado antes")
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
     * @Assert\NotBlank()
     */
    private $Nombre;

    /**
     * @ORM\Column(type="string", length=150)
     * @Assert\NotBlank()
     */
    private $Apellidos;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    private $Direccion;

    /**
     * @ORM\Column(type="string", length=70, unique=true)
     * @Assert\NotBlank(message="This value cannot be empty!")
     */
    private $Email;

    /**
     * @ORM\Column(type="string", length=70, unique=true)
     * @Assert\NotBlank(message="This value cannot be empty!")
     * @ClienteAssert\Instagram
     */
    private $Instagram;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Type("\DateTimeInterface")
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

    public function __construct()
    {
        $this->pedidos = new ArrayCollection();
        $this->facturas = new ArrayCollection();
        $this->FechaCreacion = new \DateTime();
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
}
