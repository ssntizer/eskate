public function __construct()
{
    try {
        $this->compraModel = new CompraModel();
        $this->apiContext = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                'CLIENT_ID',  // Reemplaza con tus credenciales
                'CLIENT_SECRET'
            )
        );
        $this->apiContext->setConfig([
            'mode' => 'sandbox',
            'log.LogEnabled' => true,
            'log.FileName' => '../PayPal.log',
            'log.LogLevel' => 'DEBUG',
            'cache.enabled' => true,
        ]);
    } catch (\Exception $e) {
        log_message('error', 'PayPal Error: ' . $e->getMessage());
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al inicializar PayPal',
            'debug_info' => $e->getMessage()
        ]);
        exit;
    }
}