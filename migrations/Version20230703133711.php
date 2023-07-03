<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230703133711 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE boutique_utilisateur (id INT AUTO_INCREMENT NOT NULL, boutique_id INT NOT NULL, utilisateur_id INT NOT NULL, role ENUM(\'vendor\', \'admin\') NOT NULL COMMENT \'(DC2Type:enum_role_type)\', INDEX IDX_E6DB2CA7AB677BE6 (boutique_id), INDEX IDX_E6DB2CA7FB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE boutique_utilisateur ADD CONSTRAINT FK_E6DB2CA7AB677BE6 FOREIGN KEY (boutique_id) REFERENCES boutique (id)');
        $this->addSql('ALTER TABLE boutique_utilisateur ADD CONSTRAINT FK_E6DB2CA7FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE boutique_utilisateur DROP FOREIGN KEY FK_E6DB2CA7AB677BE6');
        $this->addSql('ALTER TABLE boutique_utilisateur DROP FOREIGN KEY FK_E6DB2CA7FB88E14F');
        $this->addSql('DROP TABLE boutique_utilisateur');
    }
}
