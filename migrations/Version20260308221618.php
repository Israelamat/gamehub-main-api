<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260308221618 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE order_game (order_id INT NOT NULL, game_id INT NOT NULL, INDEX IDX_6EDA0B9B8D9F6D38 (order_id), INDEX IDX_6EDA0B9BE48FD905 (game_id), PRIMARY KEY (order_id, game_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE order_course (order_id INT NOT NULL, course_id INT NOT NULL, INDEX IDX_D5117F6C8D9F6D38 (order_id), INDEX IDX_D5117F6C591CC992 (course_id), PRIMARY KEY (order_id, course_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE order_game ADD CONSTRAINT FK_6EDA0B9B8D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE order_game ADD CONSTRAINT FK_6EDA0B9BE48FD905 FOREIGN KEY (game_id) REFERENCES game (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE order_course ADD CONSTRAINT FK_D5117F6C8D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE order_course ADD CONSTRAINT FK_D5117F6C591CC992 FOREIGN KEY (course_id) REFERENCES course (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_game DROP FOREIGN KEY FK_6EDA0B9B8D9F6D38');
        $this->addSql('ALTER TABLE order_game DROP FOREIGN KEY FK_6EDA0B9BE48FD905');
        $this->addSql('ALTER TABLE order_course DROP FOREIGN KEY FK_D5117F6C8D9F6D38');
        $this->addSql('ALTER TABLE order_course DROP FOREIGN KEY FK_D5117F6C591CC992');
        $this->addSql('DROP TABLE order_game');
        $this->addSql('DROP TABLE order_course');
    }
}
