<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180820125401 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('SET FOREIGN_KEY_CHECKS=0;');
        $this->addSql('ALTER TABLE attendee CHANGE registrantkey registrantkey BIGINT UNSIGNED NOT NULL, CHANGE sessionkey sessionkey BIGINT UNSIGNED NOT NULL');
        $this->addSql('ALTER TABLE organizer CHANGE organizerkey organizerkey BIGINT UNSIGNED NOT NULL, CHANGE accountkey accountkey BIGINT UNSIGNED NOT NULL, CHANGE expiresin expiresin INT UNSIGNED NOT NULL, CHANGE refreshexpiresin refreshexpiresin INT UNSIGNED NOT NULL');
        $this->addSql('ALTER TABLE registrant CHANGE registrantkey registrantkey BIGINT UNSIGNED NOT NULL, CHANGE webinarkey webinarkey BIGINT UNSIGNED NOT NULL');
        $this->addSql('ALTER TABLE session CHANGE sessionkey sessionkey BIGINT UNSIGNED NOT NULL, CHANGE webinarkey webinarkey BIGINT UNSIGNED NOT NULL');
        $this->addSql('ALTER TABLE webinar CHANGE webinarkey webinarkey BIGINT UNSIGNED NOT NULL, CHANGE organizerkey organizerkey BIGINT UNSIGNED NOT NULL, CHANGE webinarid webinarid BIGINT UNSIGNED NOT NULL');
        $this->addSql('SET FOREIGN_KEY_CHECKS=1;');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('SET FOREIGN_KEY_CHECKS=0;');
        $this->addSql('ALTER TABLE attendee CHANGE sessionkey sessionkey INT NOT NULL, CHANGE registrantkey registrantkey INT NOT NULL');
        $this->addSql('ALTER TABLE organizer CHANGE organizerkey organizerkey INT NOT NULL, CHANGE expiresin expiresin INT NOT NULL, CHANGE accountkey accountkey INT NOT NULL, CHANGE refreshexpiresin refreshexpiresin INT NOT NULL');
        $this->addSql('ALTER TABLE registrant CHANGE registrantkey registrantkey INT NOT NULL, CHANGE webinarkey webinarkey INT NOT NULL');
        $this->addSql('ALTER TABLE session CHANGE sessionkey sessionkey INT NOT NULL, CHANGE webinarkey webinarkey INT NOT NULL');
        $this->addSql('ALTER TABLE webinar CHANGE webinarkey webinarkey INT NOT NULL, CHANGE organizerkey organizerkey INT NOT NULL, CHANGE webinarid webinarid INT NOT NULL');
        $this->addSql('SET FOREIGN_KEY_CHECKS=1;');
    }
}
