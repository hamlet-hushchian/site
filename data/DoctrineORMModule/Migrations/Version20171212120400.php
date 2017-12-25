<?php

namespace DoctrineORMModule\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

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

        //Create table currencies
        $table = $schema->createTable('currencies');
        $table->addColumn('id', 'integer', ['autoincrement'=>true]);
        $table->addColumn('short', 'text', ['notnull'=>true]);
        $table->addColumn('sign', 'text', ['notnull'=>true]);
        $table->setPrimaryKey(['id']);
        $table->addOption('engine' , 'InnoDB');

        //Create table deal_categories
        $table = $schema->createTable('deal_categories');
        $table->addColumn('id', 'integer', ['autoincrement'=>true]);
        $table->addColumn('name', 'text', ['notnull'=>true]);
        $table->addColumn('name_lat', 'text', ['notnull'=>true]);
        $table->setPrimaryKey(['id']);
        $table->addOption('engine' , 'InnoDB');

        //Create table deal_types
        $table = $schema->createTable('deal_types');
        $table->addColumn('id', 'integer', ['autoincrement'=>true]);
        $table->addColumn('deal_categories_id', 'integer', ['notnull'=>true]);
        $table->addColumn('name', 'text', ['notnull'=>true]);
        $table->addColumn('name_lat', 'text', ['notnull'=>true]);
        $table->setPrimaryKey(['id']);
        $table->addOption('engine' , 'InnoDB');

        //Create table districts
        $table = $schema->createTable('districts');
        $table->addColumn('id', 'integer', ['autoincrement'=>true]);
        $table->addColumn('city_id', 'integer', ['notnull'=>true]);
        $table->addColumn('name', 'text', ['notnull'=>true]);
        $table->addColumn('name_lat', 'text', ['notnull'=>true]);
        $table->setPrimaryKey(['id']);
        $table->addOption('engine' , 'InnoDB');

        //Create table listings
        $table = $schema->createTable('listings');
        $table->addColumn('id', 'integer', ['autoincrement'=>true]);
        $table->addColumn('date_add', 'datetime', ['notnull'=>true]);
        $table->addColumn('date_edit', 'datetime', ['notnull'=>true]);
        $table->addColumn('date_call', 'datetime', ['notnull'=>true]);
        $table->addColumn('user_id', 'integer', ['notnull'=>true]);
        $table->addColumn('deal_type_id', 'integer', ['notnull'=>true]);
        $table->addColumn('property_type_id', 'integer', ['notnull'=>true]);
        $table->addColumn('microdistrict_id', 'integer', ['notnull'=>true]);
        $table->addColumn('subway_station_id', 'integer', ['notnull'=>true]);
        $table->addColumn('street', 'text', ['notnull'=>true]);
        $table->addColumn('house_number', 'text', ['notnull'=>true]);
        $table->addColumn('price', 'integer', ['notnull'=>true]);
        $table->addColumn('currency_id', 'integer', ['notnull'=>true]);
        $table->addColumn('description', 'text', ['notnull'=>false]);
        $table->setPrimaryKey(['id']);
        $table->addOption('engine' , 'InnoDB');

        //Create table listing_images
        $table = $schema->createTable('listing_images');
        $table->addColumn('id', 'integer', ['autoincrement'=>true]);
        $table->addColumn('uniq_id', 'varchar', ['notnull'=>true]);
        $table->addColumn('listing_id', 'integer', ['notnull'=>true]);
        $table->addColumn('source_link', 'text', ['notnull'=>true]);
        $table->addColumn('thumb_link', 'text', ['notnull'=>true]);
        $table->addColumn('name', 'text', ['notnull'=>true]);
        $table->addColumn('ext', 'text', ['notnull'=>true]);
        $table->addColumn('crop', 'text', ['notnull'=>true]);
        $table->addColumn('odr', 'integer', ['notnull'=>true]);
        $table->setPrimaryKey(['id']);
        $table->addOption('engine' , 'InnoDB');

        //Create table listing_phones
        $table = $schema->createTable('listing_phones');
        $table->addColumn('id', 'integer', ['autoincrement'=>true]);
        $table->addColumn('listing_id', 'integer', ['notnull'=>true]);
        $table->addColumn('number', 'text', ['notnull'=>true]);
        $table->setPrimaryKey(['id']);
        $table->addOption('engine' , 'InnoDB');

        //Create table microdistricts
        $table = $schema->createTable('microdistricts');
        $table->addColumn('id', 'integer', ['autoincrement'=>true]);
        $table->addColumn('district_id', 'integer', ['notnull'=>true]);
        $table->addColumn('name', 'text', ['notnull'=>true]);
        $table->addColumn('name_lat', 'text', ['notnull'=>true]);
        $table->setPrimaryKey(['id']);
        $table->addOption('engine' , 'InnoDB');

        //Create table property_categories
        $table = $schema->createTable('property_categories');
        $table->addColumn('id', 'integer', ['autoincrement'=>true]);
        $table->addColumn('name', 'text', ['notnull'=>true]);
        $table->addColumn('name_lat', 'text', ['notnull'=>true]);
        $table->setPrimaryKey(['id']);
        $table->addOption('engine' , 'InnoDB');

        //Create table property_params
        $table = $schema->createTable('property_params');
        $table->addColumn('id', 'integer', ['autoincrement'=>true]);
        $table->addColumn('param_key', 'text', ['notnull'=>true]);
        $table->addColumn('param_name', 'text', ['notnull'=>true]);
        $table->setPrimaryKey(['id']);
        $table->addOption('engine' , 'InnoDB');

        //Create table property_params_value
        $table = $schema->createTable('property_params_value');
        $table->addColumn('id', 'integer', ['autoincrement'=>true]);
        $table->addColumn('listing_id', 'integer', ['notnull'=>true]);
        $table->addColumn('param_id', 'integer', ['notnull'=>true]);
        $table->addColumn('value', 'text', ['notnull'=>true]);
        $table->setPrimaryKey(['id']);
        $table->addOption('engine' , 'InnoDB');

        //Create table property_types
        $table = $schema->createTable('property_types');
        $table->addColumn('id', 'integer', ['autoincrement'=>true]);
        $table->addColumn('category_id', 'integer', ['notnull'=>true]);
        $table->addColumn('name', 'text', ['notnull'=>true]);
        $table->addColumn('name_lat', 'text', ['notnull'=>true]);
        $table->setPrimaryKey(['id']);
        $table->addOption('engine' , 'InnoDB');

        //Create table selections
        $table = $schema->createTable('selections');
        $table->addColumn('id', 'integer', ['autoincrement'=>true]);
        $table->addColumn('listing_id', 'integer', ['notnull'=>true]);
        $table->addColumn('name', 'text', ['notnull'=>true]);
        $table->addColumn('phone', 'text', ['notnull'=>true]);
        $table->addColumn('message', 'text', ['notnull'=>false]);
        $table->setPrimaryKey(['id']);
        $table->addOption('engine' , 'InnoDB');

        //Create table subway_branches
        $table = $schema->createTable('subway_branches');
        $table->addColumn('id', 'integer', ['autoincrement'=>true]);
        $table->addColumn('color', 'text', ['notnull'=>true]);
        $table->addColumn('name', 'text', ['notnull'=>true]);
        $table->addColumn('name_lat', 'text', ['notnull'=>false]);
        $table->setPrimaryKey(['id']);
        $table->addOption('engine' , 'InnoDB');

        //Create table subway_stations
        $table = $schema->createTable('subway_stations');
        $table->addColumn('id', 'integer', ['autoincrement'=>true]);
        $table->addColumn('city_id', 'integer', ['notnull'=>true]);
        $table->addColumn('branch_id', 'integer', ['notnull'=>true]);
        $table->addColumn('name', 'text', ['notnull'=>true]);
        $table->addColumn('name_lat', 'text', ['notnull'=>false]);
        $table->setPrimaryKey(['id']);
        $table->addOption('engine' , 'InnoDB');

        //Create table users
        $table = $schema->createTable('users');
        $table->addColumn('id', 'integer', ['autoincrement'=>true]);
        $table->addColumn('login', 'text', ['notnull'=>true]);
        $table->addColumn('password', 'text', ['notnull'=>true]);
        $table->addColumn('phone', 'text', ['notnull'=>false]);
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
          ('USD','$'),
          ('UAH','грн.'),
          ('EUR','€')";
        $this->connection->executeQuery($sql);

        //Insert default content to table deal_categories
        $sql = "INSERT INTO deal_categories
          (`name`,`name_lat`)
          VALUES
          ('Продажа','prodazha'),
          ('Аренда','arenda')";
        $this->connection->executeQuery($sql);

        //Insert default content to table deal_types
        $sql = "INSERT INTO deal_types
          (`deal_categories_id`,`name`,`name_lat`)
          VALUES
          (1,'Продажа','prodazha'),
          (2,'Аренда','arenda'),
          (3,'Посуточная аренда','posutochno')";
        $this->connection->executeQuery($sql);

        //Insert default content to table districts
        $sql = "INSERT INTO districts
          (`city_id`,`name`,`name_lat`)
          VALUES
          (1,'Печерский','pechersky'),
          (1,'Деснянский','dyesnyanskyiy'),
          (1,'Дарницкий','darnickiy'),
          (1,'Голосеевский','golosyeyevskiy'),
          (1,'Днепровский','dneprovskiy'),
          (1,'Оболонский','obolonskiy'),
          (1,'Подольский','podolskiy'),
          (1,'Святошинский','svyatoshinskiy'),
          (1,'Соломенский','solomyenskiy'),
          (1,'Шевченковский','shevchenkovskiy')";
        $this->connection->executeQuery($sql);

        //Insert default content to table microdistricts
        $sql = "INSERT INTO microdistricts
          (`district_id`,`name`,`name_lat`)
          VALUES
          (1,'Печерский (центр)','pechersky_center'),
          (1,'Зверинец','Zverinets'),
          (1,'Липки','Lipki'),
          (1,'Печерск','Pechersk'),
          (1,'Чёрная гора','Chernaya_hora'),
          (1,'Верхняя Теличка','Verhnaya_Telychka'),
          (2,'Троещина','Troeschyna'),
          (2,'Лесной','Lesnoy'),
          (3,'Харьковский массив','Harkovsky_massiv'),
          (3,'Новая Дарница','Novaya_Darnitsa'),
          (3,'Осокорки','Osokorki'),
          (3,'Позняки','Poznyaki'),
          (3,'Бортничи','Bortnichy'),
          (3,'Красный хутор','Krasnyi_hutor'),
          (4,'Демеевка','Demeyevka'),
          (4,'Саперная Слободка','Sapernaya_slobodka'),
          (4,'Теремки-1','Teremki-1'),
          (4,'Теремки-2','Teremki-2'),
          (4,'Голосеево','Holoseyevo'),
          (4,'Голосеевский (центр)','Holoseyevsky_(tsentr)'),
          (4,'Корчеватое','Korchevatoe'),
          (4,'Феофания','Feofaniya'),
          (4,'Университетский городок','Universitetskiy_horodok'),
          (4,'Мышеловка','Myshelovka'),
          (4,'Багриновая гора','Bahrinovaya_hora'),
          (5,'Левобережный массив','Levoberezhny_massiv'),
          (5,'Комсомольский массив','Komsomolsky_massiv'),
          (5,'Радужный','Raduzhnyi'),
          (5,'Русановка','Rusanovka'),
          (5,'Соцгородок','Sotshorodok'),
          (5,'ДВРЗ','DVRZ'),
          (5,'Никольская слободка','Nikolskaya_slobodka'),
          (5,'Воскресенка','Voskresenka'),
          (5,'Старая Дарница','Staraya_Darnitsa'),
          (5,'Березняки','Bereznyaki'),
          (6,'Минский массив','Minsky_massiv'),
          (6,'Оболонь','Obolon'),
          (6,'Пуща-Водица','Puscha_Voditsa'),
          (6,'Приорка (Об.)','Priorka_(Ob.)'),
          (6,'Вышгородский массив','Vyshhorodsry_massiv'),
          (7,'Мостицкий','Mostitsky'),
          (7,'Подол','Podol'),
          (7,'Ветряные горы','Vetryanye_hory'),
          (7,'Куриневка','Kurinevka'),
          (7,'Виноградарь','Vinohradar'),
          (7,'Приорка (Под.)','Priorka_(Pod.)'),
          (7,'Рыбальский остров','Rybalskiy_ostrov'),
          (7,'Нивки (Подольский)','Nivki (Podolskiy)'),
          (8,'Беличи','Belichi'),
          (8,'Святошино','Svyatoshyno'),
          (8,'Новобеличи','Novobelichi'),
          (8,'Академгородок','Akademhorodok'),
          (8,'Борщаговка','Borschahovka'),
          (8,'Галаганы','Halahany'),
          (8,'Катериновка','Katerinovka'),
          (8,'Южная Борщаговка','Yuzhnaya_Borschahovka'),
          (8,'Нивки (Святошинский)','Nivki_(Svyatoshynskiy)'),
          (9,'Жуляны','Zhulyany'),
          (9,'Первомайский','Pervomayskiy'),
          (9,'Отрадный','Otradny'),
          (9,'Короваевы Дачи','Korovaevy_Dachi'),
          (9,'Соломенка','Solomenka'),
          (9,'Чоколовка','Chokolovka'),
          (9,'Кадетский Гай','Kadetskiy_Hay'),
          (9,'Шулявка (Солом.)','Shulyavka_(Solom.)'),
          (9,'Александровская Слободка','Aleksandrovskaya_Slobodka'),
          (9,'Железнодорожний','Zheleznodorozhniy'),
          (9,'Монтажник','Montazhnik'),
          (9,'Совки','Sovki'),
          (9,'Протасов Яр','Protasov_Yar'),
          (10,'Нивки','Nivki'),
          (10,'Шевченковский (КПИ)','Shevchenkovskiy_(KPI)'),
          (10,'Лукьяновка','Lukianovka'),
          (10,'Сырец','Syrets'),
          (10,'Татарка','Tatarka'),
          (10,'Шевченковский (центр)','Shevchenkovskiy_(tsentr)'),
          (10,'Шулявка (Шевч.)','Shylyavka_(Shevch.)'),
          (1,'Не указано','not_exist')";
        $this->connection->executeQuery($sql);

        //Insert default content to table property_categories
        $sql = "INSERT INTO property_categories
          (`name`,`name_lat`)
          VALUES
          ('Квартира','kvartira'),
          ('Комната','komnata'),
          ('Дом','dom'),
          ('Коммерческая недвижимость','komercheskya')";
        $this->connection->executeQuery($sql);

        //Insert default content to table property_params
        $sql = "INSERT INTO property_params
          (`param_key`,`param_name`)
          VALUES
          ('flat_number','Номер квартиры'),
          ('q_rooms','Количество комнат'),
          ('level','Этаж'),
          ('levels','Этажность'),
          ('common_square','Общая площадь'),
          ('real_square','Жилая площадь'),
          ('kitchen_square','Площадь кухни'),
          ('balkon_square','Площадь балкона'),
          ('plan_build','Планировка'),
          ('san_node','Сан узел'),
          ('type_wall','Тип стен'),
          ('type_window','Тип окон'),
          ('type_warm','Тип отопления'),
          ('house_number','Номер дома'),
          ('q_cab','Количество кабинетов'),
          ('size_land','Размер участка'),
          ('build_type','Тип здания')";
        $this->connection->executeQuery($sql);

        //Insert default content to table property_types
        $sql = "INSERT INTO property_types
          (`category_id`,`name`,`name_lat`)
          VALUES
          (1,'квартира','kvartira'),
          (2,'комната','komnat'),
          (3,'дом','domov'),
          (4,'склад','sklad'),
          (4,'офис','ofis')";
        $this->connection->executeQuery($sql);

        //Insert default content to table subway_branches
        $sql = "INSERT INTO subway_branches
          (`color`,`name`,`name_lat`)
          VALUES
          ('red','красная ветка','red'),
          ('green','зеленая ветка','green'),
          ('blue','синяя ветка','blue')";
        $this->connection->executeQuery($sql);

        //Insert default content to table subway_stations
        $sql = "INSERT INTO subway_stations
          (`city_id`,`branch_id`,`name`,`name_lat`)
          VALUES
          (1,1,'Лесная','Lesnaya'),
          (1,1,'Черниговская','chernyhovskaya'),
          (1,1,'Дарница','darnitsa'),
          (1,1,'Левобережная','Levoberezhnaya'),
          (1,1,'Гидропарк','Hidropark'),
          (1,1,'Днепро','Dnepro'),
          (1,1,'Арсенальная','Arsenalnaya'),
          (1,1,'Крещатик','Hreschatyk'),
          (1,1,'Театральная','Teatralnaya'),
          (1,1,'Университет','Universytet'),
          (1,1,'Вокзальная','Vokzalnaya'),
          (1,1,'Политехнический институт','Politehnicheskiy_insytut'),
          (1,1,'Шулявская','Shulyavskaya'),
          (1,1,'Берестейская','Beresteiskaya'),
          (1,1,'Нивки','Nivki'),
          (1,1,'Святошин','Sviatoshyn'),
          (1,1,'Житомирская','Zhytomerskaya'),
          (1,1,'Академгородок','Akademhorodok'),
          (1,2,'Красный хутор','Krasnyi_Khutor'),
          (1,2,'Бориспольская','Boryspolskaya'),
          (1,2,'Вырлица','Vyrlitsa'),
          (1,2,'Харьковская','Kharkovskaya'),
          (1,2,'Позняки','Pozniaky'),
          (1,2,'Осокорки','Osokorky'),
          (1,2,'Славутич','Slavutych'),
          (1,2,'Выдубычи','Vydubychi'),
          (1,2,'Дружбы Народ','Druzhby_Narodov'),
          (1,2,'Печерская','Pecherskaya'),
          (1,2,'Кловская','Klovsskaya'),
          (1,2,'Палац Спорта','Palats_Sporta'),
          (1,2,'Золотые Ворота','Zolotye_Vorota'),
          (1,2,'Лукьяновская','Lukianovskaya'),
          (1,2,'Дорогожичи','Dorohozhychi'),
          (1,2,'Сирец','Syrets'),
          (1,3,'Теремки','Teremky'),
          (1,3,'Выставковый центр','Vystavkovyi_Tsentr'),
          (1,3,'Васильковская','Vasilkovskaya'),
          (1,3,'Голосеевская','Holoseevskaya'),
          (1,3,'Демеевская','Demeevskaya'),
          (1,3,'Лыбидская','Lybitskaya'),
          (1,3,'Палац Украина','Palats_Ukraina'),
          (1,3,'Олимпийская','Olimpiiskyay'),
          (1,3,'Площадь Льва Толстого','Ploshcad_Lva_Tolstoho'),
          (1,3,'Майдан Независимости','Maidan_Nezavysimosti'),
          (1,3,'Почтовая площадь','Pochtovaya_ploschad'),
          (1,3,'Контрактова площадь','Kontraktova_ploschad'),
          (1,3,'Тараса Шевченка','Tarasa_Shevchenka'),
          (1,3,'Петровка','Petrovka'),
          (1,3,'Оболонь','Obolon'),
          (1,3,'Минская','Minskaya'),
          (1,3,'Героев Днепра','Heroev_Dnepra'),
          (1,1,'Не указано','not_exist')";
        $this->connection->executeQuery($sql);

        //Insert default content to table users
        $sql = "INSERT INTO users
          (`login`,`password`,`phone`)
          VALUES
          ('admin','bestpass','0508280798')";
        $this->connection->executeQuery($sql);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $schema->dropTable('cities');
        $schema->dropTable('contact_letters');
        $schema->dropTable('currencies');
        $schema->dropTable('deal_categories');
        $schema->dropTable('deal_types');
        $schema->dropTable('districts');
        $schema->dropTable('listings');
        $schema->dropTable('listing_images');
        $schema->dropTable('listing_phones');
        $schema->dropTable('microdistricts');
        $schema->dropTable('property_categories');
        $schema->dropTable('property_params');
        $schema->dropTable('property_params_value');
        $schema->dropTable('property_types');
        $schema->dropTable('selections');
        $schema->dropTable('subway_branches');
        $schema->dropTable('subway_stations');
        $schema->dropTable('users');
    }
}
