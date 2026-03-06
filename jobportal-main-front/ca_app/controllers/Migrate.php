<?php
class Migrate extends CI_Controller
{
    public function index()
    {   
        if (!is_cli()) {
            show_404();
        }

        $this->load->library('migration');

		if (!$this->db->table_exists('migrations_batch')) {
			$this->dbforge->add_field([
				'id' => ['type' => 'INT', 'unsigned' => TRUE, 'auto_increment' => TRUE],
                'migration' => ['type' => 'VARCHAR', 'constraint' => 180],
                'batch' => ['type' => 'INT', 'constraint' => 11]
            ]);

            $this->dbforge->add_key('id', TRUE);
			$this->dbforge->create_table('migrations_batch', TRUE);
		}
        
        $batch_row = $this->db->select('MAX(batch) AS batch')->from('migrations_batch')->limit(1)->get()->row();
        $results = $this->db->from('migrations_batch')->get()->result();
        
        $migrations = [];

        foreach ($results as $row) {
            $migrations[$row->migration] = $row->migration;
        }

        $pending = [];
        $adapter = new League\Flysystem\Local\LocalFilesystemAdapter(APPPATH);
        $filesystem = new League\Flysystem\Filesystem($adapter);
        
        $listing = $filesystem->listContents('migrations', false);
        $list_files = [];

        foreach ($listing as $item) {

            if (!$item instanceof \League\Flysystem\FileAttributes) {
                continue;
            }

            $filename = basename($item->path(), ".php");

            if (isset($migrations[$filename])) {
                continue;
            }
            
            $list_files[] = $item->path();
        }

        natsort($list_files);

        foreach ($list_files as $path) {

            $filename = basename($path, ".php");
            require_once(APPPATH . '/' . $path);
            $class = 'Migration_' . ucfirst(strtolower($this->get_file_name($filename)));
            $pending[] = [$filename, $class, 'up'];
        }

        if (count($pending) == 0) {
            echo "Sin migraciones que ejecutar\n";
            return;
        }

        foreach ($pending as $migration) {

            $filename = $migration[0];

            $migration[1] = new $migration[1];
            $return = call_user_func([
                $migration[1],
                $migration[2],
            ]);

            if ($return !== NULL) {
                echo $file_name . " Error al migrar \n";
                return;
            }
            
            $this->db->insert('migrations_batch', [
                'migration' => $filename,
                'batch' => ($batch_row ? $batch_row->batch + 1 : 1)
            ]);

            echo $filename . " Migrado \n";
        }
    }

    public function get_file_name($file_name)
    {
        $parts = explode('_', $file_name);
        array_shift($parts);
        return implode('_', $parts);
    }
}
