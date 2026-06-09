<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260609050942 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Rename user and publication tables with app prefix';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            'RENAME TABLE app_user TO app__user, publication TO app__publication'
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql(
            'RENAME TABLE app__user TO app_user, app__publication TO publication'
        );
    }
}
