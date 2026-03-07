<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260307115335 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, reference VARCHAR(20) NOT NULL, total DOUBLE PRECISION NOT NULL, status VARCHAR(255) NOT NULL, user_id INT DEFAULT NULL, INDEX IDX_F5299398A76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE review (id INT AUTO_INCREMENT NOT NULL, rating INT NOT NULL, comment LONGTEXT DEFAULT NULL, user_id INT NOT NULL, game_id INT NOT NULL, INDEX IDX_794381C6A76ED395 (user_id), INDEX IDX_794381C6E48FD905 (game_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6E48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
        $this->addSql('ALTER TABLE game ADD app_id INT NOT NULL, ADD header_image VARCHAR(255) DEFAULT NULL, ADD genres LONGTEXT DEFAULT NULL, ADD tags LONGTEXT DEFAULT NULL, ADD metadata LONGTEXT DEFAULT NULL, ADD developer VARCHAR(255) DEFAULT NULL, ADD screenshot LONGTEXT DEFAULT NULL, CHANGE created_by_id created_by_id INT DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_232B318C7987212D ON game (app_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398A76ED395');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6A76ED395');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6E48FD905');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE review');
        $this->addSql('DROP INDEX UNIQ_232B318C7987212D ON game');
        $this->addSql('ALTER TABLE game DROP app_id, DROP header_image, DROP genres, DROP tags, DROP metadata, DROP developer, DROP screenshot, CHANGE created_by_id created_by_id INT NOT NULL');
    }
}
