<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210616151610 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE properties ADD pizza_id INT NOT NULL');
        $this->addSql('ALTER TABLE properties ADD CONSTRAINT FK_87C331C7D41D1D42 FOREIGN KEY (pizza_id) REFERENCES pizzas (id)');
        $this->addSql('CREATE INDEX IDX_87C331C7D41D1D42 ON properties (pizza_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE properties DROP FOREIGN KEY FK_87C331C7D41D1D42');
        $this->addSql('DROP INDEX IDX_87C331C7D41D1D42 ON properties');
        $this->addSql('ALTER TABLE properties DROP pizza_id');
    }
}
