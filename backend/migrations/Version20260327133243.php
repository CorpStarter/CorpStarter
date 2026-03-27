<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260327133243 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE project (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, requested_budget NUMERIC(15, 2) DEFAULT NULL, illustration_path VARCHAR(255) DEFAULT NULL, allocated_budget NUMERIC(15, 2) DEFAULT NULL, creation_date DATETIME NOT NULL, approver_id INT DEFAULT NULL, status_id INT NOT NULL, INDEX IDX_2FB3D0EEBB23766C (approver_id), INDEX IDX_2FB3D0EE6BF700BD (status_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE project_users (project_id INT NOT NULL, users_id INT NOT NULL, INDEX IDX_7D6AC77166D1F9C (project_id), INDEX IDX_7D6AC7767B3B43D (users_id), PRIMARY KEY (project_id, users_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE project_status (id INT AUTO_INCREMENT NOT NULL, status_name VARCHAR(255) NOT NULL, creation_date DATETIME NOT NULL, validated TINYINT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE user_types (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, creation_date DATETIME NOT NULL, can_accept_project TINYINT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password_hash VARCHAR(255) NOT NULL, email_confirmed TINYINT NOT NULL, terms_accepted TINYINT NOT NULL, creation_date DATETIME NOT NULL, connection_token VARCHAR(255) DEFAULT NULL, token_date DATETIME DEFAULT NULL, user_type_id INT DEFAULT NULL, INDEX IDX_1483A5E99D419299 (user_type_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EEBB23766C FOREIGN KEY (approver_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE6BF700BD FOREIGN KEY (status_id) REFERENCES project_status (id)');
        $this->addSql('ALTER TABLE project_users ADD CONSTRAINT FK_7D6AC77166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE project_users ADD CONSTRAINT FK_7D6AC7767B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E99D419299 FOREIGN KEY (user_type_id) REFERENCES user_types (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EEBB23766C');
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EE6BF700BD');
        $this->addSql('ALTER TABLE project_users DROP FOREIGN KEY FK_7D6AC77166D1F9C');
        $this->addSql('ALTER TABLE project_users DROP FOREIGN KEY FK_7D6AC7767B3B43D');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E99D419299');
        $this->addSql('DROP TABLE project');
        $this->addSql('DROP TABLE project_users');
        $this->addSql('DROP TABLE project_status');
        $this->addSql('DROP TABLE user_types');
        $this->addSql('DROP TABLE users');
    }
}
