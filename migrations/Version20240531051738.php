<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240531051738 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_status DROP FOREIGN KEY FK_B88F75C92EF91FD8');
        $this->addSql('ALTER TABLE order_status DROP FOREIGN KEY FK_B88F75C9CD9CFB16');
        $this->addSql('DROP INDEX IDX_B88F75C92EF91FD8 ON order_status');
        $this->addSql('DROP INDEX UNIQ_B88F75C9CD9CFB16 ON order_status');
        $this->addSql('ALTER TABLE order_status ADD order_header_id INT NOT NULL, ADD order_status_type_id INT NOT NULL, DROP header_id, DROP status_type_id');
        $this->addSql('ALTER TABLE order_status ADD CONSTRAINT FK_B88F75C9927E6420 FOREIGN KEY (order_header_id) REFERENCES order_header (id)');
        $this->addSql('ALTER TABLE order_status ADD CONSTRAINT FK_B88F75C9A5EC2BA6 FOREIGN KEY (order_status_type_id) REFERENCES order_status_type (id)');
        $this->addSql('CREATE INDEX IDX_B88F75C9927E6420 ON order_status (order_header_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B88F75C9A5EC2BA6 ON order_status (order_status_type_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_status DROP FOREIGN KEY FK_B88F75C9927E6420');
        $this->addSql('ALTER TABLE order_status DROP FOREIGN KEY FK_B88F75C9A5EC2BA6');
        $this->addSql('DROP INDEX IDX_B88F75C9927E6420 ON order_status');
        $this->addSql('DROP INDEX UNIQ_B88F75C9A5EC2BA6 ON order_status');
        $this->addSql('ALTER TABLE order_status ADD header_id INT NOT NULL, ADD status_type_id INT NOT NULL, DROP order_header_id, DROP order_status_type_id');
        $this->addSql('ALTER TABLE order_status ADD CONSTRAINT FK_B88F75C92EF91FD8 FOREIGN KEY (header_id) REFERENCES order_header (id)');
        $this->addSql('ALTER TABLE order_status ADD CONSTRAINT FK_B88F75C9CD9CFB16 FOREIGN KEY (status_type_id) REFERENCES order_status_type (id)');
        $this->addSql('CREATE INDEX IDX_B88F75C92EF91FD8 ON order_status (header_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B88F75C9CD9CFB16 ON order_status (status_type_id)');
    }
}
