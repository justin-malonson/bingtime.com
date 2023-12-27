<?php


class Job extends Entity
{
    public $functionName;

    public $arguments;

    public $lockName;

    /**
     * @return string
     */
    public function getFunctionName(): string
    {
        return $this->functionName;
    }

    /**
     * @param string $functionName
     */
    public function setFunctionName(string $functionName): void
    {
        $this->functionName = $functionName;
    }

    /**
     * @return array
     */
    public function getArguments(): array
    {
        return json_decode($this->arguments);
    }

    /**
     * @param array $arguments
     */
    public function setArguments(array $arguments)
    {
        $this->arguments = json_encode($arguments);
    }

    /**
     * @return string|null
     */
    public function getLockName(): ?string
    {
        return $this->lockName;
    }

    /**
     * @param string|null $lockName
     */
    public function setLockName(?string $lockName): void
    {
        $this->lockName = $lockName;
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function execute() {
        if ($this->lockName) {
            return lock($this->lockName, function () {
                return call_user_func_array($this->getFunctionName(), $this->getArguments());
            });
        } else {
            return call_user_func_array($this->getFunctionName(), $this->getArguments());
        }
    }

    /**
     * @param string $functionName
     * @param array $arguments
     * @param string|null $lockName
     * @throws Exception
     */
    public static function queue(string $functionName, array $arguments, ?string $lockName = null) {
        $job = new Job();
        $job->setFunctionName($functionName);
        $job->setArguments($arguments);
        $job->setLockName($lockName);
        $job->save();
    }
}