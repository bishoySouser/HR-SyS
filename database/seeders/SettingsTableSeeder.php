<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsTableSeeder extends Seeder
{
    /**
     * The settings to add.
     */
    protected $settings = [
        [
            'key'         => 'hr_email',
            'name'        => 'HR Department Email',
            'description' => 'Email address for the HR department.',
            'value'       => 'hr@company.com',
            'field'       => '{"name":"value","label":"Value","type":"email"}',
            'active'      => 1,
        ],
        [
            'key'         => 'it_email',
            'name'        => 'IT Department Email',
            'description' => 'Email address for the IT department.',
            'value'       => 'it@company.com',
            'field'       => '{"name":"value","label":"Value","type":"email"}',
            'active'      => 1,
        ],
        [
            'key'         => 'accounting_email',
            'name'        => 'Accounting Department Email',
            'description' => 'Email address for the Accounting department.',
            'value'       => 'accounting@company.com',
            'field'       => '{"name":"value","label":"Value","type":"email"}',
            'active'      => 1,
        ],
        [
            'key'         => 'excuses_count',
            'name'        => 'Excuses Count',
            'description' => 'The number of excuses submitted in a month by Hours.',
            'value'       => '4',
            'field'       => '{"name":"value","label":"Value","type":"number"}',
            'active'      => 1,
        ],
        [
            'key'         => 'work_from_home_count',
            'name'        => 'Work From Home Policy',
            'description' => 'Details of the work-from-home policy by Days',
            'value'       => '3',
            'field'       => '{"name":"value","label":"Value","type":"number"}',
            'active'      => 1,
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->settings as $index => $setting) {
            $result = DB::table(config('backpack.settings.table_name'))->insert($setting);

            if (!$result) {
                $this->command->info("Insert failed at record $index.");

                return;
            }
        }

        $this->command->info('Inserted '.count($this->settings).' records.');
    }
}

