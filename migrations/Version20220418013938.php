<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220418013938 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE attachment (id INT AUTO_INCREMENT NOT NULL, house_id INT NOT NULL, url VARCHAR(255) NOT NULL, INDEX IDX_795FD9BB6BB74515 (house_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, guest_id INT NOT NULL, house_id INT NOT NULL, reservation_id INT NOT NULL, message LONGTEXT DEFAULT NULL, note DOUBLE PRECISION NOT NULL, INDEX IDX_9474526C9A4AA658 (guest_id), INDEX IDX_9474526C6BB74515 (house_id), UNIQUE INDEX UNIQ_9474526CB83297E7 (reservation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, house_id INT DEFAULT NULL, start_at DATE NOT NULL, end_at DATE NOT NULL, INDEX IDX_3BAE0AA76BB74515 (house_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE favorite (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, house_id INT NOT NULL, INDEX IDX_68C58ED9A76ED395 (user_id), INDEX IDX_68C58ED96BB74515 (house_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE house (id INT AUTO_INCREMENT NOT NULL, host_id INT NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, full_address VARCHAR(255) NOT NULL, street_number INT NOT NULL, street_sub_number VARCHAR(255) DEFAULT NULL, street_label VARCHAR(255) NOT NULL, city_name VARCHAR(255) NOT NULL, postal_code VARCHAR(255) NOT NULL, nbr_accepted INT NOT NULL, nbr_beds INT NOT NULL, nbr_rooms INT NOT NULL, nbr_showeroom INT NOT NULL, description LONGTEXT DEFAULT NULL, price DOUBLE PRECISION NOT NULL, equiments JSON NOT NULL, country VARCHAR(255) NOT NULL, lat VARCHAR(255) NOT NULL, lng VARCHAR(255) NOT NULL, nbr_wc INT NOT NULL, wc_type VARCHAR(255) NOT NULL, shower_room_type VARCHAR(255) NOT NULL, confort JSON DEFAULT NULL, outside JSON DEFAULT NULL, note DOUBLE PRECISION DEFAULT NULL, house_area DOUBLE PRECISION NOT NULL, arrival_time TIME NOT NULL, departure_time TIME NOT NULL, guarantee DOUBLE PRECISION NOT NULL, breakfast_price DOUBLE PRECISION DEFAULT NULL, breakfast_dispo TINYINT(1) NOT NULL, arrival_time_max TIME NOT NULL, strong_points JSON DEFAULT NULL, INDEX IDX_67D5399D1FB8D185 (host_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notification (id INT AUTO_INCREMENT NOT NULL, house_id INT NOT NULL, reservation_id INT DEFAULT NULL, user_id INT NOT NULL, type VARCHAR(255) NOT NULL, opened TINYINT(1) NOT NULL, INDEX IDX_BF5476CA6BB74515 (house_id), INDEX IDX_BF5476CAB83297E7 (reservation_id), INDEX IDX_BF5476CAA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE promotion (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL, percentage INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, guest_id INT NOT NULL, house_id INT NOT NULL, nbr_voyagers INT NOT NULL, start_date DATE NOT NULL, end_date DATE NOT NULL, total DOUBLE PRECISION NOT NULL, nbr_nights INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_42C849559A4AA658 (guest_id), INDEX IDX_42C849556BB74515 (house_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', avatar VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, postal_code VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, confirmed_host TINYINT(1) NOT NULL, civility VARCHAR(255) NOT NULL, status VARCHAR(255) DEFAULT NULL, fonction VARCHAR(255) DEFAULT NULL, company_name VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE attachment ADD CONSTRAINT FK_795FD9BB6BB74515 FOREIGN KEY (house_id) REFERENCES house (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C9A4AA658 FOREIGN KEY (guest_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C6BB74515 FOREIGN KEY (house_id) REFERENCES house (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CB83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA76BB74515 FOREIGN KEY (house_id) REFERENCES house (id)');
        $this->addSql('ALTER TABLE favorite ADD CONSTRAINT FK_68C58ED9A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE favorite ADD CONSTRAINT FK_68C58ED96BB74515 FOREIGN KEY (house_id) REFERENCES house (id)');
        $this->addSql('ALTER TABLE house ADD CONSTRAINT FK_67D5399D1FB8D185 FOREIGN KEY (host_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CA6BB74515 FOREIGN KEY (house_id) REFERENCES house (id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CAB83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CAA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849559A4AA658 FOREIGN KEY (guest_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849556BB74515 FOREIGN KEY (house_id) REFERENCES house (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE attachment DROP FOREIGN KEY FK_795FD9BB6BB74515');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C6BB74515');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA76BB74515');
        $this->addSql('ALTER TABLE favorite DROP FOREIGN KEY FK_68C58ED96BB74515');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CA6BB74515');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849556BB74515');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CB83297E7');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CAB83297E7');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C9A4AA658');
        $this->addSql('ALTER TABLE favorite DROP FOREIGN KEY FK_68C58ED9A76ED395');
        $this->addSql('ALTER TABLE house DROP FOREIGN KEY FK_67D5399D1FB8D185');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CAA76ED395');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849559A4AA658');
        $this->addSql('DROP TABLE attachment');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE favorite');
        $this->addSql('DROP TABLE house');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE promotion');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
