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
}