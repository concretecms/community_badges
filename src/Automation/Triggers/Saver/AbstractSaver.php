<?php

declare(strict_types=1);

/**
 * @project:   Community Badges
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

namespace PortlandLabs\CommunityBadges\Automation\Triggers\Saver;

use Concrete\Core\Form\Service\Validation;
use Doctrine\ORM\EntityManagerInterface;

abstract class AbstractSaver implements SaverInterface
{
    /** @var Validation */
    protected $formValidator;
    /** @var EntityManagerInterface */
    protected $entityManager;

    public function __construct(Validation $formValidator, EntityManagerInterface $entityManager)
    {
        $this->formValidator = $formValidator;
        $this->entityManager = $entityManager;
    }
}
