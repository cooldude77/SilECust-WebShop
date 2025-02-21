<?php

declare(strict_types=1);

namespace Silecust\WebShop\migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240826084123 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
         $this->addSql('ALTER TABLE order_payment CHANGE payment_details payment_response JSON NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE order_payment DROP status');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_payment CHANGE payment_response payment_details JSON NOT NULL COMMENT \'(DC2Type:json)\'');
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_payment ADD status TINYINT(1) NOT NULL');

    }
}
