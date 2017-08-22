<?php

namespace DALTCORE\ReleaseTools\Helpers\Playbook;

use DALTCORE\ReleaseTools\Helpers\Exceptions\PlaybookValidationFailedException;

class Argument
{
    public $arguments = [];

    /**
     * Argument constructor.
     */
    public function __construct()
    {
        return $this;
    }

    /**
     * Build object from array
     *
     * @param array $array
     *
     * @return $this
     */
    public function fromArray(array $array)
    {
        $this->arguments = $array;

        return $this;
    }

    /**
     * Validate input arguments
     *
     * @param array $validations
     *
     * @throws \DALTCORE\ReleaseTools\Helpers\Exceptions\PlaybookValidationFailedException
     */
    public function validate(array $validations)
    {
        foreach ($validations as $name => $required) {
            if (isset($this->arguments[$name]) && $required === true) {
                continue;
            } else {
                // If is bool; assume this is a existance validation
                if (is_bool($required)) {
                    if ($required === true) {
                        throw new PlaybookValidationFailedException('[Required] Validation for ' . $name . ' did not pass.');
                    } else {
                        continue;
                    }
                } elseif (is_string($required)) {
                    if (preg_match($required, addslashes($this->arguments[$name]), $matches) == 1) {
                        foreach ($matches as $k => $v) {
                            if (is_int($k)) {
                                unset($matches[$k]);
                            }
                        }

                        $original = $this->arguments[$name];
                        $this->arguments[$name] = [
                            'original' => $original
                        ];

                        $this->arguments[$name] = (new RegexParameters())
                            ->fromArray(array_merge($this->arguments[$name], $matches));
                    } else {
                        throw new PlaybookValidationFailedException('[Regex failed] Validation for ' . $name . ' did not pass.');
                    }
                } else {
                    throw new PlaybookValidationFailedException('[No bool or string] Validation for ' . $name . ' did not pass.');
                }
            }
        }
    }

    /**
     * Get object magically
     *
     * @param $name
     *
     * @return mixed|null
     */
    public function __get($name)
    {
        if (isset($this->arguments[$name])) {
            return $this->arguments[$name];
        }

        return null;
    }

}
