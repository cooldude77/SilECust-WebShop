<?php

declare(strict_types=1);

namespace Silecust\WebShop\migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240508160756 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE city ADD name VARCHAR(255) NOT NULL, CHANGE city code VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE country ADD code VARCHAR(255) NOT NULL, ADD name VARCHAR(255) NOT NULL, DROP country_code, DROP country_name');
        $this->addSql('ALTER TABLE state CHANGE description name VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE country ADD country_code VARCHAR(255) NOT NULL, ADD country_name VARCHAR(255) NOT NULL, DROP code, DROP name');
        $this->addSql('ALTER TABLE city ADD city VARCHAR(255) NOT NULL, DROP code, DROP name');
        $this->addSql('ALTER TABLE state CHANGE name description VARCHAR(255) NOT NULL');
    }
}
