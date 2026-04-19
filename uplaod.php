<?php
// 1. הגדרות
$target_dir = "";
$message = "";
$correct_password = "dwr8khux"; // שנה את הסיסמה כאן

if (!is_dir($target_dir)) {
    mkdir($target_dir, 0755, true);
}

// 2. עיבוד הטופס
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // בדיקת סיסמה
    if (!isset($_POST['password']) || $_POST['password'] !== $correct_password) {
        $message = "שגיאה: סיסמה לא נכונה.";
    } 
    // בדיקה אם נשלחו קבצים
    elseif (isset($_FILES["filesToUpload"])) {
        
        $files = $_FILES["filesToUpload"];
        $file_count = count($files['name']);
        $uploaded_count = 0;
        $errors = [];

        // לולאה שעוברת על כל הקבצים שנבחרו
        for ($i = 0; $i < $file_count; $i++) {
            $file_name = basename($files["name"][$i]);
            $target_file = $target_dir . $file_name;
            $uploadOk = 1;

            // דילוג על שדות ריקים
            if (empty($file_name)) continue;

            // בדיקת תקינות בסיסית לכל קובץ
            

            // העלאה
            if ($uploadOk == 1) {
                if (move_uploaded_file($files["tmp_name"][$i], $target_file)) {
                    $uploaded_count++;
                } else {
                    $errors[] = "תקלה בהעלאת '$file_name'.";
                }
            }
        }

        $message = "הועלו $uploaded_count מתוך $file_count קבצים.";
        if (!empty($errors)) {
            $message .= "<br>שגיאות: " . implode(", ", $errors);
        }
    }
}
?>

<!DOCTYPE html>
<html dir="rtl" lang="he">
<head>
    <meta charset="UTF-8">
    <title>העלאת מספר קבצים</title>
</head>
<body>
    <h2>מעלה קבצים מרובים (מוגן בסיסמה)</h2>
    
    <?php if ($message): ?>
        <p><strong><?php echo $message; ?></strong></p>
    <?php endif; ?>

    <form action="upload.php" method="post" enctype="multipart/form-data">
        <p>
            <label>סיסמה:</label><br>
            <input type="password" name="password" required>
        </p>
        <p>
            <label>בחר קבצים (ניתן לבחור כמה):</label><br>
            <!-- שים לב ל-[] בשם ולתכונה multiple -->
            <input type="file" name="filesToUpload[]" id="filesToUpload" multiple required>
        </p>
        <input type="submit" value="העלה הכל" name="submit">
    </form>
</body>
</html>
