<?php
declare(strict_types=1);

include_once UTILS_PATH . "/envSetter.util.php";

class Auth
{
    /** Call early in bootstrap or at top of each page */
    public static function init(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /** Attempt login; returns true if successful */
    public static function login(PDO $pdo, string $username, string $password): bool
    {
        try {
            // 1) Fetch the user record
            $stmt = $pdo->prepare("
                SELECT id, first_name, last_name, username, password, role
                FROM users
                WHERE username = :username
            ");
            $stmt->execute([':username' => $username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (\PDOException $e) {
            // Log any SQL errors
            error_log('[Auth::login] PDOException: ' . $e->getMessage());
            return false;
        }

        // Debug output: did we get a row?
        if (!$user) {
            error_log("[Auth::login] No user found for username='{$username}'");
            return false;
        } else {
            error_log('[Auth::login] Retrieved user: ' . var_export([
                'id' => $user['id'],
                'username' => $user['username'],
                'role' => $user['role'],
            ], true));
        }

        // 2) Verify password
        if (!password_verify($password, $user['password'])) {
            error_log("[Auth::login] Password mismatch for user_id={$user['id']}");
            return false;
        }

        // 3) Success: regenerate session & store user + role
        session_regenerate_id(true);
        $_SESSION['user'] = [
            'id' => $user['id'],
            'first_name' => $user['first_name'],
            'last_name' => $user['last_name'],
            'role' => $user['role'],
        ];
        error_log("[Auth::login] Login successful for user_id={$user['id']}");

        return true;
    }

    /** Returns current user data or null */
    public static function user(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    /** Returns true if someone is logged in */
    public static function check(): bool
    {
        return isset($_SESSION['user']);
    }

    /** Log out the current user */
    public static function logout(): void
    {
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        session_destroy();
    }
}
