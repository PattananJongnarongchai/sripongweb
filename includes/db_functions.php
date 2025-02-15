<?php
function getUserProfile($pdo, $userId) {
    $stmt = $pdo->prepare("
        SELECT u.*, 
            (SELECT COUNT(*) FROM points_transactions WHERE user_id = u.id) as total_transactions
        FROM users u 
        WHERE u.id = ?
    ");
    $stmt->execute([$userId]);
    return $stmt->fetch();
}

function getPointHistory($pdo, $userId, $limit = 10) {
    $stmt = $pdo->prepare("
        SELECT pt.*, 
            CASE 
                WHEN pt.transaction_type = 'earn' THEN 'ได้รับคะแนน'
                ELSE 'ใช้คะแนน'
            END as type_text
        FROM points_transactions pt
        WHERE pt.user_id = ? 
        ORDER BY pt.created_at DESC 
        LIMIT ?
    ");
    $stmt->execute([$userId, $limit]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function updateUserProfile($pdo, $userId, $name, $phone) {
    try {
        $pdo->beginTransaction();
        
        $stmt = $pdo->prepare("
            UPDATE users 
            SET name = ?, 
                phone = ?, 
                updated_at = CURRENT_TIMESTAMP 
            WHERE id = ?
        ");
        
        $success = $stmt->execute([$name, $phone, $userId]);
        
        if ($success) {
            $pdo->commit();
            return true;
        }
        
        $pdo->rollBack();
        return false;
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log("Update profile error: " . $e->getMessage());
        return false;
    }
}

function changeUserPassword($pdo, $userId, $currentPassword, $newPassword) {
    try {
        $pdo->beginTransaction();
        
        $stmt = $pdo->prepare("
            UPDATE users 
            SET password = ?, 
                updated_at = CURRENT_TIMESTAMP 
            WHERE id = ? 
            AND EXISTS (
                SELECT 1 FROM users 
                WHERE id = ? 
                AND password = ?
            )
        ");

        $hashed_password = password_hash($newPassword, PASSWORD_DEFAULT);
        $success = $stmt->execute([
            $hashed_password, 
            $userId,
            $userId,
            password_hash($currentPassword, PASSWORD_DEFAULT)
        ]);

        if ($success && $stmt->rowCount() > 0) {
            $pdo->commit();
            return true;
        }
        
        $pdo->rollBack();
        return false;
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log("Change password error: " . $e->getMessage());
        return false;
    }
} 