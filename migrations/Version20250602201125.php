<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250602201125 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE region ADD country_id_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE region ADD CONSTRAINT FK_F62F176D8A48BBD FOREIGN KEY (country_id_id) REFERENCES country (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_F62F176D8A48BBD ON region (country_id_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE region DROP FOREIGN KEY FK_F62F176D8A48BBD
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_F62F176D8A48BBD ON region
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE region DROP country_id_id
        SQL);
    }
}
