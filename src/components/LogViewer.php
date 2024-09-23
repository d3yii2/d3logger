<?php

namespace d3logger\components;


use d3system\helpers\D3FileHelper;
use yii\base\Component;
use yii\base\Exception;
use yii\base\Model;
use yii\helpers\FileHelper;
use Yii;
use yii\web\HttpException;

/**
 * This is the model class for the Directory of Files
 */
class LogViewer extends Component
{    
    public array $exclude = [];

    public ?string $showFileContent = null;

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
        
        $this->directories = $this->getCurrentDirectories($currentPath);
        $this->files = $this->getCurrentDirectoryFiles($currentPath);
    }

    /**
     * @return array
     */
    public function getCurrentDirectoryFiles(): array
    {
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

}
