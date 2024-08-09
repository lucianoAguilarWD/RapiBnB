<?php
require_once(__DIR__ . '/../Model/controladorDeSessiones.php');
require_once(__DIR__ . '/../Model/usuario.php');
require_once(__DIR__ . '/../Model/intereses.php');
require_once(__DIR__ . '/../Model/documentacion.php');
require_once(__DIR__ . '/../Model/administrador.php');
require_once(__DIR__ . '/../Model/verificacion.php');

class UsuarioController extends Controller
{

    private $usuarioModel;
    private $userSessionControl;
    private $intereses;
    private $documentacion;
    private $verificacion;

    public function __construct($connect, $session)
    {
        $this->usuarioModel = new Usuario($connect);
        $this->intereses = new Intereses($connect);
        $this->documentacion = new documentacion($connect);
        $this->userSessionControl = new ControladorDeSessiones($session, $connect);
        $this->verificacion = new Verificacion($connect);
    }

    //---------------------------------------------muestra de perfil de usuario--------------------------------------------------//

    public function home()
    {
        if ($this->userSessionControl->Roll() === LOG) {
            $this->render('usuario', [], 'site');
        } else {
            header("Location: " . URL_PATH);
        }
    }

    public function table()
    {

        $result = new Result();
        $user = $this->userSessionControl->ID();
        $userVerificado = $this->userSessionControl->esVerificado();
        $usuario = $this->usuarioModel->getById($user);
        $intereses = $this->intereses->buscarRegistrosRelacionados('usuarios', 'usuarioID', 'userInteresesID', $user);
        // toca pasar el parametro de verificado
        if (empty($intereses)) {
            $result->success = true;
            $result->result = [
                'usuario' => $usuario,
                'esVerificado' => $userVerificado,
            ];
        } else {
            $result->success = true;
            $result->result = [
                'usuario' => $usuario,
                'intereses' => $intereses,
                'esVerificado' => $userVerificado,
            ];
        }
        echo json_encode($result);
    }

    //---------------------------------------------------------------------------------------------------------------------//

    //---------------------------------------------registro usuarios--------------------------------------------------//

    public function signUp()
    {
        if ($this->userSessionControl->Roll() === NO_LOG) {
            $this->render("UsuarioSignUp", [], "login");
        } else {
            header("Location: " . URL_PATH);
        }
    }

    public function create()
    {
        $result = new Result();

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $usuario = (isset($_POST['usuario'])) ? $_POST['usuario'] : '';
            $correo = (isset($_POST['correo'])) ? $_POST['correo'] : '';
            $contrasena = (isset($_POST['password'])) ? $_POST['password'] : '';
            $confirmarPw = (isset($_POST['passwordConfirm'])) ? $_POST['passwordConfirm'] : '';

            if ($usuario !== '' && $correo !== '' && $contrasena !== '' && $confirmarPw !== '') {
                $validacionContrasena = $this->validarContrasena($contrasena);

                if ($validacionContrasena === true) {
                    if ($contrasena === $confirmarPw) {
                        if (filter_var($correo, FILTER_VALIDATE_EMAIL)) {
                            // Verificar si el usuario o el correo ya existen
                            $existsUser = $this->usuarioModel->exists('nombreUsuario', $usuario);
                            $existsCorreo = $this->usuarioModel->exists('correo', $correo);

                            if (!$existsUser) {
                                if (!$existsCorreo) {
                                    try {
                                        $this->usuarioModel->insert([
                                            'nombreUsuario' => $usuario,
                                            'correo' => $correo,
                                            'contrasena' => password_hash($contrasena, PASSWORD_BCRYPT)
                                        ]);
                                        $mensaje = $this->userSessionControl->inicioSesion($usuario, $contrasena);

                                        switch ($mensaje) {
                                            case "Sesión iniciada correctamente.":
                                                $result->success = true;
                                                $result->message = "Cuenta y sesión iniciadas con éxito.";
                                                break;
                                            case "Error: El usuario no fue encontrado.":
                                                $result->success = false;
                                                $result->message = "Usuario no encontrado.";
                                                break;
                                            case "Error: Contraseña incorrecta.":
                                                $result->success = false;
                                                $result->message = "Contraseña incorrecta.";
                                                break;
                                            default:
                                                $result->success = false;
                                                $result->message = "Error desconocido: $mensaje"; // Agrega el mensaje real
                                        }
                                    } catch (Exception $error) {
                                        $result->success = false;
                                        $result->message = "Error al registrar usuario: " . $error->getMessage();
                                    }
                                } else {
                                    $result->success = false;
                                    $result->message = "El correo ya existe.";
                                }
                            } else {
                                $result->success = false;
                                $result->message = "El nombre de usuario ya existe.";
                            }
                        } else {
                            $result->success = false;
                            $result->message = "El correo no es válido.";
                        }
                    } else {
                        $result->success = false;
                        $result->message = "Las contraseñas no coinciden.";
                    }
                } else {
                    $result->success = false;
                    $result->message = "Contraseña no válida: " . $validacionContrasena;
                }
            } else {
                $result->success = false;
                $result->message = "Datos de registro incompletos.";
            }
        } else {
            $result->success = false;
            $result->message = "Solicitud inválida.";
        }
        echo json_encode($result);
    }


    function validarContrasena($contrasena)
    {
        // Longitud mínima
        if (strlen($contrasena) < 8) {
            return "La contraseña debe tener al menos 8 caracteres.";
        }

        // Longitud máxima
        if (strlen($contrasena) > 20) {
            return "La contraseña no puede tener más de 20 caracteres.";
        }

        // Uso de letras mayúsculas y minúsculas
        if (!preg_match('/[A-Z]/', $contrasena) || !preg_match('/[a-z]/', $contrasena)) {
            return "La contraseña debe incluir al menos una letra mayúscula y una letra minúscula.";
        }

        // Uso de números
        if (!preg_match('/[0-9]/', $contrasena)) {
            return "La contraseña debe incluir al menos un número.";
        }

        // Uso de caracteres especiales
        if (!preg_match('/[!@#\$%^&*()_+{}:;<>,.?~\-=|\\[\]]/', $contrasena)) {
            return "La contraseña debe incluir al menos un carácter especial.";
        }

        // No debe ser una contraseña común (puedes verificar contra una lista de contraseñas comunes aquí)

        return true;
    }
    //---------------------------------------------------------------------------------------------------------------------//

    //---------------------------------------------inicio sesion--------------------------------------------------//

    public function login()
    {
        if ($this->userSessionControl->Roll() === NO_LOG) {
            $this->render('usuarioLog', [], "login");
        } else {
            header("Location: " . URL_PATH);
        }
    }

    public function loginProcess()
    {

        $result = new Result();

        // Comprobar si los datos requeridos están presentes
        if (!isset($_POST['usuario']) || !isset($_POST['contrasena'])) {
            $result->success = false;
            $result->message = "Faltan datos requeridos.";
        } else {
            $mensaje = $this->userSessionControl->inicioSesion($_POST['usuario'], $_POST['contrasena']);
            switch ($mensaje) {
                case "Sesión iniciada correctamente.":
                    $result->success = true;
                    $result->message = "Sesión iniciada correctamente.";
                    break;
                case "Error: El usuario no fue encontrado.":
                    $result->success = false;
                    $result->message = "El usuario no fue encontrado.";
                    break;
                case "Error: Contraseña incorrecta.":
                    $result->success = false;
                    $result->message = "Contraseña incorrecta.";
                    break;
                default:
                    $result->success = false;
                    $result->message = "Error desconocido: $mensaje"; // Agrega el mensaje real
            }
        }

        echo json_encode($result);
    }
    //---------------------------------------------------------------------------------------------------------------------//

    //---------------------------------------------cerrar sesion--------------------------------------------------//

    public function LogOut()
    {
        if ($this->userSessionControl->Roll() === LOG || $this->userSessionControl->Roll() === ADMIN) {
            $this->userSessionControl->cerrarSesion();
            $this->home();
        } else {
            header("Location: " . URL_PATH);
        }
    }



    //---------------------------------------------------------------------------------------------------------------------//

    //---------------------------------------------editar perfil--------------------------------------------------//

    public function edit()
    {
        if ($this->userSessionControl->Roll() === LOG) {
            $id = $_GET['id'];
            $usuario = $this->usuarioModel->getById($id);
            $this->render('usuarioEdit', [
                'usuario' => $usuario,
            ], 'site');
        } else {
            header("Location:" . URL_PATH);
        }
    }

    public function update()
    {
        $result = new Result();

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $idUsuario = (isset($_POST['textID'])) ? $_POST['textID'] : null;
            $nombre = (isset($_POST['usuario'])) ? $_POST['usuario'] : null;
            $correo = (isset($_POST['correo'])) ? $_POST['correo'] : null;
            $nombreCompleto = (isset($_POST['nombre'])) ? $_POST['nombre'] : null;
            $bio = (isset($_POST['bio'])) ? $_POST['bio'] : null;
            $tel = (isset($_POST['telefono'])) ? $_POST['telefono'] : null;
            $userVerificado = $this->userSessionControl->esVerificado();

            $telValidacion = '';
            if ($tel !== '') {
                $telValidacion = $this->validarTelefono($tel);
            }

            $nombreValidacion = '';
            if ($nombreCompleto !== '') {
                $nombreValidacion = $this->validarSoloLetras($nombreCompleto);
            }


            if ($idUsuario !== null && is_numeric($idUsuario)) {
                $usuario = $this->usuarioModel->getById($idUsuario);

                if ($usuario) {
                    if ($telValidacion !== false || $telValidacion === '') {
                        if ($nombreValidacion !== false || $nombreValidacion === '') {
                            // Verificar si se subió una nueva foto y eliminar la foto anterior si existe
                            $foto = (isset($_FILES['fotoPerfil']['name'])) ? $_FILES['fotoPerfil']['name'] : "";
                            if ($foto !== "") {
                                if (isset($usuario['fotoRostro'])) {
                                    $fotoRostroPath =  'Assets/images/fotoPerfil/' . $usuario['fotoRostro'];

                                    if (file_exists($fotoRostroPath)) {
                                        unlink($fotoRostroPath);
                                    }
                                }

                                // Mover la nueva imagen a la carpeta de imágenes
                                $fecha_img = new DateTime();
                                $nombre_foto = $fecha_img->getTimestamp() . "_" . $foto;
                                $img_tmp = $_FILES['fotoPerfil']['tmp_name'];

                                if ($img_tmp !== "") {
                                    $destinationPath = 'Assets/images/fotoPerfil/' . $nombre_foto;
                                    move_uploaded_file($img_tmp, $destinationPath);
                                }

                                // Actualizar los datos del usuario con la nueva foto
                                $this->usuarioModel->updateById($idUsuario, [
                                    'nombreUsuario' => $nombre,
                                    'correo' => $correo,
                                    'nombreCompleto' => $nombreCompleto,
                                    'fotoRostro' => $nombre_foto,
                                    'bio' => $bio,
                                    'telefono' => $tel,
                                ]);
                            } else {
                                // No se subió una nueva foto, actualizar sin cambios en la foto
                                $this->usuarioModel->updateById($idUsuario, [
                                    'nombreUsuario' => $nombre,
                                    'correo' => $correo,
                                    'nombreCompleto' => $nombreCompleto,
                                    'bio' => $bio,
                                    'telefono' => $tel,
                                ]);
                            }

                            if ($userVerificado === true) {
                                $verificado = $this->verificacion->buscarRegistrosRelacionados('usuarios', 'usuarioID', 'usuarioPropuestaID', $idUsuario);
                                if ($verificado) {
                                    foreach ($verificado as $ver) {
                                        $this->verificacion->deleteById($ver['verificacionID']);
                                    }
                                }
                            }

                            $result->success = true;
                            $result->message = "Usuario modificado con éxito";
                        } else {
                            $result->success = false;
                            $result->message = "El nombre solo puede contener letras";
                        }
                    } else {
                        $result->success = false;
                        $result->message = "Telefono invalido";
                    }
                } else {
                    $result->success = false;
                    $result->message = "Usuario no encontrado";
                }
            } else {
                $result->success = false;
                $result->message = "ID de usuario no válido";
            }
        } else {
            $result->success = false;
            $result->message = "Solicitud no válida";
        }

        echo json_encode($result);
    }

    function validarTelefono($telefono)
    {
        // Eliminar espacios en blanco y guiones para facilitar la validación.
        $telefono = preg_replace('/[\s-]+/', '', $telefono);

        // Verificar si el número de teléfono tiene 10 dígitos
        if (preg_match('/^\d{10}$/', $telefono)) {
            return true; // Número de teléfono válido
        } else {
            return false; // Número de teléfono no válido
        }
    }
    function validarSoloLetras($cadena)
    {
        if (preg_match('/^[A-Za-z\'\- ]+$/', $cadena)) {
            return true;
        } else {
            return false;
        }
    }

    //---------------------------------------------------------------------------------------------------------------------//

    //---------------------------------------------intereses del usuario(teniendo en cuenta las etiquetas)--------------------------------------------------//

    public function interesesForm()
    {
        if ($this->userSessionControl->Roll() === LOG) {
            $user = $this->userSessionControl->ID();
            $interesPrev = $this->intereses->buscarRegistrosRelacionados('usuarios', 'usuarioID', 'userInteresesID', $user);
            $interesID = '';
            foreach ($interesPrev as $interes) {
                $interesID = $interes['interesID'];
            }
            $this->render('usuarioIntereses', [
                'intereses' => $interesID,
            ], "site");
        } else {
            header("Location: " . URL_PATH);
        }
    }

    public function intereses()
    {
        $result = new Result();
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // traemos la data del front
            $user = $this->userSessionControl->ID();
            if (!$user) {
                $result->success = false;
                $result->message = "Error de sesión";
                echo json_encode($result);
                return;
            }

            $id = (isset($_POST['textID'])) ? $_POST['textID'] : '';

            $ubicacion = (isset($_POST['ubicacion'])) ? implode("- ", $_POST['ubicacion']) : '';
            $etiqueta = (isset($_POST['etiqueta'])) ? implode(", ", $_POST['etiqueta']) : '';
            $listServicios = isset($_POST['servicios']) ? implode(", ", $_POST['servicios']) : '';

            // Verifica si los datos combinados no están vacíos antes de actualizar o insertar en la base de datos
            if ($ubicacion || $etiqueta || $listServicios) {
                $this->intereses->upsert($id, [
                    'ubicacion' => $ubicacion,
                    'etiquetas' => $etiqueta,
                    'listServicios' => $listServicios,
                    'userInteresesID' => $user,
                ]);
                $result->success = true;
                $result->message = "Datos cargados con éxito";
            } else {
                $result->success = false;
                $result->message = "Datos combinados vacíos. Por favor, seleccione al menos una opción. ";
            }
        } else {
            $result->success = false;
            $result->message = "Solicitud no válida";
        }
        echo json_encode($result);
    }
    //---------------------------------------------------------------------------------------------------------------------//

    //---------------------------------------------Envio de documentacion para verificar cuenta--------------------------------------------------//

    public function verificar()
    {
        //que no este verificado previamente y que no este en proceso
        if ($this->userSessionControl->Roll() === LOG) {
            $user = $this->userSessionControl->ID();
            $documentacion = $this->documentacion->buscarRegistrosRelacionados('usuarios', 'usuarioID', 'usarioAVerfID', $user);
            $documentacionID = '';
            foreach ($documentacion as $doc) {
                $documentacionID = $doc['certificacionID'];
            }
            if (empty($documentacion)/* && empty($verificado)*/) {
                $this->render('usuarioDocumentacion', [
                    'doc' => $documentacionID,
                ], 'site');
            } else {
                header("Location:" . URL_PATH . '/usuario/home');
            }
        } else {
            header("Location:" . URL_PATH);
        }
    }

    public function documentacion()
    {
        $result = new Result();

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $user = $this->userSessionControl->ID();

            if (!$user) {
                $result->success = false;
                $result->message = "Error de sesión";
            } else {
                $id = $_POST['textID'] ?? '';
                $documentacion = $this->documentacion->buscarRegistrosRelacionados('usuarios', 'usuarioID', 'usarioAVerfID', $user);
                $foto = $_FILES['documentacion']['name'] ?? "";

                // Función para mover la imagen a la carpeta de documentos
                function moveDocumento($newName)
                {
                    $img_tmp = $_FILES['documentacion']['tmp_name'];
                    $destinationPath = 'Assets/images/documentacion/' . $newName;
                    move_uploaded_file($img_tmp, $destinationPath);
                }

                $fecha_img = new DateTime();
                $nombre_foto = $fecha_img->getTimestamp() . "_$foto";
                $fechaVencimiento = new DateTime();
                $fechaVencimiento->modify('+2 days');
                $fechaFormateada = $fechaVencimiento->format('Y-m-d H:i:s');

                if (empty($documentacion)) {
                    moveDocumento($nombre_foto);
                    $this->documentacion->insert([
                        'documentoAdjunto' => $nombre_foto,
                        'fechaDeVencimiento' => $fechaFormateada,
                        'usarioAVerfID' => $user,
                    ]);
                    $docs = $this->documentacion->buscarRegistrosRelacionados('usuarios', 'usuarioID', 'usarioAVerfID', $user);
                    $idDoc = '';
                    foreach ($docs as $doc) {
                        $idDoc = $doc['certificacionID'];
                    }
                    $this->usuarioModel->updateById($user, [
                        'documentacionID' => $idDoc,
                    ]);
                }

                $result->success = true;
                $result->message = "Documento guardado exitosamente.";
            }
        } else {
            $result->success = false;
            $result->message = "Solicitud no válida";
        }

        echo json_encode($result);
    }

    //---------------------------------------------------------------------------------------------------------------------//

}
