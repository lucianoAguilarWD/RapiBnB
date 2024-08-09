<?php
require_once(__DIR__ . '/../Model/controladorDeSessiones.php');
require_once(__DIR__ . '/../Model/ofertaAlquiler.php');

class OfertaAlquilerController extends Controller
{

    private $ofertaModel;
    private $userSession;

    public function __construct($connect, $session)
    {
        $this->ofertaModel = new OfertaAlquiler($connect);
        $this->userSession = new ControladorDeSessiones($session, $connect);
    }

    //------------------------------------------------Mostrar Ofertas ---------------------------------------------------------//

    public function home()
    {
        if ($this->userSession->Roll() === LOG) {
            $user = $this->userSession->ID();
            $userVerificado = $this->userSession->esVerificado();
            $ofertasUsuario = $this->ofertaModel->buscarRegistrosRelacionados('usuarios', 'usuarioID', 'creadorID', $user);
            $cont = 0;
            foreach ($ofertasUsuario as $ofertas) {
                if ($ofertas != null) {
                    $cont++;
                }
            }
            $this->render('publicarOferta', [
                'esVerificado' => $userVerificado,
                'cantOfertas' => $cont,
            ], 'site');
        } else {
            header("Location:" . URL_PATH);
        }
    }

    public function table()
    {
        $result = new Result();
        $userLogin = $this->userSession->ID();
        $ofertasUsuario = $this->ofertaModel->buscarRegistrosRelacionados('usuarios', 'usuarioID', 'creadorID', $userLogin);
        $result->success = true;
        $result->result = [
            'ofertas' => $ofertasUsuario,
        ];
        echo json_encode($result);
    }

    //------------------------------------------------------------------------------------------------------------------------//

    //------------------------------------------------Crear Ofertas ---------------------------------------------------------//

    public function crear()
    {
        if ($this->userSession->Roll() === LOG) {
            $this->render('publicarOfertaCreate', [], 'site');
        } else {
            header("Location:" . URL_PATH);
        }
    }

    public function create()
    {
        $result = new Result();

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $titulo = (isset($_POST['titulo'])) ? $_POST['titulo'] : '';
            $descripcion = (isset($_POST['descripcion'])) ? $_POST['descripcion'] : '';
            $ubicacion = (isset($_POST['ubicacion'])) ? $_POST['ubicacion'] : '';
            $etiqueta = (isset($_POST['etiqueta'])) ? $_POST['etiqueta'] : '';
            $costo = (isset($_POST['costoAlquilerPorDia'])) ? $_POST['costoAlquilerPorDia'] : '';
            $cupo = (isset($_POST['cupo'])) ? $_POST['cupo'] : '';
            $tiempoMin = (isset($_POST['tiempoMinPermanencia'])) ? $_POST['tiempoMinPermanencia'] : '';
            $tiempoMax = (isset($_POST['tiempoMaxPermanencia'])) ? $_POST['tiempoMaxPermanencia'] : '';
            $fechaIni = (isset($_POST['fechaInicio'])) ? $_POST['fechaInicio'] : '';
            $fechaFin = (isset($_POST['fechaFin'])) ? $_POST['fechaFin'] : '';
            $listServicios = isset($_POST['servicios']) ? implode(", ", $_POST['servicios']) : '';

            if ($titulo != '' && $descripcion != '' && $ubicacion  != '' && $etiqueta  != '' && $costo != '' && $cupo != '' && $tiempoMin != '' && $tiempoMax != '' && $listServicios != '') {
                if ($tiempoMin < $tiempoMax) {

                    $validacionRangoMayorATiempos = '';
                    if ($fechaFin !== '' && $fechaIni !== '') {
                        $timestampInicio = strtotime($fechaIni);
                        $timestampFin = strtotime($fechaFin);
                        $diferenciaEnSegundos = $timestampFin - $timestampInicio;
                        $duracionEnDias = $diferenciaEnSegundos / 86400;
                        if ($duracionEnDias > $tiempoMax) {
                            $validacionRangoMayorATiempos = true;
                        } else {
                            $validacionRangoMayorATiempos = false;
                        }
                    }

                    if ($validacionRangoMayorATiempos === true || $validacionRangoMayorATiempos === '') {
                        if (isset($_FILES['galeriaFotos']) && is_array($_FILES['galeriaFotos']['tmp_name'])) {
                            $galeriaFotos = [];

                            foreach ($_FILES['galeriaFotos']['tmp_name'] as $key => $tmp_name) {
                                // Validar si es una imagen y obtener la extensión del archivo
                                $tipoMIME = $_FILES['galeriaFotos']['type'][$key];
                                $extension = pathinfo($_FILES['galeriaFotos']['name'][$key], PATHINFO_EXTENSION);

                                // Verificar que es una imagen válida 
                                if (strpos($tipoMIME, 'image') === 0) {
                                    // Generar un nombre de archivo único con una extensión segura
                                    $fecha_img = new DateTime();
                                    $nombre_foto = $fecha_img->getTimestamp() . '_' . uniqid() . '.' . $extension;

                                    // Ruta de destino relativa al directorio de imágenes
                                    //'Assets/images/galeriaFotos/''C:\xampp\htdocs\PM\Public\Assets\images\galeriaFotos\\'
                                    $destinationPath = 'Assets/images/galeriaFotos/' . $nombre_foto;

                                    // Mover la imagen al directorio de destino
                                    if (move_uploaded_file($tmp_name, $destinationPath)) {
                                        $galeriaFotos[] = $nombre_foto;
                                    }
                                }
                            }
                            $fotosString = implode(", ", $galeriaFotos);

                            $esVerificado = $this->userSession->esVerificado();
                            $estado = '';
                            $userVerificado = '';
                            if ($esVerificado === true) {
                                $estado = 'publicado';
                                $userVerificado = 'si';
                            } else {
                                $estado = 'espera';
                                $userVerificado = 'no';
                            }

                            $id = $this->userSession->ID();

                            $this->ofertaModel->insert([
                                'titulo' => $titulo,
                                'descripcion' => $descripcion,
                                'ubicacion' => $ubicacion,
                                'etiquetas' =>  $etiqueta,
                                'galeriaFotos' => $fotosString,
                                'listServicios' => $listServicios,
                                'costoAlquilerPorDia' => $costo,
                                'tiempoMinPermanencia' => $tiempoMin,
                                'tiempoMaxPermanencia' => $tiempoMax,
                                'cupo' => $cupo,
                                'fechaInicio' => $fechaIni,
                                'fechaFin' => $fechaFin,
                                'estado' => $estado,
                                'userVerificado' => $userVerificado,
                                'creadorID' => $id
                            ]);

                            $result->success = true;
                            $result->message = "Oferta de alquiler creada con éxito";
                        } else {
                            $result->success = false;
                            $result->message = "Error: información invalida.";
                        }
                    } else {
                        $result->success = false;
                        $result->message = "El rango de fechas seleccionadas de actividad de la publicación es menor al tiempos maximo de permanencia indicado.";
                    }
                } else {
                    $result->success = false;
                    $result->message = "El tiempo minimo de permanencia no puede ser mayor al maximo.";
                }
            } else {
                $result->success = false;
                $result->message = "Error: información invalida.";
            }
        } else {
            $result->success = false;
            $result->message = "Error: solicitud invalida.";
        }

        echo json_encode($result);
    }
    //------------------------------------------------------------------------------------------------------------------------//
    //---------------------------------------------------------Editar Oferta--------------------------------------------------------//
    public function edit()
    {
        if ($this->userSession->Roll() === LOG) {
            $id = $_GET['id'];

            $oferta = $this->ofertaModel->getById($id);

            $this->render('publicarOfertaEdit', [
                'oferta' => $oferta,
            ], 'site');
        } else {
            header("Location:" . URL_PATH);
        }
    }

    public function update()
    {
        $result = new Result();

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $idOferta = (isset($_POST['textID'])) ? $_POST['textID'] : null;
            $titulo = (isset($_POST['titulo'])) ? $_POST['titulo'] : '';
            $descripcion = (isset($_POST['descripcion'])) ? $_POST['descripcion'] : '';
            $ubicacion = (isset($_POST['ubicacion'])) ? $_POST['ubicacion'] : '';
            $etiqueta = (isset($_POST['etiqueta'])) ? $_POST['etiqueta'] : '';
            $costo = (isset($_POST['costoAlquilerPorDia'])) ? $_POST['costoAlquilerPorDia'] : '';
            $cupo = (isset($_POST['cupo'])) ? $_POST['cupo'] : '';
            $tiempoMin = (isset($_POST['tiempoMinPermanencia'])) ? $_POST['tiempoMinPermanencia'] : '';
            $tiempoMax = (isset($_POST['tiempoMaxPermanencia'])) ? $_POST['tiempoMaxPermanencia'] : '';
            $fechaIni = (isset($_POST['fechaInicio'])) ? $_POST['fechaInicio'] : '';
            $fechaFin = (isset($_POST['fechaFin'])) ? $_POST['fechaFin'] : '';
            $listServicios = isset($_POST['servicios']) ? implode(", ", $_POST['servicios']) : '';

            $tiemposCorrectos = '';
            if ($tiempoMin > $tiempoMax) {
                $tiemposCorrectos = false;
            }

            $validacionRangoMayorATiempos = '';
            if ($fechaFin !== '0000-00-00' && $fechaIni !== '0000-00-00') {
                $timestampInicio = strtotime($fechaIni);
                $timestampFin = strtotime($fechaFin);
                $diferenciaEnSegundos = $timestampFin - $timestampInicio;
                $duracionEnDias = $diferenciaEnSegundos / 86400;
                if ($duracionEnDias > $tiempoMax) {
                    $validacionRangoMayorATiempos = true;
                } else {
                    $validacionRangoMayorATiempos = false;
                }
            }


            if ($idOferta != null && is_numeric($idOferta)) {

                $oferta = $this->ofertaModel->getById($idOferta);

                if ($oferta) {

                    if ($tiemposCorrectos !== false || $tiemposCorrectos === '') {

                        if ($validacionRangoMayorATiempos === true || $validacionRangoMayorATiempos === '') {

                            if (count($_FILES) > 0) {
                                // Procesar y subir nuevas imágenes
                                if (isset($_FILES['galeriaFotos']) && is_array($_FILES['galeriaFotos']['tmp_name'])) {
                                    $galeriaFotos = [];
                                    if (isset($oferta['galeriaFotos'])) {
                                        $imagenes = explode(", ", $oferta['galeriaFotos']);
                                        foreach ($imagenes as $imagen) {
                                            $imagenPath = 'Assets/images/galeriaFotos/' . $imagen;
                                            if (file_exists($imagenPath) && is_file($imagenPath)) {
                                                unlink($imagenPath);
                                            }
                                        }
                                    }


                                    foreach ($_FILES['galeriaFotos']['tmp_name'] as $key => $tmp_name) {
                                        // Validar si es una imagen y obtener la extensión del archivo
                                        $tipoMIME = $_FILES['galeriaFotos']['type'][$key];
                                        $extension = pathinfo($_FILES['galeriaFotos']['name'][$key], PATHINFO_EXTENSION);

                                        // Verificar que es una imagen válida
                                        if (strpos($tipoMIME, 'image') === 0) {
                                            // Generar un nombre de archivo único con una extensión segura
                                            $fecha_img = new DateTime();
                                            $nombre_foto = $fecha_img->getTimestamp() . '_' . uniqid() . '.' . $extension;

                                            // Ruta de destino relativa al directorio de imágenes
                                            $destinationPath = 'Assets/images/galeriaFotos/' . $nombre_foto;

                                            // Mover la imagen al directorio de destino
                                            if (move_uploaded_file($tmp_name, $destinationPath)) {
                                                $galeriaFotos[] = $nombre_foto;
                                            }
                                        }
                                    }
                                    $fotosString = implode(", ", $galeriaFotos);
                                }
                            } else {
                                $fotosString = $oferta['galeriaFotos']; // No se subieron nuevas imágenes, mantener las antiguas
                            }
                            $id = $this->userSession->ID();
                            // Luego, actualiza la oferta con los datos proporcionados
                            $this->ofertaModel->updateById($idOferta, [
                                'titulo' => $titulo,
                                'descripcion' => $descripcion,
                                'ubicacion' => $ubicacion,
                                'etiquetas' => $etiqueta,
                                'galeriaFotos' => $fotosString, // Utilizar las nuevas imágenes o las antiguas
                                'listServicios' => $listServicios,
                                'costoAlquilerPorDia' => $costo,
                                'tiempoMinPermanencia' => $tiempoMin,
                                'tiempoMaxPermanencia' => $tiempoMax,
                                'cupo' => $cupo,
                                'fechaInicio' => $fechaIni,
                                'fechaFin' => $fechaFin,
                                'creadorID' => $id
                            ]);

                            $result->success = true;
                            $result->message = "Oferta modificada con éxito";
                        } else {
                            $result->success = false;
                            $result->message = "El rango de fechas seleccionadas de actividad de la publicación es menor al tiempos maximo de permanencia indicado.";
                        }
                    } else {
                        $result->success = false;
                        $result->message = "El tiempo minimo de permanencia no puede ser mayor al maximo.";
                    }
                } else {
                    $result->success = false;
                    $result->message = "oferta no encontrada";
                }
            } else {
                $result->success = false;
                $result->message = "Identificador inválido";
            }
        } else {
            $result->success = false;
            $result->message = "Solicitud invalidad";
        }
        echo json_encode($result);
    }

    //------------------------------------------------Eliminar Oferta------------------------------------------------------------//

    public function delete()
    {
        $result = new Result();

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $idOferta = (isset($_POST['id'])) ? $_POST['id'] : '';
            if ($idOferta != null && is_numeric($idOferta)) {
                $oferta = $this->ofertaModel->getById($idOferta);
                if ($oferta) {
                    $imagenes = explode(", ", $oferta['galeriaFotos']);
                    foreach ($imagenes as $imagen) {
                        $imagenPath = 'Assets/images/galeriaFotos/' . $imagen;
                        if (file_exists($imagenPath)) {
                            unlink($imagenPath);
                        }
                    }
                    $this->ofertaModel->deleteById($_POST['id']);
                    $result->success = true;
                    $result->message = "Oferta eliminada con éxito";
                } else {
                    $result->success = false;
                    $result->message = "Oferta no encontrada";
                }
            } else {
                $result->success = false;
                $result->message = "Identificador invalido";
            }
        } else {
            $result->success = false;
            $result->message = "Solicitud no válida";
        }


        echo json_encode($result);
    }
    //------------------------------------------------------------------------------------------------------------------------//

    //------------------------------------------------------------------------------------------------------------------------//
}
