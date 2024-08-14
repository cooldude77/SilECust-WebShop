<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240524034108 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE order_address (id INT AUTO_INCREMENT NOT NULL, order_header_id INT NOT NULL, line1 VARCHAR(255) NOT NULL, line2 VARCHAR(255) DEFAULT NULL, line3 VARCHAR(255) DEFAULT NULL, pin_code VARCHAR(255) DEFAULT NULL, address_type VARCHAR(255) NOT NULL, INDEX IDX_FB34C6CA927E6420 (order_header_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_payment (id INT AUTO_INCREMENT NOT NULL, order_header_id INT NOT NULL, payment_details JSON NOT NULL COMMENT \'(DC2Type:json)\', status TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_9B522D46927E6420 (order_header_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE order_address ADD CONSTRAINT FK_FB34C6CA927E6420 FOREIGN KEY (order_header_id) REFERENCES order_header (id)');
        $this->addSql('ALTER TABLE order_payment ADD CONSTRAINT FK_9B522D46927E6420 FOREIGN KEY (order_header_id) REFERENCES order_header (id)');
        $this->addSql('ALTER TABLE customer_address ADD default_address TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_address DROP FOREIGN KEY FK_FB34C6CA927E6420');
        $this->addSql('ALTER TABLE order_payment DROP FOREIGN KEY FK_9B522D46927E6420');
        $this->addSql('DROP TABLE order_address');
        $this->addSql('DROP TABLE order_payment');
        $this->addSql('ALTER TABLE customer_address DROP default_address');
    }
}
