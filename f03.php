<?php
// 設定編碼，避免亂碼
header("Content-Type: text/html; charset=utf-8");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. 接收資料並去除空白
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $birthday = isset($_POST['birthday']) ? $_POST['birthday'] : '';
    $exp = isset($_POST['exp']) ? $_POST['exp'] : '';

    $errors = [];
    
    // 2. 檢查必填欄位
    if (empty($name) || empty($email) || empty($birthday)) {
        $errors[] = "所有欄位皆為必填。";
    }

    // 3. 檢查 Email 格式是否合法
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email 格式錯誤，請重新檢查。";
    }

    // 4. 計算年齡並檢查是否合法 (18-65 歲)
    $birthDate = new DateTime($birthday);
    $today = new DateTime();
    $age = $today->diff($birthDate)->y;

    if ($age < 18 || $age > 65) {
        $errors[] = "報名失敗：年齡需在 18 至 65 歲之間 (您目前為 $age 歲)。";
    }

    // --- 顯示結果介面 ---
    echo "<html><head><title>審核結果</title></head><body style='font-family: sans-serif; padding: 20px;'>";
    echo "<h1>報名審核結果</h1><hr>";

    if (!empty($errors)) {
        // 顯示錯誤訊息
        echo "<div style='color: red;'>";
        echo "<h3>抱歉，您的申請未通過：</h3>";
        echo "<ul>";
        foreach ($errors as $error) {
            echo "<li>" . htmlspecialchars($error) . "</li>";
        }
        echo "</ul>";
        echo "</div>";
        echo "<a href='f04.html'>返回修改資料</a>";
    } else {
        // 顯示成功訊息
        echo "<div style='color: green;'>";
        echo "<h3>恭喜您，審核通過！</h3>";
        echo "<p>姓名： " . htmlspecialchars($name) . "</p>";
        echo "<p>年齡： $age 歲</p>";
        echo "<p>我們已將確認信寄至： " . htmlspecialchars($email) . "</p>";
        
        if ($exp == "no") {
            echo "<p style='background: #fff3cd; padding: 10px; border: 1px solid #ffeeba;'>";
            echo "<strong>備註：</strong> 由於您選擇無開發經驗，我們將額外為您安排入門導覽課程。</p>";
        }
        echo "</div>";
        echo "<br><a href='f04.html'>再次填寫</a>";
    }
    echo "</body></html>";

} else {
    // 如果不是透過 POST 進入，引導回表單頁面
    header("Location: f03.html");
    exit;
}
?>