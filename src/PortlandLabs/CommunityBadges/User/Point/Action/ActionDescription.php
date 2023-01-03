<?php
namespace PortlandLabs\CommunityBadges\User\Point\Action;

class ActionDescription
{

    public $comments;
    
    public function setComments($comments)
    {
        $this->comments = $comments;
    }

    public function getComments()
    {
        return $this->comments;
    }

    public function getUserPointActionDescription()
    {
        return $this->getComments();
    }
}
