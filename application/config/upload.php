<?php 
/*
 * Archivo de configuracion de la clase upload para manejar las subidas de archivos al
 * sistema 
 */

/*
 * Ruta de la carpeta
 */
$config['upload_path'] = PATH . '/uploads/';

/*
 * Extensiones permitidas
 * $config['allowed']     = 'gif|jpg|png|txt|doc|pdf|docx|xls|csv|ppt';
 */
$config['allowed']     = 'txt|doc|xls|csv|ppt|pdf|gif|jpg|jpeg|png|psd|xlsx|docx|pptx';


/*
 * Tamaño máximo
 */
$config['max_size']    = '50000000';

/* Fin del archivo upload_config.phtml */
/* Ubicacion: /config/upload_config.phtml */