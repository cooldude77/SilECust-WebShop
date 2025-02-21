<?php

declare(strict_types=1);

namespace Silecust\WebShop\migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240307051305 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE web_shop_home (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE web_shop_home_section (id INT AUTO_INCREMENT NOT NULL, web_shop_home_id INT NOT NULL, name VARCHAR(255) NOT NULL, header VARCHAR(255) NOT NULL, footer VARCHAR(255) DEFAULT NULL, section_order INT NOT NULL, INDEX IDX_35102A51ACFF1C48 (web_shop_home_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE web_shop_home_section ADD CONSTRAINT FK_35102A51ACFF1C48 FOREIGN KEY (web_shop_home_id) REFERENCES web_shop_home (id)');
        $this->addSql('ALTER TABLE webshop_home_section DROP FOREIGN KEY FK_DA93F5EB7C7416D9');
        $this->addSql('DROP TABLE webshop_home_section');
        $this->addSql('DROP TABLE webshop_home');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE webshop_home_section (id INT AUTO_INCREMENT NOT NULL, webshop_home_id INT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, header VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, footer VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, section_order INT NOT NULL, INDEX IDX_DA93F5EB7C7416D9 (webshop_home_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE webshop_home (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE webshop_home_section ADD CONSTRAINT FK_DA93F5EB7C7416D9 FOREIGN KEY (webshop_home_id) REFERENCES webshop_home (id)');
        $this->addSql('ALTER TABLE web_shop_home_section DROP FOREIGN KEY FK_35102A51ACFF1C48');
        $this->addSql('DROP TABLE web_shop_home');
        $this->addSql('DROP TABLE web_shop_home_section');
    }
}
