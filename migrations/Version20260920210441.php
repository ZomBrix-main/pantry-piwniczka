<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260920210441 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE audit_log (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, description CLOB NOT NULL, created_at DATETIME NOT NULL, action_type VARCHAR(50) NOT NULL)');
        $this->addSql('CREATE TABLE audit_log_jar (audit_log_id INTEGER NOT NULL, jar_id INTEGER NOT NULL, PRIMARY KEY (audit_log_id, jar_id), CONSTRAINT FK_62B21B9C9B9715D FOREIGN KEY (audit_log_id) REFERENCES audit_log (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_62B21B9E4C9A757 FOREIGN KEY (jar_id) REFERENCES jar (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_62B21B9C9B9715D ON audit_log_jar (audit_log_id)');
        $this->addSql('CREATE INDEX IDX_62B21B9E4C9A757 ON audit_log_jar (jar_id)');
        $this->addSql('CREATE TABLE audit_log_empty_jar_stat (audit_log_id INTEGER NOT NULL, empty_jar_stat_id INTEGER NOT NULL, PRIMARY KEY (audit_log_id, empty_jar_stat_id), CONSTRAINT FK_71F33124C9B9715D FOREIGN KEY (audit_log_id) REFERENCES audit_log (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_71F33124B28F8BBC FOREIGN KEY (empty_jar_stat_id) REFERENCES empty_jar_stat (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_71F33124C9B9715D ON audit_log_empty_jar_stat (audit_log_id)');
        $this->addSql('CREATE INDEX IDX_71F33124B28F8BBC ON audit_log_empty_jar_stat (empty_jar_stat_id)');
        $this->addSql('CREATE TABLE empty_jar_stat (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, type VARCHAR(100) NOT NULL, quantity INTEGER NOT NULL)');
        $this->addSql('CREATE TABLE jar (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, content VARCHAR(255) NOT NULL, created_at DATE NOT NULL, status VARCHAR(50) NOT NULL, location VARCHAR(100) NOT NULL, type VARCHAR(100) NOT NULL)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE audit_log');
        $this->addSql('DROP TABLE audit_log_jar');
        $this->addSql('DROP TABLE audit_log_empty_jar_stat');
        $this->addSql('DROP TABLE empty_jar_stat');
        $this->addSql('DROP TABLE jar');
    }
}
