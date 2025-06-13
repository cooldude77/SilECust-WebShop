<?php

declare(strict_types=1);

namespace Silecust\WebShop\migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250613084553 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            ALTER TABLE order_address DROP FOREIGN KEY FK_FB34C6CA4D4CFF2B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_address DROP FOREIGN KEY FK_FB34C6CA79D0C0E4
        SQL);
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE order_address ADD CONSTRAINT FK_FB34C6CA4D4CFF2B FOREIGN KEY (shipping_address_id) REFERENCES customer_address (id) ON DELETE SET NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_address ADD CONSTRAINT FK_FB34C6CA79D0C0E4 FOREIGN KEY (billing_address_id) REFERENCES customer_address (id) ON DELETE SET NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_item DROP FOREIGN KEY FK_52EA1F094584665A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_item ADD product_in_json JSON NOT NULL COMMENT '(DC2Type:json)', CHANGE product_id product_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F094584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE SET NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE order_item DROP FOREIGN KEY FK_52EA1F094584665A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_item DROP product_in_json, CHANGE product_id product_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F094584665A FOREIGN KEY (product_id) REFERENCES product (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_address DROP FOREIGN KEY FK_FB34C6CA4D4CFF2B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_address DROP FOREIGN KEY FK_FB34C6CA79D0C0E4
        SQL);
    }
}
