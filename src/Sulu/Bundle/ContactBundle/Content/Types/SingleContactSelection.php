<?php

/*
 * This file is part of Sulu.
 *
 * (c) MASSIVE ART WebServices GmbH
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Sulu\Bundle\ContactBundle\Content\Types;

use Sulu\Bundle\ContactBundle\Entity\ContactInterface;
use Sulu\Bundle\ContactBundle\Entity\ContactRepositoryInterface;
use Sulu\Bundle\WebsiteBundle\ReferenceStore\ReferenceStoreInterface;
use Sulu\Component\Content\Compat\PropertyInterface;
use Sulu\Component\Content\SimpleContentType;

class SingleContactSelection extends SimpleContentType
{
    /**
     * @var ContactRepositoryInterface
     */
    protected $contactRepository;

    /**
     * @var ReferenceStoreInterface
     */
    private $contactReferenceStore;

    public function __construct(
        ContactRepositoryInterface $contactRepository,
        ReferenceStoreInterface $contactReferenceStore
    ) {
        $this->contactRepository = $contactRepository;
        $this->contactReferenceStore = $contactReferenceStore;

        parent::__construct('SingleContact');
    }

    public function getContentData(PropertyInterface $property): ?ContactInterface
    {
        $id = $property->getValue();

        if (!$id) {
            return null;
        }

        return $this->contactRepository->findById($id);
    }

    /**
     * {@inheritdoc}
     */
    public function preResolve(PropertyInterface $property)
    {
        $id = $property->getValue();
        if (!$id) {
            return;
        }

        $this->contactReferenceStore->add($id);
    }
}
