<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240529051721 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE order_status (id INT AUTO_INCREMENT NOT NULL, header_id INT NOT NULL, status_id INT NOT NULL, date_of_status_set DATE NOT NULL, note LONGTEXT NOT NULL, snap_shot LONGTEXT NOT NULL COMMENT \'(DC2Type:object)\', INDEX IDX_B88F75C92EF91FD8 (header_id), UNIQUE INDEX UNIQ_B88F75C96BF700BD (status_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE order_status ADD CONSTRAINT FK_B88F75C92EF91FD8 FOREIGN KEY (header_id) REFERENCES order_header (id)');
        $this->addSql('ALTER TABLE order_status ADD CONSTRAINT FK_B88F75C96BF700BD FOREIGN KEY (status_id) REFERENCES order_status_type (id)');
        $this->addSql('ALTER TABLE order_address ADD shipping_address_id INT NOT NULL, ADD billing_address_id INT NOT NULL, DROP line1, DROP line2, DROP line3, DROP pin_code, DROP address_type');
        $this->addSql('ALTER TABLE order_address ADD CONSTRAINT FK_FB34C6CA4D4CFF2B FOREIGN KEY (shipping_address_id) REFERENCES customer_address (id)');
        $this->addSql('ALTER TABLE order_address ADD CONSTRAINT FK_FB34C6CA79D0C0E4 FOREIGN KEY (billing_address_id) REFERENCES customer_address (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FB34C6CA4D4CFF2B ON order_address (shipping_address_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FB34C6CA79D0C0E4 ON order_address (billing_address_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_status DROP FOREIGN KEY FK_B88F75C92EF91FD8');
        $this->addSql('ALTER TABLE order_status DROP FOREIGN KEY FK_B88F75C96BF700BD');
        $this->addSql('DROP TABLE order_status');
        $this->addSql('ALTER TABLE order_address DROP FOREIGN KEY FK_FB34C6CA4D4CFF2B');
        $this->addSql('ALTER TABLE order_address DROP FOREIGN KEY FK_FB34C6CA79D0C0E4');
        $this->addSql('DROP INDEX UNIQ_FB34C6CA4D4CFF2B ON order_address');
        $this->addSql('DROP INDEX UNIQ_FB34C6CA79D0C0E4 ON order_address');
        $this->addSql('ALTER TABLE order_address ADD line1 VARCHAR(255) NOT NULL, ADD line2 VARCHAR(255) DEFAULT NULL, ADD line3 VARCHAR(255) DEFAULT NULL, ADD pin_code VARCHAR(255) DEFAULT NULL, ADD address_type VARCHAR(255) NOT NULL, DROP shipping_address_id, DROP billing_address_id');
    }
}
