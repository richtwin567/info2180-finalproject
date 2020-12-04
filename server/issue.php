<?php
session_start();
include_once("user.php");

class Issue
{
    private $id;
    private $title;
    private $description;
    private $type;
    private $priority;
    private $status;
    private $assigned_to;
    private $created_by;
    private $created;
    private $updated;

    public function __construct($id = null, $title, $description, $type, $priority, $status = null, $assigned_to, $created_by = null, $created = null, $updated = null)
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->type = $type;
        $this->priority = $priority;
        if ($status == null) {
            $this->status = "Open";
        } else {
            $this->status = $status;
        }
        $this->assigned_to = $assigned_to;
        if ($created_by == null) {
            $this->created_by = (unserialize($_SESSION["user"]))->getID();
        } else {
            $this->created_by = $created_by;
        }
        if ($created == null) {
            $this->created = date("Y-m-d H:i:s");
        } else {
            $this->created = $created;
        }
        if ($updated) {
            $this->updated = date("Y-m-d H:i:s");
        } else {
            $this->updated = $updated;
        }
    }

    public function getID()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getPriority()
    {
        return $this->priority;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getAssignedTo()
    {
        return $this->assigned_to;
    }

    public function getCreatedBy()
    {
        return $this->created_by;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function getUpdated()
    {
        return $this->updated;
    }

    public function setUpdated()
    {
        $this->updated = date("Y-m-d H:i:s");
    }

    public function toJSON()
    {
        return get_object_vars($this);
    }
    
    public function __serialize()
    {
        return array(
            'id' => $this->getID(),
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
            'type' => $this->getType(),
            'priority' => $this->getPriority(),
            'status' => $this->getStatus(),
            'assigned_to' => $this->getAssignedTo(),
            'created_by' => $this->getCreated(),
            'created' => $this->getCreated(),
            'updated' => $this->getUpdated()
        );
    }

    public function __unserialize($data)
    {
        $this->id = $data["id"];
        $this->title = $data["title"];
        $this->description = $data["description"];
        $this->type = $data["type"];
        $this->priority = $data["priority"];
        $this->status = $data["status"];
        $this->assigned_to = $data["assigned_to"];
        $this->created_by = $data["created_by"];
        $this->created = $data["created"];
        $this->updated = $data["updated"];
    }
}
