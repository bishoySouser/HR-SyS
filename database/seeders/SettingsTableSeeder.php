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
        // [
        //     'key'         => 'hr_email',
        //     'name'        => 'HR Department Email',
        //     'description' => 'Email address for the HR department.',
        //     'value'       => 'hr@company.com',
        //     'field'       => '{"name":"value","label":"Value","type":"email"}',
        //     'active'      => 1,
        // ],
        // [
        //     'key'         => 'it_email',
        //     'name'        => 'IT Department Email',
        //     'description' => 'Email address for the IT department.',
        //     'value'       => 'it@company.com',
        //     'field'       => '{"name":"value","label":"Value","type":"email"}',
        //     'active'      => 1,
        // ],
        // [
        //     'key'         => 'accounting_email',
        //     'name'        => 'Accounting Department Email',
        //     'description' => 'Email address for the Accounting department.',
        //     'value'       => 'accounting@company.com',
        //     'field'       => '{"name":"value","label":"Value","type":"email"}',
        //     'active'      => 1,
        // ],
        // [
        //     'key'         => 'excuses_count',
        //     'name'        => 'Excuses Count',
        //     'description' => 'The number of excuses submitted in a month by Hours.',
        //     'value'       => '4',
        //     'field'       => '{"name":"value","label":"Value","type":"number"}',
        //     'active'      => 1,
        // ],

        //------- Revaluation Employee  -------//
        [
            'key'         => 'revaluation_employee_from',
            'name'        => 'Revaluation Employee From',
            'description' => 'Details of revaluation employee from day',
            'value'       => '25',
            'field'       => '{"name":"value","label":"Value","type":"number"}',
            'active'      => 1,
        ],
        [
            'key'         => 'revaluation_employee_to',
            'name'        => 'Revaluation Employee To',
            'description' => 'Details of revaluation employee to day',
            'value'       => '28',
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

