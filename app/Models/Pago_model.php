<?php
class Pago_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Guarda un pago en la base de datos
    public function guardarPago($datos) {
        return $this->db->insert('pagos', $datos);
    }

    // Obtiene la lista de pagos
    public function obtenerPagos() {
        return $this->db->get('pagos')->result_array();
    }
}
?>
