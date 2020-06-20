<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200620195410 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE test (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP INDEX cliente_email_uindex ON cliente');
        $this->addSql('ALTER TABLE cliente RENAME INDEX cliente_instagram_uindex TO UNIQ_F41C9B2584A87EC3');
        $this->addSql('ALTER TABLE pedidos RENAME INDEX pedidos_tracking_uindex TO UNIQ_6716CCAAA87C621C');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE test');
        $this->addSql('CREATE UNIQUE INDEX cliente_email_uindex ON cliente (email)');
        $this->addSql('ALTER TABLE cliente RENAME INDEX uniq_f41c9b2584a87ec3 TO cliente_instagram_uindex');
        $this->addSql('ALTER TABLE pedidos RENAME INDEX uniq_6716ccaaa87c621c TO pedidos_tracking_uindex');
    }
}
