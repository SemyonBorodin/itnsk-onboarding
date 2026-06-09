<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260609050126 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Rename user__user table to app_user';
    }

    public function up(Schema $schema): void
    {
        // The failed auto-generated migration may have left an empty app_user table.
        $this->addSql('DROP TABLE IF EXISTS app_user');
        $this->addSql('RENAME TABLE user__user TO app_user');
        $this->addSql('ALTER TABLE app_user RENAME INDEX UNIQ_32745D0A92FC23A8 TO UNIQ_88BDF3E992FC23A8');
        $this->addSql('ALTER TABLE app_user RENAME INDEX UNIQ_32745D0AA0D96FBF TO UNIQ_88BDF3E9A0D96FBF');
        $this->addSql('ALTER TABLE app_user RENAME INDEX UNIQ_32745D0AC05FB297 TO UNIQ_88BDF3E9C05FB297');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE app_user RENAME INDEX UNIQ_88BDF3E992FC23A8 TO UNIQ_32745D0A92FC23A8');
        $this->addSql('ALTER TABLE app_user RENAME INDEX UNIQ_88BDF3E9A0D96FBF TO UNIQ_32745D0AA0D96FBF');
        $this->addSql('ALTER TABLE app_user RENAME INDEX UNIQ_88BDF3E9C05FB297 TO UNIQ_32745D0AC05FB297');
        $this->addSql('RENAME TABLE app_user TO user__user');
    }
}
