<?php

namespace d3logger\components;


use d3system\helpers\D3FileHelper;
use yii\base\Component;
use yii\base\Exception;
use yii\base\Model;
use yii\helpers\FileHelper;
use Yii;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

/**
 * This is the model class for the Directory of Files
 */
class LogViewer extends Component
{    
    public array $exclude = [];

    public ?string $showFileContent = null;

    public ?int $fileViewSizeLimit = 1000000; // 1 Mb

    private ?string $path = null;

    private array $directories = [];
    
    private array $files = [];

    private ?string $currentDirectory = null;

    /**
     * @throws Exception
     * @throws HttpException
     */
    public function __construct(?string $route = null, ?string $file = null)
    {
        parent::__construct();
        if ($route) {
            if (!$this->hasAccessToDirectory($route)) {
                throw new HttpException('403', 'You don\'t have permissions to view the Files');
            }
            $this->setPath($route);
            $this->currentDirectory = $route;
        } else {
//            $this->path = D3FileHelper::getRuntimeDirectoryPath('logs');
            $this->path =  Yii::$app->runtimePath;
        }

        if ($file) {
            $this->showFileContent = $this->getFilePath($route, $file);
        }

    }
    
    /**
     * @param string|null $path
     * @return void
     */
    public function loadDirectory(string $path = null): void
    {
        $currentPath = $path ?? $this->path;
        
        if (is_dir($path)) {
            $this->directories = $this->getCurrentDirectories($currentPath);
            $this->files = $this->getCurrentDirectoryFiles($currentPath);
        }
    }

    /**
     * @return array
     */
    public function getCurrentDirectoryFiles(): array
    {
        if (!is_dir($this->path)) {
            return [];
        }
        
        return FileHelper::findFiles($this->path, ['recursive' => false]);
    }

    /**
     * @return array
     */
    public function getCurrentDirectories(): array
    {
        $directories =  FileHelper::findDirectories($this->path);
        
        return $this->getDirectoriesWithAccess($directories);
    }

    /**
     * @param $directories
     * @return array
     */
    private function getDirectoriesWithAccess($directories): array
    {
            
        $allowedDirs = [];
        foreach ($directories as $dir) {
            if ($this->hasAccessToDirectory($dir)) {
                $allowedDirs[] = $dir;
            }
        }
        
        return $allowedDirs;
    }

    /**
     * @param string $path
     * @return bool
     */
    private function hasAccessToDirectory(string $path): bool
    {
        $roles = $this->getAccessRoles();
        $currentUser = Yii::$app->user;
        foreach ($roles as $roleName => $allowed) {
            if (!$currentUser->can($roleName)) {
                continue;
            }
            if (in_array($path, $allowed)) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * @param string $path
     * @return void
     */
    public function setPath(string $path): void
    {
        $this->path = $this->normalizeDirPath($path);
    }

    /**
     * @param string $path
     * @return string
     * @throws \yii\base\Exception
     */
    private function normalizeDirPath(string $path): string
    {
        $path = str_replace(Yii::$app->runtimePath, '', $path);

        if ($path[0] === DIRECTORY_SEPARATOR) {
            $path = ltrim($path, DIRECTORY_SEPARATOR);
        }

        return Yii::$app->runtimePath . DIRECTORY_SEPARATOR . $path;
    }
    
    /**
     * @return string|null
     */
    public function getPath(): ?string
    {
        return $this->path;
    }

    /**
     * @return string|null
     */
    public function getRoute(string $path = null): ?string
    {
        return str_replace(Yii::$app->runtimePath, '', $path ?? $this->path);
    }

    /**
     * @return string|null
     */
    public function getFilePath(string $route, string $file): ?string
    {
        return $this->normalizeDirPath($route) . DIRECTORY_SEPARATOR . $file;
    }

    /**
     * @return mixed|object|null
     */
    private function getAccessRoles()
    {
        return Yii::$app->getModule('d3logger')->accessRoles;
    }

    public function userDirectories(): array
    {
        $list = [];
        foreach ($this->getAccessRoles() as $roleName => $direcotries) {
            if (Yii::$app->user->can($roleName)) {
                $list += $direcotries;
            }
        }
        return $list;
    }

    /**
     * @param string $path
     * @return bool
     */
    public function fileIsOversized(string $path)
    {
        return filesize($path) > $this->fileViewSizeLimit;
    }

    /**
     * @param string|null $route
     * @param string|null $file
     * @return void
     * @throws D3FilesUserException
     */
    public function download(?string $route = null, string $file = null): void
    {
        $filePath = $this->getFilePath($route, $file);

        if (!is_file($filePath)) {
            throw new NotFoundHttpException(Yii::t('d3logger', 'The requested file does not exist.'));
        }

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $file . '"');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . filesize($filePath));
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s T', filemtime($filePath)));
        readfile($filePath);
        exit;
    }

    /**
     * @param $filePath
     * @param $limit
     * @return false|string[]
     */
    public function readFileLastLines($filePath, $limit = 200)
    {
        $file = fopen($filePath, "r");
        if (!$file) {
            return false; // Handle error: unable to open file
        }

        $buffer = '';
        $line_count = 0;
        $position = -2; // Start by looking at the second last character

        // Seek from the end of the file using fseek
        fseek($file, $position, SEEK_END);

        // Loop until we've read the desired number of lines or reached the start of the file
        while ($line_count < $limit) {
            $char = fgetc($file);

            // If we hit the start of the file, break
            if ($char === false) {
                break;
            }

            // Add the character to the buffer (prepend to reverse read)
            $buffer = $char . $buffer;

            // Check for new line character
            if ($char === "\n") {
                $line_count++;
            }

            // Move position further back
            $position--;

            // If we can't move further back, break the loop
            if (fseek($file, $position, SEEK_END) !== 0) {
                break;
            }
        }

        fclose($file);

        // Split buffer into array of lines
        $lines = explode("\n", trim($buffer));

        // Return last 200 lines (or less if file has fewer lines)
        return array_slice($lines, -$line_count);
    }
}
