<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250117123855 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE depositary (id INT AUTO_INCREMENT NOT NULL, quantity INT NOT NULL, stock_id INT NOT NULL, potrfolio_id INT NOT NULL, INDEX IDX_7CD08B68DCD6110 (stock_id), INDEX IDX_7CD08B68C76F5F8E (potrfolio_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE portfolio (id INT AUTO_INCREMENT NOT NULL, balance DOUBLE PRECISION NOT NULL, user_id INT NOT NULL, INDEX IDX_A9ED1062A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE depositary ADD CONSTRAINT FK_7CD08B68DCD6110 FOREIGN KEY (stock_id) REFERENCES stock (id)');
        $this->addSql('ALTER TABLE depositary ADD CONSTRAINT FK_7CD08B68C76F5F8E FOREIGN KEY (potrfolio_id) REFERENCES portfolio (id)');
        $this->addSql('ALTER TABLE portfolio ADD CONSTRAINT FK_A9ED1062A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE depositary DROP FOREIGN KEY FK_7CD08B68DCD6110');
        $this->addSql('ALTER TABLE depositary DROP FOREIGN KEY FK_7CD08B68C76F5F8E');
        $this->addSql('ALTER TABLE portfolio DROP FOREIGN KEY FK_A9ED1062A76ED395');
        $this->addSql('DROP TABLE depositary');
        $this->addSql('DROP TABLE portfolio');
    }
}
