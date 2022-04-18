<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220418222750 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE category_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE product_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE attachment_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE comment_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE favorite_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE house_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE notification_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE promotion_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE reservation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE attachment (id INT NOT NULL, house_id INT NOT NULL, url VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_795FD9BB6BB74515 ON attachment (house_id)');
        $this->addSql('CREATE TABLE comment (id INT NOT NULL, guest_id INT NOT NULL, house_id INT NOT NULL, reservation_id INT NOT NULL, message TEXT DEFAULT NULL, note DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9474526C9A4AA658 ON comment (guest_id)');
        $this->addSql('CREATE INDEX IDX_9474526C6BB74515 ON comment (house_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9474526CB83297E7 ON comment (reservation_id)');
        $this->addSql('CREATE TABLE event (id INT NOT NULL, house_id INT DEFAULT NULL, start_at DATE NOT NULL, end_at DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3BAE0AA76BB74515 ON event (house_id)');
        $this->addSql('CREATE TABLE favorite (id INT NOT NULL, user_id INT NOT NULL, house_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_68C58ED9A76ED395 ON favorite (user_id)');
        $this->addSql('CREATE INDEX IDX_68C58ED96BB74515 ON favorite (house_id)');
        $this->addSql('CREATE TABLE house (id INT NOT NULL, host_id INT NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, full_address VARCHAR(255) NOT NULL, street_number INT NOT NULL, street_sub_number VARCHAR(255) DEFAULT NULL, street_label VARCHAR(255) NOT NULL, city_name VARCHAR(255) NOT NULL, postal_code VARCHAR(255) NOT NULL, nbr_accepted INT NOT NULL, nbr_beds INT NOT NULL, nbr_rooms INT NOT NULL, nbr_showeroom INT NOT NULL, description TEXT DEFAULT NULL, price DOUBLE PRECISION NOT NULL, equiments JSON NOT NULL, country VARCHAR(255) NOT NULL, lat VARCHAR(255) NOT NULL, lng VARCHAR(255) NOT NULL, nbr_wc INT NOT NULL, wc_type VARCHAR(255) NOT NULL, shower_room_type VARCHAR(255) NOT NULL, confort JSON DEFAULT NULL, outside JSON DEFAULT NULL, note DOUBLE PRECISION DEFAULT NULL, house_area DOUBLE PRECISION NOT NULL, arrival_time TIME(0) WITHOUT TIME ZONE NOT NULL, departure_time TIME(0) WITHOUT TIME ZONE NOT NULL, guarantee DOUBLE PRECISION NOT NULL, breakfast_price DOUBLE PRECISION DEFAULT NULL, breakfast_dispo BOOLEAN NOT NULL, arrival_time_max TIME(0) WITHOUT TIME ZONE NOT NULL, strong_points JSON DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_67D5399D1FB8D185 ON house (host_id)');
        $this->addSql('CREATE TABLE notification (id INT NOT NULL, house_id INT NOT NULL, reservation_id INT DEFAULT NULL, user_id INT NOT NULL, type VARCHAR(255) NOT NULL, opened BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_BF5476CA6BB74515 ON notification (house_id)');
        $this->addSql('CREATE INDEX IDX_BF5476CAB83297E7 ON notification (reservation_id)');
        $this->addSql('CREATE INDEX IDX_BF5476CAA76ED395 ON notification (user_id)');
        $this->addSql('CREATE TABLE promotion (id INT NOT NULL, code VARCHAR(255) NOT NULL, percentage INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE reservation (id INT NOT NULL, guest_id INT NOT NULL, house_id INT NOT NULL, nbr_voyagers INT NOT NULL, start_date DATE NOT NULL, end_date DATE NOT NULL, total DOUBLE PRECISION NOT NULL, nbr_nights INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_42C849559A4AA658 ON reservation (guest_id)');
        $this->addSql('CREATE INDEX IDX_42C849556BB74515 ON reservation (house_id)');
        $this->addSql('COMMENT ON COLUMN reservation.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN reservation.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, avatar VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, postal_code VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, confirmed_host BOOLEAN NOT NULL, civility VARCHAR(255) NOT NULL, status VARCHAR(255) DEFAULT NULL, fonction VARCHAR(255) DEFAULT NULL, company_name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('COMMENT ON COLUMN "user".created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE attachment ADD CONSTRAINT FK_795FD9BB6BB74515 FOREIGN KEY (house_id) REFERENCES house (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C9A4AA658 FOREIGN KEY (guest_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C6BB74515 FOREIGN KEY (house_id) REFERENCES house (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CB83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA76BB74515 FOREIGN KEY (house_id) REFERENCES house (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE favorite ADD CONSTRAINT FK_68C58ED9A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE favorite ADD CONSTRAINT FK_68C58ED96BB74515 FOREIGN KEY (house_id) REFERENCES house (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE house ADD CONSTRAINT FK_67D5399D1FB8D185 FOREIGN KEY (host_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CA6BB74515 FOREIGN KEY (house_id) REFERENCES house (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CAB83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CAA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849559A4AA658 FOREIGN KEY (guest_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849556BB74515 FOREIGN KEY (house_id) REFERENCES house (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE attachment DROP CONSTRAINT FK_795FD9BB6BB74515');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT FK_9474526C6BB74515');
        $this->addSql('ALTER TABLE event DROP CONSTRAINT FK_3BAE0AA76BB74515');
        $this->addSql('ALTER TABLE favorite DROP CONSTRAINT FK_68C58ED96BB74515');
        $this->addSql('ALTER TABLE notification DROP CONSTRAINT FK_BF5476CA6BB74515');
        $this->addSql('ALTER TABLE reservation DROP CONSTRAINT FK_42C849556BB74515');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT FK_9474526CB83297E7');
        $this->addSql('ALTER TABLE notification DROP CONSTRAINT FK_BF5476CAB83297E7');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT FK_9474526C9A4AA658');
        $this->addSql('ALTER TABLE favorite DROP CONSTRAINT FK_68C58ED9A76ED395');
        $this->addSql('ALTER TABLE house DROP CONSTRAINT FK_67D5399D1FB8D185');
        $this->addSql('ALTER TABLE notification DROP CONSTRAINT FK_BF5476CAA76ED395');
        $this->addSql('ALTER TABLE reservation DROP CONSTRAINT FK_42C849559A4AA658');
        $this->addSql('DROP SEQUENCE attachment_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE comment_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE favorite_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE house_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE notification_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE promotion_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE reservation_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE category_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE product_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('DROP TABLE attachment');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE favorite');
        $this->addSql('DROP TABLE house');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE promotion');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
