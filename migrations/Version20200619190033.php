<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200619190033 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX cliente_email_uindex ON cliente');
        $this->addSql('DROP INDEX cliente_instagram_uindex ON cliente');
        $this->addSql('ALTER TABLE cliente ADD pedidos_id LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cliente DROP pedidos_id');
        $this->addSql('CREATE UNIQUE INDEX cliente_email_uindex ON cliente (email)');
        $this->addSql('CREATE UNIQUE INDEX cliente_instagram_uindex ON cliente (instagram)');
    }
}
