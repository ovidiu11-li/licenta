<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pdf;

class UpdateExistingPdfsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update existing PDFs to have proper versioning structure
        Pdf::whereNull('version')->update([
            'version' => 1,
            'is_current' => true,
            'parent_pdf_id' => null
        ]);
        
        $this->command->info('Existing PDFs updated with versioning structure.');
    }
}
