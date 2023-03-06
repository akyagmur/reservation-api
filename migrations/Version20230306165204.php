<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230306165204 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE listing ALTER reference TYPE UUID');
        $this->addSql('ALTER TABLE listing ALTER reference SET NOT NULL');
        $this->addSql('COMMENT ON COLUMN listing.reference IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CB0048D4AEA34913 ON listing (reference)');
        $this->addSql('ALTER TABLE reservation ADD reference UUID DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX UNIQ_CB0048D4AEA34913');
        $this->addSql('ALTER TABLE listing ALTER reference TYPE UUID');
        $this->addSql('ALTER TABLE listing ALTER reference DROP NOT NULL');
        $this->addSql('COMMENT ON COLUMN listing.reference IS NULL');
        $this->addSql('ALTER TABLE reservation DROP reference');
    }
}
