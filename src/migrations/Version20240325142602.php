<?php

declare(strict_types=1);

namespace Silecust\WebShop\migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240325142602 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE web_shop_section DROP FOREIGN KEY FK_A941AE3BACFF1C48');
        $this->addSql('DROP INDEX IDX_A941AE3BACFF1C48 ON web_shop_section');
        $this->addSql('ALTER TABLE web_shop_section CHANGE web_shop_home_id web_shop_id INT NOT NULL');
        $this->addSql('ALTER TABLE web_shop_section ADD CONSTRAINT FK_A941AE3BA5B96A43 FOREIGN KEY (web_shop_id) REFERENCES web_shop (id)');
        $this->addSql('CREATE INDEX IDX_A941AE3BA5B96A43 ON web_shop_section (web_shop_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE web_shop_section DROP FOREIGN KEY FK_A941AE3BA5B96A43');
        $this->addSql('DROP INDEX IDX_A941AE3BA5B96A43 ON web_shop_section');
        $this->addSql('ALTER TABLE web_shop_section CHANGE web_shop_id web_shop_home_id INT NOT NULL');
        $this->addSql('ALTER TABLE web_shop_section ADD CONSTRAINT FK_A941AE3BACFF1C48 FOREIGN KEY (web_shop_home_id) REFERENCES web_shop (id)');
        $this->addSql('CREATE INDEX IDX_A941AE3BACFF1C48 ON web_shop_section (web_shop_home_id)');
    }
}
