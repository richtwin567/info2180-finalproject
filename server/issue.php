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
    private $assigned_to_id;
    private $created_by;
    private $created;
    private $updated;

    public function __construct(
        $id = null,
        $title,
        $description,
        $type,
        $priority,
        $status = null,
        $assigned_to,
        $created_by = null,
        $created = null,
        $updated = null,
        $assigned_to_id = null
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->type = $type;
        $this->priority = $priority;
        if ($status == null) {
            $this->status = "OPEN";
        } else {
            $this->status = $status;
        }
        $this->assigned_to = $assigned_to;
        if ($created_by == null) {
            $this->created_by = (unserialize($_SESSION["user"]))->getID();
        } else {
            $this->created_by = $created_by;
        }
        $now = date("Y-m-d H:i:s");
        if ($created == null) {
            $this->created = $now;
        } else {
            $this->created = $created;
        }
        if ($updated == null) {
            $this->updated = $now;
        } else {
            $this->updated = $updated;
        }
        $this->assigned_to_id = $assigned_to_id;
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


    public function getAssignedToID()
    {
        return $this->assigned_to_id;
    }

    /**
     * Converts the issue attributes to an array that can be easily converted
     * to a JSON string with json_encode.
     * 
     * @return array 
     */
    public function toJSON()
    {
        return get_object_vars($this);
    }


    /**
     * Factory constructor for `Issue`. Sanitizes the data before creating the issue.
     * @param array $data An associative PHP array.
     * @return Issue
     */
    public static function buildAndSanitize($data)
    {
        $issue = new Issue(
            intval(filter_var(htmlspecialchars($data["id"]), FILTER_SANITIZE_NUMBER_INT)),
            filter_var($data["title"], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            filter_var($data["description"], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            filter_var($data["type"], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            filter_var($data["priority"], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            filter_var($data["status"], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            filter_var($data["assigned_to"], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            filter_var($data["created_by"], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            filter_var($data["created"], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            filter_var($data["updated"], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            intval(filter_var(htmlspecialchars($data["assigned_to_id"]), FILTER_SANITIZE_NUMBER_INT))
        );
        return $issue;
    }
}
