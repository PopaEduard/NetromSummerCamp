<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250626212432 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE editions ADD festival_id_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE editions ADD CONSTRAINT FK_96AE53E611F56659 FOREIGN KEY (festival_id_id) REFERENCES festival (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_96AE53E611F56659 ON editions (festival_id_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE festival_artist ADD edition_id_id INT NOT NULL, ADD artist_id_id INT NOT NULL, ADD stage_id_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE festival_artist ADD CONSTRAINT FK_E68F0A7885FB94DF FOREIGN KEY (edition_id_id) REFERENCES editions (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE festival_artist ADD CONSTRAINT FK_E68F0A781F48AE04 FOREIGN KEY (artist_id_id) REFERENCES artist (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE festival_artist ADD CONSTRAINT FK_E68F0A78FFE32C93 FOREIGN KEY (stage_id_id) REFERENCES stage (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_E68F0A7885FB94DF ON festival_artist (edition_id_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_E68F0A781F48AE04 ON festival_artist (artist_id_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_E68F0A78FFE32C93 ON festival_artist (stage_id_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE purchase ADD user_id_id INT NOT NULL, ADD festival_id_id INT NOT NULL, ADD type_id_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE purchase ADD CONSTRAINT FK_6117D13B9D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE purchase ADD CONSTRAINT FK_6117D13B11F56659 FOREIGN KEY (festival_id_id) REFERENCES festival (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE purchase ADD CONSTRAINT FK_6117D13B714819A0 FOREIGN KEY (type_id_id) REFERENCES ticket (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_6117D13B9D86650F ON purchase (user_id_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_6117D13B11F56659 ON purchase (festival_id_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_6117D13B714819A0 ON purchase (type_id_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_details ADD user_id_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_details ADD CONSTRAINT FK_2A2B15809D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_2A2B15809D86650F ON user_details (user_id_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE editions DROP FOREIGN KEY FK_96AE53E611F56659
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_96AE53E611F56659 ON editions
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE editions DROP festival_id_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE festival_artist DROP FOREIGN KEY FK_E68F0A7885FB94DF
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE festival_artist DROP FOREIGN KEY FK_E68F0A781F48AE04
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE festival_artist DROP FOREIGN KEY FK_E68F0A78FFE32C93
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_E68F0A7885FB94DF ON festival_artist
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_E68F0A781F48AE04 ON festival_artist
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_E68F0A78FFE32C93 ON festival_artist
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE festival_artist DROP edition_id_id, DROP artist_id_id, DROP stage_id_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE purchase DROP FOREIGN KEY FK_6117D13B9D86650F
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE purchase DROP FOREIGN KEY FK_6117D13B11F56659
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE purchase DROP FOREIGN KEY FK_6117D13B714819A0
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_6117D13B9D86650F ON purchase
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_6117D13B11F56659 ON purchase
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_6117D13B714819A0 ON purchase
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE purchase DROP user_id_id, DROP festival_id_id, DROP type_id_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_details DROP FOREIGN KEY FK_2A2B15809D86650F
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_2A2B15809D86650F ON user_details
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_details DROP user_id_id
        SQL);
    }
}
