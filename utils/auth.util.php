<?php
declare(strict_types=1);

include_once UTILS_PATH . "/envSetter.util.php";

class Auth
{
    /**
     * Initialize session if not already started
     * 
     * @return void
     */
    public static function init(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Attempt login; returns true if successful
     * 
     * @param PDO       $pdo        Used to check for existing users
     * @param string    $username   Key for user lookup
     * @param string    $password   User's password
     * @return bool                 True if login successful, false otherwise
     */
    public static function login(PDO $pdo, string $username, string $password): bool
    {
        try {
            // 1) Fetch the user record
            $stmt = $pdo->prepare("
                SELECT
                u.id,
                u.first_name,
                u.last_name,
                u.username,
                u.password,
                u.role,
                i.filepath AS profile_image_path
                FROM public.\"users\" u
                LEFT JOIN public.images i
                ON u.profile_image_id = i.id
                WHERE u.username = :username
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
            'username' => $user['username'],
            'role' => $user['role'],
            'profile_image_path' => $user['profile_image_path'] ?? null,
        ];
        error_log("[Auth::login] Login successful for user_id={$user['id']}");

        return true;
    }

    /**
     * Returns the currently logged-in user, or null if not logged in
     * 
     * @return array|null   User data if logged in, null otherwise
     */
    public static function user(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    /**
     * Check if a user is logged in
     * 
     * @return bool   True if logged in, false otherwise
     */
    public static function check(): bool
    {
        return isset($_SESSION['user']);
    }

    /**
     * Log out the current user
     * 
     * @return void
     */
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
