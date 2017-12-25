<?php

namespace DoctrineORMModule\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171212120400 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        //Create cities table
        $table = $schema->createTable('cities');
        $table->addColumn('id', 'integer', ['autoincrement'=>true]);
        $table->addColumn('name', 'text', ['notnull'=>true]);
        $table->addColumn('name_lat', 'text', ['notnull'=>true]);
        $table->setPrimaryKey(['id']);
        $table->addOption('engine' , 'InnoDB');

        //Create table contact_letters
        $table = $schema->createTable('contact_letters');
        $table->addColumn('id', 'integer', ['autoincrement'=>true]);
        $table->addColumn('name', 'text', ['notnull'=>true]);
        $table->addColumn('phone', 'text', ['notnull'=>true]);
        $table->addColumn('message', 'text');
        $table->setPrimaryKey(['id']);
        $table->addOption('engine' , 'MyISAM');

        //Create table curencies
        $table = $schema->createTable('curencies');
        $table->addColumn('id', 'integer', ['autoincrement'=>true]);
        $table->addColumn('short', 'text', ['notnull'=>true]);
        $table->addColumn('sign', 'text', ['notnull'=>true]);
        $table->setPrimaryKey(['id']);
        $table->addOption('engine' , 'InnoDB');
    }

    public function postUp(Schema $schema)
    {
        //Insert default content to table cities
        $sql = "INSERT INTO cities (`name`,`name_lat`) VALUES ('Киев','Kiev')";
        $this->connection->executeQuery($sql);

        //Insert default content to table currencies
        $sql = "INSERT INTO currencies
          (`short`,`sign`)
          VALUES
          ('USD','$')
          ('UAH','грн.')
          ('EUR','€')";
        $this->connection->executeQuery($sql);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $schema->dropTable('cities');
    }
}
