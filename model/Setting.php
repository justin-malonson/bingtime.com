<?php

class Setting extends Entity
{
    public $name;

    public $value;

    private static $all;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * @param string|null $value
     */
    public function setValue(?string $value)
    {
        $this->value = $value;
    }

    /**
     * @return array
     * @throws Exception
     */
    public static function all(): array
    {
        if (!isset(self::$all)) {
            $settings = Setting::read_all();
            self::$all = [];
            foreach ($settings as $setting) {
                self::$all[$setting->getName()] = $setting->getValue();
            }
        }
        return self::$all;
    }

    /**
     * @param string $name
     * @return string|null
     * @throws Exception
     */
    public static function get(string $name): ?string
    {
        global $lang;
        $settings = self::all();
        if (array_key_exists($name, $settings)) {
            return $settings[$name];
        } else {
            if (isset($lang[$name])) {
                return $lang[$name];
            }
            return "";
        }
    }

    /**
     * @param array $data
     * @param bool $overwrite
     * @throws Exception
     */
    public static function apply(array $data, bool $overwrite = true)
    {
        $startedTransaction = MysqliDb::getInstance()->startTransaction();
        foreach ($data as $entryName => $entryValue) {
            $setting = new Setting();
            $setting->setName($entryName);
            if ($setting->read() && $overwrite === false) {
                continue;
            }
            $setting->setValue($entryValue);
            $setting->save(false);
        }
        if ($startedTransaction) {
            MysqliDb::getInstance()->commit();
        }
        if (isset(self::$all)) {
            foreach ($data as $name => $value) {
                self::$all[$name] = $value;
            }
        }
    }
}