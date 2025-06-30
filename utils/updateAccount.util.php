<?php
declare(strict_types=1);

include_once UTILS_PATH . '/envSetter.util.php';
include_once UTILS_PATH . '/auth.util.php';

class UpdateAccount
{
    /**
     * Validate the raw input for updating an account.
     *
     * @param PDO   $pdo    Used to check for existing usernames
     * @param array $data   Keys: first_name, middle_name, last_name, username, password
     * @return string[]     List of validation error messages
     */
    public static function validate(PDO $pdo, array $data): array
    {
        $errors = [];

        // Trim inputs
        $first_name = trim($data['first_name'] ?? '');
        $middle_name = trim($data['middle_name'] ?? '');
        $last_name = trim($data['last_name'] ?? '');
        $username = trim($data['username'] ?? '');
        $password = $data['password'] ?? '';

        // 1) Required fields
        if ($first_name === '') {
            $errors[] = 'First name is required.';
        }
        if ($last_name === '') {
            $errors[] = 'Last name is required.';
        }
        if ($username === '') {
            $errors[] = 'Username is required.';
        }

        // 2) Username uniqueness (excluding current user)
        $currentUser = Auth::user();
        if ($username !== '' && $currentUser) {
            $stmt = $pdo->prepare("
                SELECT COUNT(*) FROM public.\"users\"
                WHERE username = :username
                  AND id <> :current_id
            ");
            $stmt->execute([
                ':username' => $username,
                ':current_id' => $currentUser['id'],
            ]);
            if ($stmt->fetchColumn() > 0) {
                $errors[] = 'Username already taken by another user.';
            }
        }

        // 3) Password (optional) — if provided, enforce policy
        if ($password !== '') {
            $pwLen = strlen($password);
            if (
                $pwLen < 8
                || !preg_match('/[A-Z]/', $password)
                || !preg_match('/[a-z]/', $password)
                || !preg_match('/\d/', $password)
                || !preg_match('/\W/', $password)
            ) {
                $errors[] = 'New password must be at least 8 characters and include uppercase, lowercase, number, and special character.';
            }
        }

        return $errors;
    }

    /**
     * Apply the updates to the current user’s record.
     *
     * @param PDO   $pdo
     * @param array $data   Same keys as validate()
     * @return void
     * @throws PDOException on SQL error
     */
    public static function apply(PDO $pdo, array $data): void
    {
        $currentUser = Auth::user();
        if (!$currentUser) {
            throw new RuntimeException('No authenticated user.');
        }
        $fields = [];
        $params = [':id' => $currentUser['id']];

        // Always update names & username
        $fields[] = 'first_name = :first';
        $params[':first'] = trim($data['first_name']);
        $fields[] = 'middle_name = :middle';
        $params[':middle'] = trim($data['middle_name']) !== ''
            ? trim($data['middle_name'])
            : null;
        $fields[] = 'last_name = :last';
        $params[':last'] = trim($data['last_name']);
        $fields[] = 'username = :username';
        $params[':username'] = trim($data['username']);

        // If a new password was provided, hash & update it
        if (trim($data['password']) !== '') {
            $fields[] = 'password = :password';
            $params[':password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        // Build the SET clause
        $setClause = implode(",\n    ", $fields);

        $sql = "
            UPDATE public.\"users\"
               SET {$setClause}
             WHERE id = :id
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
    }
}
