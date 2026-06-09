<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260609043933 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add an optional author to publications and require updated_at';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            ALTER TABLE
              publication
            ADD
              author_id INT DEFAULT NULL,
            CHANGE
              updated_at updated_at DATETIME NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE
              publication
            ADD
              CONSTRAINT FK_AF3C6779F675F31B FOREIGN KEY (author_id) REFERENCES user__user (id)
        SQL);
        $this->addSql('CREATE INDEX IDX_AF3C6779F675F31B ON publication (author_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE publication DROP FOREIGN KEY FK_AF3C6779F675F31B');
        $this->addSql('DROP INDEX IDX_AF3C6779F675F31B ON publication');
        $this->addSql('ALTER TABLE publication DROP author_id, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
    }
}
