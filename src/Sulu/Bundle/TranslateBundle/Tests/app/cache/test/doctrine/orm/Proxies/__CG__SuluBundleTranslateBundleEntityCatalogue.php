<?php

namespace Proxies\__CG__\Sulu\Bundle\TranslateBundle\Entity;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ORM. DO NOT EDIT THIS FILE.
 */
class Catalogue extends \Sulu\Bundle\TranslateBundle\Entity\Catalogue implements \Doctrine\ORM\Proxy\Proxy
{
    private $_entityPersister;
    private $_identifier;
    public $__isInitialized__ = false;
    public function __construct($entityPersister, $identifier)
    {
        $this->_entityPersister = $entityPersister;
        $this->_identifier = $identifier;
    }
    /** @private */
    public function __load()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;

            if (method_exists($this, "__wakeup")) {
                // call this after __isInitialized__to avoid infinite recursion
                // but before loading to emulate what ClassMetadata::newInstance()
                // provides.
                $this->__wakeup();
            }

            if ($this->_entityPersister->load($this->_identifier, $this) === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            unset($this->_entityPersister, $this->_identifier);
        }
    }

    /** @private */
    public function __isInitialized()
    {
        return $this->__isInitialized__;
    }

    
    public function getId()
    {
        if ($this->__isInitialized__ === false) {
            return (int) $this->_identifier["id"];
        }
        $this->__load();
        return parent::getId();
    }

    public function setPackage(\Sulu\Bundle\TranslateBundle\Entity\Package $package)
    {
        $this->__load();
        return parent::setPackage($package);
    }

    public function getPackage()
    {
        $this->__load();
        return parent::getPackage();
    }

    public function addTranslation(\Sulu\Bundle\TranslateBundle\Entity\Translation $translation)
    {
        $this->__load();
        return parent::addTranslation($translation);
    }

    public function removeTranslation(\Sulu\Bundle\TranslateBundle\Entity\Translation $translation)
    {
        $this->__load();
        return parent::removeTranslation($translation);
    }

    public function findTranslation($key)
    {
        $this->__load();
        return parent::findTranslation($key);
    }

    public function getTranslations()
    {
        $this->__load();
        return parent::getTranslations();
    }

    public function setLocale($locale)
    {
        $this->__load();
        return parent::setLocale($locale);
    }

    public function getLocale()
    {
        $this->__load();
        return parent::getLocale();
    }

    public function setIsDefault($isDefault)
    {
        $this->__load();
        return parent::setIsDefault($isDefault);
    }

    public function getIsDefault()
    {
        $this->__load();
        return parent::getIsDefault();
    }

    public function getLinks()
    {
        $this->__load();
        return parent::getLinks();
    }

    public function createSelfLink()
    {
        $this->__load();
        return parent::createSelfLink();
    }

    public function getApiPath()
    {
        $this->__load();
        return parent::getApiPath();
    }

    public function hasApiPath()
    {
        $this->__load();
        return parent::hasApiPath();
    }


    public function __sleep()
    {
        return array('__isInitialized__', 'locale', 'isDefault', 'id', 'translations', 'package');
    }

    public function __clone()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;
            $class = $this->_entityPersister->getClassMetadata();
            $original = $this->_entityPersister->load($this->_identifier);
            if ($original === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            foreach ($class->reflFields as $field => $reflProperty) {
                $reflProperty->setValue($this, $reflProperty->getValue($original));
            }
            unset($this->_entityPersister, $this->_identifier);
        }
        
    }
}