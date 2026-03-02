<?php

namespace PortlandLabs\CommunityBadges\User\Point;

use Concrete\Core\Events\EventDispatcher;
use Concrete\Core\Support\Facade\Application;
use Concrete\Core\User\UserInfo;
use DateTimeZone;
use Loader;
use PortlandLabs\CommunityBadges\Events\AfterAssignCommunityPoints;
use PortlandLabs\CommunityBadges\Events\BeforeAssignCommunityPoints;
use PortlandLabs\CommunityBadges\User\Point\Action\Action as UserPointAction;
use PortlandLabs\CommunityBadges\User\Point\Action\ActionDescription;

class Entry
{
    public $upID;
    public $upuID;
    public $upaID = 0;
    public $upPoints;
    /** @var \DateTimeImmutable */
    public $timestamp;
    /** @var string A serialized ActionDescription instance */
    protected $object;

    public function load($upID)
    {
        $db = Loader::db();
        $row = $db->GetRow('select * from UserPointHistory where upID = ?', array($upID));
        if (is_array($row) && $row['upID']) {
            $this->setDataFromArray($row);
        }
    }

    protected function setDataFromArray(array $data): void
    {
        $this->upID = $data['upID'];
        $this->upuID = $data['upuID'];
        $this->upaID = $data['upaID'];
        $this->object = (string) $data['object'];
        $this->upPoints = $data['upPoints'];
        $this->timestamp = \DateTimeImmutable::createFromFormat(
            'Y-m-d H:i:s',
            $data['timestamp'],
            new DateTimeZone('UTC'),
        );
    }

    public function save($updateTimestamp = true)
    {
        $app = Application::getFacadeApplication();
        /** @var EventDispatcher $eventDispatcher */
        $eventDispatcher = $app->make(EventDispatcher::class);

        if ($updateTimestamp) {
            $this->timestamp = new \DateTimeImmutable('now', new DateTimeZone('UTC'));
        }
        $event = new BeforeAssignCommunityPoints();
        $event->setEntry($this);
        $eventDispatcher->dispatch('on_before_assign_community_points', $event);

        $db = Loader::db();
        if ($this->upID) {
            $db->update(
                'UserPointHistory',
                array(
                    'upID' => $this->upID,
                    'upuID' => $this->upuID,
                    'upaID' => $this->upaID,
                    'object' => $this->object,
                    'upPoints' => $this->upPoints,
                    'timestamp' => $this->timestamp->format('Y-m-d H:i:s'),
                ),
                array('upID' => $this->upID),
            );
        } else {
            $db->insert('UserPointHistory', array(
                'upID' => $this->upID,
                'upuID' => $this->upuID,
                'object' => $this->object,
                'upaID' => $this->upaID,
                'upPoints' => $this->upPoints,
                'timestamp' => $this->timestamp->format('Y-m-d H:i:s'),
            ));
        }

        $event = new AfterAssignCommunityPoints();
        $event->setEntry($this);
        $eventDispatcher->dispatch('on_after_assign_community_points', $event);
    }

    public function delete()
    {
        $db = Loader::db();
        $db->delete('UserPointHistory', array('upID' => $this->upID));
    }

    public function getUserPointEntryID()
    {
        return $this->upID;
    }

    public function getUserPointEntryActionID()
    {
        return $this->upaID;
    }

    public function getUserPointEntryActionObject()
    {
        return UserPointAction::getByID($this->getUserPointEntryActionID());
    }

    public function getUserPointEntryValue()
    {
        return $this->upPoints;
    }

    public function getUserPointEntryTimestamp()
    {
        return $this->timestamp;
    } // - returns unix timestamp stored in the timestamp column

    public function getUserPointEntryDateTime()
    {
        return $this->timestamp;
    } // - returns it in a nicely formatted way

    public function getUserPointEntryUserID()
    {
        return $this->upuID;
    }

    public function getUserPointEntryDescriptionObject()
    {
        return unserialize($this->object);
    }

    public function setUserPointEntryDescriptionObject(ActionDescription $obj): self
    {
        $this->object = serialize($obj);
        return $this;
    }

    public function getUserPointEntryDescription()
    {
        if ($this->object) {
            $obj = unserialize($this->object);

            return $obj->getUserPointActionDescription();
        }
    }

    public function getUserPointEntryUserObject()
    {
        $ui = UserInfo::getByID($this->upuID);

        return $ui;
    }

    public static function getTotal($ui)
    {
        $db = Loader::db();
        $cnt = $db->GetOne('select sum(upPoints) as total from UserPointHistory where upuID = ?', array($ui->getUserID()));

        return $cnt;
    }

    public static function createFromArray(array $data): self
    {
        $upa = new self();
        $upa->setDataFromArray($data);
        return $upa;
    }
}
