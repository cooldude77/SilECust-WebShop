<?php

declare(strict_types=1);

namespace App\migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240903060318 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE order_shipping (id INT AUTO_INCREMENT NOT NULL, order_header_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, value DOUBLE PRECISION NOT NULL, data JSON NOT NULL COMMENT \'(DC2Type:json)\', INDEX IDX_CCE4F595927E6420 (order_header_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE order_shipping ADD CONSTRAINT FK_CCE4F595927E6420 FOREIGN KEY (order_header_id) REFERENCES order_header (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_shipping DROP FOREIGN KEY FK_CCE4F595927E6420');
        $this->addSql('DROP TABLE order_shipping');
    }
}
