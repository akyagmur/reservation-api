<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230306165805 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE INDEX IDX_CB0048D4AEA34913 ON listing (reference)');
        $this->addSql('CREATE INDEX IDX_CB0048D4B478319EC6260DE0 ON listing (available_from_date, available_to_date)');
        $this->addSql('ALTER TABLE reservation ALTER reference TYPE UUID');
        $this->addSql('ALTER TABLE reservation ALTER reference SET NOT NULL');
        $this->addSql('COMMENT ON COLUMN reservation.reference IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_42C84955AEA34913 ON reservation (reference)');
        $this->addSql('CREATE INDEX IDX_42C84955AEA34913 ON reservation (reference)');
        $this->addSql('CREATE INDEX IDX_42C8495595275AB8845CBB3E ON reservation (start_date, end_date)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX IDX_CB0048D4AEA34913');
        $this->addSql('DROP INDEX IDX_CB0048D4B478319EC6260DE0');
        $this->addSql('DROP INDEX UNIQ_42C84955AEA34913');
        $this->addSql('DROP INDEX IDX_42C84955AEA34913');
        $this->addSql('DROP INDEX IDX_42C8495595275AB8845CBB3E');
        $this->addSql('ALTER TABLE reservation ALTER reference TYPE UUID');
        $this->addSql('ALTER TABLE reservation ALTER reference DROP NOT NULL');
        $this->addSql('COMMENT ON COLUMN reservation.reference IS NULL');
    }
}
