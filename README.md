# agile-scrum-daily-app

# Note: 

# 1)
# If you have some troubles with the daily and users table results you can replace the "if condition" string values with integer ones like this below.
# For example: use this one |if ($is_admin === 1)| -------------------- not this |if ($is_admin === "1")|

# 2)
# Also do not forget to replace the pdo.php file config parameters for production.

# 3)
# Register Form User Admin Status 
# !!!! If you got trouble with "Registration Process" "$is_admin = $_POST['is_admin'];" statement in class.php file (125)
# You can replace it with this one  => "$is_admin = intval($_POST['is_admin']);" 
