<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240529045447 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE order_status_type (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('INSERT INTO order_status_type (type, description ) VALUES ("ORDER_CREATED","Order Created")');
        $this->addSql('INSERT INTO order_status_type (type, description ) VALUES ("ORDER_PAYMENT_COMPLETE","Order Payment Complete")');
        $this->addSql('INSERT INTO order_status_type (type, description ) VALUES ("ORDER_IN_PROCESS","Order In Process")');
        $this->addSql('INSERT INTO order_status_type (type, description ) VALUES ("ORDER_SHIPPED","Order Shipped")');
        $this->addSql('INSERT INTO order_status_type (type, description ) VALUES ("ORDER_COMPLETED","Order Completed")');


    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE order_status_type');
    }
}
