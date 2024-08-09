<?php
require_once(__DIR__ . '/../Model/controladorDeSessiones.php');
require_once(__DIR__ . '/../Model/ofertaAlquiler.php');
require_once(__DIR__ . '/../Model/usuario.php');
require_once(__DIR__ . '/../Model/reserva.php');
require_once(__DIR__ . '/../Model/aplicaOferta.php');

class RentasController extends Controller
{

    private $ofertas;
    private $usuarios;
    private $userSession;
    private $reserva;
    private $aplicaOferta;

    public function __construct($connect, $session)
    {
        $this->ofertas = new OfertaAlquiler($connect);
        $this->usuarios = new Usuario($connect);
        $this->userSession = new ControladorDeSessiones($session, $connect);
        $this->reserva = new Reserva($connect);
        $this->aplicaOferta = new AplicaOferta($connect);
    }

    //------------------------------------------ muestra de informacion de ofertas, aplicaciones y reservas de usuarios----------------------------------//

    public function home()
    {
        if ($this->userSession->Roll() === LOG) {
            $this->render('rentas', [], 'site');
        } else {
            header("Location: " . URL_PATH);
        }
    }

    public function table()
    {

        $result = new Result();
        $userID = $this->userSession->ID();
        $esVerificado = $this->userSession->esVerificado();
        // Obtener solo las que están publicadas
        $ofertasPublicadasUser = $this->obtenerOfertasPublicadas($userID);
        $rentas = $this->aplicaOferta->getAll();
        $usuarios = $this->usuarios->getAll();

        // Obtener aplicantes de oferta
        $oferta_con_aplicante = [];

        if ($ofertasPublicadasUser != null && $rentas != null) {
            $rentasPorOferta = [];

            foreach ($rentas as $renta) {
                $ofertaID = ($renta['estado'] === ESPERA_RENTA) ? $renta['ofertaAlquilerID'] : null;
                if (!is_null($ofertaID)) {
                    if (!array_key_exists($ofertaID, $rentasPorOferta)) {
                        $rentasPorOferta[$ofertaID] = [];
                    }
                    $rentasPorOferta[$ofertaID][] = $renta;
                }
            }

            $oferta_con_aplicante = [];

            foreach ($ofertasPublicadasUser as $oferPublicada) {
                $ofertaID = $oferPublicada['ofertaID'];
                $aplicantes = isset($rentasPorOferta[$ofertaID]) ? $rentasPorOferta[$ofertaID] : [];
                $usuariosAplicantes = [];

                foreach ($usuarios as $usuario) {
                    foreach ($aplicantes as $aplicante) {
                        if ($aplicante['usuarioAplicoID'] === $usuario['usuarioID']) {
                            $usuariosAplicantes[] = $usuario;
                        }
                    }
                }

                $oferta_con_aplicante[] = [
                    'ofertaPublicada' => $oferPublicada,
                    'usuariosAplicantes' => $usuariosAplicantes,
                ];
            }
        } else {
            $oferta_con_aplicante = [];
        }


        //hay que pasar sus propias aplicaciones. darle a que oferta aplico
        $aplicacionesDelUsuario = $this->aplicaOferta->buscarRegistrosRelacionados('usuarios', 'usuarioID', 'usuarioAplicoID', $userID);

        $ofertasAplicadasUsuario = [];
        if (count($aplicacionesDelUsuario) > 0) {
            $ofertas = $this->ofertas->getAll();
            foreach ($aplicacionesDelUsuario as $aUsuario) {
                foreach ($ofertas as $oferta) {
                    if ($aUsuario['ofertaAlquilerID'] === $oferta['ofertaID']) {
                        $ofertasAplicadasUsuario[] = [
                            'oferta' => $oferta,
                            'aplicacion' => $aUsuario,
                        ];
                    }
                }
            }
        }

        // pasar las reservas que hizo el usuario
        $reservasUsuario = $this->reserva->buscarRegistrosRelacionados('usuarios', 'usuarioID', 'autorID', $userID);
        $reservas = [];
        if ($reservasUsuario) {
            $ofertas = $this->ofertas->getAll();
            foreach ($reservasUsuario as $reserva) {
                foreach ($ofertas as $oferta) {
                    if ($reserva['ofertaAlquilerID'] === $oferta['ofertaID']) {
                        $reservas[] = [
                            'oferta' => $oferta,
                            'reservaUser' => $reserva,
                        ];
                    }
                }
            }
        }

        // pasar las reservas que le hicieron a las ofertas publicadas del usuario
        $reservasDeOfertas = [];
        if (count($ofertasPublicadasUser) > 0) {
            foreach ($ofertasPublicadasUser as $ofertaPublicada) {
                $reservasDeOfertas[] = [
                    'reservas' => $this->reserva->buscarRegistrosRelacionados('oferta_de_alquiler', 'ofertaID', 'ofertaAlquilerID', $ofertaPublicada['ofertaID']),
                    'ofertaUser' => $ofertaPublicada,
                ];
            }
        }



        // Enviar la data
        $result->success = true;
        $result->result = [
            'ofertasAplicantes' => $oferta_con_aplicante,
            'aplicacionesDelUsuario' => $ofertasAplicadasUsuario,
            'reservasDelUsuario' => $reservas,
            'reservasDeOfertasP' => $reservasDeOfertas,
            'esVerificado' => $esVerificado,
        ];

        echo json_encode($result);
    }

    public function obtenerOfertasPublicadas($idUser)
    {

        $ofertasUser = $this->ofertas->buscarRegistrosRelacionados('usuarios', 'usuarioID', 'creadorID', $idUser);

        // Obtener solo las que están publicadas
        $ofertasPublicadas = [];

        foreach ($ofertasUser as $ofUser) {
            if ($ofUser['estado'] === PUBLICADO) {
                $ofertasPublicadas[] = $ofUser;
            }
        }

        return $ofertasPublicadas;
    }


    //---------------------------------------------------------------------------------------------------------------------------------------------//
    //---------------------------------------------------- Reservas reseñar, puntuar y contestar reseña-------------------------------------------------------------//

    public function resenar()
    {
        $result = new Result();
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $idReserva = (isset($_POST['reservaID'])) ? $_POST['reservaID'] : '';
            $esVerificado = (isset($_POST['esVerificado'])) ? $_POST['esVerificado'] : '';
            $resena = (isset($_POST['nuevaResena'])) ? $_POST['nuevaResena'] : '';
            $puntuacion = (isset($_POST['nuevaPuntuacion'])) ? $_POST['nuevaPuntuacion'] : '';
            if ($idReserva != null && $esVerificado != null && is_numeric($idReserva)) {

                if (empty($resena) && $puntuacion != null) {
                    $this->reserva->updateById($idReserva, [
                        'puntaje' => $puntuacion,
                    ]);
                    $result->success = true;
                    $result->message = "Evaluación realizada con éxito.";
                } else {
                    if ($esVerificado === 'true' && $resena != null && $puntuacion != null) {
                        $this->reserva->updateById($idReserva, [
                            'textoReserva' => $resena,
                            'puntaje' => $puntuacion,
                        ]);

                        $result->success = true;
                        $result->message = "Evaluación realizada con éxito.";
                    } else {
                        $result->success = false;
                        $result->message = "Solo los usuarios verificados pueden reseñar.";
                    }
                }
            } else {
                $result->success = false;
                $result->message = "Error: información inválida.";
            }
        } else {
            $result->success = false;
            $result->message = 'Error: Solicitud inválida.';
        }
        echo json_encode($result);
    }

    public function responder()
    {
        $result = new Result();
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $idReserva = (isset($_POST['reservaID'])) ? $_POST['reservaID'] : '';
            $contestacion = (isset($_POST['responder'])) ? $_POST['responder'] : '';

            if ($idReserva != null && is_numeric($idReserva) && $contestacion != null) {
                $this->reserva->updateById($idReserva, [
                    'respuesta' => $contestacion,
                ]);
                $result->success = true;
                $result->message = "Respuesta enviada con éxito.";
            } else {
                $result->success = false;
                $result->message = "Error: información inválida.";
            }
        } else {
            $result->success = false;
            $result->message = 'Error: Solicitud invalida.';
        }
        echo json_encode($result);
    }

    //---------------------------------------------------------------------------------------------------------------------------------------------//

    //---------------------------------------------------------Rentas--------------------------------------------------------------------//

    public function crearReserva($idUsuarioReserva, $idOferta)
    {
        $result = [];
        if (is_numeric($idUsuarioReserva) && is_numeric($idOferta)) {
            $rentas = $this->aplicaOferta->buscarRegistrosRelacionados('oferta_de_alquiler', 'ofertaID', 'ofertaAlquilerID', $idOferta);
            if ($rentas) {
                foreach ($rentas as $renta) {
                    if ($renta['usuarioAplicoID'] === $idUsuarioReserva) {
                        $this->aplicaOferta->updateById($renta['aplicacionID'], [
                            'estado' => ACEPTADO,
                        ]);
                    }
                }
                $this->reserva->insert([
                    'estado' => ENCURSO,
                    'ofertaAlquilerID' => $idOferta,
                    'autorID' => $idUsuarioReserva,
                ]);
                $result = [
                    'success' => true,
                    'message' => 'Reserva creada con éxito.',
                ];
            } else {
                $result = [
                    'success' => false,
                    'message' => 'Error: no existe la aplicación a la oferta hecha por el usuario.',
                ];
            }
        } else {
            $result = [
                'success' => false,
                'message' => 'Error: información inválida.',
            ];
        }
        return $result;
    }

    public function rentar()
    {
        // rentar ahora debe de agregar el rango de fechas a las que postula y tener en cuenta las restricciones, ver para traer las aplicaciones de la oferta y los rangos ya pedidos para el UI
        // agregar tambien el rango de fechas en la tabla aplicar oferta. 
        // solo debe traer el rango de fechas de las aceptadas y quisas tener en cuenta para resenar el que se acabara la fecha
        // debe empezar el rango minimamente en el dia de la fecha o si se indico en la oferta desde ese dia
        // tener en cuenta el tiempo minimo y maximo de estadia de la oferta
        $result = new Result();
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $idOferta = (isset($_POST['ofertaID'])) ? $_POST['ofertaID'] : '';
            $fechaInicio = (isset($_POST['fecha-inicio'])) ? $_POST['fecha-inicio'] : '';
            $fechaFin = (isset($_POST['fecha-fin'])) ? $_POST['fecha-fin'] : '';
            $idUsuario = $this->userSession->ID();
            $esVerificado = $this->userSession->esVerificado();
            if ($idOferta !== '' && $idUsuario !== '' && is_numeric($idUsuario) && is_numeric($idOferta) && $esVerificado !== false && $fechaInicio !== '' && $fechaFin !== '') {
                //si es verificado debe crear la aplicacion con estado aceptado else con estado espera
                $oferta = $this->ofertas->getById($idOferta);

                $timestampInicio = strtotime($fechaInicio);
                $timestampFin = strtotime($fechaFin);
                $diferenciaEnSegundos = $timestampFin - $timestampInicio;
                $duracionEnDias = $diferenciaEnSegundos / 86400;

                $validacionIniFin = null;
                if ($oferta['fechaInicio'] !== '0000-00-00' && $oferta['fechaFin'] !== '0000-00-00') {
                    $timestampFormIni = strtotime($fechaInicio);
                    $timestampOfertaIni = strtotime($oferta['fechaInicio']);
                    $timestampFormFin = strtotime($fechaFin);
                    $timestampOfertaFin = strtotime($oferta['fechaFin']);
                    if ($timestampFormIni > $timestampOfertaIni && $timestampFormFin < $timestampOfertaFin) {
                        $validacionIniFin = true;
                    } else {
                        $validacionIniFin = false;
                    }
                } else {
                    $validacionIniFin = null;
                }

                if ($oferta['creadorID'] !== $idUsuario) {

                    if ($duracionEnDias > $oferta['tiempoMinPermanencia']) {

                        if ($duracionEnDias < $oferta['tiempoMaxPermanencia']) {

                            if ($validacionIniFin !== false) {
                                if ($esVerificado === true) {
                                    $this->aplicaOferta->insert([
                                        'estado' => ACEPTADO,
                                        'fechaInicio' => $fechaInicio,
                                        'fechaFin' => $fechaFin,
                                        'usuarioAplicoID' => $idUsuario,
                                        'ofertaAlquilerID' => $idOferta,
                                    ]);
                                    $resultado = $this->crearReserva($idUsuario, $idOferta);
                                    if ($resultado['success'] === true) {
                                        $result->success = true;
                                        $result->message = "Renta verificada creada con éxito.";
                                    } else {
                                        $result->success = $resultado['success'];
                                        $result->message = $resultado['message'];
                                    }
                                } else {
                                    $rentasUser = $this->aplicaOferta->buscarRegistrosRelacionados('usuarios', 'usuarioID', 'usuarioAplicoID', $idUsuario);
                                    if (count($rentasUser) > 0) {
                                        $rentaId = '';
                                        foreach ($rentasUser as $renta) {
                                            $rentaId = $renta['aplicacionID'];
                                        }
                                        $renta = $this->aplicaOferta->getById($rentaId);
                                        if ($renta) {
                                            if ($renta['estado'] !== ESPERA_RENTA) {
                                                $this->aplicaOferta->insert([
                                                    'estado' => ESPERA_RENTA,
                                                    'fechaInicio' => $fechaInicio,
                                                    'fechaFin' => $fechaFin,
                                                    'usuarioAplicoID' => $idUsuario,
                                                    'ofertaAlquilerID' => $idOferta,
                                                ]);
                                                $result->success = true;
                                                $result->message = "Renta creada con éxito.";
                                            } else {
                                                $result->success = false;
                                                $result->message = "Ya tiene una oferta en proceso.";
                                            }
                                        } else {
                                            $result->success = false;
                                            $result->message = "Error: de proceso.";
                                        }
                                    } else {
                                        $this->aplicaOferta->insert([
                                            'estado' => ESPERA_RENTA,
                                            'fechaInicio' => $fechaInicio,
                                            'fechaFin' => $fechaFin,
                                            'usuarioAplicoID' => $idUsuario,
                                            'ofertaAlquilerID' => $idOferta,
                                        ]);
                                        $result->success = true;
                                        $result->message = "Renta creada con éxito.";
                                    }
                                }
                            } else {
                                $result->success = false;
                                $result->message = 'Las fechas seleccionadas están fuera del período de disponibilidad de la oferta.';
                            }
                        } else {
                            $result->success = false;
                            $result->message = 'La cantidad de días elegidos supera el tiempo máximo de alquiler de la oferta.';
                        }
                    } else {
                        $result->success = false;
                        $result->message = 'La cantidad de días elegidos no cumple con el tiempo mínimo de alquiler de la oferta.';
                    }
                } else {
                    $result->success = false;
                    $result->message = 'No puede aplicar a su propia publicación.';
                }
            } else {
                $result->success = false;
                $result->message = 'Debe ingresar la cantidad de días que desea alquilar para poder aplicar a la oferta.';
            }
        } else {
            $result->success = false;
            $result->message = 'Error: Solicitud invalida.';
        }
        echo json_encode($result);
    }

    public function aceptarRenta()
    {
        $result = new Result();
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $idOferta = (isset($_POST['oferta'])) ? intval($_POST['oferta']) : 0;
            $idUsuario = (isset($_POST['usuario'])) ? intval($_POST['usuario']) : 0;
            if ($idOferta != '' && $idUsuario != '' && is_numeric($idUsuario) && is_numeric($idOferta)) {
                $oferta = $this->ofertas->getById($idOferta);
                if ($oferta['creadorID'] !== $idUsuario) {
                    $resultado = $this->crearReserva($idUsuario, $idOferta);
                    if ($resultado['success'] === true) {
                        $result->success = true;
                        $result->message = "Reserva creada con éxito.";
                    } else {
                        $result->success = $resultado['success'];
                        $result->message = $resultado['message'];
                    }
                } else {
                    $result->success = false;
                    $result->message = "Error: usuario dueño de la oferta.";
                }
            } else {
                $result->success = false;
                $result->message = "Error: información inválida.";
            }
        } else {
            $result->success = false;
            $result->message = 'Error: Solicitud invalida.';
        }
        echo json_encode($result);
    }

    public function rechazarRenta()
    {
        $result = new Result();
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $idOferta = (isset($_POST['oferta'])) ? intval($_POST['oferta']) : 0;
            $idUsuario = (isset($_POST['usuario'])) ? intval($_POST['usuario']) : 0;

            if ($idOferta != '' && $idUsuario != '' && is_numeric($idUsuario) && is_numeric($idOferta)) {
                $rentas = $this->aplicaOferta->buscarRegistrosRelacionados('oferta_de_alquiler', 'ofertaID', 'ofertaAlquilerID', $idOferta);
                if ($rentas) {
                    foreach ($rentas as $renta) {
                        if ($renta['usuarioAplicoID'] === $idUsuario) {
                            $this->aplicaOferta->updateById($renta['aplicacionID'], [
                                'estado' => RECHAZADO,
                            ]);
                        }
                    }
                    $result->success = true;
                    $result->message = $idOferta;
                } else {
                    $result->success = false;
                    $result->message = 'Error: Oferta no encontrada.';
                }
            } else {
                $result->success = false;
                $result->message = 'Error: información invalida.';
            }
        } else {
            $result->success = false;
            $result->message = 'Error: Solicitud invalida.';
        }
        echo json_encode($result);
    }
    //---------------------------------------------------------------------------------------------------------------------------------------------//

}
//---------------------------------------------------------------------------------------------------------------------------------------------//
