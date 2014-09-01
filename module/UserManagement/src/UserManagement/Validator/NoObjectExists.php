<?php

namespace UserManagement\Validator;


use Zend\Validator\Exception;

/**
 * Class that validates if objects does not exist in a given repository with a given list of matched fields
 *
 * @license MIT
 * @link    http://www.doctrine-project.org/
 * @since   0.4.0
 * @author  Marco Pivetta <ocramius@gmail.com>
 */
class NoObjectExists extends ObjectExists
{
    /**
     * Error constants
     */
    const ERROR_OBJECT_FOUND    = 'objectFound';

    /**
     * @var array Message templates
     */
    protected $messageTemplates = array(
        self::ERROR_OBJECT_FOUND    => "An object matching '%value%' was found",
    );

    /**
     * @var string Field name for excluding entities
     */
    protected $excludeField;

    /**
     * @var array Values to match against during exclusion
     */
    protected $excludedValues = array();

    /**
     * {@inheritDoc}
     */
    public function isValid($value)
    {
        $value = $this->cleanSearchValue($value);
        $match = $this->objectRepository->findOneBy($value);

        if (is_object($match) && !$this->isExcluded($match)) {
            $this->error(self::ERROR_OBJECT_FOUND, $value);

            return false;
        }

        return true;
    }

    /**
     * Checks if matched value is excluded
     *
     * @param $match
     * @return bool
     * @throws \Zend\Validator\Exception\InvalidArgumentException
     */
    protected function isExcluded($match)
    {
        if (isset($this->excludeField)) {
            $methodName = 'get' . ucfirst($this->excludeField);

            if (method_exists($match, $methodName)) {
                $checkAgainst = $match->{$methodName}();

                return in_array($checkAgainst, $this->excludedValues);
            } else {
                return false;
            }
        }

        return false;
    }

    /**
     * Sets field name and values used for exclusion
     *
     * @param $exclude
     * @throws \Zend\Validator\Exception\InvalidArgumentException
     */
    public function setExclude($exclude)
    {
        if (isset($exclude['field']) && isset($exclude['value'])) {
            if (is_string($exclude['field'])) {
                $this->excludeField = $exclude['field'];
            } else {
                throw new Exception\InvalidArgumentException(
                    '`field` key in "exclude" option must contain a string value.'
                );
            }
            $this->excludedValues = is_array($exclude['value']) ? $exclude['value'] : [$exclude['value']];
        }
    }

}