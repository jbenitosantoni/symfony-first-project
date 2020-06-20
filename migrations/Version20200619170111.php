<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200619170111 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cliente (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(50) NOT NULL, apellidos VARCHAR(150) NOT NULL, direccion LONGTEXT NOT NULL, email VARCHAR(70) NOT NULL, instagram VARCHAR(70) NOT NULL, fecha_creacion DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE factura (id INT AUTO_INCREMENT NOT NULL, id_pedido_id INT NOT NULL, id_cliente_id INT NOT NULL, pagado TINYINT(1) NOT NULL, fecha DATETIME NOT NULL, fecha_pago DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_F9EBA009C861D91D (id_pedido_id), INDEX IDX_F9EBA0097BF9CE86 (id_cliente_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pedidos (id INT AUTO_INCREMENT NOT NULL, id_cliente_id INT NOT NULL, precio_final NUMERIC(10, 2) NOT NULL, articulos LONGTEXT NOT NULL, enviado TINYINT(1) NOT NULL, tracking VARCHAR(50) DEFAULT NULL, devuelto TINYINT(1) NOT NULL, recibido TINYINT(1) NOT NULL, fecha_creacion DATETIME NOT NULL, fecha_recibido DATETIME DEFAULT NULL, INDEX IDX_6716CCAA7BF9CE86 (id_cliente_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE factura ADD CONSTRAINT FK_F9EBA009C861D91D FOREIGN KEY (id_pedido_id) REFERENCES pedidos (id)');
        $this->addSql('ALTER TABLE factura ADD CONSTRAINT FK_F9EBA0097BF9CE86 FOREIGN KEY (id_cliente_id) REFERENCES cliente (id)');
        $this->addSql('ALTER TABLE pedidos ADD CONSTRAINT FK_6716CCAA7BF9CE86 FOREIGN KEY (id_cliente_id) REFERENCES cliente (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE factura DROP FOREIGN KEY FK_F9EBA0097BF9CE86');
        $this->addSql('ALTER TABLE pedidos DROP FOREIGN KEY FK_6716CCAA7BF9CE86');
        $this->addSql('ALTER TABLE factura DROP FOREIGN KEY FK_F9EBA009C861D91D');
        $this->addSql('DROP TABLE cliente');
        $this->addSql('DROP TABLE factura');
        $this->addSql('DROP TABLE pedidos');
    }
}
