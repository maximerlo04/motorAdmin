<?php
session_start(); // Debe ser lo primero
include("../connection.php");


// Validación básica
if (!isset($_POST["email"]) || !isset($_POST["password"])) {
    die(json_encode(["error" => "Datos incompletos"]));
}

$email = mysqli_real_escape_string($connection, trim($_POST["email"]));
$password = mysqli_real_escape_string($connection, $_POST["password"]);

// Consulta preparada para mayor seguridad
$stmt = mysqli_prepare($connection, "SELECT * FROM usuarios WHERE email = ?");
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result) {
    die(json_encode(["error" => "Error en la consulta al servidor"]));
}

$user_data = mysqli_fetch_assoc($result);

if ($user_data && password_verify($password, $user_data['contrasenia'])) {
    $_SESSION['valid'] = $user_data['rol'];
    $_SESSION['nombre'] = $user_data['nombre'];
    $_SESSION['apellido'] = $user_data['apellido'];
    $_SESSION['telefono'] = $user_data['telefono'];
    $_SESSION['email'] = $user_data['email'];
    $_SESSION['rol'] = $user_data['rol'];
    $_SESSION['valid'] = $user_data['rol'];
    $_SESSION['id'] = $user_data['id'];

    switch($user_data['rol']) {
        case 'Cliente':
            header("Location: ../CLIENTE/inicio.php");
            break;
        case 'Empleado':
            header("Location: ../PAGES/resenia.php");
            break;
        case 'Admin':
            header("Location: ../ADMINISTRADOR/tabla-usuarios.php");
            break;
        default:
            header("Location: ../PAGES/inicio.php");
    }
    exit;
} else {
    header("Location: ../PAGES/index.php?login=1&error=1");
    exit;
}
?>
/*/header('Content-Type: application/json');
include("../connection.php");

$email = mysqli_real_escape_string($connection, $_POST["email"]);
$consulta=mysqli_query($connection, "SELECT * FROM usuarios where email='$email'");
$contrasenia = mysqli_real_escape_string($connection, $_POST["password"]);
$resultado=mysqli_num_rows($consulta);


    if($resultado!=0){
        $respuesta=mysqli_fetch_array($consulta);
	if(password_verify($contrasenia,$respuesta['contrasena'])){
        
	$_SESSION['valid'] = $respuesta['rol'];
	$_SESSION['nombre']=$respuesta['nombre'];
	$_SESSION['apellido']=$respuesta['apellido'];
		
		print_r( "Hola ".$respuesta['rol']." ".$_SESSION['apellido']."<br />");	
        if(isset($_SESSION['valid'])) {
        $rol=$_SESSION['valid'];
        switch($rol){
            case'Cliente':
                header("Location:../PAGES/turnos");
                breaK;
            case'Empleado':
                header("Location:../PAGES/resenia.php");
                breaK;
            case'Admin':
                header("Location:../ADMINISTRADOR/almacen-resenia.php");
                breaK;
        }
        header("Location:../PAGES/inicio.php");// ... PAGINA DEL USUARIO
    }
    }

}
    
   
    if(is_array($row) && !empty($row)){
        $_SESSION['valid'] = $row['rol'];
        echo  $_SESSION['valid'];
        //echo json_encode(['success' => true]);
    } else {
        //echo json_encode(['success' => false, 'message' => 'El email o contraseña son incorrectos']);
    }

    
    
*/
?>