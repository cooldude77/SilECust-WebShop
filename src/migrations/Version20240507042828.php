<?php

declare(strict_types=1);

namespace Silecust\WebShop\migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240507042828 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer ADD salutation_id INT DEFAULT NULL, ADD first_name VARCHAR(255) NOT NULL, ADD middle_name VARCHAR(255) DEFAULT NULL, ADD last_name VARCHAR(255) NOT NULL, ADD given_name VARCHAR(1000) NOT NULL, CHANGE customer_code code VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE customer ADD CONSTRAINT FK_81398E092E5AD854 FOREIGN KEY (salutation_id) REFERENCES salutation (id)');
        $this->addSql('CREATE INDEX IDX_81398E092E5AD854 ON customer (salutation_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer DROP FOREIGN KEY FK_81398E092E5AD854');
        $this->addSql('DROP INDEX IDX_81398E092E5AD854 ON customer');
        $this->addSql('ALTER TABLE customer ADD customer_code VARCHAR(255) NOT NULL, DROP salutation_id, DROP code, DROP first_name, DROP middle_name, DROP last_name, DROP given_name');
    }
}
