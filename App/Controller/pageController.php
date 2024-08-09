<?php
require_once(__DIR__ . '/../Model/controladorDeSessiones.php');
require_once(__DIR__ . '/../Model/usuario.php');
require_once(__DIR__ . '/../Model/reserva.php');
require_once(__DIR__ . '/../Model/ofertaAlquiler.php');
require_once(__DIR__ . '/../Model/intereses.php');
require_once(__DIR__ . '/../Model/aplicaOferta.php');

class PageController extends Controller
{

    private $userSessionControl;
    private $ofertas;
    private $usuarios;
    private $reservas;
    private $intereses;
    private $rentas;

    public function __construct($connect, $session)
    {
        $this->userSessionControl = new ControladorDeSessiones($session, $connect);
        $this->ofertas = new OfertaAlquiler($connect);
        $this->usuarios = new Usuario($connect);
        $this->reservas = new Reserva($connect);
        $this->intereses = new Intereses($connect);
        $this->rentas = new AplicaOferta($connect);
    }

    public function home()
    {
        if ($this->userSessionControl->Roll() === NO_LOG) {
            $this->render('home', [], 'noLog');
        } elseif ($this->userSessionControl->Roll() === LOG) {
            $this->render('home', [], 'site');
        } elseif ($this->userSessionControl->Roll() === ADMIN) {
            $this->render('administrador', [], 'admin');
        }
    }
    //--------------------------------------------Mostrar data----------------------------------------------------------------//

    /** 
     * las ofertas se deben mostrar en cards, que deben mostrar:
     * la oferta de manera resumida : fotos,titulo,ubicacion.
     * al darle click a la oferta se debe abrir un modal pantalla completa/o page nueva, que muestre el resto de la data de la oferta y las reseñas y respuestas hechas(si las tiene) y quienes la hicieron(foto perfil y nombre).
     * se va a trabaja en la page home tanto para usuarios logeados y para los no logeados.
     */
    public function listarOfertas()
    {
        //tienen que ser solo con estado:publicado y de users normales.
        $result = new Result();

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $page = (isset($_POST['pageNumber'])) ? $_POST['pageNumber'] : 1;

            $consulta = "WHERE estado = 'publicado' AND userVerificado = 'no' ";
            $ofertasPublicadas = $this->ofertas->paginationWithConditions($page, 4, '*', $consulta);
            $result->success = true;
            $result->result = $ofertasPublicadas;
            $result->message = $page;
        } else {
            $result->success = false;
            $result->message = 'Error: Solicitud no válida.';
        }
        echo json_encode($result);
    }



    public function listarOfertasVerificados()
    {
        // son las ofertas que deben aparecer de manera destacada
        $result = new Result();
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $page = (isset($_POST['pageNumber'])) ? $_POST['pageNumber'] : 1;

            $consulta = "WHERE estado = 'publicado' AND userVerificado = 'si' ";
            $ofertasPublicadas = $this->ofertas->paginationWithConditions($page, 4, '*', $consulta);
            $result->success = true;
            $result->result = $ofertasPublicadas;
            $result->message = $page;
        } else {
            $result->success = false;
            $result->message = 'Error: Solicitud no válida.';
        }

        echo json_encode($result);
    }

    public function listarRecomentaciones()
    {
        // son las que salen del pareo de intereses(usuario) y etiquetas(ofertas alquiler)
        $result = new Result();
        $userID = $this->userSessionControl->ID();
        $user = $this->usuarios->getById($userID);
        $interesesUser = $this->intereses->buscarRegistrosRelacionados('usuarios', 'usuarioID', 'userInteresesID', $userID);
        $intereses = '';
        if ($interesesUser) {

            foreach ($interesesUser as $interes) {
                $ubicaciones = $interes['ubicacion'];
                $etiquetas = $interes['etiquetas'];
                $servicios = $interes['listServicios'];
            }

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $page = (isset($_POST['pageNumber'])) ? $_POST['pageNumber'] : 1;
                $consulta = 'WHERE '; // Inicializa la consulta como una cadena vacía
                $condiciones = []; // Inicializa un array para las condiciones

                if (!empty($ubicaciones)) {
                    $ubicaciones = !empty($ubicaciones) ? explode('- ', $ubicaciones) : [];
                    $ubicaciones = array_map(function ($ubicacion) {
                        return "'" . $ubicacion . "'";
                    }, $ubicaciones);
                    $condiciones[] = 'ubicacion IN (' . implode('- ', $ubicaciones) . ')';
                }

                if (!empty($etiquetas)) {
                    $etiquetas = !empty($etiquetas) ? explode(', ', $etiquetas) : [];
                    $etiquetas = array_map(function ($etiqueta) {
                        return "'" . $etiqueta . "'";
                    }, $etiquetas);
                    $condiciones[] = 'etiquetas IN (' . implode(', ', $etiquetas) . ')';
                }

                if (!empty($servicios)) {
                    $servicios = !empty($servicios) ? explode(', ', $servicios) : [];
                    $servicios = array_map(function ($servicio) {
                        return "'" . $servicio . "'";
                    }, $servicios);
                    $condiciones[] = 'listServicios IN (' . implode(', ', $servicios) . ')';
                }

                if (!empty($condiciones)) {
                    $consulta .= implode(' OR ', $condiciones);
                }

                $ofertasPublicadas = $this->ofertas->paginationWithConditions($page, 4, '*', $consulta);



                $result->success = true;
                $result->result = $ofertasPublicadas;
                $result->message = $page;
            } else {
                $result->success = false;
                $result->message = 'Error: Solicitud no válida.';
            }
        } else {
            $result->success = false;
            $result->message = 'El usuario no tiene intereses cargados.';
        }

        echo json_encode($result);
    }
    //---------------------------------------------------------------------------------------------------------------------------//
    //------------------------------------------------Busquedas por palabras y por etiquetas--------------------------------------------------//

    public function buscar()
    {
        if ($this->userSessionControl->Roll() === NO_LOG) {
            $this->render('Buscador', [], 'noLog');
        } elseif ($this->userSessionControl->Roll() === LOG) {
            $this->render('Buscador', [], 'site');
        }
    }

    public function listarBusqueda()
    {
        $result = new Result();

        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $page = (isset($_POST['pageNumber'])) ? $_POST['pageNumber'] : 1;

            $texto = (isset($_POST['buscarPorTexto'])) ? $_POST['buscarPorTexto'] : '';
            $ubicacion = (isset($_POST['ubicacion'])) ? $_POST['ubicacion'] : '';
            $etiqueta = (isset($_POST['etiqueta'])) ? $_POST['etiqueta'] : '';
            $listServicios = isset($_POST['servicios']) ? $_POST['servicios'] : '';

            if ($texto === '' && $ubicacion === '' && $etiqueta === '' && $listServicios === '') {
                // aca si no hay busqueda hecha(tambien seria el por defecto)
                $consulta = '';
                $ofertasPublicadas = $this->ofertas->paginationWithConditions($page, 6, '*', $consulta);

                $result->success = true;
                $result->result = $ofertasPublicadas;
            } else {
                // aca va si hay alguna busqueda hecha
                $consulta = 'WHERE '; // Inicializa la consulta como una cadena vacía
                $condiciones = [];
                $condicionTextoCompleta = '';
                if (!empty($texto)) {
                    // Crear un array de condiciones para la búsqueda de texto
                    $condicionesTexto = [];

                    // Dividir el texto de búsqueda en palabras clave (por ejemplo, palabras separadas por espacios)
                    $palabrasClave = explode(' ', $texto);

                    // Iterar sobre las palabras clave y agregar condiciones para cada una
                    foreach ($palabrasClave as $palabra) {
                        // Utiliza el operador LIKE para buscar coincidencias en el título y la descripción
                        $condicionesTexto[] = "(titulo LIKE '%$palabra%' OR descripcion LIKE '%$palabra%')";
                    }

                    // Combina las condiciones con el operador OR para buscar cualquier coincidencia
                    $condicionTextoCompleta = implode(' OR ', $condicionesTexto);

                    // Agrega esta condición a la consulta general
                    $condiciones[] = "($condicionTextoCompleta)";
                }

                if (!empty($ubicacion)) {
                    $condiciones[] = "ubicacion IN ('$ubicacion')";
                }
                if (!empty($etiqueta)) {
                    $condiciones[] = "etiquetas IN ('$etiqueta')";
                }
                if (!empty($listServicios)) {
                    foreach ($listServicios as $list) {
                        $condiciones[] = "(listServicios LIKE '%$list%')";
                    }
                }


                if (!empty($condiciones)) {
                    $consulta .= implode(' AND ', $condiciones);
                }


                $ofertasPublicadas = $this->ofertas->paginationWithConditions($page, 10, '*', $consulta);
                $result->success = true;
                $result->result = $ofertasPublicadas;
                $result->message = $condicionTextoCompleta;
            }
        } else {
            $result->success = false;
            $result->message = 'Error: Solicitud no válida.';
        }

        echo json_encode($result);
    }
    //---------------------------------------------------------------------------------------------------------------------------//
    //---------------------------------------------------mostrar Oferta------------------------------------------------------------//
    public function oferta()
    {
        if ($this->userSessionControl->Roll() === NO_LOG) {
            $idOferta = $_GET['ofertaID'];
            $oferta = $this->ofertas->getById($idOferta);
            $rentasAOferta = $this->rentas->buscarRegistrosRelacionados('oferta_de_alquiler', 'ofertaID', 'ofertaAlquilerID', $oferta['ofertaID']);
            $rentasAE = [];
            foreach ($rentasAOferta as $renta) {
                if ($renta['estado'] === ESPERA_RENTA || $renta['estado'] === ACEPTADO) {
                    $rentasAE[] = $renta;
                }
            }

            $this->render('mostrarOferta', [
                'oferta' => $oferta,
                'rentas' => $rentasAE,
            ], 'noLog');
        } elseif ($this->userSessionControl->Roll() === LOG) {
            $idOferta = $_GET['ofertaID'];
            $oferta = $this->ofertas->getById($idOferta);
            $rentasAOferta = $this->rentas->buscarRegistrosRelacionados('oferta_de_alquiler', 'ofertaID', 'ofertaAlquilerID', $oferta['ofertaID']);
            $rentasAE = [];
            foreach ($rentasAOferta as $renta) {
                if ($renta['estado'] === ESPERA_RENTA || $renta['estado'] === ACEPTADO) {
                    $rentasAE[] = $renta;
                }
            }

            $this->render('mostrarOferta', [
                'oferta' => $oferta,
                'rentas' => $rentasAE,
            ], 'site');
        }
    }

    public function mostrarReservasOferta()
    {
        $result = new Result();

        $page = (isset($_GET['pageNumber'])) ? $_GET['pageNumber'] : 1;
        $ofertaID = (isset($_GET['id'])) ? $_GET['id'] : '';
        $result->message = $ofertaID;
        if ($ofertaID != '' && is_numeric($ofertaID)) {
            $consulta = "WHERE puntaje != '' AND ofertaAlquilerID = $ofertaID";
            $reservasOferta = $this->reservas->paginationWithConditions($page, 6, '*', $consulta);
            $userLog = $this->userSessionControl->estaConectado();
            if (count($reservasOferta['data']) > 0) {
                $reservaUsers = [];
                $users = $this->usuarios->getAll();
                foreach ($reservasOferta['data'] as $reserva) {
                    foreach ($users as $user) {
                        if ($reserva['autorID'] === $user['usuarioID']) {
                            $reservaUsers[] = [
                                'usuario' => $user,
                                'reserva' => $reserva,
                            ];
                        }
                    }
                }
                $result->success = true;
                $result->result = [
                    'info' => $reservaUsers,
                    'page' => $reservasOferta['page'],
                    'pages' => $reservasOferta['pages'],
                    'userLog' => $userLog,
                ];
            } else {
                $result->success = false;
                $result->result = $userLog;
                $result->message = 'no tiene reservas hechas o minimamente puntuadas.';
            }
        } else {
            $result->success = false;
            $result->message = 'Error: informacion invalida';
        }

        echo json_encode($result);
    }
    //---------------------------------------------------------------------------------------------------------------------------//
    //---------------------------------------------------------------------------------------------------------------------------//
}
