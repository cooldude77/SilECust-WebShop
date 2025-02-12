<?php

declare(strict_types=1);

namespace App\migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240509042418 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer_address ADD pin_code_id INT NOT NULL, ADD line1 VARCHAR(255) NOT NULL, ADD line2 VARCHAR(255) DEFAULT NULL, ADD line3 VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE customer_address ADD CONSTRAINT FK_1193CB3F1A465690 FOREIGN KEY (pin_code_id) REFERENCES pin_code (id)');
        $this->addSql('CREATE INDEX IDX_1193CB3F1A465690 ON customer_address (pin_code_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer_address DROP FOREIGN KEY FK_1193CB3F1A465690');
        $this->addSql('DROP INDEX IDX_1193CB3F1A465690 ON customer_address');
        $this->addSql('ALTER TABLE customer_address DROP pin_code_id, DROP line1, DROP line2, DROP line3');
    }
}
