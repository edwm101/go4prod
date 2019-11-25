<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191111171134 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE option_groups (OptionGroupID INT AUTO_INCREMENT NOT NULL, OptionGroupName VARCHAR(50) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_german2_ci`, PRIMARY KEY(OptionGroupID)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('CREATE TABLE options (OptionID INT AUTO_INCREMENT NOT NULL, OptionGroupID INT DEFAULT NULL, OptionName VARCHAR(50) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_german2_ci`, PRIMARY KEY(OptionID)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('CREATE TABLE product_categories (CategoryID INT AUTO_INCREMENT NOT NULL, CategoryName VARCHAR(50) CHARACTER SET latin1 NOT NULL COLLATE `latin1_german2_ci`, PRIMARY KEY(CategoryID)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('CREATE TABLE product_options (ProductOptionID INT UNSIGNED AUTO_INCREMENT NOT NULL, ProductID INT UNSIGNED NOT NULL, OptionID INT UNSIGNED NOT NULL, OptionPriceIncrement DOUBLE PRECISION DEFAULT NULL, OptionGroupID INT NOT NULL, PRIMARY KEY(ProductOptionID)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('CREATE TABLE products (ProductID INT AUTO_INCREMENT NOT NULL, ProductSKU VARCHAR(50) CHARACTER SET latin1 NOT NULL COLLATE `latin1_german2_ci`, ProductName VARCHAR(100) CHARACTER SET latin1 NOT NULL COLLATE `latin1_german2_ci`, ProductPrice DOUBLE PRECISION NOT NULL, ProductWeight DOUBLE PRECISION NOT NULL, ProductCartDesc VARCHAR(250) CHARACTER SET latin1 NOT NULL COLLATE `latin1_german2_ci`, ProductShortDesc VARCHAR(1000) CHARACTER SET latin1 NOT NULL COLLATE `latin1_german2_ci`, ProductLongDesc TEXT CHARACTER SET latin1 NOT NULL COLLATE `latin1_german2_ci`, ProductThumb VARCHAR(100) CHARACTER SET latin1 NOT NULL COLLATE `latin1_german2_ci`, ProductImage VARCHAR(100) CHARACTER SET latin1 NOT NULL COLLATE `latin1_german2_ci`, ProductCategoryID INT DEFAULT NULL, ProductUpdateDate DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, ProductStock DOUBLE PRECISION DEFAULT NULL, ProductLive TINYINT(1) DEFAULT NULL, ProductUnlimited TINYINT(1) DEFAULT \'1\', ProductLocation VARCHAR(250) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_german2_ci`, PRIMARY KEY(ProductID)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('CREATE TABLE users (UserID INT AUTO_INCREMENT NOT NULL, UserEmail VARCHAR(500) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_german2_ci`, UserPassword VARCHAR(500) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_german2_ci`, UserFirstName VARCHAR(50) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_german2_ci`, UserLastName VARCHAR(50) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_german2_ci`, UserCity VARCHAR(90) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_german2_ci`, UserState VARCHAR(20) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_german2_ci`, UserZip VARCHAR(12) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_german2_ci`, UserEmailVerified TINYINT(1) DEFAULT NULL, UserRegistrationDate DATETIME DEFAULT CURRENT_TIMESTAMP, UserVerificationCode VARCHAR(20) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_german2_ci`, UserIP VARCHAR(50) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_german2_ci`, UserPhone VARCHAR(20) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_german2_ci`, UserFax VARCHAR(20) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_german2_ci`, UserCountry VARCHAR(20) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_german2_ci`, UserAddress VARCHAR(100) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_german2_ci`, UserAddress2 VARCHAR(50) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_german2_ci`, PRIMARY KEY(UserID)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('DROP TABLE product');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE option_groups (OptionGroupID INT AUTO_INCREMENT NOT NULL, OptionGroupName VARCHAR(50) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_german2_ci`, PRIMARY KEY(OptionGroupID)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('CREATE TABLE options (OptionID INT AUTO_INCREMENT NOT NULL, OptionGroupID INT DEFAULT NULL, OptionName VARCHAR(50) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_german2_ci`, PRIMARY KEY(OptionID)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('CREATE TABLE product_categories (CategoryID INT AUTO_INCREMENT NOT NULL, CategoryName VARCHAR(50) CHARACTER SET latin1 NOT NULL COLLATE `latin1_german2_ci`, PRIMARY KEY(CategoryID)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('CREATE TABLE product_options (ProductOptionID INT UNSIGNED AUTO_INCREMENT NOT NULL, ProductID INT UNSIGNED NOT NULL, OptionID INT UNSIGNED NOT NULL, OptionPriceIncrement DOUBLE PRECISION DEFAULT NULL, OptionGroupID INT NOT NULL, PRIMARY KEY(ProductOptionID)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('CREATE TABLE products (ProductID INT AUTO_INCREMENT NOT NULL, ProductSKU VARCHAR(50) CHARACTER SET latin1 NOT NULL COLLATE `latin1_german2_ci`, ProductName VARCHAR(100) CHARACTER SET latin1 NOT NULL COLLATE `latin1_german2_ci`, ProductPrice DOUBLE PRECISION NOT NULL, ProductWeight DOUBLE PRECISION NOT NULL, ProductCartDesc VARCHAR(250) CHARACTER SET latin1 NOT NULL COLLATE `latin1_german2_ci`, ProductShortDesc VARCHAR(1000) CHARACTER SET latin1 NOT NULL COLLATE `latin1_german2_ci`, ProductLongDesc TEXT CHARACTER SET latin1 NOT NULL COLLATE `latin1_german2_ci`, ProductThumb VARCHAR(100) CHARACTER SET latin1 NOT NULL COLLATE `latin1_german2_ci`, ProductImage VARCHAR(100) CHARACTER SET latin1 NOT NULL COLLATE `latin1_german2_ci`, ProductCategoryID INT DEFAULT NULL, ProductUpdateDate DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, ProductStock DOUBLE PRECISION DEFAULT NULL, ProductLive TINYINT(1) DEFAULT NULL, ProductUnlimited TINYINT(1) DEFAULT \'1\', ProductLocation VARCHAR(250) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_german2_ci`, PRIMARY KEY(ProductID)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('CREATE TABLE users (UserID INT AUTO_INCREMENT NOT NULL, UserEmail VARCHAR(500) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_german2_ci`, UserPassword VARCHAR(500) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_german2_ci`, UserFirstName VARCHAR(50) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_german2_ci`, UserLastName VARCHAR(50) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_german2_ci`, UserCity VARCHAR(90) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_german2_ci`, UserState VARCHAR(20) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_german2_ci`, UserZip VARCHAR(12) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_german2_ci`, UserEmailVerified TINYINT(1) DEFAULT NULL, UserRegistrationDate DATETIME DEFAULT CURRENT_TIMESTAMP, UserVerificationCode VARCHAR(20) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_german2_ci`, UserIP VARCHAR(50) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_german2_ci`, UserPhone VARCHAR(20) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_german2_ci`, UserFax VARCHAR(20) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_german2_ci`, UserCountry VARCHAR(20) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_german2_ci`, UserAddress VARCHAR(100) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_german2_ci`, UserAddress2 VARCHAR(50) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_german2_ci`, PRIMARY KEY(UserID)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('DROP TABLE product');
    }
}
