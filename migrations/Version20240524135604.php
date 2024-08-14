<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240524135604 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE address DROP FOREIGN KEY FK_D4E6F81BDBA6A61');
        $this->addSql('DROP INDEX IDX_D4E6F81BDBA6A61 ON address');
        $this->addSql('ALTER TABLE address CHANGE postal_code_id pin_code_id INT NOT NULL');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F811A465690 FOREIGN KEY (pin_code_id) REFERENCES pin_code (id)');
        $this->addSql('CREATE INDEX IDX_D4E6F811A465690 ON address (pin_code_id)');
        $this->addSql('ALTER TABLE customer_address ADD is_default TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer_address DROP is_default');
        $this->addSql('ALTER TABLE address DROP FOREIGN KEY FK_D4E6F811A465690');
        $this->addSql('DROP INDEX IDX_D4E6F811A465690 ON address');
        $this->addSql('ALTER TABLE address CHANGE pin_code_id postal_code_id INT NOT NULL');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F81BDBA6A61 FOREIGN KEY (postal_code_id) REFERENCES pin_code (id)');
        $this->addSql('CREATE INDEX IDX_D4E6F81BDBA6A61 ON address (postal_code_id)');
    }
}
