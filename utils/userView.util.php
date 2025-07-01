<?php
declare(strict_types=1);

include_once UTILS_PATH . "/envSetter.util.php";

class UsersDatabase
{
    /**
     * View all users in the database.
     *
     * @param PDO $pdo The PDO connection to the database.
     * @return array|false Returns an array of users or false on failure.
     */
    public static function viewAll(PDO $pdo)
    {
        try {
            $stmt = $pdo->query('
                SELECT 
                    id,
                    first_name,
                    middle_name,
                    last_name,
                    username,
                    role
                FROM public."users"
                ORDER BY last_name, first_name
            ');
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $users;
        } catch (\PDOException $e) {
            // Log any SQL errors
            error_log('[UsersDatabase::viewAll] PDOException: ' . $e->getMessage());
            return false;
        }
    }
}