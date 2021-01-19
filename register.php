<?php
include "config.php";

$username = $email = $password = $rpassword = "";
$username_err = $email_error = $password_err = $confirm_password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") :
    $usernameVal = $_POST['username'];
    $emailVal = $_POST['email'];
    $passwordVal = $_POST['password'];
    $rpasswordVal = $_POST['rpassword'];

    if (empty(trim($usernameVal))) :
        $username_err = "Por favor introduce un nombre de usuario.";
    else :
        $sql = "SELECT id FROM users WHERE username = '" . $usernameVal . "'";
        $query = $database->query($sql);
        if ($query->num_rows) :
            $username_err = "Este nombre de usuario ya está en uso.";
        else :
            $username = trim($usernameVal);
        endif;
    endif;
    if (empty(trim($emailVal))) :
        $username_err = "Por favor, introduce un email.";
    else :
        $sql = "SELECT id FROM users WHERE email = '" . $emailVal . "'";
        $query = $database->query($sql);
        if ($query->num_rows) :
            $email_error = "Este correo ya está en uso.";
        else :
            $email = trim($emailVal);
        endif;
    endif;
    
    if (empty(trim($passwordVal))) :
        $password_err = "Por favor, introduce una contraseña.";
    elseif (strlen(trim($passwordVal)) < 6) :
        $password_err = "La contraseña tiene que tener un mínimo de 6 carácteres.";
    else :
        $password = trim($passwordVal);
    endif;

    if (empty(trim($rpasswordVal))) :
        $confirm_password_err = "Por favor, confirma la contraseña.";
    else :
        $rpassword = trim($rpasswordVal);
        if (empty($password_err) && ($password != $rpassword)) :
            $confirm_password_err = "Las contraseñas no coinciden.";
        endif;
    endif;

    if (empty($username_err) && empty($password_err) && empty($confirm_password_err)) :

        $sql = "INSERT INTO users (username, password, email, joined) VALUES (?, ?, ?, now())";

        if ($stmt = $database->prepare($sql)) :
            $stmt->bind_param("sss", $param_username, $param_password, $param_email);

            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $param_email = $email;
            if ($stmt->execute()) :
                header("location: login.php");
            else :
                echo "Algo salió mal. Por favor, inténtalo más tarde.";
            endif;

            $stmt->close();
        endif;
    endif;
endif;
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Silent Azure - Regístrate</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <link rel="stylesheet" href="/css/index.css">
    <style>
        body {
            margin: 0;
            text-align: left;
        }
    </style>
</head>

<body>
    <div class="my-container" style="height: 100%">
        <div class="form-wrapper" style="width: 500px; height: fit-content; border: 1px solid black; margin: auto; padding: 20px; background-color: #444444; border-radius: 1em;">
            <div class="content-text">
                <h3 style="margin: 0;">Registrarse</h3><br>
            </div>
            <form method="POST">
                <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                    <label>
                        <div class="content-text">Nombre de usuario</div>
                    </label>
                    <input type="text" name="username" id="username" class="form-control" style="width: 60%;" value="<?php echo $username; ?>">
                    <span class="help-block"><?php echo $username_err; ?></span>
                </div>
                <div class="form-group <?php echo (!empty($email_error)) ? 'has-error' : ''; ?>">
                    <label>
                        <div class="content-text">Correo electrónico</div>
                    </label>
                    <input type="email" name="email" id="email" class="form-control" style="width: 60%;">
                    <span class="help-block"><?php echo $email_error; ?></span>
                </div>
                <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                    <label>
                        <div class="content-text">Contraseña</div>
                    </label>
                    <input type="password" name="password" id="password" class="form-control" style="width: 60%;" value="<?php echo $password; ?>">
                </div>
                <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                    <label>
                        <div class="content-text">Repite la contraseña</div>
                    </label>
                    <input type="password" name="rpassword" id="rpassword" class="form-control" style="width: 60%;" value="<?php echo $rpassword; ?>">
                </div>
                <div class="form-group">
                    <input type="submit" value="Registrarse" class="btn btn-primary" style="width: 130px;">
                    <input type="reset" class="btn btn-default" style="width: 130px;">
                </div>
                <p class="content-text">¿Ya tienes una cuenta? <a href="login.php">Inicia sesión</a></p>
            </form>
        </div>

    </div>
</body>
</html>