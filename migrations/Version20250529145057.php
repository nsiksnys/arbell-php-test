<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250529145057 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'User and profile';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE profile (
            id INT AUTO_INCREMENT NOT NULL,
            role VARCHAR(255) NOT NULL,
            PRIMARY KEY(id)
          ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
      
        $this->addSql("CREATE TABLE user (
            id INT AUTO_INCREMENT NOT NULL,
            profile_id INT DEFAULT NULL,
            email VARCHAR(180) NOT NULL,
            password VARCHAR(255) NOT NULL,
            name VARCHAR(255) NOT NULL,
            phone VARCHAR(255) NOT NULL,
            created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)',
            updated_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)',
            deleted_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
            INDEX IDX_8D93D649CCFA12B8 (profile_id),
            UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email),
            PRIMARY KEY(id)
          ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB");

        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649CCFA12B8 FOREIGN KEY (profile_id) REFERENCES profile (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649CCFA12B8');
        $this->addSql('DROP TABLE profile');
        $this->addSql('DROP TABLE user');
    }
}
