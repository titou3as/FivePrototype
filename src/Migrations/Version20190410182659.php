<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190410182659 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE contributor (id INT AUTO_INCREMENT NOT NULL, login VARCHAR(255) NOT NULL, pwd VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE decision (id INT AUTO_INCREMENT NOT NULL, document_id INT DEFAULT NULL, contributor_id INT DEFAULT NULL, is_taken TINYINT(1) NOT NULL, content VARCHAR(255) NOT NULL, deposit VARCHAR(255) DEFAULT NULL, INDEX IDX_84ACBE48C33F7837 (document_id), INDEX IDX_84ACBE487A19A357 (contributor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE document (id INT AUTO_INCREMENT NOT NULL, doi VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE document_contributor (document_id INT NOT NULL, contributor_id INT NOT NULL, INDEX IDX_58C6A0F9C33F7837 (document_id), INDEX IDX_58C6A0F97A19A357 (contributor_id), PRIMARY KEY(document_id, contributor_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE decision ADD CONSTRAINT FK_84ACBE48C33F7837 FOREIGN KEY (document_id) REFERENCES document (id)');
        $this->addSql('ALTER TABLE decision ADD CONSTRAINT FK_84ACBE487A19A357 FOREIGN KEY (contributor_id) REFERENCES contributor (id)');
        $this->addSql('ALTER TABLE document_contributor ADD CONSTRAINT FK_58C6A0F9C33F7837 FOREIGN KEY (document_id) REFERENCES document (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE document_contributor ADD CONSTRAINT FK_58C6A0F97A19A357 FOREIGN KEY (contributor_id) REFERENCES contributor (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE decision DROP FOREIGN KEY FK_84ACBE487A19A357');
        $this->addSql('ALTER TABLE document_contributor DROP FOREIGN KEY FK_58C6A0F97A19A357');
        $this->addSql('ALTER TABLE decision DROP FOREIGN KEY FK_84ACBE48C33F7837');
        $this->addSql('ALTER TABLE document_contributor DROP FOREIGN KEY FK_58C6A0F9C33F7837');
        $this->addSql('DROP TABLE contributor');
        $this->addSql('DROP TABLE decision');
        $this->addSql('DROP TABLE document');
        $this->addSql('DROP TABLE document_contributor');
    }
}
