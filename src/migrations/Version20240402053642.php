<?php

declare(strict_types=1);

namespace Silecust\WebShop\migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240402053642 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE web_shop_file (id INT AUTO_INCREMENT NOT NULL, web_shop_id INT NOT NULL, INDEX IDX_93C111BFA5B96A43 (web_shop_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE web_shop_image_file (id INT AUTO_INCREMENT NOT NULL, web_shop_file_id INT NOT NULL, web_shop_image_type_id INT NOT NULL, UNIQUE INDEX UNIQ_D9A6107D17F9ADB8 (web_shop_file_id), INDEX IDX_D9A6107D1998DF99 (web_shop_image_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE web_shop_image_type (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, min_width INT NOT NULL, min_height INT NOT NULL, long_description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE web_shop_file ADD CONSTRAINT FK_93C111BFA5B96A43 FOREIGN KEY (web_shop_id) REFERENCES web_shop (id)');
        $this->addSql('ALTER TABLE web_shop_image_file ADD CONSTRAINT FK_D9A6107D17F9ADB8 FOREIGN KEY (web_shop_file_id) REFERENCES web_shop_file (id)');
        $this->addSql('ALTER TABLE web_shop_image_file ADD CONSTRAINT FK_D9A6107D1998DF99 FOREIGN KEY (web_shop_image_type_id) REFERENCES web_shop_image_type (id)');
        $this->addSql('INSERT INTO web_shop_image_type(type,description,long_description,min_width,min_height) VALUES ("FEATURE","Web Shop Feature image","Web Shop Feature Image",1920,1080)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE web_shop_file DROP FOREIGN KEY FK_93C111BFA5B96A43');
        $this->addSql('ALTER TABLE web_shop_image_file DROP FOREIGN KEY FK_D9A6107D17F9ADB8');
        $this->addSql('ALTER TABLE web_shop_image_file DROP FOREIGN KEY FK_D9A6107D1998DF99');
        $this->addSql('DROP TABLE web_shop_file');
        $this->addSql('DROP TABLE web_shop_image_file');
        $this->addSql('DROP TABLE web_shop_image_type');
    }
}
