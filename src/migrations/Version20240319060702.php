<?php

declare(strict_types=1);

namespace App\migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240319060702 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE product_file (id INT AUTO_INCREMENT NOT NULL, file_id INT NOT NULL, product_id INT NOT NULL, UNIQUE INDEX UNIQ_17714B193CB796C (file_id), INDEX IDX_17714B14584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_image_file (id INT AUTO_INCREMENT NOT NULL, product_file_id INT NOT NULL, product_image_type_id INT NOT NULL, UNIQUE INDEX UNIQ_EDC283A5421262DC (product_file_id), INDEX IDX_EDC283A58AB1C7BD (product_image_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product_file ADD CONSTRAINT FK_17714B193CB796C FOREIGN KEY (file_id) REFERENCES file (id)');
        $this->addSql('ALTER TABLE product_file ADD CONSTRAINT FK_17714B14584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product_image_file ADD CONSTRAINT FK_EDC283A5421262DC FOREIGN KEY (product_file_id) REFERENCES product_file (id)');
        $this->addSql('ALTER TABLE product_image_file ADD CONSTRAINT FK_EDC283A58AB1C7BD FOREIGN KEY (product_image_type_id) REFERENCES product_image_type (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_file DROP FOREIGN KEY FK_17714B193CB796C');
        $this->addSql('ALTER TABLE product_file DROP FOREIGN KEY FK_17714B14584665A');
        $this->addSql('ALTER TABLE product_image_file DROP FOREIGN KEY FK_EDC283A5421262DC');
        $this->addSql('ALTER TABLE product_image_file DROP FOREIGN KEY FK_EDC283A58AB1C7BD');
        $this->addSql('DROP TABLE product_file');
        $this->addSql('DROP TABLE product_image_file');
    }
}
