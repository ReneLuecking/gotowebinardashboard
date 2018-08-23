<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180817122014 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE attendee (sessionkey INT NOT NULL, registrantkey INT NOT NULL, attendance INT NOT NULL, jointime DATETIME NOT NULL, leavetime DATETIME NOT NULL, INDEX IDX_1150D5678DEE8246 (sessionkey), INDEX IDX_1150D567DFD5C98F (registrantkey), PRIMARY KEY(sessionkey, registrantkey)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE organizer (organizerkey INT NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, consumerkey VARCHAR(32) NOT NULL, consumersecret VARCHAR(16) NOT NULL, accesstoken VARCHAR(32) NOT NULL, expiresin INT NOT NULL, refreshtoken VARCHAR(64) NOT NULL, accountkey INT NOT NULL, refreshexpiresin INT NOT NULL, PRIMARY KEY(organizerkey)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE registrant (registrantkey INT NOT NULL, webinarkey INT NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, registrationdate DATETIME NOT NULL, timezone VARCHAR(127) NOT NULL, INDEX IDX_66135AC04F40B6F7 (webinarkey), PRIMARY KEY(registrantkey)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE session (sessionkey INT NOT NULL, webinarkey INT NOT NULL, starttime DATETIME NOT NULL, endtime DATETIME NOT NULL, timezone VARCHAR(127) NOT NULL, INDEX IDX_D044D5D44F40B6F7 (webinarkey), PRIMARY KEY(sessionkey)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE webinar (webinarkey INT NOT NULL, organizerkey INT NOT NULL, webinarid INT NOT NULL, subject VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_C9E29F4A6BBD2343 (organizerkey), PRIMARY KEY(webinarkey)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE attendee ADD CONSTRAINT FK_1150D5678DEE8246 FOREIGN KEY (sessionkey) REFERENCES session (sessionkey)');
        $this->addSql('ALTER TABLE attendee ADD CONSTRAINT FK_1150D567DFD5C98F FOREIGN KEY (registrantkey) REFERENCES registrant (registrantkey)');
        $this->addSql('ALTER TABLE registrant ADD CONSTRAINT FK_66135AC04F40B6F7 FOREIGN KEY (webinarkey) REFERENCES webinar (webinarkey)');
        $this->addSql('ALTER TABLE session ADD CONSTRAINT FK_D044D5D44F40B6F7 FOREIGN KEY (webinarkey) REFERENCES webinar (webinarkey)');
        $this->addSql('ALTER TABLE webinar ADD CONSTRAINT FK_C9E29F4A6BBD2343 FOREIGN KEY (organizerkey) REFERENCES organizer (organizerkey)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE webinar DROP FOREIGN KEY FK_C9E29F4A6BBD2343');
        $this->addSql('ALTER TABLE attendee DROP FOREIGN KEY FK_1150D567DFD5C98F');
        $this->addSql('ALTER TABLE attendee DROP FOREIGN KEY FK_1150D5678DEE8246');
        $this->addSql('ALTER TABLE registrant DROP FOREIGN KEY FK_66135AC04F40B6F7');
        $this->addSql('ALTER TABLE session DROP FOREIGN KEY FK_D044D5D44F40B6F7');
        $this->addSql('DROP TABLE attendee');
        $this->addSql('DROP TABLE organizer');
        $this->addSql('DROP TABLE registrant');
        $this->addSql('DROP TABLE session');
        $this->addSql('DROP TABLE webinar');
    }
}
