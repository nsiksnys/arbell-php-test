<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250530182428 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create profiles';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("INSERT INTO `profile` (`role`) VALUES ('ROLE_ADMIN'), ('ROLE_USER')");
    }

    public function down(Schema $schema): void
    {
        $this->addSql("DELETE FROM `profile`");
    }
}
