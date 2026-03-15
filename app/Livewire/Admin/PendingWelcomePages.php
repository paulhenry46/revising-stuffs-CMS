<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\File;
use App\Models\Curriculum;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PendingWelcomePages extends Component
{
    public $pendingPages = [];

    public function mount()
    {
        $pendingDir = storage_path('app/pending-welcome-pages');
        $files = File::files($pendingDir);
        foreach ($files as $file) {
            preg_match('/^(\d+)\.blade\.php$/', $file->getFilename(), $matches);
            $curriculumId = $matches[1] ?? null;
            if (!$curriculumId) {
                continue;
            }

            $curriculum = Curriculum::find($curriculumId);
            $this->pendingPages[$curriculumId] = $curriculum;
        }
    }

    public function approve($curriculumId)
    {
        $pendingPath = storage_path('app/pending-welcome-pages/' . $curriculumId . '.blade.php');
        $activePath = storage_path('app/welcome-pages/' . $curriculumId . '.blade.php');
        if (File::exists($pendingPath)) {
            File::move($pendingPath, $activePath);
            session()->flash('message', 'Welcome page approved and activated.');
        }
        
        $curriculum = Curriculum::find($curriculumId);
        if ($curriculum) {
            
            $coAdmins = \App\Models\User::whereIn('id', 
                DB::table('co_admin_curricula')
                    ->where('curriculum_id', $curriculumId)
                    ->pluck('user_id')
            )->get();


            foreach ($coAdmins as $coAdmin) {
                $coAdmin->notify(new \App\Notifications\WPageValidated($curriculum));
            }
        }
        unset($this->pendingPages[$curriculumId]);
    }

    public function reject($curriculumId)
    {
        $pendingPath = storage_path('app/pending-welcome-pages/' . $curriculumId . '.blade.php');
        if (File::exists($pendingPath)) {
            File::delete($pendingPath);
            session()->flash('message', 'Welcome page rejected and deleted.');
        }
        unset($this->pendingPages[$curriculumId]);
    }
    public function download($curriculumId)
    {
        $pendingPath = storage_path('app/pending-welcome-pages/' . $curriculumId . '.blade.php');

        if (File::exists($pendingPath)) {
            return response()->download($pendingPath, $curriculumId . '.blade.php');
        }
        session()->flash('message', 'File not found.');
        return null;
    }

    public function render()
    {
        return view('livewire.admin.pending-welcome-pages', [
            'pendingPages' => $this->pendingPages,
           
        ]);
    }
}
