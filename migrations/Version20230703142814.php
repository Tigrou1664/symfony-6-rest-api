<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230703142814 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE boutique_article (id INT AUTO_INCREMENT NOT NULL, boutique_id INT NOT NULL, article_id INT NOT NULL, stock INT DEFAULT NULL, tarif_location_jour INT DEFAULT NULL, INDEX IDX_CFFECF1AAB677BE6 (boutique_id), INDEX IDX_CFFECF1A7294869C (article_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE boutique_article ADD CONSTRAINT FK_CFFECF1AAB677BE6 FOREIGN KEY (boutique_id) REFERENCES boutique (id)');
        $this->addSql('ALTER TABLE boutique_article ADD CONSTRAINT FK_CFFECF1A7294869C FOREIGN KEY (article_id) REFERENCES article (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE boutique_article DROP FOREIGN KEY FK_CFFECF1AAB677BE6');
        $this->addSql('ALTER TABLE boutique_article DROP FOREIGN KEY FK_CFFECF1A7294869C');
        $this->addSql('DROP TABLE boutique_article');
    }
}
