<?php
namespace d3logger;

use d3system\helpers\D3FileHelper;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Psr\Log\LogLevel;
use taurameda\deckelnagelmaschine\ConfigRobotexMachine;
use yii\base\Component;
use yii\base\InvalidConfigException;

class D3Monolog extends Component
{
    private ?Logger $logger = null;

    public ?string $name = null;
    public ?string $fileName = null;
    public ?string $directory = 'monolog';
    public ?int $maxFiles = 7;

    public function init(): void
    {
        if (!$this->name) {
            throw new InvalidConfigException('Logger name must be set');
        }
        if (!$this->name) {
            throw new InvalidConfigException('LOgger file name must be set');
        }
        parent::init();


        $this->logger = new Logger($this->name);
        $logfile = D3FileHelper::getRuntimeFilePath($this->directory, $this->fileName);
        $this->logger->pushHandler(new RotatingFileHandler(
            $logfile,
            $this->maxFiles,
            LogLevel::INFO)
        );
    }

    public function info($message, array $context = []): void
    {
        $this->logger->info($message, $context);
    }

    public function debug($message, array $context = []): void
    {
        $this->logger->debug($message, $context);
    }

    public function notice($message, array $context = []): void
    {
        $this->logger->notice($message, $context);
    }

    public function warning($message, array $context = []): void
    {
        $this->logger->warning($message, $context);
    }

    public function error($message, array $context = []): void
    {
        $this->logger->error($message, $context);
    }

    public function critical($message, array $context = []): void
    {
        $this->logger->critical($message, $context);
    }

    public function alert($message, array $context = []): void
    {
        $this->logger->alert($message, $context);
    }

    public function emergency($message, array $context = []): void
    {
        $this->logger->emergency($message, $context);
    }

}