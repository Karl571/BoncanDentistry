<?php

class SQLConnection
{
    // === Database Configuration ===
    private $serverName = 'localhost';
    private $databaseName = 'boncan_db';
    private $username = 'root';
    private $password = '';
    private $connectionOptions;

    public function __construct()
    {
        // Standard MySQL PDO options
        $this->connectionOptions = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Fetch results as associative arrays
            PDO::ATTR_EMULATE_PREPARES => false, // Better security against SQL injection
        ];
    }

    private function getConnection()
    {
        try {
            // DSN for MySQL
            $dsn = "mysql:host={$this->serverName};dbname={$this->databaseName}";

            $conn = new PDO($dsn, $this->username, $this->password, $this->connectionOptions);

            return $conn;
        } catch (PDOException $e) {
            // Log the error
            error_log('Connection error: '.$e->getMessage());

            return null; // Return null on failure
        }
    }

    // --- Core Query Execution Methods ---
    private function executeSelectQuery(string $query, array $params = [])
    {
        $conn = $this->getConnection();
        if ($conn === null) {
            return null;
        }

        try {
            $stmt = $conn->prepare($query);
            $stmt->execute($params);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Select Query error: '.$e->getMessage().' Query: '.$query);

            return null;
        }
    }

    private function executeNonQuery(string $query, array $params = []): bool
    {
        $conn = $this->getConnection();
        if ($conn === null) {
            return false;
        }

        try {
            $stmt = $conn->prepare($query);

            return $stmt->execute($params);
        } catch (PDOException $e) {
            error_log('Non-Query error: '.$e->getMessage().' Query: '.$query);

            return false;
        }
    }

    // --- Account Maintenance Methods ---

    // Corresponds to db.GetRoles()
    public function GetRoles(): ?array
    {
        $query = 'SELECT role_id, role_name FROM role_tbl ORDER BY role_name';

        return $this->executeSelectQuery($query);
    }

    // Corresponds to db.GetAccountsByStatus(...)
    public function GetAccountsByStatus(string $status): ?array
    {
        $query = '
            SELECT 
                a.account_id, a.firstname, a.middlename, a.surname, 
                a.username, a.status, 
                r.role_id, r.role_name
            FROM account_tbl a
            INNER JOIN role_tbl r ON a.role_id = r.role_id
            WHERE a.status = :status
            ORDER BY a.surname, a.firstname';

        $params = [':status' => $status];

        return $this->executeSelectQuery($query, $params);
    }

    // Corresponds to db.CheckUsernameExists(...)
    public function CheckUsernameExists(string $username): bool
    {
        $query = 'SELECT COUNT(*) FROM account_tbl WHERE username = :username';
        $params = [':username' => $username];

        $result = $this->executeSelectQuery($query, $params);

        // Check if the result is valid and count is greater than 0
        return $result !== null && $result[0]['COUNT(*)'] > 0;
    }

    // Corresponds to db.AddAccount(...)
    public function AddAccount(string $firstname, ?string $middlename, string $surname, int $roleId, string $username, string $password): bool
    {
        $query = '
            INSERT INTO account_tbl 
                (firstname, middlename, surname, role_id, username, password, status)
            VALUES 
                (:firstname, :middlename, :surname, :roleId, :username, :password, "Active")';

        $params = [
            ':firstname' => $firstname,
            ':middlename' => $middlename,
            ':surname' => $surname,
            ':roleId' => $roleId,
            ':username' => $username,
            // WARNING: In a real app, use password_hash()!
            ':password' => $password,
        ];

        return $this->executeNonQuery($query, $params);
    }

    // Corresponds to db.UpdateAccountStatus(...)
    public function UpdateAccountStatus(int $accountId, string $newStatus): bool
    {
        $query = 'UPDATE account_tbl SET status = :status WHERE account_id = :id';
        $params = [
            ':status' => $newStatus,
            ':id' => $accountId,
        ];

        return $this->executeNonQuery($query, $params);
    }

    // --- Other Methods (for login, getmodule, etc.) ---

    // Corresponds to db.ValidateUser(...) in login.php
    public function validateUser(string $username, string $password): ?array
    {
        $query = '
            SELECT 
                a.firstname, a.middlename, a.surname, a.role_id, 
                r.role_name 
            FROM account_tbl a
            INNER JOIN role_tbl r ON a.role_id = r.role_id
            WHERE a.username = :username 
              AND a.password = :password 
              AND a.status = "Active"'; // Only allow Active accounts to log in

        $params = [
            ':username' => $username,
            ':password' => $password,
        ];

        $result = $this->executeSelectQuery($query, $params);

        if (empty($result)) {
            return null;
        }

        // Format the output similar to your C# logic
        $user = $result[0];
        $fullName = trim("{$user['firstname']} {$user['middlename']} {$user['surname']}");

        return [
            'FullName' => $fullName,
            'RoleId' => $user['role_id'],
            'RoleName' => $user['role_name'],
        ];
    }

    // Corresponds to db.GetModulesForRole(...) in getmodule.php
    public function getModulesForRole(int $roleId): ?array
    {
        $query = 'SELECT t2.module, t2.module_id 
                FROM access_tbl t1
                INNER JOIN module_tbl t2 ON t1.module_id = t2.module_id
                WHERE t1.role_id = :role_id
                ORDER BY t2.module';

        $params = [':role_id' => $roleId];

        return $this->executeSelectQuery($query, $params);
    }

    // ROLE MAINTENANCE

    // --- Add these methods to the SQLConnection class in connection.php ---

    // Corresponds to LoadModules() from C#
    public function GetModules(): ?array
    {
        $query = 'SELECT module_id, module FROM module_tbl ORDER BY module_id';

        // executeSelectQuery should return an array of associative arrays (or null on fail)
        return $this->executeSelectQuery($query);
    }

    // Corresponds to btnSave_Click_1 (Role and Access Insertion) from C#
    public function AddRoleAndAccess(string $roleName, array $moduleIds): bool
    {
        $conn = $this->getConnection();
        if ($conn === null) {
            return false;
        }

        // Start Transaction
        $conn->beginTransaction();

        try {
            // 1. Insert Role and get new ID
            $insertRole = 'INSERT INTO role_tbl (role_name) VALUES (:role_name)';
            $stmt = $conn->prepare($insertRole);
            $stmt->execute([':role_name' => $roleName]);
            $newRoleId = $conn->lastInsertId(); // Get the ID of the new role

            // 2. Insert Access records
            $insertAccess = 'INSERT INTO access_tbl (role_id, module_id) VALUES (:role_id, :module_id)';
            $stmtAccess = $conn->prepare($insertAccess);

            foreach ($moduleIds as $moduleId) {
                $stmtAccess->execute([
                    ':role_id' => $newRoleId,
                    ':module_id' => (int) $moduleId,
                ]);
            }

            // Commit transaction if all inserts succeed
            $conn->commit();

            return true;
        } catch (Exception $e) {
            // Rollback on any error
            if ($conn->inTransaction()) {
                $conn->rollBack();
            }
            error_log('Error saving role and access: '.$e->getMessage());

            return false;
        }
    }
}
