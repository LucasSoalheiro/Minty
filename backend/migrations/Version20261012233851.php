<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261012233851 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE Accounts (id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, balance INT NOT NULL, user_id VARCHAR(255) NOT NULL, is_active BOOLEAN NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE TABLE Categories (id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, user_id VARCHAR(255) NOT NULL, is_active BOOLEAN NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE TABLE Session (id VARCHAR(255) NOT NULL, user_id VARCHAR(255) NOT NULL, token_hash VARCHAR(255) NOT NULL, expires_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, revoked BOOLEAN NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE TABLE Transactions (id VARCHAR(255) NOT NULL, account_id VARCHAR(255) NOT NULL, amount INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, type VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, category_id VARCHAR(255) NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE TABLE Users (id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY (id))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE Accounts');
        $this->addSql('DROP TABLE Categories');
        $this->addSql('DROP TABLE Session');
        $this->addSql('DROP TABLE Transactions');
        $this->addSql('DROP TABLE Users');
    }
}
