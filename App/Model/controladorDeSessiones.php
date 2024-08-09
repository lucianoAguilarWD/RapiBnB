<?php
class ControladorDeSessiones
{
    private $session;
    private $usuarios;
    private $administradores;
    private $verificados;

    public function __construct($session, $connect)
    {
        $this->session = $session;
        $this->usuarios = new Orm('usuarioID', 'usuarios', $connect);
        $this->administradores = new Orm('administradorID', 'administrador', $connect);
        $this->verificados = new Orm('verificacionID', 'verificacion_cuenta', $connect);
    }

    public function ID()
    {
        if ($this->session->exists()) {
            if ($this->Roll() === "usuarioLog") {
                $users = $this->usuarios->getAll();
                foreach ($users as $user) {
                    if ($user['nombreUsuario'] === $this->session->getCurrentUser()) {
                        return $user['usuarioID'];
                    }
                }
            } elseif ($this->Roll() === "admin") {
                $admins = $this->administradores->getAll();
                foreach ($admins as $admin) {
                    if ($admin['nombreUsuario'] === $this->session->getCurrentUser()) {
                        return $admin['administradorID'];
                    }
                }
            }
        }

        return 0;
    }

    public function estaConectado()
    {
        if ($this->session->exists()) {
            return true;
        } else {
            return false;
        }
    }

    public function Roll()
    {
        if ($this->session->exists()) {
            $users = $this->usuarios->getAll();
            foreach ($users as $user) {
                if ($user['nombreUsuario'] === $this->session->getCurrentUser()) {
                    return LOG;
                }
            }
            $admins = $this->administradores->getAll();
            foreach ($admins as $admin) {
                if ($admin['nombreUsuario'] === $this->session->getCurrentUser()) {
                    return ADMIN;
                }
            }
        }

        return NO_LOG;
    }
    // Orm('verificadoID','verificacion_cuenta',$connect);

    public function esVerificado()
    {
        if ($this->session->exists()) {
            $user = $this->usuarios->getById($this->ID());

            if ($user) {
                $verificado = $this->verificados->buscarRegistrosRelacionados('usuarios', 'usuarioID', 'usuarioPropuestaID', $user['usuarioID']);
                if (!empty($verificado)) {
                    return true;
                } else {
                    return null;
                }
            }
        }
        // false si no es verificado
        return false;
    }


    public function usuariosVerificados()
    {
        if ($this->session->exists()) {
            $users = $this->usuarios->busquedaForanea('verificacion_cuenta', 'usuarioPropuestaID', 'usuarioID');

            if ($users) {
                return $users; // Devolver el array de usuarios verificados
            } else {
                return null; // Devolver null para indicar que no hay usuarios verificados
            }
        }

        return false; // Devolver false si la sesión no existe
    }


    public function inicioSesion($nombreUsuario, $contrasena)
    {
        if ($this->session->exists()) {
            return "Ya has iniciado sesión.";
        }

        $usuarios = $this->usuarios->getAll();
        foreach ($usuarios as $usuario) {
            if ($usuario['nombreUsuario'] === $nombreUsuario) {
                if (password_verify($contrasena, $usuario['contrasena'])) {
                    $this->session->setCurrentUser($nombreUsuario);
                    return "Sesión iniciada correctamente.";
                } else {
                    return "Error: Contraseña incorrecta.";
                }
            }
        }

        $administradores = $this->administradores->getAll();
        foreach ($administradores as $admin) {
            if ($admin['nombreUsuario'] === $nombreUsuario) {
                if (password_verify($contrasena, $admin['contrasena'])) {
                    $this->session->setCurrentUser($nombreUsuario);
                    return "Sesión iniciada correctamente.";
                } else {
                    return "Error: Contraseña incorrecta.";
                }
            }
        }

        return "Error: El usuario no fue encontrado.";
    }

    public function cerrarSesion()
    {
        $this->session->closeSession();
    }
}
