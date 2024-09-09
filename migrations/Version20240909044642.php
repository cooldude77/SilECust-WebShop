<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240909044642 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE product_group_attribute_key_value_visiblity (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, product_group_id INT NOT NULL, product_attribute_key_id INT NOT NULL, product_attribute_key_value_id INT NOT NULL, is_available TINYINT(1) NOT NULL, INDEX IDX_BBD8EF104584665A (product_id), INDEX IDX_BBD8EF1035E4B3D0 (product_group_id), INDEX IDX_BBD8EF10A1791F95 (product_attribute_key_id), INDEX IDX_BBD8EF10FC0B49EC (product_attribute_key_value_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product_group_attribute_key_value_visiblity ADD CONSTRAINT FK_BBD8EF104584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product_group_attribute_key_value_visiblity ADD CONSTRAINT FK_BBD8EF1035E4B3D0 FOREIGN KEY (product_group_id) REFERENCES product_group (id)');
        $this->addSql('ALTER TABLE product_group_attribute_key_value_visiblity ADD CONSTRAINT FK_BBD8EF10A1791F95 FOREIGN KEY (product_attribute_key_id) REFERENCES product_attribute_key (id)');
        $this->addSql('ALTER TABLE product_group_attribute_key_value_visiblity ADD CONSTRAINT FK_BBD8EF10FC0B49EC FOREIGN KEY (product_attribute_key_value_id) REFERENCES product_attribute_key_value (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_group_attribute_key_value_visiblity DROP FOREIGN KEY FK_BBD8EF104584665A');
        $this->addSql('ALTER TABLE product_group_attribute_key_value_visiblity DROP FOREIGN KEY FK_BBD8EF1035E4B3D0');
        $this->addSql('ALTER TABLE product_group_attribute_key_value_visiblity DROP FOREIGN KEY FK_BBD8EF10A1791F95');
        $this->addSql('ALTER TABLE product_group_attribute_key_value_visiblity DROP FOREIGN KEY FK_BBD8EF10FC0B49EC');
        $this->addSql('DROP TABLE product_group_attribute_key_value_visiblity');
    }
}
