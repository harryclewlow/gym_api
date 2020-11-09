<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201108224747 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE member (id INT AUTO_INCREMENT NOT NULL, given_names VARCHAR(255) NOT NULL, surname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, mobile_number VARCHAR(32) NOT NULL, postcode VARCHAR(32) NOT NULL, date_of_birth DATETIME NOT NULL, terms_accepted TINYINT(1) NOT NULL, newsletter_accepted TINYINT(1) DEFAULT \'0\' NOT NULL, date_created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, UNIQUE INDEX email_unique (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE member_membership (id INT AUTO_INCREMENT NOT NULL, member_id INT DEFAULT NULL, membership_id INT DEFAULT NULL, hash VARCHAR(255) NOT NULL, expiry_date DATETIME DEFAULT NULL, start_date DATETIME DEFAULT NULL, status INT NOT NULL, date_created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX IDX_6DB1CC6C7597D3FE (member_id), INDEX IDX_6DB1CC6C1FB354CD (membership_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE membership (id INT AUTO_INCREMENT NOT NULL, membership_type_id INT DEFAULT NULL, gym_id INT DEFAULT NULL, hash VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, date_created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX IDX_86FFD2854CE11AC2 (membership_type_id), INDEX IDX_86FFD285BD2F03 (gym_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE membership_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, length INT NOT NULL, date_created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE member_membership ADD CONSTRAINT FK_6DB1CC6C7597D3FE FOREIGN KEY (member_id) REFERENCES member (id)');
        $this->addSql('ALTER TABLE member_membership ADD CONSTRAINT FK_6DB1CC6C1FB354CD FOREIGN KEY (membership_id) REFERENCES membership (id)');
        $this->addSql('ALTER TABLE membership ADD CONSTRAINT FK_86FFD2854CE11AC2 FOREIGN KEY (membership_type_id) REFERENCES membership_type (id)');
        $this->addSql('ALTER TABLE membership ADD CONSTRAINT FK_86FFD285BD2F03 FOREIGN KEY (gym_id) REFERENCES gym (id)');

        $this->addSql('INSERT INTO `gym` (`id`, `name`, `date_created`) VALUES (\'1\',\'Canary Wharf\',\'2020-11-01 11:00:00\');');
        $this->addSql('INSERT INTO `gym` (`id`, `name`, `date_created`) VALUES (\'2\',\'Oxford Circus\',\'2020-11-01 11:00:00\');');
        $this->addSql('INSERT INTO `gym` (`id`, `name`, `date_created`) VALUES (\'3\',\'Camden Town\',\'2020-11-01 11:00:00\');');
        $this->addSql('INSERT INTO `gym` (`id`, `name`, `date_created`) VALUES (\'4\',\'Wimbledon\',\'2020-11-01 11:00:00\');');
        $this->addSql('INSERT INTO `gym` (`id`, `name`, `date_created`) VALUES (\'5\',\'Brent Cross\',\'2020-11-01 11:00:00\');');
        $this->addSql('INSERT INTO `membership_type` (`id`, `name`, `length`, `date_created`) VALUES (\'1\',\'Day Pass\',\'1\',\'2020-11-01 11:00:00\');');
        $this->addSql('INSERT INTO `membership_type` (`id`, `name`, `length`, `date_created`) VALUES (\'2\',\'1 Month Pass\',\'31\',\'2020-11-01 11:00:00\');');
        $this->addSql('INSERT INTO `membership_type` (`id`, `name`, `length`, `date_created`) VALUES (\'3\',\'3 Month Pass\',\'90\',\'2020-11-01 11:00:00\');');
        $this->addSql('INSERT INTO `membership_type` (`id`, `name`, `length`, `date_created`) VALUES (\'4\',\'1 Year Pass\',\'365\',\'2020-11-01 11:00:00\');');
        $this->addSql('INSERT INTO `membership` (`id`, `hash`, `gym_id`, `membership_type_id`, `price`, `date_created`) VALUES (\'1\',\'aaaaaa\',\'1\',\'1\',\'12\',\'2020-11-01 11:00:00\');');
        $this->addSql('INSERT INTO `membership` (`id`, `hash`, `gym_id`, `membership_type_id`, `price`, `date_created`) VALUES (\'2\',\'bbbbbb\',\'1\',\'2\',\'27\',\'2020-11-01 11:00:00\');');
        $this->addSql('INSERT INTO `membership` (`id`, `hash`, `gym_id`, `membership_type_id`, `price`, `date_created`) VALUES (\'3\',\'cccccc\',\'1\',\'3\',\'75\',\'2020-11-01 11:00:00\');');
        $this->addSql('INSERT INTO `membership` (`id`, `hash`, `gym_id`, `membership_type_id`, `price`, `date_created`) VALUES (\'4\',\'dddddd\',\'1\',\'4\',\'240\',\'2020-11-01 11:00:00\');');
        $this->addSql('INSERT INTO `membership` (`id`, `hash`, `gym_id`, `membership_type_id`, `price`, `date_created`) VALUES (\'5\',\'eeeeee\',\'2\',\'2\',\'20\',\'2020-11-01 11:00:00\');');
        $this->addSql('INSERT INTO `membership` (`id`, `hash`, `gym_id`, `membership_type_id`, `price`, `date_created`) VALUES (\'6\',\'ffffff\',\'2\',\'3\',\'55\',\'2020-11-01 11:00:00\');');
        $this->addSql('INSERT INTO `membership` (`id`, `hash`, `gym_id`, `membership_type_id`, `price`, `date_created`) VALUES (\'7\',\'gggggg\',\'2\',\'4\',\'180\',\'2020-11-01 11:00:00\');');
        $this->addSql('INSERT INTO `membership` (`id`, `hash`, `gym_id`, `membership_type_id`, `price`, `date_created`) VALUES (\'8\',\'hhhhhh\',\'3\',\'1\',\'10\',\'2020-11-01 11:00:00\');');
        $this->addSql('INSERT INTO `membership` (`id`, `hash`, `gym_id`, `membership_type_id`, `price`, `date_created`) VALUES (\'9\',\'iiiiii\',\'3\',\'2\',\'25\',\'2020-11-01 11:00:00\');');
        $this->addSql('INSERT INTO `membership` (`id`, `hash`, `gym_id`, `membership_type_id`, `price`, `date_created`) VALUES (\'10\',\'jjjjjj\',\'4\',\'1\',\'10\',\'2020-11-01 11:00:00\');');
        $this->addSql('INSERT INTO `membership` (`id`, `hash`, `gym_id`, `membership_type_id`, `price`, `date_created`) VALUES (\'11\',\'kkkkkk\',\'4\',\'2\',\'25\',\'2020-11-01 11:00:00\');');
        $this->addSql('INSERT INTO `membership` (`id`, `hash`, `gym_id`, `membership_type_id`, `price`, `date_created`) VALUES (\'12\',\'llllll\',\'4\',\'3\',\'60\',\'2020-11-01 11:00:00\');');
        $this->addSql('INSERT INTO `membership` (`id`, `hash`, `gym_id`, `membership_type_id`, `price`, `date_created`) VALUES (\'13\',\'mmmmmm\',\'4\',\'4\',\'200\',\'2020-11-01 11:00:00\');');
        $this->addSql('INSERT INTO `membership` (`id`, `hash`, `gym_id`, `membership_type_id`, `price`, `date_created`) VALUES (\'14\',\'nnnnnn\',\'5\',\'1\',\'12\',\'2020-11-01 11:00:00\');');
        $this->addSql('INSERT INTO `membership` (`id`, `hash`, `gym_id`, `membership_type_id`, `price`, `date_created`) VALUES (\'15\',\'oooooo\',\'5\',\'2\',\'27\',\'2020-11-01 11:00:00\');');
        $this->addSql('INSERT INTO `membership` (`id`, `hash`, `gym_id`, `membership_type_id`, `price`, `date_created`) VALUES (\'16\',\'pppppp\',\'5\',\'4\',\'240\',\'2020-11-01 11:00:00\');');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE member_membership DROP FOREIGN KEY FK_6DB1CC6C7597D3FE');
        $this->addSql('ALTER TABLE member_membership DROP FOREIGN KEY FK_6DB1CC6C1FB354CD');
        $this->addSql('ALTER TABLE membership DROP FOREIGN KEY FK_86FFD2854CE11AC2');
        $this->addSql('DROP TABLE member');
        $this->addSql('DROP TABLE member_membership');
        $this->addSql('DROP TABLE membership');
        $this->addSql('DROP TABLE membership_type');
    }
}
