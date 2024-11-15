<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241007232403 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE company DROP FOREIGN KEY FK_4FBF094F9D86650F');
        $this->addSql('DROP INDEX UNIQ_4FBF094F9D86650F ON company');
        $this->addSql('ALTER TABLE company CHANGE user_id_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4FBF094FA76ED395 ON company (user_id)');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398881ECFA7');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F52993989D86650F');
        $this->addSql('DROP INDEX IDX_F5299398881ECFA7 ON `order`');
        $this->addSql('DROP INDEX IDX_F52993989D86650F ON `order`');
        $this->addSql('ALTER TABLE `order` ADD user_id INT NOT NULL, ADD status_id INT NOT NULL, DROP user_id_id, DROP status_id_id');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F52993986BF700BD FOREIGN KEY (status_id) REFERENCES status (id)');
        $this->addSql('CREATE INDEX IDX_F5299398A76ED395 ON `order` (user_id)');
        $this->addSql('CREATE INDEX IDX_F52993986BF700BD ON `order` (status_id)');
        $this->addSql('ALTER TABLE order_item DROP FOREIGN KEY FK_52EA1F09DE18E50B');
        $this->addSql('ALTER TABLE order_item DROP FOREIGN KEY FK_52EA1F09FCDAEAAA');
        $this->addSql('DROP INDEX IDX_52EA1F09DE18E50B ON order_item');
        $this->addSql('DROP INDEX IDX_52EA1F09FCDAEAAA ON order_item');
        $this->addSql('ALTER TABLE order_item ADD order_number_id INT NOT NULL, ADD product_id INT NOT NULL, DROP order_id_id, DROP product_id_id');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F098C26A5E8 FOREIGN KEY (order_number_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F094584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_52EA1F098C26A5E8 ON order_item (order_number_id)');
        $this->addSql('CREATE INDEX IDX_52EA1F094584665A ON order_item (product_id)');
        $this->addSql('ALTER TABLE product CHANGE category_id_id category_id INT NOT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_D34A04AD12469DE2 ON product (category_id)');
        $this->addSql('ALTER TABLE user_profile ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_profile ADD CONSTRAINT FK_D95AB405A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D95AB405A76ED395 ON user_profile (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_item DROP FOREIGN KEY FK_52EA1F098C26A5E8');
        $this->addSql('ALTER TABLE order_item DROP FOREIGN KEY FK_52EA1F094584665A');
        $this->addSql('DROP INDEX IDX_52EA1F098C26A5E8 ON order_item');
        $this->addSql('DROP INDEX IDX_52EA1F094584665A ON order_item');
        $this->addSql('ALTER TABLE order_item ADD order_id_id INT NOT NULL, ADD product_id_id INT NOT NULL, DROP order_number_id, DROP product_id');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F09DE18E50B FOREIGN KEY (product_id_id) REFERENCES product (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F09FCDAEAAA FOREIGN KEY (order_id_id) REFERENCES `order` (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_52EA1F09DE18E50B ON order_item (product_id_id)');
        $this->addSql('CREATE INDEX IDX_52EA1F09FCDAEAAA ON order_item (order_id_id)');
        $this->addSql('ALTER TABLE user_profile DROP FOREIGN KEY FK_D95AB405A76ED395');
        $this->addSql('DROP INDEX UNIQ_D95AB405A76ED395 ON user_profile');
        $this->addSql('ALTER TABLE user_profile DROP user_id');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398A76ED395');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F52993986BF700BD');
        $this->addSql('DROP INDEX IDX_F5299398A76ED395 ON `order`');
        $this->addSql('DROP INDEX IDX_F52993986BF700BD ON `order`');
        $this->addSql('ALTER TABLE `order` ADD user_id_id INT NOT NULL, ADD status_id_id INT NOT NULL, DROP user_id, DROP status_id');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398881ECFA7 FOREIGN KEY (status_id_id) REFERENCES status (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F52993989D86650F FOREIGN KEY (user_id_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_F5299398881ECFA7 ON `order` (status_id_id)');
        $this->addSql('CREATE INDEX IDX_F52993989D86650F ON `order` (user_id_id)');
        $this->addSql('ALTER TABLE company DROP FOREIGN KEY FK_4FBF094FA76ED395');
        $this->addSql('DROP INDEX UNIQ_4FBF094FA76ED395 ON company');
        $this->addSql('ALTER TABLE company CHANGE user_id user_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094F9D86650F FOREIGN KEY (user_id_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4FBF094F9D86650F ON company (user_id_id)');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD12469DE2');
        $this->addSql('DROP INDEX IDX_D34A04AD12469DE2 ON product');
        $this->addSql('ALTER TABLE product CHANGE category_id category_id_id INT NOT NULL');
    }
}
