<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250602215010 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Insert a test user';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("INSERT INTO `user` (`id`, `profile_id`, `email`, `password`, `name`, `phone`) VALUES
            (1, 1, 'admin@example.com', '\$2y\$13\$tA3ZzOWYdQKTvHQ0wFDpCusFKPBYPLxjk96nISGewiCVjo0NCDze6', 'Test Admin User', '111111111')");
    }

    public function down(Schema $schema): void
    {
        $this->addSql("DELETE FROM `user`");
    }
}
