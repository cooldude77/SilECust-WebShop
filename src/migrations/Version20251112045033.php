<?php

declare(strict_types=1);

namespace Silecust\WebShop\migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251112045033 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_header DROP date_time_of_order');
        $this->addSql('ALTER TABLE order_status ADD time_zone_of_status_set VARCHAR(255) NOT NULL, DROP snap_shot, CHANGE date_of_status_set date_of_status_set DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_header ADD date_time_of_order DATE NOT NULL');
        $this->addSql('ALTER TABLE order_status ADD snap_shot LONGTEXT NOT NULL COMMENT \'(DC2Type:object)\', DROP time_zone_of_status_set, CHANGE date_of_status_set date_of_status_set DATE NOT NULL');
    }
}
