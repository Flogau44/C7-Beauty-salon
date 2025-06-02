<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250602204350 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE department ADD region_id_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE department ADD CONSTRAINT FK_CD1DE18AC7209D4F FOREIGN KEY (region_id_id) REFERENCES region (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_CD1DE18AC7209D4F ON department (region_id_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE sales ADD salon_id_id INT DEFAULT NULL, ADD sales_average_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE sales ADD CONSTRAINT FK_6B8170441EC9CE27 FOREIGN KEY (salon_id_id) REFERENCES salon (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE sales ADD CONSTRAINT FK_6B817044CF940967 FOREIGN KEY (sales_average_id) REFERENCES sales_average (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_6B8170441EC9CE27 ON sales (salon_id_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_6B817044CF940967 ON sales (sales_average_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE salon ADD department_id_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE salon ADD CONSTRAINT FK_F268F41764E7214B FOREIGN KEY (department_id_id) REFERENCES department (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_F268F41764E7214B ON salon (department_id_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user ADD salon_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user ADD CONSTRAINT FK_8D93D6494C91BDE4 FOREIGN KEY (salon_id) REFERENCES salon (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_8D93D6494C91BDE4 ON user (salon_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE department DROP FOREIGN KEY FK_CD1DE18AC7209D4F
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_CD1DE18AC7209D4F ON department
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE department DROP region_id_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE sales DROP FOREIGN KEY FK_6B8170441EC9CE27
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE sales DROP FOREIGN KEY FK_6B817044CF940967
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_6B8170441EC9CE27 ON sales
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_6B817044CF940967 ON sales
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE sales DROP salon_id_id, DROP sales_average_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE salon DROP FOREIGN KEY FK_F268F41764E7214B
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_F268F41764E7214B ON salon
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE salon DROP department_id_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user DROP FOREIGN KEY FK_8D93D6494C91BDE4
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_8D93D6494C91BDE4 ON user
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user DROP salon_id
        SQL);
    }
}
