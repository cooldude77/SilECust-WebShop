<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240827144049 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE address DROP FOREIGN KEY FK_D4E6F811A465690');
        $this->addSql('ALTER TABLE customer_address DROP FOREIGN KEY FK_1193CB3F1A465690');
        $this->addSql('CREATE TABLE postal_code (id INT AUTO_INCREMENT NOT NULL, city_id INT NOT NULL, postal_code VARCHAR(255) NOT NULL, INDEX IDX_EA98E3768BAC62AF (city_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE postal_code ADD CONSTRAINT FK_EA98E3768BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
        $this->addSql('ALTER TABLE pin_code DROP FOREIGN KEY FK_9B09E7B8BAC62AF');
        $this->addSql('DROP TABLE pin_code');
        $this->addSql('DROP INDEX IDX_D4E6F811A465690 ON address');
        $this->addSql('ALTER TABLE address CHANGE pin_code_id postal_code_id INT NOT NULL');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F81BDBA6A61 FOREIGN KEY (postal_code_id) REFERENCES postal_code (id)');
        $this->addSql('CREATE INDEX IDX_D4E6F81BDBA6A61 ON address (postal_code_id)');
        $this->addSql('DROP INDEX IDX_1193CB3F1A465690 ON customer_address');
        $this->addSql('ALTER TABLE customer_address CHANGE pin_code_id postal_code_id INT NOT NULL');
        $this->addSql('ALTER TABLE customer_address ADD CONSTRAINT FK_1193CB3FBDBA6A61 FOREIGN KEY (postal_code_id) REFERENCES postal_code (id)');
        $this->addSql('CREATE INDEX IDX_1193CB3FBDBA6A61 ON customer_address (postal_code_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE address DROP FOREIGN KEY FK_D4E6F81BDBA6A61');
        $this->addSql('ALTER TABLE customer_address DROP FOREIGN KEY FK_1193CB3FBDBA6A61');
        $this->addSql('CREATE TABLE pin_code (id INT AUTO_INCREMENT NOT NULL, city_id INT NOT NULL, pin_code VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_9B09E7B8BAC62AF (city_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE pin_code ADD CONSTRAINT FK_9B09E7B8BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
        $this->addSql('ALTER TABLE postal_code DROP FOREIGN KEY FK_EA98E3768BAC62AF');
        $this->addSql('DROP TABLE postal_code');
        $this->addSql('DROP INDEX IDX_D4E6F81BDBA6A61 ON address');
        $this->addSql('ALTER TABLE address CHANGE postal_code_id pin_code_id INT NOT NULL');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F811A465690 FOREIGN KEY (pin_code_id) REFERENCES pin_code (id)');
        $this->addSql('CREATE INDEX IDX_D4E6F811A465690 ON address (pin_code_id)');
        $this->addSql('DROP INDEX IDX_1193CB3FBDBA6A61 ON customer_address');
        $this->addSql('ALTER TABLE customer_address CHANGE postal_code_id pin_code_id INT NOT NULL');
        $this->addSql('ALTER TABLE customer_address ADD CONSTRAINT FK_1193CB3F1A465690 FOREIGN KEY (pin_code_id) REFERENCES pin_code (id)');
        $this->addSql('CREATE INDEX IDX_1193CB3F1A465690 ON customer_address (pin_code_id)');
    }
}
