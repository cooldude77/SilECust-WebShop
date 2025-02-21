<?php

declare(strict_types=1);

namespace Silecust\WebShop\migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240308053009 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE file_base_type (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE file_type ADD base_type_id INT NOT NULL');
        $this->addSql('ALTER TABLE file_type ADD CONSTRAINT FK_5223F47F14A35F7 FOREIGN KEY (base_type_id) REFERENCES file_base_type (id)');
        $this->addSql('CREATE INDEX IDX_5223F47F14A35F7 ON file_type (base_type_id)');
         $this->addSql('INSERT INTO file_base_type(type,description) VALUES ("IMAGE","Image")');
        $this->addSql('INSERT INTO file_type(type,description,base_type_id) VALUES ("JPEG","image/jpeg",1)');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE file_type DROP FOREIGN KEY FK_5223F47F14A35F7');
        $this->addSql('DROP TABLE file_base_type');
        $this->addSql('DROP INDEX IDX_5223F47F14A35F7 ON file_type');
        $this->addSql('ALTER TABLE file_type DROP base_type_id');
    }
}
