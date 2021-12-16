<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211216135819 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_6EEAA67DA76ED395 ON commande (user_id)');
        $this->addSql('ALTER TABLE commentaire ADD chaussure_id INT NOT NULL');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCF8458E35 FOREIGN KEY (chaussure_id) REFERENCES chaussure (id)');
        $this->addSql('CREATE INDEX IDX_67F068BCF8458E35 ON commentaire (chaussure_id)');
        $this->addSql('ALTER TABLE details_commande ADD chaussure_id INT NOT NULL, ADD commande_id INT NOT NULL');
        $this->addSql('ALTER TABLE details_commande ADD CONSTRAINT FK_4BCD5F6F8458E35 FOREIGN KEY (chaussure_id) REFERENCES chaussure (id)');
        $this->addSql('ALTER TABLE details_commande ADD CONSTRAINT FK_4BCD5F682EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('CREATE INDEX IDX_4BCD5F6F8458E35 ON details_commande (chaussure_id)');
        $this->addSql('CREATE INDEX IDX_4BCD5F682EA2E54 ON details_commande (commande_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DA76ED395');
        $this->addSql('DROP INDEX IDX_6EEAA67DA76ED395 ON commande');
        $this->addSql('ALTER TABLE commande DROP user_id');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCF8458E35');
        $this->addSql('DROP INDEX IDX_67F068BCF8458E35 ON commentaire');
        $this->addSql('ALTER TABLE commentaire DROP chaussure_id');
        $this->addSql('ALTER TABLE details_commande DROP FOREIGN KEY FK_4BCD5F6F8458E35');
        $this->addSql('ALTER TABLE details_commande DROP FOREIGN KEY FK_4BCD5F682EA2E54');
        $this->addSql('DROP INDEX IDX_4BCD5F6F8458E35 ON details_commande');
        $this->addSql('DROP INDEX IDX_4BCD5F682EA2E54 ON details_commande');
        $this->addSql('ALTER TABLE details_commande DROP chaussure_id, DROP commande_id');
    }
}
