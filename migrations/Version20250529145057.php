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
      
        $this->addSql('CREATE TABLE user (
            id INT AUTO_INCREMENT NOT NULL,
            profile_id INT DEFAULT NULL,
            email VARCHAR(180) NOT NULL,
            password VARCHAR(255) NOT NULL,
            name VARCHAR(255) NOT NULL,
            phone VARCHAR(255) NOT NULL,
            INDEX IDX_8D93D649CCFA12B8 (profile_id),
            UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email),
            PRIMARY KEY(id)
          ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649CCFA12B8 FOREIGN KEY (profile_id) REFERENCES profile (id)');

        //Delete after messenger is uninstalled
        $this->addSql("CREATE TABLE messenger_messages (
            id BIGINT AUTO_INCREMENT NOT NULL,
            body LONGTEXT NOT NULL,
            headers LONGTEXT NOT NULL,
            queue_name VARCHAR(190) NOT NULL,
            created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)',
            available_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)',
            delivered_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
            INDEX IDX_75EA56E0FB7336F0 (queue_name),
            INDEX IDX_75EA56E0E3BD61CE (available_at),
            INDEX IDX_75EA56E016BA31DB (delivered_at),
            PRIMARY KEY(id)
        )
        DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649CCFA12B8');
        $this->addSql('DROP TABLE profile');
        $this->addSql('DROP TABLE user');

        //Delete after messenger is uninstalled
        $this->addSql('DROP TABLE messenger_messages');
    }
}
