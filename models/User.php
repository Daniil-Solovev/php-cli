<?php

class User
{
    /**
     * @param $db
     * @param array $users
     * @return array|bool
     */
    public function saveUser($db, $users = [])
    {
        $affected_rows = [];

        foreach ($users as $user) {
            $git_id = $user['id'];
            $git_login = $user['login'];

            if ($this->getUserByGitId($db, $git_id) !== false) {
                $sql = "UPDATE user SET github_login = :github_login WHERE github_id = :github_id";
            } else {
                $sql = "INSERT INTO user (github_id, github_login) VALUES (:github_id, :github_login)";
            }

            $result = $db->prepare($sql);
            $result->bindValue(':github_id', $git_id, PDO::PARAM_INT);
            $result->bindValue(':github_login', $git_login, PDO::PARAM_STR);
            $result->execute();
            if ($result->rowCount() === 1) {
                $affected_rows[] = $user['id'];
            }
        }
        if (!empty($affected_rows)) {
            return $affected_rows;
        }
        return false;
    }

    /**
     * @param $db
     * @param $git_id
     * @return mixed
     */
    private function getUserByGitId($db, $git_id)
    {
        $sql = "SELECT * FROM user WHERE github_id = :github_id";
        $result = $db->prepare($sql);
        $result->bindParam(':github_id', $git_id, PDO::PARAM_INT);
        $result->execute();
        return $result->fetch();

    }
}