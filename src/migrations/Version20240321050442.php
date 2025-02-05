<?php

declare(strict_types=1);

namespace App\migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240321050442 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category_image_file (id INT AUTO_INCREMENT NOT NULL, category_file_id INT NOT NULL, category_image_type_id INT NOT NULL, UNIQUE INDEX UNIQ_58BB8CF054133912 (category_file_id), INDEX IDX_58BB8CF04E87FE04 (category_image_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category_image_type (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, min_width INT NOT NULL, min_height INT NOT NULL, long_description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE category_image_file ADD CONSTRAINT FK_58BB8CF054133912 FOREIGN KEY (category_file_id) REFERENCES category_file (id)');
        $this->addSql('ALTER TABLE category_image_file ADD CONSTRAINT FK_58BB8CF04E87FE04 FOREIGN KEY (category_image_type_id) REFERENCES category_image_type (id)');
        $this->addSql('INSERT INTO product_image_type(type,description,long_description,min_width,min_height) VALUES ("CAROUSEL","Carousel","Carousel Image which has a ratio of 16:9",1920,1080)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category_image_file DROP FOREIGN KEY FK_58BB8CF054133912');
        $this->addSql('ALTER TABLE category_image_file DROP FOREIGN KEY FK_58BB8CF04E87FE04');
        $this->addSql('DROP TABLE category_image_file');
        $this->addSql('DROP TABLE category_image_type');
    }
}
