<?php

Class EntityGenerator
{
    public static function generate($params)
    {
//        ini_set(display_errors,true);
//        error_reporting(E_ALL);
        $entity = $params['entity'];
        $module = $params['module'];
        $table = $params['table'];
        $entity_name = $params['entity_name'];

        $config = require __DIR__ . "/autoload/local.php";
        $config = $config['doctrine']['connection']['orm_default']['params'];
        $dbh = new PDO('mysql:host=' . $config['host'] . ';dbname=' . $config['dbname'], $config['user'], $config['password']);

        $q = $dbh->prepare("DESCRIBE " . $table);
        $q->execute();
        $table_fields = $q->fetchAll(PDO::FETCH_COLUMN);

        $file = __DIR__ . "/../module/" . ucfirst($module) . "/src/Entity/" . ucfirst($entity) .".txt";

        if(!file_exists($file))
        {
            $file = fopen($file, "w") or die("Unable to open file!");
        }


        if(is_readable($file))
        {
            $file_data = "<?php\n";
            $file_data .= "namespace " . ucfirst($module) . "\Entity; \n\n";
            $file_data .= "use Doctrine\ORM\Mapping as ORM;\n\n";
            $file_data .= "/**\n* Этот класс представляет собой " . $entity_name .".\n";
            $file_data .= "* @ORM\Entity()\n";
            $file_data .= "* @ORM\Table(name=\"" . $table . "\")\n*/\n";
            $file_data .= "class " . ucfirst($entity) . "\n{\n";


            foreach ($table_fields as $field)
            {
                if($field == 'id')
                {
                    $file_data .= "/**\n* @ORM\Id\n";
                    $file_data .= "* @ORM\Column(name=\"id\")\n";
                    $file_data .= "* @ORM\GeneratedValue\n*/\n";
                    $file_data .= 'protected $id;'."\n\n";
                }
                else
                {
                    $file_data .= "/**\n* @ORM\Column(name=\"" . $field . "\")\n*/\n";
                    $fa = explode('_',$field);
                    $field = count($fa) > 1 ? $fa[0].ucfirst($fa[1]) : $field;
                    $file_data .= 'protected $' . $field . ';'."\n\n";
                }
            }
            
            foreach ($table_fields as $field)
            {
                if($field == 'id')
                {
                    $file_data .= "/**\n* Возвращает ID " . $entity_name . "а.\n";
                    $file_data .= "* @return integer\n*/\n";
                    $file_data .= "public function getId()\n{\n\treturn " . '$this->id;' . "\n}\n\n";

                    $file_data .= "/**\n* Задает ID " . $entity_name . "а.\n";
                    $file_data .= '* @param $id' . "\n*/\n";
                    $file_data .= 'public function setId($id)' . "\n{\n\t" . '$this->id = $id;' . "\n}\n\n";
                }
                else
                {
                    $sql = "SELECT DATA_TYPE as type
                                                FROM INFORMATION_SCHEMA.COLUMNS
                                                WHERE 
                                                     TABLE_NAME = '$table' AND 
                                                     COLUMN_NAME = '$field'";
                    $q = $dbh->prepare($sql);
                    $q->execute();
                    $tab = $q->fetchAll(PDO::FETCH_COLUMN);
                    $type = $tab[0];
                    switch ($type)
                    {
                        case 'bigint':
                            $type = 'int';
                            break;
                        case 'datetime':
                            $type = 'string';
                            break;
                        case 'text':
                            $type = 'string';
                            break;
                        case 'varchar':
                            $type = 'string';
                            break;
                    }
                    $fa = explode('_',$field);
                    $fcc = count($fa) > 1 ? $fa[0].ucfirst($fa[1]) : $field;

                    $file_data .= "/**\n* Возвращает $field " . $entity_name . "а.\n";
                    $file_data .= "* @return $type\n*/\n";

                    $file_data .= "public function get".ucfirst($fcc)."()\n{\n\treturn " . '$this->'. $fcc .';' . "\n}\n\n";

                    $file_data .= "/**\n* Задает $field " . $entity_name . "а.\n";
                    $file_data .= '* @param $'.$field . "\n*/\n";


                    $file_data .= 'public function set'.ucfirst($fcc).'($'.$field.')' . "\n{\n\t" . '$this->'.$fcc.' = $'.$field.';' . "\n}\n\n";
                }
            }
            $file_data .= "}";

            $fp = fopen($file, 'w');
            fwrite($fp, $file_data);
            fclose($fp);
        }



        print("Generated <br> <pre>");
        var_dump($table_fields);
        echo "</pre> ";

    }
}