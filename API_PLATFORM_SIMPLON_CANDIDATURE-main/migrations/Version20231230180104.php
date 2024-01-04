<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231230180104 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE candidatures (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, formation_id INT DEFAULT NULL, INDEX IDX_DE57CF66A76ED395 (user_id), INDEX IDX_DE57CF665200282E (formation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE formations (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, duree INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE candidatures ADD CONSTRAINT FK_DE57CF66A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE candidatures ADD CONSTRAINT FK_DE57CF665200282E FOREIGN KEY (formation_id) REFERENCES formations (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE candidatures DROP FOREIGN KEY FK_DE57CF66A76ED395');
        $this->addSql('ALTER TABLE candidatures DROP FOREIGN KEY FK_DE57CF665200282E');
        $this->addSql('DROP TABLE candidatures');
        $this->addSql('DROP TABLE formations');
        $this->addSql('DROP TABLE users');
    }
}
