<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241223101554 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE film (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, rcs VARCHAR(255) NOT NULL, capital INT NOT NULL, adress VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO film (name, rcs, capital, adress) VALUES (\'Banlieusard 2\', \'RCS Paris 123456789\', 30000, \'33 Avenue Philippe Auguste 75011 Paris\')');
        $this->addSql('ALTER TABLE "user" ALTER permit TYPE VARCHAR(255)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE film');
        $this->addSql('ALTER TABLE "user" ALTER permit TYPE BOOLEAN');
    }
}
