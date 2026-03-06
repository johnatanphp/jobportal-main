<?php

class Make extends CI_Controller
{
    public function migration()
    {
        if (!is_cli()) {
            show_404();
        }

        $argv = $_SERVER['argv'];
        $filename = isset($argv[3]) ? trim($argv[3]) : null;

        if (empty($filename)) {
            exit("Error Migration: Nombre de archivo es inválido \n");
        }

        if (strlen($filename) > 180) {
            exit("Error Migration: Nombre de la migración debe tener menos 180 caracteres \n");
        }

        $adapter = new League\Flysystem\Local\LocalFilesystemAdapter(APPPATH);
        $filesystem = new League\Flysystem\Filesystem($adapter);
        $class_name = ucfirst(strtolower($filename));

$contents = <<<EOF
<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_$class_name extends CI_Migration
{
    public function up()
    {
        \$this->dbforge->add_field([
            'field1' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'field2' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
            ],
            'field3' => [
                'type' => 'TEXT',
                'null' => TRUE,
            ],
        ]);
        \$this->dbforge->add_key('field1', TRUE);
        \$this->dbforge->create_table('table');
    }

    public function down()
    {
        \$this->dbforge->drop_table('table');
    }
}

EOF;
        $file_path = 'migrations/' . date('YmdHis'). '_' . $filename .'.php';
        $filesystem->write($file_path, $contents);

        if (PHP_OS == 'Linux') {
            @chmod(APPPATH . $file_path, 0777); 
        }

        echo "Migration " . $filename . " Creado \n";
    }
}
