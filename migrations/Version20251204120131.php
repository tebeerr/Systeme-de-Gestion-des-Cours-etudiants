<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251204120131 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE examen ADD user_id INT DEFAULT NULL, ADD cours_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE examen ADD CONSTRAINT FK_514C8FECA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE examen ADD CONSTRAINT FK_514C8FEC7ECF78B0 FOREIGN KEY (cours_id) REFERENCES cours (id)');
        $this->addSql('CREATE INDEX IDX_514C8FECA76ED395 ON examen (user_id)');
        $this->addSql('CREATE INDEX IDX_514C8FEC7ECF78B0 ON examen (cours_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE examen DROP FOREIGN KEY FK_514C8FECA76ED395');
        $this->addSql('ALTER TABLE examen DROP FOREIGN KEY FK_514C8FEC7ECF78B0');
        $this->addSql('DROP INDEX IDX_514C8FECA76ED395 ON examen');
        $this->addSql('DROP INDEX IDX_514C8FEC7ECF78B0 ON examen');
        $this->addSql('ALTER TABLE examen DROP user_id, DROP cours_id');
    }
}
