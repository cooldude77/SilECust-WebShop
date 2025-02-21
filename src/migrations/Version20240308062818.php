<?php

declare(strict_types=1);

namespace Silecust\WebShop\migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240308062818 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category_file (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, file_id INT NOT NULL, INDEX IDX_7044C5712469DE2 (category_id), UNIQUE INDEX UNIQ_7044C5793CB796C (file_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE category_file ADD CONSTRAINT FK_7044C5712469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE category_file ADD CONSTRAINT FK_7044C5793CB796C FOREIGN KEY (file_id) REFERENCES file (id)');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category_file DROP FOREIGN KEY FK_7044C5712469DE2');
        $this->addSql('ALTER TABLE category_file DROP FOREIGN KEY FK_7044C5793CB796C');
        $this->addSql('DROP TABLE category_file');
        $this->addSql('ALTER TABLE file_type DROP FOREIGN KEY FK_5223F47F14A35F7');
        $this->addSql('DROP INDEX IDX_5223F47F14A35F7 ON file_type');
    }
}
