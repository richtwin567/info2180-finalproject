<?php
trait UpdateQueries{
    public function updateIssue($data, $query)
    {
        $issue = json_decode($this->getIssues($query),true);
        $issue = $issue[0];
        //echo var_dump($issue);
        $sql = "UPDATE `issues` SET";
        foreach ($issue as $key => $value) {
            if(isset($data[$key])){
                $issue[$key] = filter_var($data[$key],FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $sql = $sql." `$key` = '{$issue[$key]}',";
            }
        }
        $sql = substr($sql,0,-1);
        $sql = $this->buildQueryTail($sql,$query,"AND", "issues");
        $sql = $sql.";";
        $result= $this->conn->query($sql);
        if ($result === FALSE) {
            return null;
        } else {
            return TRUE;
        }

    }

}