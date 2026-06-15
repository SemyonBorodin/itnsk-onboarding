<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260615085727 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add categories and assign uncategorized to existing publications';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE app__category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_80E30394989D9B62 (slug), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE app__publication ADD category_id INT DEFAULT NULL');
        $this->addSql("INSERT INTO app__category (name, slug) VALUES ('Без категории', 'uncategorized')");
        $this->addSql("UPDATE app__publication SET category_id = (SELECT id FROM app__category WHERE slug = 'uncategorized')");
        $this->addSql('ALTER TABLE app__publication CHANGE category_id category_id INT NOT NULL');
        $this->addSql('ALTER TABLE app__publication ADD CONSTRAINT FK_330FCF0612469DE2 FOREIGN KEY (category_id) REFERENCES app__category (id)');
        $this->addSql('CREATE INDEX IDX_330FCF0612469DE2 ON app__publication (category_id)');
        $this->addSql('ALTER TABLE app__publication RENAME INDEX uniq_af3c6779989d9b62 TO UNIQ_330FCF06989D9B62');
        $this->addSql('ALTER TABLE app__publication RENAME INDEX idx_af3c6779f675f31b TO IDX_330FCF06F675F31B');
        $this->addSql('ALTER TABLE app__user RENAME INDEX uniq_88bdf3e992fc23a8 TO UNIQ_6652B9F92FC23A8');
        $this->addSql('ALTER TABLE app__user RENAME INDEX uniq_88bdf3e9a0d96fbf TO UNIQ_6652B9FA0D96FBF');
        $this->addSql('ALTER TABLE app__user RENAME INDEX uniq_88bdf3e9c05fb297 TO UNIQ_6652B9FC05FB297');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE app__publication DROP FOREIGN KEY FK_330FCF0612469DE2');
        $this->addSql('DROP INDEX IDX_330FCF0612469DE2 ON app__publication');
        $this->addSql('ALTER TABLE app__publication DROP category_id');
        $this->addSql('DROP TABLE app__category');
        $this->addSql('ALTER TABLE app__publication RENAME INDEX idx_330fcf06f675f31b TO IDX_AF3C6779F675F31B');
        $this->addSql('ALTER TABLE app__publication RENAME INDEX uniq_330fcf06989d9b62 TO UNIQ_AF3C6779989D9B62');
        $this->addSql('ALTER TABLE app__user RENAME INDEX uniq_6652b9f92fc23a8 TO UNIQ_88BDF3E992FC23A8');
        $this->addSql('ALTER TABLE app__user RENAME INDEX uniq_6652b9fa0d96fbf TO UNIQ_88BDF3E9A0D96FBF');
        $this->addSql('ALTER TABLE app__user RENAME INDEX uniq_6652b9fc05fb297 TO UNIQ_88BDF3E9C05FB297');
    }
}
