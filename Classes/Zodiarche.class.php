<?php

namespace Zodiarche;

class Zodiarche {
    private $commandDir;
    private $commandPath;
    private $command;
    private $subcommand;
    private $isVerbose;

    /**
     * Cerberus constructor.
     * @param array $argv Command line arguments
     */
    public function __construct($argv) {
        $start = microtime(true);
        $this->setCommandDir(dirname(__DIR__).'/Commands/');
        $this->setCommand(explode('::', $argv[1])[0]);
        $this->setSubcommand(explode('::', $argv[1])[1]);
        $this->setCommandPath($this->getCommandDir().$this->getCommand().'.php');

        self::writeStdOut('');
        self::writeStdOut('::::::::::::::::::::::::::::::::::::::');
        self::writeStdOut(':::      Starting Zodiarche      :::::');
        self::writeStdOut(':::      Command: ' . str_pad($this->getCommand(), 15, ' ', STR_PAD_RIGHT) . ':::::');
        self::writeStdOut(':::      Command: ' . str_pad($this->getSubcommand(), 15, ' ', STR_PAD_RIGHT) . ':::::');
        self::writeStdOut('::::::::::::::::::::::::::::::::::::::');
        self::writeStdOut('');

        if(!$this->validate()) {
            self::writeStdOut('Something went wrong while calling zodiarche! Please check the parameters!');
            exit();
        }


        $this->executeCommand();

        self::writeStdOut('');
        self::writeStdOut('');
        self::writeStdOut('Elapsed time of script: ' . round(microtime(true) - $start, 5) . 'ms');
        self::writeStdOut('');
    }

    /**
     * Execute function. Doesn't take any params, pulls everyone from this instance which is set in the constructor.
     */
    public function executeCommand() {
        $commandName = '\\'.__NAMESPACE__.'\\'.$this->getCommand();
        $subcommandName = $this->getSubcommand();
        $command = new $commandName();

        $command->$subcommandName();
    }

    /**
     * Executes two filters
     * @return bool
     */
    public function validate() {
        return $this->_validateCommand() && $this->_validateSubcommand();
    }

    /**
     * Validate the existence of te filter by checking if a file with that name exists
     * @return bool
     */
    private function _validateCommand() {
        if(!file_exists($this->getCommandPath())) {
            self::writeStdOut( "The Command {$this->getCommand()} is not defined in {$this->getCommandDir()}");
            return false;
        }
        return true;
    }

    /**
     * Instanciates a class, then checks if the command exists as a method in that class
     * @return bool
     */
    private function _validateSubcommand() {
        require $this->getCommandPath();

        $class = '\\'.__NAMESPACE__.'\\'.$this->getCommand();

        $filterObject = new $class;

        if(!method_exists($filterObject, $this->getSubcommand())) {
            self::writeStdOut( "The subcommand {$this->getSubcommand()} is not defined in {$this->getCommandPath()}");
            return false;
        }
        return true;
    }

    /**
     * write to stdout
     *
     * @param string $sMessage log message
     *
     * @return void
     */
    public static function writeStdOut($message) {
        file_put_contents('php://stdout', $message.PHP_EOL);
    }

    /**
     * @return mixed
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * @param $command
     */
    public function setCommand($command)
    {
        $this->command = $command;
    }

    /**
     * @return mixed
     */
    public function getSubcommand()
    {
        return $this->subcommand;
    }

    /**
     * @param $subcommand
     */
    public function setSubcommand($subcommand)
    {
        $this->subcommand = $subcommand;
    }

    /**
     * @return bool
     */
    public function isVerbose()
    {
        return $this->isVerbose;
    }

    /**
     * @param bool $isVerbose
     */
    public function setIsVerbose($isVerbose)
    {
        $this->isVerbose = $isVerbose;
    }

    /**
     * @param bool $rootDir
     */
    public function setRootDir($rootDir)
    {
        $this->rootDir = $rootDir;
    }

    /**
     * @param $commandDir
     */
    public function setCommandDir($commandDir)
    {
        $this->commandDir = $commandDir;
    }

    /**
     * @return mixed
     */
    public function getCommandDir()
    {
        return $this->commandDir;
    }

    /**
     * @return mixed
     */
    public function getCommandPath()
    {
        return $this->commandPath;
    }

    /**
     * @param $commandPath
     */
    public function setCommandPath($commandPath)
    {
        $this->commandPath = $commandPath;
    }


}