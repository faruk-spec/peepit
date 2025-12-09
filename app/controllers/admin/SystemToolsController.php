<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Order;
use App\Models\User;

class SystemToolsController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
        $this->requireRole(['superadmin', 'manager']);
    }

    /**
     * System tools dashboard
     */
    public function index()
    {
        $data = [
            'title' => 'System Tools',
            'cache_info' => $this->getCacheInfo(),
            'log_info' => $this->getLogInfo(),
            'backup_info' => $this->getBackupInfo(),
            'disk_info' => $this->getDiskInfo()
        ];

        $this->view('admin/system-tools/index', $data);
    }

    /**
     * Cache management
     */
    public function cache()
    {
        $data = [
            'title' => 'Cache Management',
            'cache_size' => $this->getCacheSize(),
            'cache_files' => $this->getCacheFiles()
        ];

        $this->view('admin/system-tools/cache', $data);
    }

    /**
     * Clear cache
     */
    public function clearCache()
    {
        if (!$this->validateCSRF()) {
            flash('error', 'Invalid request. Please try again.');
            redirect('/admin/system-tools/cache');
        }

        try {
            $cacheDir = __DIR__ . '/../../../storage/cache/';
            if (is_dir($cacheDir)) {
                $files = glob($cacheDir . '*');
                foreach ($files as $file) {
                    if (is_file($file)) {
                        unlink($file);
                    }
                }
            }

            flash('success', 'Cache cleared successfully!');
        } catch (\Exception $e) {
            error_log('Cache clear error: ' . $e->getMessage());
            flash('error', 'Failed to clear cache. Please try again.');
        }

        redirect('/admin/system-tools/cache');
    }

    /**
     * Log viewer
     */
    public function logs()
    {
        $logFile = $_GET['file'] ?? 'error.log';
        
        $data = [
            'title' => 'System Logs',
            'log_files' => $this->getLogFiles(),
            'current_file' => $logFile,
            'log_content' => $this->readLogFile($logFile)
        ];

        $this->view('admin/system-tools/logs', $data);
    }

    /**
     * Clear logs
     */
    public function clearLogs()
    {
        if (!$this->validateCSRF()) {
            flash('error', 'Invalid request. Please try again.');
            redirect('/admin/system-tools/logs');
        }

        $logFile = $_POST['log_file'] ?? 'error.log';

        try {
            $logPath = __DIR__ . '/../../../storage/logs/' . basename($logFile);
            if (file_exists($logPath)) {
                file_put_contents($logPath, '');
                flash('success', 'Log file cleared successfully!');
            } else {
                flash('error', 'Log file not found.');
            }
        } catch (\Exception $e) {
            error_log('Log clear error: ' . $e->getMessage());
            flash('error', 'Failed to clear log file. Please try again.');
        }

        redirect('/admin/system-tools/logs');
    }

    /**
     * Backup management
     */
    public function backup()
    {
        $data = [
            'title' => 'Backup Management',
            'backups' => $this->getBackupList()
        ];

        $this->view('admin/system-tools/backup', $data);
    }

    /**
     * Create database backup
     */
    public function createBackup()
    {
        if (!$this->validateCSRF()) {
            flash('error', 'Invalid request. Please try again.');
            redirect('/admin/system-tools/backup');
        }

        try {
            $config = require __DIR__ . '/../../../config/config.php';
            $backupDir = __DIR__ . '/../../../storage/backups/';
            
            if (!is_dir($backupDir)) {
                mkdir($backupDir, 0755, true);
            }

            $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
            $filepath = $backupDir . $filename;

            // MySQL dump command
            $command = sprintf(
                'mysqldump --user=%s --password=%s --host=%s %s > %s',
                escapeshellarg($config['db']['username']),
                escapeshellarg($config['db']['password']),
                escapeshellarg($config['db']['host']),
                escapeshellarg($config['db']['database']),
                escapeshellarg($filepath)
            );

            exec($command, $output, $returnVar);

            if ($returnVar === 0 && file_exists($filepath)) {
                flash('success', 'Database backup created successfully!');
            } else {
                flash('error', 'Failed to create backup. Please check server configuration.');
            }
        } catch (\Exception $e) {
            error_log('Backup error: ' . $e->getMessage());
            flash('error', 'Failed to create backup. Please try again.');
        }

        redirect('/admin/system-tools/backup');
    }

    /**
     * Download backup
     */
    public function downloadBackup($filename)
    {
        $filename = basename($filename);
        $filepath = __DIR__ . '/../../../storage/backups/' . $filename;

        if (!file_exists($filepath)) {
            flash('error', 'Backup file not found.');
            redirect('/admin/system-tools/backup');
        }

        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($filepath));
        readfile($filepath);
        exit;
    }

    /**
     * Delete backup
     */
    public function deleteBackup()
    {
        if (!$this->validateCSRF()) {
            flash('error', 'Invalid request. Please try again.');
            redirect('/admin/system-tools/backup');
        }

        $filename = $_POST['filename'] ?? '';
        $filename = basename($filename);
        $filepath = __DIR__ . '/../../../storage/backups/' . $filename;

        try {
            if (file_exists($filepath)) {
                unlink($filepath);
                flash('success', 'Backup deleted successfully!');
            } else {
                flash('error', 'Backup file not found.');
            }
        } catch (\Exception $e) {
            error_log('Backup delete error: ' . $e->getMessage());
            flash('error', 'Failed to delete backup. Please try again.');
        }

        redirect('/admin/system-tools/backup');
    }

    /**
     * Get cache information
     */
    private function getCacheInfo()
    {
        $cacheDir = __DIR__ . '/../../../storage/cache/';
        $size = 0;
        $files = 0;

        if (is_dir($cacheDir)) {
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($cacheDir, \FilesystemIterator::SKIP_DOTS)
            );

            foreach ($iterator as $file) {
                $size += $file->getSize();
                $files++;
            }
        }

        return [
            'size' => $this->formatBytes($size),
            'files' => $files
        ];
    }

    /**
     * Get log information
     */
    private function getLogInfo()
    {
        $logDir = __DIR__ . '/../../../storage/logs/';
        $size = 0;
        $files = 0;

        if (is_dir($logDir)) {
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($logDir, \FilesystemIterator::SKIP_DOTS)
            );

            foreach ($iterator as $file) {
                $size += $file->getSize();
                $files++;
            }
        }

        return [
            'size' => $this->formatBytes($size),
            'files' => $files
        ];
    }

    /**
     * Get backup information
     */
    private function getBackupInfo()
    {
        $backupDir = __DIR__ . '/../../../storage/backups/';
        $size = 0;
        $files = 0;

        if (is_dir($backupDir)) {
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($backupDir, \FilesystemIterator::SKIP_DOTS)
            );

            foreach ($iterator as $file) {
                $size += $file->getSize();
                $files++;
            }
        }

        return [
            'size' => $this->formatBytes($size),
            'files' => $files
        ];
    }

    /**
     * Get disk information
     */
    private function getDiskInfo()
    {
        $diskFree = disk_free_space('/');
        $diskTotal = disk_total_space('/');
        $diskUsed = $diskTotal - $diskFree;

        return [
            'total' => $this->formatBytes($diskTotal),
            'used' => $this->formatBytes($diskUsed),
            'free' => $this->formatBytes($diskFree),
            'percent' => round(($diskUsed / $diskTotal) * 100, 2)
        ];
    }

    /**
     * Get cache size
     */
    private function getCacheSize()
    {
        $cacheDir = __DIR__ . '/../../../storage/cache/';
        $size = 0;

        if (is_dir($cacheDir)) {
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($cacheDir, \FilesystemIterator::SKIP_DOTS)
            );

            foreach ($iterator as $file) {
                $size += $file->getSize();
            }
        }

        return $this->formatBytes($size);
    }

    /**
     * Get cache files
     */
    private function getCacheFiles()
    {
        $cacheDir = __DIR__ . '/../../../storage/cache/';
        $files = [];

        if (is_dir($cacheDir)) {
            $items = scandir($cacheDir);
            foreach ($items as $item) {
                if ($item !== '.' && $item !== '..' && is_file($cacheDir . $item)) {
                    $files[] = [
                        'name' => $item,
                        'size' => $this->formatBytes(filesize($cacheDir . $item)),
                        'modified' => date('Y-m-d H:i:s', filemtime($cacheDir . $item))
                    ];
                }
            }
        }

        return $files;
    }

    /**
     * Get log files
     */
    private function getLogFiles()
    {
        $logDir = __DIR__ . '/../../../storage/logs/';
        $files = [];

        if (is_dir($logDir)) {
            $items = scandir($logDir);
            foreach ($items as $item) {
                if ($item !== '.' && $item !== '..' && is_file($logDir . $item)) {
                    $files[] = [
                        'name' => $item,
                        'size' => $this->formatBytes(filesize($logDir . $item)),
                        'modified' => date('Y-m-d H:i:s', filemtime($logDir . $item))
                    ];
                }
            }
        }

        return $files;
    }

    /**
     * Read log file
     */
    private function readLogFile($filename)
    {
        $filename = basename($filename);
        $filepath = __DIR__ . '/../../../storage/logs/' . $filename;

        if (!file_exists($filepath)) {
            return 'Log file not found.';
        }

        // Read last 1000 lines
        $lines = [];
        $file = fopen($filepath, 'r');
        
        if ($file) {
            fseek($file, -1, SEEK_END);
            $pos = ftell($file);
            $line = '';
            
            while ($pos >= 0 && count($lines) < 1000) {
                fseek($file, $pos--, SEEK_SET);
                $char = fgetc($file);
                
                if ($char === "\n" || $pos === 0) {
                    $lines[] = $line;
                    $line = '';
                } else {
                    $line = $char . $line;
                }
            }
            
            fclose($file);
        }

        return implode("\n", array_reverse($lines));
    }

    /**
     * Get backup list
     */
    private function getBackupList()
    {
        $backupDir = __DIR__ . '/../../../storage/backups/';
        $backups = [];

        if (is_dir($backupDir)) {
            $items = scandir($backupDir);
            foreach ($items as $item) {
                if ($item !== '.' && $item !== '..' && is_file($backupDir . $item)) {
                    $backups[] = [
                        'filename' => $item,
                        'size' => $this->formatBytes(filesize($backupDir . $item)),
                        'created' => date('Y-m-d H:i:s', filemtime($backupDir . $item))
                    ];
                }
            }
        }

        // Sort by creation date descending
        usort($backups, function($a, $b) {
            return strcmp($b['created'], $a['created']);
        });

        return $backups;
    }

    /**
     * Format bytes to human readable
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
