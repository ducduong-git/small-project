<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251030080302 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category ADD created_by VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE category ADD updated_by VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE category ALTER deleted_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE category ALTER created_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE category ALTER created_at SET NOT NULL');
        $this->addSql('ALTER TABLE category ALTER updated_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN category.deleted_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN category.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN category.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE permission ADD created_by VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE permission ADD updated_by VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD created_by VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD updated_by VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE product ALTER created_at SET NOT NULL');
        $this->addSql('ALTER TABLE users ADD deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD created_by VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD updated_by VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE users ALTER created_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE users ALTER created_at SET NOT NULL');
        $this->addSql('ALTER TABLE users ALTER updated_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN users.deleted_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN users.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN users.updated_at IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE category DROP created_by');
        $this->addSql('ALTER TABLE category DROP updated_by');
        $this->addSql('ALTER TABLE category ALTER created_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE category ALTER created_at DROP NOT NULL');
        $this->addSql('ALTER TABLE category ALTER updated_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE category ALTER deleted_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN category.created_at IS NULL');
        $this->addSql('COMMENT ON COLUMN category.updated_at IS NULL');
        $this->addSql('COMMENT ON COLUMN category.deleted_at IS NULL');
        $this->addSql('ALTER TABLE product DROP created_by');
        $this->addSql('ALTER TABLE product DROP updated_by');
        $this->addSql('ALTER TABLE product ALTER created_at DROP NOT NULL');
        $this->addSql('ALTER TABLE permission DROP created_by');
        $this->addSql('ALTER TABLE permission DROP updated_by');
        $this->addSql('ALTER TABLE users DROP deleted_at');
        $this->addSql('ALTER TABLE users DROP created_by');
        $this->addSql('ALTER TABLE users DROP updated_by');
        $this->addSql('ALTER TABLE users ALTER created_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE users ALTER created_at DROP NOT NULL');
        $this->addSql('ALTER TABLE users ALTER updated_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN users.created_at IS NULL');
        $this->addSql('COMMENT ON COLUMN users.updated_at IS NULL');
    }
}
