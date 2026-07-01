<?php

namespace App\Livewire\LogViewer;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\File;

#[Layout('layouts.app')]
#[Title('Log Viewer')]
class Index extends Component
{
    public $search = '';
    public $level = '';
    public $perPage = 15;
    public $currentPage = 1;
    public $showConfirm = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'level' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->currentPage = 1;
    }

    public function updatingLevel()
    {
        $this->currentPage = 1;
    }

    /**
     * Parse laravel.log file into an array of logs
     */
    private function getLogs()
    {
        $logPath = storage_path('logs/laravel.log');
        if (!File::exists($logPath)) {
            return [];
        }

        // Baca file log secara terbalik (log terbaru di atas)
        $content = File::get($logPath);
        
        // Regex pattern untuk mendeteksi log Laravel default
        // Format: [YYYY-MM-DD HH:MM:SS] env.LEVEL: Message
        $pattern = '/\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\] (\w+)\.(\w+): (.*?)(?=\n\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\]|$)/s';
        
        preg_match_all($pattern, $content, $matches, PREG_SET_ORDER);
        
        $logs = [];
        foreach ($matches as $match) {
            $timestamp = $match[1];
            $env = $match[2];
            $level = strtoupper($match[3]);
            $message = trim($match[4]);

            // Ekstrak baris pertama dari pesan sebagai judul stacktrace jika panjang
            $title = strtok($message, "\n");
            $stacktrace = str_replace($title, '', $message);

            // Filter pencarian
            if ($this->search && stripos($message, $this->search) === false && stripos($timestamp, $this->search) === false) {
                continue;
            }

            // Filter level
            if ($this->level && $level !== strtoupper($this->level)) {
                continue;
            }

            $logs[] = [
                'timestamp' => $timestamp,
                'env' => $env,
                'level' => $level,
                'title' => $title,
                'stacktrace' => trim($stacktrace),
            ];
        }

        // Balik urutan agar data log terbaru berada di urutan paling atas
        return array_reverse($logs);
    }

    public function clearLog()
    {
        $logPath = storage_path('logs/laravel.log');
        if (File::exists($logPath)) {
            File::put($logPath, '');
            session()->flash('success', 'File log berhasil dikosongkan.');
            $this->dispatch('notify', type: 'success', message: 'File log berhasil dikosongkan.');
        }
        $this->showConfirm = false;
    }

    public function render()
    {
        $allLogs = $this->getLogs();
        $totalLogs = count($allLogs);
        
        // Manual Pagination
        $perPage = (int)$this->perPage;
        $offset = ($this->currentPage - 1) * $perPage;
        $paginatedLogs = array_slice($allLogs, $offset, $perPage);
        
        $totalPages = (int)ceil($totalLogs / $perPage);
        $totalPages = $totalPages < 1 ? 1 : $totalPages;

        return view('livewire.log-viewer.index', [
            'logs' => $paginatedLogs,
            'totalLogs' => $totalLogs,
            'totalPages' => $totalPages,
            'currentPage' => $this->currentPage,
        ]);
    }

    public function setPage($page)
    {
        $this->currentPage = (int)$page;
    }
}
