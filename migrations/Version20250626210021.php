<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250626210021 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE editions DROP festival_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE festival_artist DROP edition_id, DROP artist_id, DROP stage_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE purchase DROP user_id, DROP festival_id, DROP type_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE schedule DROP artist_id, DROP edition_id, DROP stage_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_details ADD user_id INT NOT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE editions ADD festival_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE festival_artist ADD edition_id INT NOT NULL, ADD artist_id INT NOT NULL, ADD stage_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE purchase ADD user_id INT NOT NULL, ADD festival_id INT NOT NULL, ADD type_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE schedule ADD artist_id INT NOT NULL, ADD edition_id INT NOT NULL, ADD stage_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_details DROP user_id
        SQL);
    }
}
