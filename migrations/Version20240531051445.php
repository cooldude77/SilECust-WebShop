<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240531051445 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_status DROP FOREIGN KEY FK_B88F75C9A5EC2BA6');
        $this->addSql('DROP INDEX UNIQ_B88F75C9A5EC2BA6 ON order_status');
        $this->addSql('ALTER TABLE order_status CHANGE order_status_type_id status_type_id INT NOT NULL');
        $this->addSql('ALTER TABLE order_status ADD CONSTRAINT FK_B88F75C9CD9CFB16 FOREIGN KEY (status_type_id) REFERENCES order_status_type (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B88F75C9CD9CFB16 ON order_status (status_type_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_status DROP FOREIGN KEY FK_B88F75C9CD9CFB16');
        $this->addSql('DROP INDEX UNIQ_B88F75C9CD9CFB16 ON order_status');
        $this->addSql('ALTER TABLE order_status CHANGE status_type_id order_status_type_id INT NOT NULL');
        $this->addSql('ALTER TABLE order_status ADD CONSTRAINT FK_B88F75C9A5EC2BA6 FOREIGN KEY (order_status_type_id) REFERENCES order_status_type (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B88F75C9A5EC2BA6 ON order_status (order_status_type_id)');
    }
}
