<?php
require 'config.php';

// only admin
if (empty($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$infoMessage  = '';
$errorMessage = '';

// ----------------- HANDLE POST ACTIONS ----------------- //
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // new manager create
    if ($action === 'create_manager') {
        $name     = trim($_POST['name'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $phone    = trim($_POST['phone'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($name === '' || $email === '' || $password === '') {
            $errorMessage = 'Name, email and password are required.';
        } else {
            // email already exists?
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $errorMessage = 'This email is already used by another account.';
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("
                    INSERT INTO users (name, email, phone, password, role)
                    VALUES (?, ?, ?, ?, 'manager')
                ");
                $stmt->execute([$name, $email, $phone, $hash]);
                $infoMessage = 'New manager account created successfully.';
            }
        }
    }

    // role change (make manager / make admin / make customer)
    if ($action === 'change_role') {
        $userId  = (int)($_POST['user_id'] ?? 0);
        $newRole = $_POST['new_role'] ?? '';

        if ($userId > 0 && in_array($newRole, ['admin', 'manager', 'customer'], true)) {
            // nijeke customer/manager banaleo problem nai, sudhu delete hobe na
            $stmt = $pdo->prepare("UPDATE users SET role = ? WHERE id = ?");
            $stmt->execute([$newRole, $userId]);
            $infoMessage = 'User role updated.';
        }
    }

    // user delete
    if ($action === 'delete_user') {
        $userId = (int)($_POST['user_id'] ?? 0);

        if ($userId === (int)$_SESSION['user_id']) {
            $errorMessage = "You cannot delete your own admin account.";
        } elseif ($userId > 0) {
            // bookings thakle FK constraint thakai delete nai hote pare – basic demo, just try
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$userId]);
            $infoMessage = 'User deleted.';
        }
    }
}

// ----------------- FILTER / COUNTS ----------------- //
$filter = $_GET['role'] ?? 'all';   // all | customers | managers

// counts for tabs
$totalAll       = (int)$pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$totalCustomers = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE role = 'customer'")->fetchColumn();
$totalManagers  = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE role = 'manager'")->fetchColumn();

$whereSql = '';
$titleSuffix = '';

if ($filter === 'customers') {
    $whereSql = "WHERE role = 'customer'";
    $titleSuffix = ' – Customers';
} elseif ($filter === 'managers') {
    $whereSql = "WHERE role = 'manager'";
    $titleSuffix = ' – Managers';
}

$stmtUsers = $pdo->query("
    SELECT *
    FROM users
    $whereSql
    ORDER BY created_at DESC
");
$users = $stmtUsers->fetchAll(PDO::FETCH_ASSOC);

include 'header.php';
?>

<link rel="stylesheet" href="css/admin_user_style.css">

<div class="admin-users-page">
    <h2>Manage Users &amp; Managers<?php echo htmlspecialchars($titleSuffix); ?></h2>
    <p class="admin-subtitle">
        Promote customers to managers, add new managers and clean up inactive accounts.
    </p>

    <div class="admin-users-layout">
        <!-- LEFT: Add Manager -->
        <div class="admin-users-sidebar">
            <h3>Add New Manager</h3>
            <p class="admin-sidebar-text">
                Create a manager account with direct access to manager dashboard.
            </p>

            <?php if ($errorMessage): ?>
                <div class="message error"><?php echo htmlspecialchars($errorMessage); ?></div>
            <?php elseif ($infoMessage): ?>
                <div class="message success"><?php echo htmlspecialchars($infoMessage); ?></div>
            <?php endif; ?>

            <form method="post">
                <input type="hidden" name="action" value="create_manager">

                <label>Full Name</label>
                <input type="text" name="name" required>

                <label>Email</label>
                <input type="email" name="email" required>

                <label>Phone</label>
                <input type="text" name="phone" placeholder="Optional">

                <label>Password</label>
                <input type="password" name="password" required>

                <button type="submit" class="btn btn-primary btn-full">Create Manager</button>
            </form>
        </div>

        <!-- RIGHT: Users table -->
        <div class="admin-users-tablewrap">
            <div class="admin-users-header">
                <div>
                    <h3>All Users</h3>
                    <p class="admin-table-subtitle">
                        Total users:
                        <strong><?php echo $totalAll; ?></strong>
                    </p>
                </div>

                <!-- filter tabs -->
                <div class="admin-users-tabs">
                    <a href="admin_users.php?role=all"
                       class="admin-users-tab <?php echo $filter === 'all' ? 'active' : ''; ?>">
                        All (<?php echo $totalAll; ?>)
                    </a>
                    <a href="admin_users.php?role=customers"
                       class="admin-users-tab <?php echo $filter === 'customers' ? 'active' : ''; ?>">
                        Customers (<?php echo $totalCustomers; ?>)
                    </a>
                    <a href="admin_users.php?role=managers"
                       class="admin-users-tab <?php echo $filter === 'managers' ? 'active' : ''; ?>">
                        Managers (<?php echo $totalManagers; ?>)
                    </a>
                </div>
            </div>

            <div class="table-wrapper">
                <table class="admin-table">
                    <tr>
                        <th>#</th>
                        <th>Name &amp; Email</th>
                        <th>Phone</th>
                        <th>Role</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                    <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="6">No users found for this filter.</td>
                        </tr>
                    <?php else: ?>
                        <?php $i = 1; ?>
                        <?php foreach ($users as $u): ?>
                            <tr>
                                <td><?php echo $i++; ?></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($u['name']); ?></strong><br>
                                    <span class="subtext"><?php echo htmlspecialchars($u['email']); ?></span>
                                </td>
                                <td><?php echo htmlspecialchars($u['phone'] ?? ''); ?></td>
                                <td>
                                    <?php
                                    $role = $u['role'];
                                    $roleLabel = ucfirst($role);
                                    $roleClass = 'badge-role-' . $role;
                                    ?>
                                    <span class="badge badge-role <?php echo $roleClass; ?>">
                                        <?php echo htmlspecialchars($roleLabel); ?>
                                    </span>
                                </td>
                                <td><?php echo htmlspecialchars($u['created_at']); ?></td>
                                <td>
                                    <div class="admin-user-actions">
                                        <?php if ($u['role'] !== 'manager'): ?>
                                            <form method="post" class="inline-form">
                                                <input type="hidden" name="action" value="change_role">
                                                <input type="hidden" name="user_id" value="<?php echo (int)$u['id']; ?>">
                                                <input type="hidden" name="new_role" value="manager">
                                                <button type="submit" class="btn-pill btn-green"
                                                        data-confirm="Make this user a manager?">
                                                    Make Manager
                                                </button>
                                            </form>
                                        <?php endif; ?>

                                        <?php if ($u['role'] !== 'admin'): ?>
                                            <form method="post" class="inline-form">
                                                <input type="hidden" name="action" value="change_role">
                                                <input type="hidden" name="user_id" value="<?php echo (int)$u['id']; ?>">
                                                <input type="hidden" name="new_role" value="admin">
                                                <button type="submit" class="btn-pill btn-blue"
                                                        data-confirm="Give admin access to this user?">
                                                    Make Admin
                                                </button>
                                            </form>
                                        <?php endif; ?>

                                        <?php if ($u['id'] !== (int)$_SESSION['user_id']): ?>
                                            <form method="post" class="inline-form">
                                                <input type="hidden" name="action" value="delete_user">
                                                <input type="hidden" name="user_id" value="<?php echo (int)$u['id']; ?>">
                                                <button type="submit" class="btn-pill btn-red"
                                                        data-confirm="Delete this user? This cannot be undone.">
                                                    Delete
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
