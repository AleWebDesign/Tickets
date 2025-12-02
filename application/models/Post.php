<?php

class Post extends CI_Model{
	
	// Comprobar login administracion en base de datos
	public function login($username, $password){
		$sql = "SELECT * FROM usuarios WHERE usuario LIKE '%".$username."%' AND activo = 1";
		$query = $this->db->query($sql);
		if($query->num_rows() == 1){
			foreach($query->result() as $row){
				if($row->pass == password_verify($password, $row->pass)){
					return $row;
				}else{
					return false;
				}
			}
		}else{
			return false;
		}
	}

	public function login2($email){
		$email = trim($email);
		$sql = "SELECT * FROM usuarios WHERE email LIKE '%".$email."%' AND activo = 1";
		$query = $this->db->query($sql);
		if($query->num_rows() == 1){
			foreach($query->result() as $row){
				return $row;
			}
		}else{
			return false;
		}
	}
	
	/* Comprobar permisos secciones operador */
	public function permisos_operadora($op,$rol){
		if($rol == 3){
			$this->db->where('id', $op);
			$query = $this->db->get('salones');
			if($query->num_rows() > 0){
				$salon = $query->row();
				$this->db->where('id', $salon->operadora);
				$permisos = $this->db->get('operadoras');
				if($permisos->num_rows() > 0){
					return $permisos->row();
				}else{
					return false;
				}
			}else{
				return false;
			}
		}else{
			$this->db->where('id', $op);
			$permisos = $this->db->get('operadoras');
			if($permisos->num_rows() > 0){
				return $permisos->row();
			}else{
				return false;
			}
		}
	}
	
	// Recuperar contraseña
	public function recuperar_pass($email){
		$this->db->select('id');
		$this->db->from('usuarios');
		$this->db->where('email', $email);
		$this->db->limit(1);

		$query = $this->db->get();

		if($query->num_rows() == 1){
		 return $query->row();
		}else{
		 return false;
		}
	}
	
	// Actualizar usuario - Cuenta
	public function actualizar_usuario($id,$nombre,$email,$pass,$noti,$emails,$hi,$hf,$jornada){
		if($pass == ''){
			$data = array(
				'nombre' => $nombre,
			   	'email' => $email,
			   	'notificaciones' => $noti,
			   	'hora_inicio' => $hi,
			   	'hora_fin' => $hf,
			   	'jornada' => $jornada
			);
		}else{
			$data = array(
				'nombre' => $nombre,
			   	'email' => $email,
			   	'pass' => password_hash($pass, PASSWORD_DEFAULT),
			   	'notificaciones' => $noti,
			   	'hora_inicio' => $hi,
			   	'hora_fin' => $hf,
			   	'jornada' => $jornada
			);
		}
		if($id == ''){
			$this->db->where('id', $this->session->userdata('logged_in')['id']);
		}else{
			$this->db->where('id', $id);
		}
		$this->db->update('usuarios', $data);
		$data = array(
			 'Emails' => $emails
		);
		$this->db->where('id', $this->session->userdata('logged_in')['acceso']);
		$this->db->update('operadoras', $data);
	}
	
	public function actualizar_usuario2($id,$nombre,$email,$pass,$noti){
		if($pass == ''){
			$data = array(
				'nombre' => $nombre,
			   	'email' => $email,
			   	'notificaciones' => $noti
			);
		}else{
			$data = array(
				'nombre' => $nombre,
			   	'email' => $email,
			   	'pass' => password_hash($pass, PASSWORD_DEFAULT),
			   	'notificaciones' => $noti
			);
		}
		if($id == ''){
			$this->db->where('id', $this->session->userdata('logged_in')['id']);
		}else{
			$this->db->where('id', $id);
		}
		$this->db->update('usuarios', $data);
	}
	
	/* Get tabla empresas DB - formulario crear incidencia */
	public function get_empresas(){
		$sql = "select * from empresas WHERE id IN (select empresa from operadoras where id IN (select DISTINCT(operadora) from salones WHERE Activo = 1)) ORDER BY empresa";
		return $this->db->query($sql);
	}

	public function get_empresas_com(){
		$sql = "select * from empresas WHERE COM = 1 ORDER BY empresa";
		return $this->db->query($sql);
	}
	
	public function get_empresa($id){
		$this->db->where('id', $id);
		$e = $this->db->get('empresas');
		return $e->row();
	}
	
	/* Get tabla empresas DB - AJAX */
	public function get_empresas_ajax($q){
		$sql = "SELECT * FROM empresas WHERE empresa LIKE '".$q."%'";
		return $this->db->query($sql);
	}
	
	public function get_empresa_operadora($id){
		$this->db->where('id', $id);
		$query = $this->db->get('operadoras');
		$op = $query->row();
		
		$this->db->where('id', $op->empresa);
		$empresa = $this->db->get('empresas');
		return $empresa->row();
	}
	
	/* Get tabla empresas DB - rol 2 - formulario crear incidencia */
	public function get_empresa_rol_2($op){
		$this->db->where('id', $op);
		$empresas = $this->db->get('operadoras');
		return $empresas->row();		
	}
	
	/* Get tabla empresas DB - rol  - formulario crear incidencia */
	public function get_empresa_rol_3($salon){
		$this->db->where('id', $salon);
		$operadoras = $this->db->get('salones');
		$operadora = $operadoras->row();
		
		$this->db->where('id', $operadora->operadora);
		$empresas = $this->db->get('operadoras');
		return $empresas->row();
		
	}
	
	/* Get tabla situacion DB - formulario crear incidencia */
	public function get_situaciones(){
		$this->db->where('estado', 1);
		$this->db->order_by('situacion','asc');
		return $this->db->get('situacion');
	}
	
	/* Get tabla operadoreas DB - formulario crear incidencia */	
	public function get_operadoras(){
		$this->db->order_by('id','asc');
		return $this->db->get('operadoras');
	}
	
	/* Get tabla operadoreas DB - formulario crear locales */	
	public function get_operadoras_by_name(){
		$this->db->order_by('operadora','asc');
		return $this->db->get('operadoras');
	}
	
	/* Get tabla operadoreas DB - rol 2 - formulario crear incidencia */	
	public function get_operadoras_rol_2($op){
		$this->db->where('id',$op);
		return $this->db->get('operadoras');
	}
	
	/* Get tabla operadoreas DB - rol 3 - formulario crear incidencia */	
	public function get_operadoras_rol_3($salon){
		$this->db->where('id', $salon);
		$query = $this->db->get('salones');
		$rs = $query->row();
		
		$this->db->where('id', $rs->operadora);
		return $this->db->get('operadoras');
	}

	public function get_operadoras_com(){
		return $this->db->query("SELECT * FROM operadoras WHERE activo = 1 AND empresa IN (SELECT id FROM empresas WHERE COM = 1) AND id NOT IN (47,48,58,63,57,56) ORDER BY operadora");
	}

	/* Operadoras con salones activos */
	public function get_operadoras_activas(){
		return $this->db->query("SELECT * FROM operadoras WHERE id IN (SELECT operadora FROM salones WHERE activo = 1) ORDER BY operadora ASC");
	}
	
	/* Get tabla salones DB - locales */
	public function get_salones_todos(){
		$this->db->where('Activo', '1');
		$this->db->order_by('salon','asc');
		return $this->db->get('salones');
	}

	/* Get tabla salones DB - locales */
	public function get_salones_todos_pag($inicio,$tamanio){
		return $this->db->query("SELECT * FROM salones WHERE Activo = '1' ORDER BY salon ASC limit ".$inicio.",".$tamanio."");
	}

	/* Get salones averias pag - locales */
	public function get_salones_averias_pag($inicio,$tamanio){
		$sql = "SELECT * FROM salones WHERE Activo = '1' AND id IN (SELECT salon FROM maquinas WHERE modelo IN (SELECT id FROM modelos_maquinas WHERE tipo_maquina = 10)) ORDER BY salon ASC limit ".$inicio.",".$tamanio."";
		return $this->db->query($sql);
	}

	
	/* Get tabla salones DB - formulario crear incidencia */	
	public function get_salones(){
		$query = "SELECT * FROM salones WHERE Activo = '1' and id IN (SELECT salon FROM maquinas WHERE modelo IN (select id from modelos_maquinas where tipo_maquina = 10) OR ((modelo = 120 OR modelo = 121 OR modelo = 122 OR modelo = 131 OR modelo = 191) AND gestion_adm = 1)) ORDER BY salon";
		return $this->db->query($query);
	}

	/* GET SALONES */
	public function get_salones(){
		$sql = "SELECT * FROM salones WHERE (operadora = 24 OR operadora = 41) ORDER BY salon ASC";
		return $this->db->query($sql);
	}

	/* Get salones averias operadora */
	public function get_salones_averias_op($op){
		$sql = "SELECT * FROM salones WHERE Activo = '1' AND operadora = ".$op." AND id IN (SELECT salon FROM maquinas WHERE modelo IN (SELECT id FROM modelos_maquinas WHERE tipo_maquina = 10)) ORDER BY salon ASC";
		return $this->db->query($sql);
	}
	
	/* Get salones averias */
	public function get_salones_averias(){
		$sql = "SELECT * FROM salones WHERE Activo = '1' AND id IN (SELECT salon FROM maquinas WHERE modelo IN (SELECT id FROM modelos_maquinas WHERE tipo_maquina = 10)) ORDER BY salon ASC";
		return $this->db->query($sql);
	}

	public function get_locales_op($op){
		$sql = "SELECT * FROM salones WHERE Activo = '1' AND operadora = ".$op." ORDER BY salon ASC";
		return $this->db->query($sql);
	}

	/* Get salones averias COM */
	public function get_salones_averias_com(){
		$sql = "SELECT * FROM salones WHERE id IN (SELECT salon FROM maquinas WHERE modelo IN (SELECT id FROM modelos_maquinas WHERE tipo_maquina = 10)) ORDER BY salon ASC";
		return $this->db->query($sql);
	}

	/* GET PROMOCIONES AZAFATAS */
	public function get_salones_promo_azafatas(){
		$sql = "SELECT DISTINCT(salon) FROM `promo_salones` ORDER BY salon ASC";
		return $this->db->query($sql);
	}

	public function get_promos_azafatas_salon($s){
		$sql = "SELECT * FROM `promo_salones` WHERE salon LIKE '".$s."' ORDER BY salon ASC";
		return $this->db->query($sql);
	}
	
	/* Get tabla salones DB - rol 2 - formulario crear incidencia */	
	public function get_salones_rol_op($op){
		if($this->session->userdata('logged_in')['acceso'] == 41){
			$this->db->where('Activo', '1');
			$this->db->where('empresa', '3');
			$this->db->order_by('salon','asc');
			return $this->db->get('salones');
		}else{
			$this->db->where('Activo', '1');
			$this->db->where('operadora', $op);
			$this->db->order_by('salon','asc');
			return $this->db->get('salones');
		}
	}
	
	/* Get tabla salones DB - rol 2 - locales */	
	public function get_salones_rol_op_todos($op){
		$this->db->where('operadora', $op);
		$this->db->order_by('salon','asc');
		return $this->db->get('salones');
	}

	public function get_salones_rol_op_todos_pag($op,$inicio,$tamanio){
		return $this->db->query("SELECT * FROM salones WHERE operadora = '".$op."' ORDER BY salon ASC limit ".$inicio.",".$tamanio."");
	}
	
	/* Get tabla salones DB - rol 3 - formulario crear incidencia */	
	public function get_salones_rol_salon($salon){
		$this->db->where('id', $salon);
		$query = $this->db->get('salones');
		return $query->row();
	}
	
	/* Get salones contador */
	public function get_salones_contador(){
		$sql = "SELECT * FROM salones WHERE Activo = '1' AND (id = '449' OR id = '480' OR id = '502' OR id = '503' OR id = '576') ORDER BY salon ASC";
		return $this->db->query($sql);
	}
	
	/* Get tipo averias */
	public function get_tipo_gestion(){
		$this->db->order_by('gestion','asc');
		return $this->db->get('tipo_gestion');
	}
	
	/* Get tabla errores_tipo DB - formulario crear incidencia */	
	public function get_errores_tipo(){
		$this->db->order_by('id','asc');
		return $this->db->get('errores_tipo');
	}
	
	/* Get tabla errores_tipo DB - formulario editar incidencia */	
	public function get_errores_tipo_edicion($maquina){
		$this->db->where('id', $maquina);
		$maquinas = $this->db->get('maquinas');
		$maquina = $maquinas->row();		
		
		$this->db->where('id', $maquina->modelo);
		$modelos = $this->db->get('modelos_maquinas');
		$modelo = $modelos->row();
		
		$this->db->where('tipo_maquina', $modelo->tipo_maquina);		
		$this->db->order_by('id','asc');
		return $this->db->get('errores_tipo');
	}
	
	/* Get tabla departamentos DB - formulario crear incidencia */	
	public function get_departamentos(){
		$this->db->order_by('id','asc');
		return $this->db->get('grupos');
	}
	
	/* Get departamento/id */	
	public function get_departamento($id){
		$this->db->where('id',$id);
		$query = $this->db->get('grupos');
		return $query->row();
	}
	
	/* Get tabla departamentos/operadora DB - gestion departamentos */	
	public function get_departamentos_op($op){
		$this->db->where('operadora', $op);
		$this->db->order_by('id','asc');
		return $this->db->get('grupos');
	}
	
	/* Get tipo cliente DB - formulario editar incidencia */
	public function get_tipo_cliente(){
		$this->db->order_by('id','asc');
		return $this->db->get('tipo_clientes');
	}
	
	/* Get tipo cliente DB - formulario editar incidencia */
	public function get_tipo_cliente_id($id){
		$this->db->where('id',$id);
		$query = $this->db->get('tipo_clientes');
		return $query->row();
	}
	
	/* Get tabla salones por operadora DB - gestion incidencias */
	public function get_salones_op($id){
		$this->db->where('id', $id);
		return $this->db->get('salones');
	}
	
	/* Get operadoras/empresa/nombre - AJAX - formulario crear incidencia */
	public function get_operadoras_empresa_nombre($nombre){
		$query = "SELECT * FROM `operadoras` WHERE empresa IN (SELECT id FROM empresas WHERE empresa LIKE '%".$nombre."%') ORDER BY operadora";
		return $this->db->query($query);
	}
	
	/* Get operadoras/empresa - AJAX - formulario crear incidencia */
	public function get_operadoras_empresa($id){
		$query = "SELECT * FROM `operadoras` WHERE empresa = ".$id." AND activo = '1' ORDER BY id";
		return $this->db->query($query);
	}
	
	public function get_gestion_empresa_salon($s){
		$sql = "SELECT * FROM empresas WHERE id IN (SELECT empresa FROM operadoras WHERE id IN (SELECT operadora FROM salones WHERE id = '".$s."'))";
		$query = $this->db->query($sql);
		return $query->row();
	}
	
	/* Get tipo gestion empresa */
	public function get_gestion_empresa($id){
		$this->db->where('id',$id);
		$query = $this->db->get('empresas');
		return $query->row();
	}
	
	/* Get tipo gestion empresa */
	public function get_gestion_empresa_nombre($id){
		$sql = "SELECT * FROM empresas WHERE empresa LIKE '%".$id."%'";
		$query = $this->db->query($sql);
		return $query->row();
	}
	
	/* Get salones/operadora - AJAX - formulario crear incidencia - SUPERUSUARIO */
	public function get_salones_operadora_averias($id){
		$query = "SELECT * FROM salones WHERE operadora = '".$id."' and Activo = '1' and id IN (SELECT salon FROM maquinas WHERE modelo IN (select id from modelos_maquinas where tipo_maquina = 10) OR ((modelo = 120 OR modelo = 121 OR modelo = 122 OR modelo = 131 OR modelo = 191))) ORDER BY salon";
		//$query = "SELECT * FROM salones WHERE operadora = '".$id."' and Activo = '1' and salon not like '%BAR%' ORDER BY salon";
		return $this->db->query($query);
	}
	
	/* Get salones/operadora - AJAX - formulario crear incidencia */
	public function get_salones_operadora($id){
		$query = "SELECT * FROM `salones` WHERE operadora = ".$id." and Activo = '1' ORDER BY salon";
		return $this->db->query($query);
	}
	
	/* Get salones/empresa - AJAX - formulario crear incidencia */
	public function get_salones_empresa($id){
		$query = "SELECT * FROM `salones` WHERE operadora IN (SELECT id from operadoras WHERE empresa = ".$id.") ORDER BY salon";
		return $this->db->query($query);
	}
	
	/* Get tabla salones por operadora DB - AJAX - formulario crear incidencia */
	public function get_salones_model($id){
		$this->db->where('id', $id);
		$query = $this->db->get('salones');
		$operadora = $query->row();
		$operador = $operadora->operadora;
		$this->db->where('id', $operadora->operadora);
		return $this->db->get('operadoras');
	}

	/* Comprobar salon jefe tecnicos */
	public function check_salon($s){
		$query = $this->db->query("SELECT * FROM salones WHERE id  = '".$s."'");
		$salon = $query->row();
		if($salon->operadora == 24 || $salon->operadora == 41 || $salon->operadora == 49){
			return true;
		}else{
			return false;
		}
	}
	
	/* Get tabla maquinas por salon DB - AJAX - formulario crear incidencia */
	public function get_maquinas($id,$gestion){
		$query = "SELECT * FROM maquinas WHERE salon = ".$id."";
		if($gestion == 1){
			$query .= " and modelo IN (SELECT id from modelos_maquinas WHERE tipo_maquina = 10)";
		}
		if($gestion == 2){
			$query .= " and modelo IN (SELECT id from modelos_maquinas WHERE tipo_maquina = 5)";
		}
		if($gestion == 3){
			$query .= " and modelo IN (SELECT id from modelos_maquinas WHERE tipo_maquina = 3 OR tipo_maquina = 2)";
		}
		if($gestion == 4){
			$query .= " and modelo IN (SELECT id from modelos_maquinas WHERE tipo_maquina = 1)";
		}
		if($gestion == 5){
			$query .= " and modelo IN (SELECT id from modelos_maquinas WHERE tipo_maquina = 2)";
		}
		if($gestion == 6){
			$query .= " and modelo IN (SELECT id from modelos_maquinas WHERE tipo_maquina = 10)";
		}
		if($gestion == 8){
			$query .= " and modelo IN (SELECT id from modelos_maquinas WHERE (tipo_maquina = 6 OR tipo_maquina = 7 OR tipo_maquina = 8 OR tipo_maquina = 9))";
		}
		$query .= " AND activo = 1 ORDER BY maquina asc";
		return $this->db->query($query);
	}
	
	/* Get tabla cliente salon DB - AJAX - formulario crear incidencia */
	public function get_cliente($id){
		$this->db->where('id', $id);
		$query = $this->db->get('salones');
		$cliente = $query->row();
		return $cliente->telefono;
	}
	
	/* Get datos tipo cliente - AJAX - formulario crear incidencia */
	public function get_cliente_datos($cliente,$op,$salon){
		if($cliente == 2){
			$this->db->where('acceso', $salon);
			$this->db->where('activo', 1);
			$this->db->where('rol', 3);
		}else if($cliente == 3){
			$this->db->where('acceso', $op);
			$this->db->where('activo', 1);
			$this->db->where('rol', 2);
		}else if($cliente == 4){
			$this->db->where('acceso', $op);
			$this->db->where('activo', 1);
			$this->db->where('rol', 4);
		}else if($cliente == 6){
			$this->db->where('acceso', $op);
			$this->db->where('activo', 1);
			$this->db->where('rol', 1);
		}
		$query =$this->db->get('usuarios');
		return $query->row();
	}
	
	/* Get tabla errores tipo DB - AJAX - formulario crear incidencia */
	public function get_error_gestion($tipo){
		$this->db->where('tipo_gestion', $tipo);
		$this->db->order_by('tipo', 'asc');
		return $this->db->get('errores_tipo');
	}
	
	/* Get tabla errores tipo DB - AJAX - formulario crear incidencia */
	public function get_error_maquina($id){
		$this->db->where('id', $id);
		$maquinas = $this->db->get('maquinas');
		$maquina = $maquinas->row();		
		
		$this->db->where('id', $maquina->modelo);
		$modelos = $this->db->get('modelos_maquinas');
		$modelo = $modelos->row();
		
		$this->db->where('tipo_maquina', $modelo->tipo_maquina);
		$this->db->order_by('id','asc');
		return $this->db->get('errores_tipo');

	}
	
	/* Get tabla errores detalles DB - AJAX - formulario crear incidencia */
	public function get_error_detalle($id){
		$this->db->where('error_tipo', $id);
		$this->db->order_by('error_detalle','asc');
		return $this->db->get('errores_detalle');
	}
	
	/* Get tabla grupos SAT DB - AJAX - formulario crear incidencia */
	public function get_grupos_sat($id){		
		$this->db->where('operadora', $id);
		$this->db->where('nombre','SAT');
		$grupos = $this->db->get('grupos');
		return $grupos->row();
	}
	
	/* Get tabla grupos COM DB - AJAX - formulario crear incidencia */
	public function get_grupos_com($id){		
		$this->db->where('operadora', $id);
		$this->db->where('nombre','COM');
		$grupos = $this->db->get('grupos');
		return $grupos->row();
	}
	
	/* Get tabla grupos DB - AJAX - formulario crear incidencia */
	public function get_grupos_ajax(){		
		$this->db->where('operadora', '24');
		//$this->db->where('nombre','ADM');
		return $this->db->get('grupos');
	}
	
	/* Crear incidencia DB */	
	public function crear_incidencia($empresa,$situacion,$fecha_caducidad,$operador,$salon,$cliente_tipo,$cliente_nombre,$cliente_apellidos,$cliente_telefono,$cliente_email,$averia,$error_maquina,$error_tipo,$error_detalle,$cantidad_tarjetas,$error_desc,$destino,$trata_desc,$fecha,$hora,$imagen,$importe,$prioridad,$fecha_programada,$guia_maquina,$direccion_entrega,$telefono_entrega){
		 if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== FALSE)
		   $browser = 'Internet explorer';
		 elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== FALSE) //For Supporting IE 11
		    $browser = 'Internet explorer';
		 elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox') !== FALSE)
		   $browser = 'Mozilla Firefox';
		 elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== FALSE)
		   $browser = 'Google Chrome';
		 elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== FALSE)
		   $browser = "Opera Mini";
		 elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Opera') !== FALSE)
		   $browser = "Opera";
		 elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Safari') !== FALSE)
		   $browser = "Safari";
		 else
		   $browser = 'Unknown';

		/* control creador*/
		if(isset($this->session->userdata('logged_in')['id'])){
			$creador = $this->session->userdata('logged_in')['id'];
		}else{
			$busqueda = "SELECT * FROM usuarios WHERE acceso = '".$salon."' LIMIT 1";
			$user = $this->db->query($busqueda);
			if($user->num_rows() > 0){
				$usuario = $user->row();
				$creador = $usuario->id;
			}else{
				$busqueda = "SELECT * FROM usuarios WHERE acceso = '".$operador."' LIMIT 1";
				$user = $this->db->query($busqueda);
				if($user->num_rows() > 0){
					$usuario = $user->row();
					$creador = $usuario->id;
				}
			}
		}
		/* -------------- */
		
		if($situacion == 6){
			$soluciona = $creador;
		}else{
			$soluciona = 0;
		}
		
		$error_desc_scape =	addslashes($error_desc);
		$trata_desc_scape = addslashes($trata_desc);
		
		if($averia == 0){
			$averia = 6;
		}

		$fecha_edicion = $fecha;
		$hora_edicion = $hora;

		if($prioridad == 3){
			if(isset($fecha_programada) && trim($fecha_programada) != ''){
				$fecha_programada = explode(" ", $fecha_programada);
				$fecha_tmp = explode("/", $fecha_programada[0]);
				$fecha_final = $fecha_tmp[2]."-".$fecha_tmp[1]."-".$fecha_tmp[0];
				$fecha = $fecha_final;
				$hora = $fecha_programada[1];
			}
		}
		   
		$data = array(
			'empresa' => $empresa,
		    'situacion' => $situacion,
		    'operadora' => $operador,
		    'salon' => $salon,
		    'cliente' => $cliente_tipo,
		    'nombre' => $cliente_nombre,
		    'apellidos' => $cliente_apellidos,
		    'telefono' => $cliente_telefono,
		    'email' => $cliente_email,
		    'tipo_averia' => $averia,
		    'maquina' => $error_maquina,
		    'tipo_error' => $error_tipo,
		    'detalle_error' => $error_detalle,
		    'cantidad_tarjetas' => $cantidad_tarjetas,
			'error_desc' => $error_desc_scape,
			'destino' => $destino,
			'trata_desc' => $trata_desc_scape,
			'fecha_creacion' => $fecha,
			'hora_creacion' => $hora,
			'fecha_caducidad' => $fecha_caducidad,
			'creador' => $creador,
			'prioridad' => $prioridad,
			'soluciona' => $soluciona,
			'navegador' => $browser,
			'ip' => $_SERVER['REMOTE_ADDR'],
			'imagen' => $imagen[0]
		);
		$this->db->insert('tickets', $data);
		
		$this->db->order_by('id', 'desc');
		$this->db->limit(1);
		$query = $this->db->get('tickets');
		$ticket = $query->row();

		$totalImg = count($imagen);
		for($i=0; $i<$totalImg; $i++){
			if($imagen[$i] != ''){
				$data = array(
					'ticket' => $ticket->id,
					'imagen' => $imagen[$i] 
				);
				$this->db->insert('tickets_imagenes', $data);
			}
		}
		
		$data = array(
			'id_ticket' => $ticket->id,
			'situacion' => $situacion,
			'fecha_edicion' => $fecha_edicion,
			'hora_edicion' => $hora_edicion,
			'creador' => $creador,
			'edicion_inicial' => 'SI',
			'tipo_edicion' => 2
		);
		$this->db->insert('ediciones', $data);
		
		if($error_tipo == '62' || $error_tipo == '77' || $error_tipo == '113' || $error_tipo == '58'){
			$data = array(
				'id_ticket' => $ticket->id,
				'importe' => $importe,
				'estado' => 0
			);
			$this->db->insert('tickets_manual', $data);
		}

		if($error_detalle == 571 || $error_detalle == 582){
			$data = array(
				'id_ticket' => $ticket->id,
				'guia_maquina' => $guia_maquina,
				'direccion_entrega' => $direccion_entrega,
				'telefono_entrega' => $telefono_entrega 
			);
			$this->db->insert('transportes', $data);
		}
		
		return $ticket->id;
	}

	/* Get extra imagenes incidencia */
	public function get_imagenes_extra_ticket($id){
		return $this->db->query("SELECT * FROM tickets_imagenes WHERE ticket = ".$id."");
	}

	/* Obtener tarjetas ADM entregadas */
	public function get_tarjetas(){
		return $this->db->query("SELECT * FROM tickets WHERE detalle_error = 424");
	}
	
	/* Actualizar ticket para chat telegram id */
	public function actualizar_ticket_chat($chatid,$id){
		$data = array(
			 'chatid' => $chatid
		);
		$this->db->where('id', $id);
		$this->db->update('tickets', $data);
	}

	public function actualizar_ticket_chat2($chatid,$id){
		$data = array(
			 'chatid2' => $chatid
		);
		$this->db->where('id', $id);
		$this->db->update('tickets', $data);
	}
	
	/* Actualizar usuario para chat telegram id */
	public function actualizar_usuario_chat($userid,$id){
		$data = array(
			 'user_id_telegram' => $userid
		);
		$this->db->where('id', $id);
		$this->db->update('usuarios', $data);
	}
	
	/* comprobar cambio asignado incidencia - editar ticket - telegram */
	public function comprobar_asignado($id,$a){
		$asignado = 0;
		
		$this->db->where('id', $id);
		$query = $this->db->get('tickets');
		$ticket = $query->row();
		
		if($ticket->asignado != $a){
			$asignado = 1;
		}
		
		return $asignado;
	}

	/* Registrar horario control de jornada */
	public function registrar_horario($id,$tipo){
		$fecha = date('Y-m-d H:i:s');
		if($this->db->query("INSERT INTO horarios (usuario,fecha,tipo) VALUES (".$id.", '".$fecha."', ".$tipo.")")){
			return true;
		}else{
			return false;
		}
	}

	/* Comprobar ultimo registro control horario usuario */
	public function get_ultimo_registro_horario($id){
		$fecha = date('Y-m-d');
		$sql = $this->db->query("SELECT * FROM horarios WHERE usuario = ".$id." AND fecha LIKE '%".$fecha."%' ORDER BY id DESC LIMIT 1");
		return $sql->row();
	}

	public function get_registro_horario_hoy($id){
		$fecha = date('Y-m-d');
		return $this->db->query("SELECT * FROM horarios WHERE usuario = ".$id." AND fecha LIKE '%".$fecha."%'"); 
	}

	public function get_registro_horario_jornada($id, $fecha){
		return $this->db->query("SELECT * FROM horarios WHERE usuario = ".$id." AND fecha LIKE '%".$fecha."%' ORDER BY fecha ASC");
	}

	/* Get horarios informes */
	public function get_horarios($op){
		if($this->session->userdata('logged_in')['acceso'] == 24){
			return $this->db->query("SELECT * FROM `usuarios` WHERE id = 353 OR ((rol = 4 OR rol = 6) AND acceso = '".$op."') AND activo = 1 AND id != 73 AND jornada = 0 ORDER BY id DESC");
		}else{
			return $this->db->query("SELECT DISTINCT(usuario) FROM horarios WHERE usuario IN (SELECT id FROM usuarios WHERE (acceso = '".$op."' OR acceso IN (SELECT id FROM salones WHERE operadora = '".$op."'))) ORDER BY fecha DESC");
		}
	}

	public function get_horarios_persona_buscador($u){
		return $this->db->query("SELECT * FROM `usuarios` WHERE id = ".$u." ORDER BY id DESC");
	}

	public function get_horarios_persona($usuario){
		return $this->db->query("SELECT * FROM horarios WHERE usuario = ".$usuario." AND fecha > '".date('Y-m-d', strtotime('-10 day', strtotime(date('Y-m-d'))))."' ORDER BY fecha DESC");		
	}

	/* Buscar horarios informes */
	public function buscar_horarios($fecha_inicio, $fecha_fin){
		return $this->db->query("SELECT DISTINCT(fecha) FROM horarios WHERE fecha >= '".$fecha_inicio."' AND fecha <= '".$fecha_fin."'");
	}

	public function get_horarios_persona_fecha($u,$i,$f){
		return $this->db->query("SELECT * FROM horarios WHERE usuario = ".$u." AND fecha >= '".$i."' AND fecha <= '".$f."' ORDER BY fecha DESC");		
	}

	/* Modificar horario informes */
	public function modificar_horario_informes($id,$fecha,$usuario,$tipo){
		$sql = "UPDATE horarios SET";
		if($fecha){
			$fecha_tmp = explode(" ", $fecha);
			$fecha_tmp1 = explode("-", $fecha_tmp[0]);
			$fecha = $fecha_tmp1[2]."-".$fecha_tmp1[1]."-".$fecha_tmp1[0]." ".$fecha_tmp[1];
			$sql .= " fecha = '".$fecha."'";
		}
		if($usuario){
			$sql .= " usuario = '".$usuario."'";
		}
		if($tipo){
			if($tipo == 2){ $tipo = 0; }
			$sql.= " tipo = '".$tipo."'";
		}
		$sql .= " WHERE id = '".$id."'";
		if($this->db->query($sql)){
			return true;
		}else{
			return false;
		}
	}

	/* Eliminar horario informe */
	public function eliminar_horario_informes($id){
		$sql = "DELETE FROM horarios WHERE id = '".$id."'";
		if($this->db->query($sql)){
			return true;
		}else{
			return false;
		}
	}

	/* Recuperar datos transporte */
	public function get_transporte($id){
		$sql = "SELECT * FROM transportes WHERE id_ticket = ".$id." AND (guia_maquina <> '' OR direccion_entrega <> '' OR telefono_entrega <> '') LIMIT 1";
		$query = $this->db->query($sql);
		return $query->row();
	}
	
	/* Editar incidencia DB */	
	public function editar_incidencia($id,$empresa,$situacion,$fecha_caducidad,$operador,$salon,$cliente_tipo,$cliente_nombre,$cliente_apellidos,$cliente_telefono,$cliente_email,$averia,$error_maquina,$error_tipo,$error_detalle,$error_desc,$destino,$trata_desc,$asignado,$fecha,$hora,$imagen,$importe,$prioridad,$fecha_programada,$guia_maquina,$direccion_entrega,$telefono_entrega){

		$this->db->where('id', $id);
		$query = $this->db->get('tickets');
		$result = $query->row();
		
		$error_desc_scape =	addslashes($error_desc);
		$trata_desc_scape = addslashes($trata_desc);

		/* control creador*/
		if(isset($this->session->userdata('logged_in')['id'])){
			$creador = $this->session->userdata('logged_in')['id'];
		}else{
			$busqueda = "SELECT * FROM usuarios WHERE acceso = '".$salon."' LIMIT 1";
			$user = $this->db->query($busqueda);
			if($user->num_rows() > 0){
				$usuario = $user->row();
				$creador = $usuario->id;
			}else{
				$busqueda = "SELECT * FROM usuarios WHERE acceso = '".$operador."' LIMIT 1";
				$user = $this->db->query($busqueda);
				if($user->num_rows() > 0){
					$usuario = $user->row();
					$creador = $usuario->id;
				}
			}
		}
		/* -------------- */
		
		if($averia == 0){
			$averia = 6;
		}

		$fecha_edicion = $fecha;
		$hora_edicion = $hora;

		/* Comprobar si incidencia viene de SAT Operadora a SAT ADM para desasignar */
		if($situacion != 6){
			$this->db->where('id', $id);
			$query = $this->db->get('tickets');
			$ticket_recuperar = $query->row();	
			if($situacion == 2 && $ticket_recuperar->situacion == 13){
				$asignado = 0;
			}
			if($ticket_recuperar->situacion == 6){
				$data = array(
				   'fecha_solucion' => NULL,
				   'hora_solucion' => NULL,
				   'soluciona' => NULL
				);
				$this->db->where('id', $id);
				$this->db->update('tickets', $data);
			}
		}

		/* Controlar cambio de situación */
		if($situacion != $result->situacion){
			$tipo_edicion = 2;
			$trata_desc = 'Cambio de estado de incidencia.';
		}else{
			$tipo_edicion = 1;
			$trata_desc = '';
		}

		$data = array(
		   'empresa' => $empresa,
		   'situacion' => $situacion,
		   'fecha_caducidad' => $fecha_caducidad,
		   'operadora' => $operador,
		   'salon' => $salon,
		   'cliente' => $cliente_tipo,
		   'nombre' => $cliente_nombre,
		   'apellidos' => $cliente_apellidos,
		   'telefono' => $cliente_telefono,
		   'email' => $cliente_email,
		   'tipo_averia' => $averia,
		   'maquina' => $error_maquina,
		   'tipo_error' => $error_tipo,
		   'detalle_error' => $error_detalle,
		   'error_desc' => $error_desc_scape,
		   'prioridad' => $prioridad,
		   'destino' => $destino,
		   'trata_desc' => $trata_desc_scape,
		   'asignado' => $asignado,
		   'imagen' => $imagen[0]
		);

		if($prioridad == 3){
			if(isset($fecha_programada) && trim($fecha_programada) != ''){
				$fecha_programada = explode(" ", $fecha_programada);
				$fecha_tmp = explode("/", $fecha_programada[0]);
				$fecha_final = $fecha_tmp[2]."-".$fecha_tmp[1]."-".$fecha_tmp[0];
				$fecha = $fecha_final;
				$hora = $fecha_programada[1];
				$data = array(
				   'empresa' => $empresa,
				   'situacion' => $situacion,
				   'fecha_caducidad' => $fecha_caducidad,
				   'operadora' => $operador,
				   'salon' => $salon,
				   'cliente' => $cliente_tipo,
				   'nombre' => $cliente_nombre,
				   'apellidos' => $cliente_apellidos,
				   'telefono' => $cliente_telefono,
				   'email' => $cliente_email,
				   'tipo_averia' => $averia,
				   'maquina' => $error_maquina,
				   'tipo_error' => $error_tipo,
				   'detalle_error' => $error_detalle,
				   'error_desc' => $error_desc_scape,
				   'prioridad' => $prioridad,
				   'destino' => $destino,
				   'trata_desc' => $trata_desc_scape,
				   'fecha_creacion' => $fecha,
		 		   'hora_creacion' => $hora,
				   'asignado' => $asignado,
				   'imagen' => $imagen[0]
				);
			}
		}
		$this->db->where('id', $id);
		$this->db->update('tickets', $data);

		if($result->situacion == 6 && $situacion != 6){
			$data = array(
				'creador' => $creador
			);
			$this->db->where('id', $id);
			$this->db->update('tickets', $data);
		}
		
		$this->db->where('id', $id);
		$query = $this->db->get('tickets');
		$ticket = $query->row();

		$totalImg = count($imagen);
		for($i=0; $i<$totalImg; $i++){
			$data = array(
				'ticket' => $ticket->id,
				'imagen' => $imagen[$i] 
			);
			$this->db->insert('tickets_imagenes', $data);
		}
		
		if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== FALSE)
		   $browser = 'Internet explorer';
		 elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== FALSE) //For Supporting IE 11
		    $browser = 'Internet explorer';
		 elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox') !== FALSE)
		   $browser = 'Mozilla Firefox';
		 elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== FALSE)
		   $browser = 'Google Chrome';
		 elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== FALSE)
		   $browser = "Opera Mini";
		 elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Opera') !== FALSE)
		   $browser = "Opera";
		 elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Safari') !== FALSE)
		   $browser = "Safari";
		 else
		   $browser = 'Unknown';
		
		$data = array(
			'id_ticket' => $ticket->id,
			'situacion' => $situacion,
			'trata_desc' => $trata_desc,
			'fecha_edicion' => $fecha_edicion,
			'hora_edicion' => $hora_edicion,
			'creador' => $creador,
			'edicion_inicial' => 'NO',
			'tipo_edicion' => $tipo_edicion,
			'navegador' => $browser,
			'ip' => $_SERVER['REMOTE_ADDR']
		);
		$this->db->insert('ediciones', $data);
		
		if($error_tipo == '62' || $error_tipo == '77' || $error_tipo == '113'){
			$data = array(
				'id_ticket' => $ticket->id,
				'importe' => $importe,
				'estado' => 0
			);
			$this->db->insert('tickets_manual', $data);
		}

		if($error_detalle == 571 || $error_detalle == 582){
			$data = array(
				'id_ticket' => $ticket->id,
				'guia_maquina' => $guia_maquina,
				'direccion_entrega' => $direccion_entrega,
				'telefono_entrega' => $telefono_entrega 
			);
			$this->db->insert('transportes', $data);
		}

		return $ticket->id;
	}
	
	/* Obtener tickets -- Gestión */
	public function get_tickets(){
		$query = "SELECT *, 1 as rowOrder FROM tickets WHERE situacion != 6 AND situacion != 12 AND situacion != 19 AND tipo_error != 132 UNION SELECT *, 2 as rowOrder FROM tickets WHERE situacion = 2 AND tipo_error = 132 UNION SELECT *, 3 as rowOrder FROM tickets WHERE situacion = 12 ORDER BY rowOrder, prioridad DESC, fecha_creacion DESC, hora_creacion DESC";
		return $this->db->query($query);
	}

	/* Obtener tickets ONLINE -- Gestión */
	public function get_tickets_onl(){
		$query = "SELECT *, 1 as rowOrder FROM tickets WHERE situacion = '21' AND asignado = '".$this->session->userdata('logged_in')['id']."' UNION SELECT *, 2 as rowOrder FROM tickets WHERE situacion = '21' AND prioridad = '2' UNION SELECT *, 3 as rowOrder FROM tickets WHERE situacion = '21' AND asignado = '0' UNION SELECT *, 4 as rowOrder FROM tickets WHERE situacion = '21' AND asignado != '0' AND asignado != '".$this->session->userdata('logged_in')['id']."' ORDER BY rowOrder, fecha_creacion DESC, hora_creacion DESC";
		return $this->db->query($query);
	}

	/* Obtener tickets MKT -- Gestión */
	public function get_tickets_mkt(){
		$query = "SELECT *, 1 as rowOrder FROM tickets WHERE situacion = '19' AND asignado = '".$this->session->userdata('logged_in')['id']."' UNION SELECT *, 2 as rowOrder FROM tickets WHERE situacion = '19' AND prioridad = '2' UNION SELECT *, 3 as rowOrder FROM tickets WHERE situacion = '19' AND asignado = '0' UNION SELECT *, 4 as rowOrder FROM tickets WHERE situacion = '19' AND asignado != '0' AND asignado != '".$this->session->userdata('logged_in')['id']."' ORDER BY rowOrder, fecha_creacion DESC, hora_creacion DESC";
		return $this->db->query($query);
	}

	/* Obtener tickets INF -- Gestión */
	public function get_tickets_inf(){
		$query = "SELECT *, 1 as rowOrder FROM tickets WHERE (situacion = '2' OR situacion = '3' OR situacion = '13' OR situacion = '14') AND asignado = '".$this->session->userdata('logged_in')['id']."' UNION SELECT *, 2 as rowOrder FROM tickets WHERE (situacion = '2' OR situacion = '3' OR situacion = '13' OR situacion = '14') AND prioridad = '2' UNION SELECT *, 3 as rowOrder FROM tickets WHERE (situacion = '2' OR situacion = '3' OR situacion = '13' OR situacion = '14') AND asignado = '0' UNION SELECT *, 4 as rowOrder FROM tickets WHERE (situacion = '2' OR situacion = '3' OR situacion = '13' OR situacion = '14') AND asignado != '0' AND asignado != '".$this->session->userdata('logged_in')['id']."' ORDER BY rowOrder, fecha_creacion DESC, hora_creacion DESC";
		return $this->db->query($query);
	}
	
	/* Obtener tickets SAT -- Gestión */
	public function get_tickets_sat(){
		$query = "SELECT *, 1 as rowOrder FROM tickets WHERE (situacion = '2' OR situacion = '3' OR situacion = '13') AND asignado = '".$this->session->userdata('logged_in')['id']."' UNION SELECT *, 2 as rowOrder FROM tickets WHERE situacion = '2' OR situacion = '3' AND prioridad = '2' UNION SELECT *, 3 as rowOrder FROM tickets WHERE situacion = '2' OR situacion = '3' AND asignado = '0' UNION SELECT *, 4 as rowOrder FROM tickets WHERE (situacion = '2' OR situacion = '3' OR situacion = '13') AND asignado != '0' AND asignado != '".$this->session->userdata('logged_in')['id']."' UNION SELECT *, 5 as rowOrder FROM tickets WHERE situacion = '13' ORDER BY rowOrder, fecha_creacion DESC, hora_creacion DESC";
		return $this->db->query($query);
	}
	
	/* Obtener tickets COM -- Gestión */
	public function get_tickets_com(){
		if($this->session->userdata('logged_in')['acceso'] == 24 || $this->session->userdata('logged_in')['acceso'] == 41){
			$query = "SELECT *, 1 as rowOrder FROM tickets WHERE situacion = '12' AND asignado = '".$this->session->userdata('logged_in')['id']."' UNION SELECT *, 2 as rowOrder FROM tickets WHERE situacion IN (1,2,4,5,11,12,13,14,16,17,19) AND prioridad = '2' UNION SELECT *, 3 as rowOrder FROM tickets WHERE situacion = '12' AND asignado = '0' UNION SELECT *, 4 as rowOrder FROM tickets WHERE situacion IN (1,2,4,5,11,12,13,14,16,17,19) ORDER BY rowOrder, fecha_creacion DESC, hora_creacion DESC";
		}else{
			$query = "SELECT * FROM tickets WHERE (situacion = '12' OR situacion = '4' OR situacion = '2') AND destino IN (SELECT id FROM grupos WHERE (nombre LIKE 'COM' OR nombre LIKE 'SAT') AND (operadora = '".$this->session->userdata('logged_in')['acceso']."')) ORDER BY prioridad DESC, fecha_creacion DESC, hora_creacion DESC";
		}
		return $this->db->query($query);
	}

	/* Obtener todos tickets pendientes op */
	public function get_tickets_op_centralita($op){
		$tickets = "SELECT * FROM tickets WHERE (situacion = '2' OR situacion = '13' OR situacion = '14') AND operadora = '".$op."' ORDER BY fecha_creacion DESC, hora_creacion DESC";
		return $this->db->query($tickets);
	}

	/* Obtener todos tickets pendientes salon */
	public function get_tickets_salon_centralita($s){
		$tickets = "SELECT * FROM tickets WHERE (situacion = '2' OR situacion = '13' OR situacion = '14') AND salon = '".$s."' ORDER BY fecha_creacion DESC, hora_creacion DESC";
		return $this->db->query($tickets);
	}
	
	/* Obtener todos tickets SAT operadora */
	public function get_tickets_op_todos($op){
		$tickets = "SELECT * FROM tickets WHERE situacion = '2' AND operadora = '".$op."' ORDER BY fecha_creacion DESC, hora_creacion DESC";
		return $this->db->query($tickets);
	}
	
	/* Obtener tickets -- Gestión */
	public function get_tickets_op($op){
		if($op == 41){
			$query = "SELECT * FROM usuarios WHERE acceso = '".$op."' OR rol = 1 OR acceso IN (SELECT id FROM salones WHERE operadora = '".$op."')";
		}else{
			$query = "SELECT * FROM usuarios WHERE acceso = '".$op."' OR acceso IN (SELECT id FROM salones WHERE operadora = '".$op."')";
		}
		$users = $this->db->query($query);
		$usuarios = array();
		foreach($users->result() as $user){
			array_push($usuarios, $user->id);
		}
		$query = "SELECT * FROM grupos WHERE nombre LIKE 'SAT' AND operadora = '".$op."'";
		$grupos = $this->db->query($query);
		$grupo = $grupos->row();

		if($op == 41){
			$tickets = "SELECT *, 1 as rowOrder FROM tickets WHERE situacion = '2' AND asignado = '".$this->session->userdata('logged_in')['id']."' UNION SELECT *, 2 as rowOrder FROM tickets WHERE (situacion = '2' OR situacion = '13') AND salon IN (SELECT id FROM salones WHERE empresa = 3) AND prioridad = '2' AND creador IN (SELECT id FROM usuarios WHERE acceso = 24) UNION SELECT *, 3 as rowOrder FROM tickets WHERE (situacion = '2' OR situacion = '13') AND salon IN (SELECT id FROM salones WHERE empresa = 3) AND creador IN (SELECT id FROM usuarios WHERE acceso = 24) ORDER BY rowOrder, fecha_creacion DESC, hora_creacion DESC";
		}else if($op == 6){
			$tickets = "SELECT *, 1 as rowOrder FROM tickets WHERE situacion = '2' AND asignado = '".$this->session->userdata('logged_in')['id']."' UNION SELECT *, 2 as rowOrder FROM tickets WHERE situacion != '6' AND (operadora = '".$op."' OR operadora = '54' OR operadora = '60') AND (creador IN (".implode(',', $usuarios).") OR destino = '".$grupo->id."' OR destino = '4' OR situacion = '13' OR situacion = '14') AND prioridad = '2' UNION SELECT *, 3 as rowOrder FROM tickets WHERE situacion != '6' AND (operadora = '".$op."' OR operadora = '54' OR operadora = '60') AND (destino = '".$grupo->id."' OR destino = '4' OR situacion = '13' OR situacion = '14') ORDER BY rowOrder, fecha_creacion DESC, hora_creacion DESC";
		}else{
			$tickets = "SELECT *, 1 as rowOrder FROM tickets WHERE situacion = '2' AND asignado = '".$this->session->userdata('logged_in')['id']."' UNION 
			SELECT *, 2 as rowOrder FROM tickets WHERE situacion != '6' AND operadora = '".$op."' AND (creador IN (".implode(',', $usuarios).") OR destino = '".$grupo->id."' OR destino = '4' OR situacion = '13' OR situacion = '14') AND prioridad = '2' UNION SELECT *, 3 as rowOrder FROM tickets WHERE situacion != '6' AND operadora = '".$op."' AND (creador IN (".implode(',', $usuarios).") OR destino = '".$grupo->id."' OR destino = '4' OR situacion = '13' OR situacion = '14') ORDER BY rowOrder, fecha_creacion DESC, hora_creacion DESC";
		}
		return $this->db->query($tickets);
	}
	
	/* Obtener tickets -- Gestión */
	public function get_tickets_salon($salon){
		$query = "SELECT * FROM salones WHERE id = '".$salon."'";
		$salones = $this->db->query($query);
		$op = $salones->row();
		$query = "SELECT * FROM usuarios WHERE acceso = '".$op->operadora."' OR acceso IN (SELECT id FROM salones WHERE operadora = '".$op->operadora."')";
		$users = $this->db->query($query);
		$usuarios = array();
		foreach($users->result() as $user){
			array_push($usuarios, $user->id);
		}
		array_push($usuarios, "1");
		$tickets = "SELECT * FROM tickets WHERE situacion != '6' AND operadora = '".$op->operadora."' AND salon = '".$salon."' AND creador IN (".implode(',', $usuarios).") ORDER BY prioridad DESC, fecha_creacion DESC, hora_creacion DESC";
		return $this->db->query($tickets);
	}
	
	/* Obtener tickets royal -- Gestión */
	public function get_tickets_royal(){
		$query = "SELECT * FROM tickets WHERE situacion != '6' AND operadora = '41' AND salon = '449' ORDER BY prioridad DESC, fecha_creacion DESC, hora_creacion DESC";
		return $this->db->query($query);
	}
	
	/* Obtener tickets -- Gestión */
	public function buscar_tickets($query){
		if (strpos($query, '1situacion') !== false) {
		    $query = "SELECT * FROM tickets WHERE situacion != '6' ORDER BY prioridad DESC, fecha_creacion DESC, hora_creacion DESC";
		}
		return $this->db->query($query);
	}
	
	/* Obtener ticket concreto - Acciones */
	public function get_ticket($id){
		$this->db->where('id', $id);
		$query = $this->db->get('tickets');
		$ticket = $query->row();
		return $ticket;
	}
	
	public function get_ticket_manual($id){
		$this->db->where('id_ticket', $id);
		$query = $this->db->get('tickets_manual');
		return $query->row();
	}
	
	/* Obtener situacion/ticket -- Gestión */
	public function get_situacion($id_situacion){
		$this->db->where('id', $id_situacion);
		$query = $this->db->get('situacion');
		$situacion = $query->row();
		return $situacion->situacion;
	}
	
	/* Obtener operadora/ticket -- Gestión */
	public function get_operadora($id_operadora){
		$this->db->where('id', $id_operadora);
		$query = $this->db->get('operadoras');
		$operadora = $query->row();
		return $operadora->operadora;
	}
	
	/* Obtener salon/ticket -- Gestión */
	public function get_salon($id_salon){
		$this->db->where('id', $id_salon);
		$query = $this->db->get('salones');
		$salon = $query->row();
		return $salon->salon;
	}
	
	/* Obtener salon/ticket COMPLETO -- Gestión/mail */
	public function get_salon_completo($id_salon){
		$this->db->where('id', $id_salon);
		$query = $this->db->get('salones');
		if($query->num_rows() != 0){
			$salon = $query->row();
			return $salon;
		}else{
			return false;
		}
	}
		
	function get_tiempo_incidencia($date){
		$date1 = new DateTime($date);
		$date2 = new DateTime(date("Y-m-d H:i:s"));
		$diff = $date2->diff($date1);
		return $diff->format('%a días y %h horas');
	}
	
	/* Obtener tipo averia -- Gestión */
	public function get_averia($id){
		$this->db->where('id', $id);
		$query = $this->db->get('tipo_gestion');
		return $query->row();
	}
	
	/* Obtener tipo error/ticket -- Gestión */
	public function get_tipo_error($id_tipo_error){
		$this->db->where('id', $id_tipo_error);
		$query = $this->db->get('errores_tipo');
		$tipo_error = $query->row();
		return $tipo_error->tipo;
	}
	
	/* Obtener tipo error/ticket COMPLETO -- Gestión/mail */
	public function get_tipo_error_completo($id_tipo_error){
		$this->db->where('id', $id_tipo_error);
		$query = $this->db->get('errores_tipo');
		$tipo_error = $query->row();
		return $tipo_error;
	}
	
	/* Obtener tipo error/ticket -- Gestión */
	public function get_detalle_error($id_detalle_error){
		$this->db->where('id', $id_detalle_error);
		$query = $this->db->get('errores_detalle');
		$detalle_error = $query->row();
		return $detalle_error->error_detalle;
	}
	
	/* Obtener tipo error/ticket COMPLETO -- Gestión/mail */
	public function get_detalle_error_completo($id_detalle_error){
		$this->db->where('id', $id_detalle_error);
		$query = $this->db->get('errores_detalle');
		$detalle_error = $query->row();
		return $detalle_error;
	}
	
	/* Obtener maquina/ticket -- Gestión */
	public function get_maquina($id_maquina){
		$this->db->where('id', $id_maquina);
		$query = $this->db->get('maquinas');
		if($query->num_rows() != 0){
			$maquina = $query->row();
			return $maquina->maquina;
		}else{
			$this->db->where('id', 0);
			$query = $this->db->get('maquinas');
			$maquina = $query->row();
			return $maquina->maquina;
		}
	}
	
	/* Obtener maquina/ticket COMPLETO -- Gestión/mail */
	public function get_maquina_completo($id_maquina){
		$this->db->where('id', $id_maquina);
		$query = $this->db->get('maquinas');
		$maquina = $query->row();
		return $maquina;
	}

	/* Destinos tecnicos guardias km/€ */
	public function get_destinos_guardias(){
		$this->db->order_by('km', 'DESC');
		return $this->db->get('guardias_destinos_km');
	}
	
	/* Obtener departamento/ticket -- Gestión */
	public function get_destino($id_destino){
		$this->db->where('id', $id_destino);
		$query = $this->db->get('grupos');
		$destino = $query->row();
		return $destino->nombre;
	}
	
	/* Obtener departamento/ticket COMPLETO -- Gestión */
	public function get_destino_completo($id_destino){
		$this->db->where('id', $id_destino);
		$query = $this->db->get('grupos');
		return $query->row();
	}
	
	/* Obtener departamento/operador ATC COMPLETO -- crear incidencia */
	public function get_destino_atc($op){
		$this->db->where('operadora', $op);
		$this->db->where_not_in('id', 244);
		$query = $this->db->get('grupos');
		return $query;
	}
	
	/* Obtener departamento/operador COMPLETO -- crear incidencia */
	public function get_destino_op($op){
		$this->db->where('operadora', $op);
		$this->db->where('nombre', 'SAT');
		$query = $this->db->get('grupos');
		return $query->row();
	}
	
	/* Obtener departamento/salon COMPLETO -- crear incidencia */
	public function get_destino_salon($salon){
		$this->db->where('id', $salon);
		$query = $this->db->get('salones');
		$salon = $query->row();
		
		$this->db->where('operadora', $salon->operadora);
		$this->db->where('nombre', 'SAT');
		$query = $this->db->get('grupos');
		return $query->row();
	}

	public function get_destino_adm_salon($salon){
		$this->db->where('id', $salon);
		$query = $this->db->get('salones');
		$salon = $query->row();
		
		$this->db->where('operadora', $salon->operadora);
		$this->db->where('nombre', 'ADM');
		$query = $this->db->get('grupos');
		return $query->row();
	}

	/* Obtener destino ADM operadora */
	public function get_destino_adm($op){
		$this->db->where('operadora', $op);
		$this->db->where('nombre', 'ADM');
		$query = $this->db->get('grupos');
		return $query->row();
	}
	
	/* Obtener creador -- Gestión */
	public function get_creador($id_creador){
		if(isset($id_creador)){
			$this->db->where('id', $id_creador);
			$query = $this->db->get('usuarios');
			$creador = $query->row();
			return $creador->usuario;
		}
	}
	
	/* Obtener creador entero -- Gestión */
	public function get_creador_completo($id_creador){
		$this->db->where('id', $id_creador);
		$query = $this->db->get('usuarios');
		return $query->row();
	}
	
	/* Obtener ultima edicion - Gestion */
	public function get_ultima_edicion($id){
		$this->db->where('id_ticket', $id);
		$this->db->order_by('id', 'desc');
		$this->db->limit(1);
		$query = $this->db->get('ediciones');
		foreach($query->result() as $edicion){
			return $edicion->situacion;
		}
	}
	
	/* Agrupar personal */
	public function agrupar_personal($col,$sql){
		if(isset($sql) && $sql != ''){
			if($this->session->userdata('logged_in')['rol'] == 6){
				$query = $sql.' group by '.$col;
			}
		}else{
			if($this->session->userdata('logged_in')['rol'] == 6){
				$query = 'SELECT * FROM personal group by '.$col;
			}
		}
		$query .= ' ORDER BY operadora ASC';
		return $this->db->query($query);
	}
	
	/* Agrupar personal */
	public function agrupar_promos($col){
		$query = 'SELECT * FROM aio_clientes_promo group by '.$col;
		$query .= ' ORDER BY salon ASC';
		return $this->db->query($query);
	}
	
	/* Agrupar visitas */
	public function agrupar_visitas($col,$sql){
		if(isset($sql) && $sql != ''){
			if($this->session->userdata('logged_in')['rol'] == 6){
				$query = $sql.' group by '.$col;
			}
		}else{
			if($this->session->userdata('logged_in')['rol'] == 6){
				$query = 'SELECT * FROM visitas group by '.$col;
			}
		}
		$query .= ' ORDER BY operadora ASC';
		return $this->db->query($query);
	}
	
	/* Agrupar locales */
	public function agrupar_locales($col,$sql){
		if(isset($sql) && $sql != ''){
			if($this->session->userdata('logged_in')['rol'] == 1 || $this->session->userdata('logged_in')['rol'] == 2 || $this->session->userdata('logged_in')['rol'] == 6 || $this->session->userdata('logged_in')['rol'] == 7){
				$query = 'SELECT * FROM salones WHERE 1 '.$sql.' group by '.$col;
			}else{
				$query = 'SELECT * FROM salones WHERE operadora = "'.$this->session->userdata('logged_in')['acceso'].'" '.$sql.' group by '.$col;
			}
		}else{
			if($this->session->userdata('logged_in')['rol'] == 1 || $this->session->userdata('logged_in')['rol'] == 2 || $this->session->userdata('logged_in')['rol'] == 6 || $this->session->userdata('logged_in')['rol'] == 7){
				$query = 'SELECT * FROM salones group by '.$col;
			}else{
				$query = 'SELECT * FROM salones WHERE operadora = "'.$this->session->userdata('logged_in')['acceso'].'" group by '.$col;
			}
		}
		$query .= ' ORDER BY operadora ASC';
		return $this->db->query($query);
	}
	
	/* Agrupar tickets */
	public function agrupar_tickets($col,$sql){
		if(isset($sql) && $sql != ''){
			if(($this->session->userdata('logged_in')['rol'] == 2 || $this->session->userdata('logged_in')['rol'] == 4 || $this->session->userdata('logged_in')['rol'] == 8) && $this->session->userdata('logged_in')['acceso'] == 24){
				switch($col){
					case "salon":
						$query = 'SELECT t.* FROM tickets AS t JOIN '.$col.'es AS s on s.id = t.'.$col.' WHERE 1 '.$sql.' group by t.'.$col.' ORDER BY s.'.$col.'';
						break;
					default:
						$query = 'SELECT * FROM tickets WHERE 1 '.$sql.' group by '.$col.' ORDER BY '.$col.' DESC';
						break;
				}
				$query = 'SELECT * FROM tickets WHERE 1 '.$sql.' group by '.$col.' ORDER BY '.$col.' DESC';
			}else if($this->session->userdata('logged_in')['rol'] == 1 || $this->session->userdata('logged_in')['rol'] == 7){
				switch($col){
					case "salon":
						$query = 'SELECT t.* FROM tickets AS t JOIN '.$col.'es AS s on s.id = t.'.$col.' WHERE 1 group by t.'.$col.' ORDER BY s.'.$col.'';
						break;
					case "operadora":
						$query = 'SELECT t.* FROM tickets AS t JOIN '.$col.'s AS s on s.id = t.'.$col.' WHERE 1 group by t.'.$col.' ORDER BY s.'.$col.'';
						break;
					default:
						$query = 'SELECT * FROM tickets WHERE 1 '.$sql.' group by '.$col.' ORDER BY '.$col.' DESC';
						break;
				}
			}else if($this->session->userdata('logged_in')['rol'] == 6){
				if($this->session->userdata('logged_in')['acceso'] == 24){
					$query = "SELECT * FROM tickets WHERE 1 AND destino IN (SELECT id FROM grupos WHERE (operadora = '".$this->session->userdata('logged_in')['acceso']."' OR operadora = '41')) ".$sql." group by ".$col." ORDER BY ".$col." DESC";
				}else{
					$query = "SELECT * FROM tickets WHERE 1 AND destino IN (SELECT id FROM grupos WHERE (nombre LIKE 'COM' OR nombre LIKE 'SAT') AND operadora = '".$this->session->userdata('logged_in')['acceso']."') ".$sql." group by ".$col." ORDER BY ".$col." DESC";
				}
			}else if($this->session->userdata('logged_in')['rol'] == 2 || $this->session->userdata('logged_in')['rol'] == 4 || $this->session->userdata('logged_in')['rol'] == 5){
				$query = "SELECT * FROM usuarios WHERE acceso = '".$this->session->userdata('logged_in')['acceso']."' OR acceso IN (SELECT id FROM salones WHERE operadora = '".$this->session->userdata('logged_in')['acceso']."')";
				$users = $this->db->query($query);
				$usuarios = array();
				foreach($users->result() as $user){
					array_push($usuarios, $user->id);
				}
				$grupos_query = "SELECT * FROM grupos WHERE operadora = '".$this->session->userdata('logged_in')['acceso']."' AND nombre LIKE 'SAT'";
				$grupos = $this->db->query($grupos_query);
				$grupo = $grupos->row();
				$sql .= " AND (creador IN (".implode(',', $usuarios).") OR destino = '".$grupo->id."' OR situacion = '13')";
				switch($col){
					case "salon":
						$query = 'SELECT t.* FROM tickets AS t JOIN '.$col.'es AS s on s.id = t.'.$col.' WHERE t.operadora = '.$this->session->userdata('logged_in')['acceso'].' '.$sql.' group by t.'.$col.' ORDER BY s.'.$col.'';
						break;
					default:
						$query = 'SELECT * FROM tickets WHERE operadora = '.$this->session->userdata('logged_in')['acceso'].' '.$sql.' group by '.$col.' ORDER BY '.$col.' DESC';
						break;
				}
			}else if($this->session->userdata('logged_in')['rol'] == 3){
				$query = "SELECT * FROM salones WHERE id = '".$this->session->userdata('logged_in')['acceso']."'";
				$salones = $this->db->query($query);
				$op = $salones->row();
				$query = "SELECT * FROM usuarios WHERE acceso = '".$op->operadora."' OR acceso IN (SELECT id FROM salones WHERE operadora = '".$op->operadora."')";
				$users = $this->db->query($query);
				$usuarios = array();
				foreach($users->result() as $user){
					array_push($usuarios, $user->id);
				}
				$grupos_query = "SELECT * FROM grupos WHERE operadora IN (SELECT operadora FROM salones WHERE id = '".$this->session->userdata('logged_in')['acceso']."') AND nombre LIKE 'SAT'";
				$grupos = $this->db->query($grupos_query);
				$grupo = $grupos->row();
				$sql .= " AND (creador IN (".implode(',', $usuarios).") OR destino = '".$grupo->id."')";
				$query = 'SELECT * FROM tickets WHERE salon = '.$this->session->userdata('logged_in')['acceso'].' '.$sql.' group by '.$col.' ORDER BY '.$col.' DESC';
			}
		}else{
			if(($this->session->userdata('logged_in')['rol'] == 2 || $this->session->userdata('logged_in')['rol'] == 4) && $this->session->userdata('logged_in')['acceso'] == 24){
				switch($col){
					case "salon":
						$query = 'SELECT t.* FROM tickets AS t JOIN '.$col.'es AS s on s.id = t.'.$col.' WHERE 1 AND (t.situacion = 2 OR t.situacion = 3 OR t.situacion = 13 OR t.situacion = 14) group by t.'.$col.' ORDER BY s.'.$col.'';
						break;
					default:
						$query = 'SELECT * FROM tickets WHERE 1 AND (situacion = 2 OR situacion = 3 OR situacion = 13 OR situacion = 14) group by '.$col.' ORDER BY '.$col.' DESC';
						break;
				}
			}else if($this->session->userdata('logged_in')['rol'] == 8){
				switch($col){
					case "salon":
						$query = 'SELECT t.* FROM tickets AS t JOIN '.$col.'es AS s on s.id = t.'.$col.' WHERE t.situacion = 19 group by t.'.$col.' ORDER BY s.'.$col.'';
						break;
					case "operadora":
						$query = 'SELECT t.* FROM tickets AS t JOIN '.$col.'s AS s on s.id = t.'.$col.' WHERE t.situacion = 19 group by t.'.$col.' ORDER BY s.'.$col.'';
						break;
					default:
						$query = 'SELECT * FROM tickets WHERE situacion = 19 group by '.$col.' ORDER BY '.$col.' DESC';
						break;
				}
			}else if($this->session->userdata('logged_in')['rol'] == 1 || $this->session->userdata('logged_in')['rol'] == 7){
				switch($col){
					case "salon":
						$query = 'SELECT t.* FROM tickets AS t JOIN '.$col.'es AS s on s.id = t.'.$col.' WHERE t.situacion != 6 AND t.situacion != 9 group by t.'.$col.' ORDER BY s.'.$col.'';
						break;
					case "operadora":
						$query = 'SELECT t.* FROM tickets AS t JOIN '.$col.'s AS s on s.id = t.'.$col.' WHERE t.situacion != 6 AND t.situacion != 9 group by t.'.$col.' ORDER BY s.'.$col.'';
						break;
					default:
						$query = 'SELECT * FROM tickets WHERE situacion != 6 AND situacion != 9 group by '.$col.' ORDER BY '.$col.' DESC';
						break;
				}				
			}else if($this->session->userdata('logged_in')['rol'] == 6){
				if($this->session->userdata('logged_in')['acceso'] == 24){
					$query = "SELECT * FROM tickets WHERE 1 AND situacion IN (1,2,4,5,11,12,13,14,16,17,19) AND destino IN (SELECT id FROM grupos WHERE (operadora = '".$this->session->userdata('logged_in')['acceso']."' OR operadora = '41')) ".$sql." group by ".$col." ORDER BY ".$col." DESC";
				}else{
					$query = "SELECT * FROM tickets WHERE 1 AND (situacion = '12' OR situacion = '4' OR situacion = '2') AND destino IN (SELECT id FROM grupos WHERE (nombre LIKE 'COM' OR nombre LIKE 'SAT') AND operadora = '".$this->session->userdata('logged_in')['acceso']."') ".$sql." group by ".$col." ORDER BY ".$col." DESC";
				}			
			}else if($this->session->userdata('logged_in')['rol'] == 2 || $this->session->userdata('logged_in')['rol'] == 4 || $this->session->userdata('logged_in')['rol'] == 5){
				$query = "SELECT * FROM usuarios WHERE acceso = '".$this->session->userdata('logged_in')['acceso']."' OR acceso IN (SELECT id FROM salones WHERE operadora = '".$this->session->userdata('logged_in')['acceso']."')";
				$users = $this->db->query($query);
				$usuarios = array();
				foreach($users->result() as $user){
					array_push($usuarios, $user->id);
				}
				$grupos_query = "SELECT * FROM grupos WHERE operadora = '".$this->session->userdata('logged_in')['acceso']."' AND nombre LIKE 'SAT'";
				$grupos = $this->db->query($grupos_query);
				$grupo = $grupos->row();
				switch($col){
					case "salon":
						$query = 'SELECT t.* FROM tickets AS t JOIN '.$col.'es AS s on s.id = t.'.$col.' WHERE (t.situacion = 2 OR t.situacion = 13) and t.operadora = '.$this->session->userdata('logged_in')['acceso'].' AND (t.creador IN ('.implode(",", $usuarios).') OR t.destino = "'.$grupo->id.'") group by t.'.$col.' ORDER BY s.'.$col.'';
						break;
					default:
						$query = 'SELECT * FROM tickets WHERE (situacion = 2 OR situacion = 13) and operadora = '.$this->session->userdata('logged_in')['acceso'].' AND (creador IN ('.implode(",", $usuarios).') OR destino = "'.$grupo->id.'") group by '.$col.' ORDER BY '.$col.' DESC';
						break;
				}
			}else if($this->session->userdata('logged_in')['rol'] == 3){
				$query = "SELECT * FROM salones WHERE id = '".$this->session->userdata('logged_in')['acceso']."'";
				$salones = $this->db->query($query);
				$op = $salones->row();
				$query = "SELECT * FROM usuarios WHERE acceso = '".$op->operadora."' OR acceso IN (SELECT id FROM salones WHERE operadora = '".$op->operadora."')";
				$users = $this->db->query($query);
				$usuarios = array();
				foreach($users->result() as $user){
					array_push($usuarios, $user->id);
				}
				$grupos_query = "SELECT * FROM grupos WHERE operadora IN (SELECT operadora FROM salones WHERE id = '".$this->session->userdata('logged_in')['acceso']."') AND nombre LIKE 'SAT'";
				$grupos = $this->db->query($grupos_query);
				$grupo = $grupos->row();
				$query = 'SELECT * FROM tickets WHERE (situacion = 2 OR situacion = 13) and salon = '.$this->session->userdata('logged_in')['acceso'].' AND (creador IN ('.implode(",", $usuarios).') OR destino = "'.$grupo->id.'") group by '.$col.' ORDER BY '.$col.' DESC';
			}
		}
		return $this->db->query($query);	
	}
	
	/* Obtener personal por grupo */
	public function get_personal_group($group,$val,$sql){
		if(isset($sql) && $sql != ''){
			if($this->session->userdata('logged_in')['rol'] == 6){
				$query = $sql;
			}
		}else{
			if($this->session->userdata('logged_in')['rol'] == 6){
				$query = 'SELECT * FROM personal WHERE '.$group.' = "'.$val.'"';
			}
		}
		$query .= ' ORDER BY operadora ASC';
		return $this->db->query($query);
	}
	
	/* Obtener promos por grupo */
	public function get_promos_group($group,$val){
		$query = 'SELECT * FROM aio_clientes_promo WHERE '.$group.' = "'.$val.'"';
		$query .= ' ORDER BY id DESC';
		return $this->db->query($query);
	}
	
	/* Obtener visitas por grupo */
	public function get_visitas_group($group,$val,$sql){
		if(isset($sql) && $sql != ''){
			if($this->session->userdata('logged_in')['rol'] == 6){
				$query = $sql;
			}
		}else{
			if($this->session->userdata('logged_in')['rol'] == 6){
				$query = 'SELECT * FROM visitas WHERE '.$group.' = "'.$val.'"';
			}
		}
		$query .= ' ORDER BY operadora ASC';
		return $this->db->query($query);
	}
	
	/* Obtener locales por grupo */
	public function get_locales_group($group,$val,$sql){
		if(isset($sql) && $sql != ''){
			if($this->session->userdata('logged_in')['rol'] == 1 || $this->session->userdata('logged_in')['rol'] == 6 || $this->session->userdata('logged_in')['rol'] == 7 || $this->session->userdata('logged_in')['id'] == 40){
				$query = 'SELECT * FROM salones WHERE '.$group.' = "'.$val.'" '.$sql;
			}else{
				$query = 'SELECT * FROM salones WHERE operadora = "'.$this->session->userdata('logged_in')['acceso'].'" AND '.$group.' = "'.$val.'" '.$sql;
			}
		}else{
			if($this->session->userdata('logged_in')['rol'] == 1 || $this->session->userdata('logged_in')['rol'] == 6 || $this->session->userdata('logged_in')['rol'] == 7 || $this->session->userdata('logged_in')['id'] == 40){
				$query = 'SELECT * FROM salones WHERE '.$group.' = "'.$val.'"';
			}else{
				$query = 'SELECT * FROM salones WHERE operadora = "'.$this->session->userdata('logged_in')['acceso'].'" AND '.$group.' = "'.$val.'"';
			}
		}
		$query .= ' ORDER BY operadora ASC';
		return $this->db->query($query);
	}
	
	/* Obtener tickets por grupo */
	public function get_ticket_group($group,$val,$sql){
		if(isset($sql) && $sql != ''){
			if(($this->session->userdata('logged_in')['rol'] == 2 || $this->session->userdata('logged_in')['rol'] == 4) && $this->session->userdata('logged_in')['acceso'] == 24){
				$query = 'SELECT * FROM tickets WHERE '.$group.' = "'.$val.'" '.$sql;
			}else if($this->session->userdata('logged_in')['rol'] == 1 || $this->session->userdata('logged_in')['rol'] == 7  || $this->session->userdata('logged_in')['rol'] == 8){
				$query = 'SELECT * FROM tickets WHERE '.$group.' = "'.$val.'" '.$sql;
			}else if($this->session->userdata('logged_in')['rol'] == 6){
				if($this->session->userdata('logged_in')['acceso'] == 24){
					$query = "SELECT * FROM tickets WHERE 1 AND destino IN (SELECT id FROM grupos WHERE (operadora = '".$this->session->userdata('logged_in')['acceso']."' OR operadora = '41' OR operadora = '49')) AND ".$group." = '".$val."' ".$sql;
				}else{
					$query = "SELECT * FROM tickets WHERE 1 AND destino IN (SELECT id FROM grupos WHERE (nombre LIKE 'COM' OR nombre LIKE 'SAT') AND operadora = '".$this->session->userdata('logged_in')['acceso']."') AND ".$group." = '".$val."' ".$sql;
				}
			}else if($this->session->userdata('logged_in')['acceso'] == 41){
				$query = "SELECT * FROM tickets WHERE (situacion = '2' OR situacion = '13') AND salon IN (SELECT id FROM salones WHERE empresa = 3) AND creador IN (SELECT id FROM usuarios WHERE acceso = 24) AND ".$group." = '".$val."' ".$sql;
			}else if($this->session->userdata('logged_in')['rol'] == 2 || $this->session->userdata('logged_in')['rol'] == 4 || $this->session->userdata('logged_in')['rol'] == 5){
				$query = "SELECT * FROM usuarios WHERE acceso = '".$this->session->userdata('logged_in')['acceso']."' OR acceso IN (SELECT id FROM salones WHERE operadora = '".$this->session->userdata('logged_in')['acceso']."')";
				$users = $this->db->query($query);
				$usuarios = array();
				foreach($users->result() as $user){
					array_push($usuarios, $user->id);
				}
				$grupos_query = "SELECT * FROM grupos WHERE operadora = '".$this->session->userdata('logged_in')['acceso']."' AND nombre LIKE 'SAT'";
				$grupos = $this->db->query($grupos_query);
				$grupo = $grupos->row(); 
				$sql .= " AND (creador IN (".implode(',', $usuarios).") OR destino = '".$grupo->id."' OR situacion = '13')";
				$query = 'SELECT * FROM tickets WHERE operadora = '.$this->session->userdata('logged_in')['acceso'].' AND '.$group.' = "'.$val.'" '.$sql;
			}else if($this->session->userdata('logged_in')['rol'] == 3){
				$query = "SELECT * FROM salones WHERE id = '".$this->session->userdata('logged_in')['acceso']."'";
				$salones = $this->db->query($query);
				$op = $salones->row();
				$query = "SELECT * FROM usuarios WHERE acceso = '".$op->operadora."' OR acceso IN (SELECT id FROM salones WHERE operadora = '".$op->operadora."')";
				$users = $this->db->query($query);
				$usuarios = array();
				foreach($users->result() as $user){
					array_push($usuarios, $user->id);
				}
				$grupos_query = "SELECT * FROM grupos WHERE operadora IN (SELECT operadora FROM salones WHERE id = '".$this->session->userdata('logged_in')['acceso']."') AND nombre LIKE 'SAT'";
				$grupos = $this->db->query($grupos_query);
				$grupo = $grupos->row();
				$sql .= " AND (creador IN (".implode(',', $usuarios).") OR destino = '".$grupo->id."')";
				$query = 'SELECT * FROM tickets WHERE salon = '.$this->session->userdata('logged_in')['acceso'].' AND '.$group.' = "'.$val.'" '.$sql;
			}
		}else{
			if($this->session->userdata('logged_in')['id'] == 571 || $this->session->userdata('logged_in')['id'] == 351){
				$query = 'SELECT * FROM tickets WHERE (situacion = 2 OR situacion = 3 OR situacion = 13 OR situacion = 14) AND '.$group.' = "'.$val.'"';
			}else if(($this->session->userdata('logged_in')['rol'] == 2 || $this->session->userdata('logged_in')['rol'] == 4) && $this->session->userdata('logged_in')['acceso'] == 24){
				$query = 'SELECT * FROM tickets WHERE (situacion = 2 OR situacion = 3 OR situacion = 13) AND '.$group.' = "'.$val.'"';
			}else if($this->session->userdata('logged_in')['rol'] == 1 || $this->session->userdata('logged_in')['rol'] == 7 || $this->session->userdata('logged_in')['rol'] == 8){
				$query = 'SELECT * FROM tickets WHERE situacion != 6 AND '.$group.' = "'.$val.'"';
			}else if($this->session->userdata('logged_in')['rol'] == 6){
				if($this->session->userdata('logged_in')['acceso'] == 24){
					$query = "SELECT * FROM tickets WHERE 1 AND situacion IN (1,2,4,5,11,12,13,14,16,17,19) AND destino IN (SELECT id FROM grupos WHERE (operadora = '".$this->session->userdata('logged_in')['acceso']."' OR operadora = '41')) AND ".$group." = '".$val."'";
				}else{
					$query = "SELECT * FROM tickets WHERE 1 AND (situacion = '12' OR situacion = '4' OR situacion = '2') AND destino IN (SELECT id FROM grupos WHERE (nombre LIKE 'COM' OR nombre LIKE 'SAT') AND operadora = '".$this->session->userdata('logged_in')['acceso']."') AND ".$group." = '".$val."'";
				}				
			}else if($this->session->userdata('logged_in')['acceso'] == 41){
				$query = "SELECT * FROM tickets WHERE (situacion = '2' OR situacion = '13') AND salon IN (SELECT id FROM salones WHERE empresa = 3) AND creador IN (SELECT id FROM usuarios WHERE acceso = 24) AND ".$group." = '".$val."'";
			}else if($this->session->userdata('logged_in')['rol'] == 2 || $this->session->userdata('logged_in')['rol'] == 4 || $this->session->userdata('logged_in')['rol'] == 5){
				$query = "SELECT * FROM usuarios WHERE acceso = '".$this->session->userdata('logged_in')['acceso']."' OR acceso IN (SELECT id FROM salones WHERE operadora = '".$this->session->userdata('logged_in')['acceso']."')";
				$users = $this->db->query($query);
				$usuarios = array();
				foreach($users->result() as $user){
					array_push($usuarios, $user->id);
				}
				$grupos_query = "SELECT * FROM grupos WHERE operadora = '".$this->session->userdata('logged_in')['acceso']."' AND nombre LIKE 'SAT'";
				$grupos = $this->db->query($grupos_query);
				$grupo = $grupos->row(); 
				$query = 'SELECT * FROM tickets WHERE (situacion = 2 OR situacion = 13) AND operadora = '.$this->session->userdata('logged_in')['acceso'].' AND '.$group.' = "'.$val.'" AND (creador IN ('.implode(",", $usuarios).') OR destino = "'.$grupo->id.'")';
			}else if($this->session->userdata('logged_in')['rol'] == 3){
				$query = "SELECT * FROM salones WHERE id = '".$this->session->userdata('logged_in')['acceso']."'";
				$salones = $this->db->query($query);
				$op = $salones->row();
				$query = "SELECT * FROM usuarios WHERE acceso = '".$op->operadora."' OR acceso IN (SELECT id FROM salones WHERE operadora = '".$op->operadora."')";
				$users = $this->db->query($query);
				$usuarios = array();
				foreach($users->result() as $user){
					array_push($usuarios, $user->id);
				}
				$grupos_query = "SELECT * FROM grupos WHERE operadora IN (SELECT operadora FROM salones WHERE id = '".$this->session->userdata('logged_in')['acceso']."') AND nombre LIKE 'SAT'";
				$grupos = $this->db->query($grupos_query);
				$grupo = $grupos->row();
				$query = 'SELECT * FROM tickets WHERE (situacion = 2 OR situacion = 13) AND salon = '.$this->session->userdata('logged_in')['acceso'].' AND '.$group.' = "'.$val.'" AND (creador IN ('.implode(",", $usuarios).') OR destino = "'.$grupo->id.'")';
			}
		}
		$query .= ' ORDER BY prioridad DESC, fecha_creacion desc, hora_creacion DESC';
		return $this->db->query($query);
	}
	
	/* Agrupar maquinas */
	public function agrupar_maquinas($col,$sql){
		if(isset($sql) && $sql != ''){
			if($this->session->userdata('logged_in')['rol'] == 1){
				$query= $sql." AND modelo IN (SELECT id FROM modelos_maquinas WHERE tipo_maquina = '10') group by ".$col."";
			}else if($this->session->userdata('logged_in')['rol'] == 6){
				$query = $sql." AND modelo IN (SELECT id FROM modelos_maquinas WHERE fabricante = 16 OR fabricante = 19 OR fabricante = 15) group by ".$col."";
			}else{
				$query= $sql." AND salon IN (SELECT id FROM salones WHERE operadora = ".$this->session->userdata('logged_in')['acceso'].") group by ".$col."";
			}
		}else{
			if($this->session->userdata('logged_in')['rol'] == 1){
				$query="SELECT * FROM maquinas WHERE modelo IN (SELECT id FROM modelos_maquinas WHERE tipo_maquina = '10') group by ".$col."";
			}else if($this->session->userdata('logged_in')['rol'] == 6){
				$query = "SELECT * FROM maquinas WHERE modelo IN (SELECT id FROM modelos_maquinas WHERE fabricante = 16 OR fabricante = 19 OR fabricante = 15) group by ".$col."";
			}else{
				$query="SELECT * FROM maquinas WHERE salon IN (SELECT id FROM salones WHERE operadora = ".$this->session->userdata('logged_in')['acceso'].") group by ".$col."";
			}
		}
		$query .= ' ORDER BY '.$col.' asc';
		return $this->db->query($query);	
	}
	
	/* Obtener maquinas por grupo */
	public function get_maquina_group($group,$val,$sql){
		if(isset($sql) && $sql != ''){
			if($this->session->userdata('logged_in')['rol'] == 1){
				$query= $sql." AND modelo IN (SELECT id FROM modelos_maquinas WHERE tipo_maquina = '10') AND ".$group." = '".$val."'";
			}else if($this->session->userdata('logged_in')['rol'] == 6){
				$query = $sql." AND modelo IN (SELECT id FROM modelos_maquinas WHERE fabricante = 16 OR fabricante = 19 OR fabricante = 15) AND ".$group." = '".$val."'";
			}else{
				$query= $sql." AND salon IN (SELECT id FROM salones WHERE operadora = ".$this->session->userdata('logged_in')['acceso'].") AND ".$group." = '".$val."'";
			}
		}else{
			if($this->session->userdata('logged_in')['rol'] == 1){
				$query = "SELECT * FROM maquinas WHERE ".$group." = '".$val."' AND modelo IN (SELECT id FROM modelos_maquinas WHERE tipo_maquina = '10')";
			}else if($this->session->userdata('logged_in')['rol'] == 6){
				$query = "SELECT * FROM maquinas WHERE ".$group." = '".$val."' AND modelo IN (SELECT id FROM modelos_maquinas WHERE fabricante = 16 OR fabricante = 19 OR fabricante = 15)";
			}else{
				$query = "SELECT * FROM maquinas WHERE ".$group." = '".$val."' AND salon IN (SELECT id FROM salones WHERE operadora = ".$this->session->userdata('logged_in')['acceso'].") AND ".$group." = '".$val."'";
			}
		}
		$query .= ' ORDER BY maquina asc';
		return $this->db->query($query);
	}
	
	/* Asignar ticket */
	public function asignar_ticket($id,$user,$prioridad){
		$this->db->where('id', $id);
		$query = $this->db->get('tickets');
		$result = $query->row();

		if($prioridad != 3){
			$priority = $prioridad;
		}else{
			$priority = $result->prioridad;
		}
		
		if($this->session->userdata('logged_in')['rol'] == 2 || ($result->asignado == 0 && $result->situacion != 6)){
			$this->db->set('asignado', $user);
			$this->db->set('prioridad', $priority);
			$this->db->where('id', $id);
			$this->db->update('tickets');
			return $result->situacion;
		}else{
			return false;
		}
	}

	/* Get monederos maquinas */
	public function get_monederos_maquinas(){
		$this->db->order_by('nombre', 'ASC');
		return $this->db->get('monederos_maquinas');
	}

	/* Get billeteros maquinas */
	public function get_billeteros_maquinas(){
		$this->db->order_by('nombre', 'ASC');
		return $this->db->get('billeteros_maquinas');
	}

	/* Get impresoras maquinas */
	public function get_impresoras_maquinas(){
		$this->db->order_by('nombre', 'ASC');
		return $this->db->get('impresoras_maquinas');
	}

	public function get_monedero($id){
		$this->db->where('id', $id);
		$query = $this->db->get('monederos_maquinas');
		return $query->row();
	}

	public function get_billetero($id){
		$this->db->where('id', $id);
		$query = $this->db->get('billeteros_maquinas');
		return $query->row();
	}

	public function get_impresora($id){
		$this->db->where('id', $id);
		$query = $this->db->get('impresoras_maquinas');
		return $query->row();
	}
	
	/* Solucionar ticket */
	public function solucionar_ticket($situacion,$ticket,$trata,$peri_ant,$peri_nue,$fecha,$hora,$img){
		$this->db->where('id', $ticket);
		$query = $this->db->get('tickets');
		$result = $query->row();
		
		if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== FALSE)
			$browser = 'Internet explorer';
	 	elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== FALSE) //For Supporting IE 11
	    	$browser = 'Internet explorer';
	 	elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox') !== FALSE)
	   		$browser = 'Mozilla Firefox';
	 	elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== FALSE)
	   		$browser = 'Google Chrome';
	 	elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== FALSE)
	   		$browser = "Opera Mini";
	 	elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Opera') !== FALSE)
	   		$browser = "Opera";
	 	elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Safari') !== FALSE)
	   		$browser = "Safari";
	 	else
	   		$browser = 'Unknown';
		   
		$trata_scape = addslashes($trata);
		
		$data = array(
			 'id_ticket' => $ticket,
		     'situacion' => $situacion,
			 'trata_desc' => $trata_scape,
			 'fecha_edicion' => $fecha,
			 'hora_edicion' => $hora,
			 'creador' => $this->session->userdata('logged_in')['id'],
			 'edicion_inicial' => 'NO',
			 'tipo_edicion' => 2,
			 'navegador' => $browser,
			 'ip' => $_SERVER['REMOTE_ADDR']
		);
		$this->db->insert('ediciones', $data);
		
		if($situacion == 6){
			$this->db->set('situacion', $situacion);
			$this->db->set('fecha_solucion', $fecha);
			$this->db->set('hora_solucion', $hora);
			$this->db->set('soluciona', $this->session->userdata('logged_in')['id']);
			$this->db->set('trata_desc', $result->trata_desc." ".$trata_scape);
			$this->db->set('imagen2', $img);
			$this->db->where('id', $ticket);
			$this->db->update('tickets');
		}else{
			if($result->situacion == 6){
				$this->db->set('situacion', $situacion);
				$this->db->set('fecha_solucion', NULL);
				$this->db->set('hora_solucion', NULL);
				$this->db->set('soluciona', NULL);
			}
			$this->db->set('fecha_tratamiento', $fecha);
			$this->db->set('hora_tratamiento', $hora);
			$this->db->set('tratamiento', $this->session->userdata('logged_in')['id']);
			$this->db->set('imagen2', $img);
			$this->db->where('id', $ticket);
			$this->db->update('tickets');
		}
		
		$this->db->where('ticket', $ticket);
		$this->db->where('estado', 0);
		$query = $this->db->get('control_tickets_cajeros_cron');
		if($query->num_rows() != 0){
			$this->db->set('estado', 1);
			$this->db->where('ticket', $ticket);
			$this->db->update('control_tickets_cajeros_cron');
		}

		if($peri_nue != 0){
			$this->db->set('maquina', $result->maquina);
			$this->db->where('id', $peri_nue);
			if($result->tipo_error == 91){
				$this->db->update('monederos_maquinas');
			}else if($result->tipo_error == 92){
				$this->db->update('billeteros_maquinas');
			}else if($result->tipo_error == 99){
				$this->db->update('impresoras_maquinas');
			}

			$this->db->set('maquina', 0);
			$this->db->where('id', $peri_ant);
			if($result->tipo_error == 91){
				$this->db->update('monederos_maquinas');
			}else if($result->tipo_error == 92){
				$this->db->update('billeteros_maquinas');
			}else if($result->tipo_error == 99){
				$this->db->update('impresoras_maquinas');
			}			
		}
	}
	
	/* Editar ticket */
	public function editar_ticket($ticket,$situacion,$trata,$fecha,$hora){
		$data = array(
			 'id_ticket' => $ticket,
		   'situacion' => $situacion,
			 'trata_desc' => $trata,
			 'fecha_edicion' => $fecha,
			 'hora_edicion' => $hora,
			 'creador' => $this->session->userdata('logged_in')['id'],
			 'edicion_inicial' => 'NO'
		);
		$this->db->insert('ediciones', $data);
		
		$this->db->set('situacion', $situacion);
		$this->db->where('id', $ticket);
		$this->db->update('tickets');
	}
	
	/* Obtener solucion edicion/ticket */
	public function get_solucion_incidencia($id){
		$this->db->where('id_ticket', $id);
		$this->db->where('tipo_edicion', 1);
		$this->db->where('situacion', 6);
		$this->db->order_by('id', 'desc');
		$this->db->limit(1);
		return $this->db->get('ediciones');
	}
	
	/* Obtener ultima edicion/ticket */
	public function get_ediciones_incidencia($id){
		$this->db->where('id_ticket', $id);
		$this->db->where('tipo_edicion', 1);
		$this->db->order_by('id', 'desc');
		$this->db->limit(1);
		return $this->db->get('ediciones');
	}
	
	/* Obtener tratamientos/ticket */
	public function get_ediciones($id){
		$this->db->where('id_ticket', $id);
		$this->db->where('tipo_edicion', 2);
		$this->db->where('edicion_inicial', 'NO');
		return $this->db->get('ediciones');
	}
	
	/* Obtener primera ediciones/crear ticket */
	public function get_edicion_inicial($id){
		$this->db->where('id_ticket', $id);
		$this->db->where('tipo_edicion', 2);
		$this->db->where('edicion_inicial', 'SI');
		$query = $this->db->get('ediciones');
		return $query->row();
	}
	
	/* Obtener asignados/operadora -- Editar ticket */
	public function get_asignados($op){
		if($op == 24){
			$query = "SELECT * FROM `usuarios` WHERE (acceso = '24' OR acceso = '41') AND (rol = '4' OR rol = '2' OR rol = '6') AND activo = '1' AND id != 73 order by nombre asc";
		}else{
			$query = "SELECT * FROM `usuarios` WHERE acceso = '".$op."' AND (rol = '4' OR rol = '2') AND activo = '1' order by nombre asc";
		}
		return $this->db->query($query);
	}
	
	/* Obtener asignados/operadora -- Editar ticket */
	public function get_asignado_actual($id){
		$query = "SELECT * FROM `usuarios` WHERE id = '".$id."'";
		$sql = $this->db->query($query);
		return $sql->row();
	}
	
	/* Get fabricantes - Nueva maquina */
	public function get_fabricantes(){
		$sql = "SELECT * FROM fabricantes WHERE id != '23' ORDER BY nombre ASC";
		return $this->db->query($sql);
	}
	
	/* Get fabricantes/averias - Nueva maquina - rol ATC */
	public function get_fabricantes_averias(){
		$sql = "SELECT * FROM fabricantes ORDER BY nombre ASC";
		return $this->db->query($sql);
	}
	
	/* Get modelos/fabricante - AJAX - nueva maquina */
	public function get_modelos($id){
		$query = "SELECT * FROM `modelos_maquinas` WHERE fabricante = ".$id." and (tipo_maquina = 1 || tipo_maquina = 2 || tipo_maquina = 3 || tipo_maquina = 4 || tipo_maquina = 5) order by modelo asc";
		return $this->db->query($query);
	}

	public function get_modelos_sat($id){
		$query = "SELECT * FROM `modelos_maquinas` WHERE fabricante = ".$id." and (tipo_maquina = 1 || tipo_maquina = 2 || tipo_maquina = 3 || tipo_maquina = 4 || tipo_maquina = 5 || tipo_maquina = 10) order by modelo asc";
		return $this->db->query($query);
	}
	
	public function get_modelos_averias($id){
		$query = "SELECT * FROM `modelos_maquinas` WHERE fabricante = ".$id." and (tipo_maquina = 1 || tipo_maquina = 10) order by modelo asc";
		return $this->db->query($query);
	}
	
	/* Get modelos/fabricante - AJAX - nueva maquina */
	public function get_modelo($id){
		$this->db->where('id', $id);
		$query = $this->db->get('modelos_maquinas');
		return $query->row();
	}
	
	/* Get tipos maquinas */
	public function get_tipos_maquinas(){
		$sql = "SELECT * FROM tipo_maquinas WHERE id != 4 AND id != 10";
		return $this->db->query($sql);
	}

	public function get_tipos_maquinas_averias(){
		$sql = "SELECT * FROM tipo_maquinas WHERE id = 10 OR id = 5";
		return $this->db->query($sql);
	}
	
	public function get_tipos_maquinas_com(){
		$sql = "SELECT * FROM tipo_maquinas WHERE id IN (SELECT tipo_maquina FROM modelos_maquinas WHERE fabricante = 12 OR fabricante = 22 OR fabricante = 23)";
		return $this->db->query($sql);
	}
	
	/* Crear nueva maquina*/
	public function crear_maquina($salon,$modelo,$puestos,$serie1,$serie2,$serie3){
		$this->db->where('id', $modelo);
		$query = $this->db->get('modelos_maquinas');
		$tipo = $query->row();
		if($tipo->tipo_maquina == 1){
			for ($i = 1; $i <= $puestos; $i++) {
			 	$data = array(
					 'salon' => $salon,
				   'modelo' => $modelo,
				   'maquina' => $tipo->modelo." P".$i
				);
				$this->db->insert('maquinas', $data); 
			}
		}else if($tipo->tipo_maquina == 3){
			if($puestos == 0){
				$data = array(
					 'salon' => $salon,
				   'modelo' => $modelo,
				   'maquina' => $tipo->modelo." JACKPOT"   
				);
				$this->db->insert('maquinas', $data);
			}
			if($puestos == 2){
				$data = array(
					 'salon' => $salon,
				   'modelo' => $modelo,
				   'maquina' => $tipo->modelo." ".$serie1   
				);
				$this->db->insert('maquinas', $data);
				$data = array(
					 'salon' => $salon,
				   'modelo' => $modelo,
				   'maquina' => $tipo->modelo." ".$serie2
				);
				$this->db->insert('maquinas', $data);
			}
			if($puestos == 3){
				$data = array(
					 'salon' => $salon,
				   'modelo' => $modelo,
				   'maquina' => $tipo->modelo." ".$serie1  
				);
				$this->db->insert('maquinas', $data);
				$data = array(
					 'salon' => $salon,
				   'modelo' => $modelo,
				   'maquina' => $tipo->modelo." ".$serie2   
				);
				$this->db->insert('maquinas', $data);
				$data = array(
					 'salon' => $salon,
				   'modelo' => $modelo,
				   'maquina' => $tipo->modelo." ".$serie3  
				);
				$this->db->insert('maquinas', $data);
			}
			if($puestos == 5){
				$data = array(
					 'salon' => $salon,
				   'modelo' => $modelo,
				   'maquina' => $tipo->modelo." ".$serie1  
				);
				$this->db->insert('maquinas', $data);
				$data = array(
					 'salon' => $salon,
				   'modelo' => $modelo,
				   'maquina' => $tipo->modelo." ".$serie2   
				);
				$this->db->insert('maquinas', $data);
				$data = array(
					 'salon' => $salon,
				   'modelo' => $modelo,
				   'maquina' => $tipo->modelo." ".$serie3  
				);
				$this->db->insert('maquinas', $data);
				$data = array(
					 'salon' => $salon,
				   'modelo' => $modelo,
				   'maquina' => $tipo->modelo." ".$serie4  
				);
				$this->db->insert('maquinas', $data);
				$data = array(
					 'salon' => $salon,
				   'modelo' => $modelo,
				   'maquina' => $tipo->modelo." ".$serie5 
				);
				$this->db->insert('maquinas', $data);
			}
		}else if($tipo->tipo_maquina == 10){
			$data = array(
			   'salon' => $salon,
			   'modelo' => $modelo,
			   'maquina' => $serie1  
			);
			$this->db->insert('maquinas', $data);
		}else{
			$data = array(
			   'salon' => $salon,
			   'modelo' => $modelo,
			   'maquina' => $tipo->modelo  
			);
			$this->db->insert('maquinas', $data);
		}
		$result = $salon." ".$modelo." ".$tipo->tipo_maquina." ".$puestos." ".$serie1." ".$serie2." ".$serie3;
		return $result;
	}
	
	/* Crear nueva maquina*/
	public function editar_maquina($id,$salon,$modelo,$monedero,$billetero,$impresora,$maquina){
		// Actualizar maquina
		$data = array(
			'salon' => $salon,
		   	'modelo' => $modelo,
		   	'maquina' => $maquina 
		);
		$this->db->where('id', $id);
		$this->db->update('maquinas', $data);

		// Actualizar monederos

		$this->db->set('maquina', 0);
		$this->db->where('maquina', $id);
		$this->db->update('monederos_maquinas');

		$this->db->set('maquina', $id);
		$this->db->where('id', $monedero);
		$this->db->update('monederos_maquinas');

		// Actualizar billeteros

		$this->db->set('maquina', 0);
		$this->db->where('maquina', $id);
		$this->db->update('billeteros_maquinas');

		$this->db->set('maquina', $id);
		$this->db->where('id', $billetero);
		$this->db->update('billeteros_maquinas');

		// Actualizar impresoras

		$this->db->set('maquina', 0);
		$this->db->where('maquina', $id);
		$this->db->update('impresoras_maquinas');

		$this->db->set('maquina', $id);
		$this->db->where('id', $impresora);
		$this->db->update('impresoras_maquinas');
	}

	/* Activar usuario */
	public function activar_usuario($id){
		$this->db->set('activo', 1);
		$this->db->where('id', $id);
		return $this->db->update('usuarios');
	}
	
	/* Eliminar maquina */
	public function borrar_maquina($id){
		$this->db->set('activo', 0);
		$this->db->where('id', $id);
		return $this->db->update('maquinas');
	}	
	
	/* Eliminar usuario */
	public function borrar_usuario($id){
		$this->db->set('activo', 0);
		$this->db->where('id', $id);
		return $this->db->update('usuarios');
	}
	
	/* Eliminar grupo */
	public function borrar_departamento($id){
		$this->db->where('id', $id);
		return $this->db->delete('grupos');
	}
	
	/* Eliminar solicitud */
	public function borrar_solicitud($id){
		$this->db->where('id', $id);
		return $this->db->delete('solicitud_mantenimiento');
	}
	
	/* Get fabricante */
	public function get_fabricante_modelo($modelo){
		$this->db->where('id', $modelo);
		$query = $this->db->get('modelos_maquinas');
		$modelos = $query->row();
		
		$this->db->where('id', $modelos->fabricante);
		$query = $this->db->get('fabricantes');
		return $query->row();
	}
	
	/* Listar total maquinas salon sin filtros */
	public function get_maquinas_salon($salon){
		$query="SELECT * FROM maquinas WHERE salon = ".$salon." AND activo = 1 order by maquina asc";
		return $this->db->query($query);
	}

	public function get_maquina_salon_tipo($salon,$tipo){
		$query="SELECT * FROM maquinas WHERE salon = ".$salon." AND modelo IN (SELECT id FROM modelos_maquinas WHERE tipo_maquina = 5)";
		$sql = $this->db->query($query);
		return $sql->row();
	}
	
	/* Listar maquinas */
	
	public function get_maquinas_operador($op){
		$query="SELECT * FROM maquinas WHERE salon IN (SELECT id FROM salones WHERE operadora = ".$op.") AND modelo IN (select id from modelos_maquinas where tipo_maquina != 10) AND activo = 1 order by salon,maquina asc";
		return $this->db->query($query);
	}
	
	public function get_maquinas_operador_tipo($op,$tipo){
		$query="SELECT * FROM maquinas WHERE salon IN (SELECT id FROM salones WHERE operadora = ".$op.") and modelo IN (select id from modelos_maquinas where tipo_maquina = '".$tipo."') AND activo = 1 order by salon,maquina asc";
		return $this->db->query($query);
	}
	
	public function get_maquinas_com(){
		$query="SELECT * FROM `maquinas` WHERE salon IN (SELECT id FROM salones WHERE activo = 1) AND modelo IN (120,121,122,131,182,191) order by salon,maquina ASC";
		return $this->db->query($query);
	}
	
	public function get_maquinas_com_tipo($tipo){
		$query="SELECT * FROM maquinas WHERE modelo IN (SELECT id FROM modelos_maquinas WHERE tipo_maquina = '".$tipo."' AND (fabricante = 16 OR fabricante = 19 OR fabricante = 15)) AND activo = 1 order by salon,maquina asc";
		return $this->db->query($query);
	}
	
	public function get_maquinas_com_salon($id){
		$query="SELECT * FROM maquinas WHERE salon = '".$id."' AND modelo IN (SELECT id FROM modelos_maquinas WHERE fabricante = 16 OR fabricante = 19 OR fabricante = 15) AND activo = 1 order by salon,maquina asc";
		return $this->db->query($query);
	}
	
	public function get_maquinas_com_salon_tipo($id,$tipo){
		$query="SELECT * FROM maquinas WHERE salon = '".$id."' AND modelo IN (SELECT id FROM modelos_maquinas WHERE tipo_maquina = '".$tipo."' AND (fabricante = 16 OR fabricante = 19 OR fabricante = 15)) AND activo = 1 order by salon,maquina asc";
		return $this->db->query($query);
	}
	
	public function get_maquinas_averias(){
		$query="SELECT * FROM `maquinas` WHERE salon IN (SELECT id FROM salones WHERE activo = 1) AND modelo IN (120,121,122,131,182,191) AND activo = 1 order by salon,maquina ASC";
		return $this->db->query($query);
	}
	
	public function get_maquinas_averias_identificador($id){
		$query="SELECT * FROM `maquinas` WHERE id = '".$id."' order by salon,maquina asc";
		return $this->db->query($query);
	}

	public function get_maquinas_averias_monedero($m){
		$query="SELECT * FROM `maquinas` WHERE id IN (SELECT maquina FROM monederos_maquinas WHERE id = '".$m."')";
		return $this->db->query($query);
	}

	public function get_maquinas_averias_billetero($b){
		$query="SELECT * FROM `maquinas` WHERE id IN (SELECT maquina FROM billeteros_maquinas WHERE id = '".$b."')";
		return $this->db->query($query);
	}

	public function get_maquinas_averias_impresora($i){
		$query="SELECT * FROM `maquinas` WHERE id IN (SELECT maquina FROM impresoras_maquinas WHERE id = '".$i."')";
		return $this->db->query($query);
	}
	
	public function get_maquinas_averias_tipo($tipo){
		$query="SELECT * FROM `maquinas` WHERE salon IN (SELECT id FROM salones WHERE (operadora = 24 OR operadora = 41)) AND (modelo IN (select id from modelos_maquinas where tipo_maquina = '".$tipo."')) AND activo = 1 order by salon,maquina ASC";
		return $this->db->query($query);
	}
	
	/* Listar maquinas pag */
	public function get_maquinas_operador_pag($op,$inicio,$tamanio){
		$query="SELECT * FROM maquinas WHERE salon IN (SELECT id FROM salones WHERE operadora = ".$op.") AND modelo IN (select id from modelos_maquinas where tipo_maquina != 10) AND activo = 1 order by salon,maquina asc limit ".$inicio.",".$tamanio."";
		return $this->db->query($query);
	}
	
	public function get_maquinas_operador_tipo_pag($op,$tipo,$inicio,$tamanio){
		$query="SELECT * FROM maquinas WHERE salon IN (SELECT id FROM salones WHERE operadora = ".$op.") and modelo IN (select id from modelos_maquinas where tipo_maquina = '".$tipo."') AND activo = 1 order by salon,maquina asc limit ".$inicio.",".$tamanio."";
		return $this->db->query($query);
	}
	
	public function get_maquinas_com_pag($inicio,$tamanio){
		$query="SELECT * FROM `maquinas` WHERE salon IN (SELECT id FROM salones WHERE activo = 1) AND modelo IN (120,121,122,131,182,191) order by salon,maquina ASC limit ".$inicio.",".$tamanio."";
		return $this->db->query($query);
	}
	
	public function get_maquinas_com_tipo_pag($tipo,$inicio,$tamanio){
		$query="SELECT * FROM maquinas WHERE modelo IN (SELECT id FROM modelos_maquinas WHERE tipo_maquina = '".$tipo."') AND activo = 1 order by salon,maquina asc limit ".$inicio.",".$tamanio."";
		return $this->db->query($query);
	}
	
	public function get_maquinas_com_salon_pag($id,$inicio,$tamanio){
		$query="SELECT * FROM maquinas WHERE salon = '".$id."' AND modelo IN (120,121,122,131,182,191) AND activo = 1 order by salon,maquina asc limit ".$inicio.",".$tamanio."";
		return $this->db->query($query);
	}
	
	public function get_maquinas_com_salon_tipo_pag($id,$tipo,$inicio,$tamanio){
		$query="SELECT * FROM maquinas WHERE salon = '".$id."' AND modelo IN (SELECT id FROM modelos_maquinas WHERE tipo_maquina = '".$tipo."') AND activo = 1 order by salon,maquina asc limit ".$inicio.",".$tamanio."";
		return $this->db->query($query);
	}
	
	public function get_maquinas_averias_pag($inicio,$tamanio){
		$query="SELECT * FROM `maquinas` WHERE salon IN (SELECT id FROM salones WHERE activo = 1) AND modelo IN (120,121,122,131,182,191) AND activo = 1 order by salon,maquina ASC limit ".$inicio.",".$tamanio."";
		return $this->db->query($query);
	}
	
	public function get_maquinas_averias_tipo_pag($tipo,$inicio,$tamanio){
		$query="SELECT * FROM `maquinas` WHERE salon IN (SELECT id FROM salones WHERE (operadora = 24 OR operadora = 41)) AND (modelo IN (select id from modelos_maquinas where tipo_maquina = '".$tipo."')) AND activo = 1 order by salon,maquina asc limit ".$inicio.",".$tamanio."";
		return $this->db->query($query);
	}	
	
	/* Listar maquinas/salon/tipo */
	public function get_maquinas_operador_salon($id){
		$query = "SELECT * FROM maquinas WHERE salon = ".$id." AND modelo IN (select id from modelos_maquinas where tipo_maquina != 10) AND activo = 1 ORDER BY salon,maquina asc";
		return $this->db->query($query);
	}
	
	public function get_maquinas_operador_salon_tipo($id,$tipo){
		$query = "SELECT * FROM maquinas WHERE salon = ".$id." and modelo IN (select id from modelos_maquinas where tipo_maquina = '".$tipo."') AND activo = 1 ORDER BY salon,maquina asc";
		return $this->db->query($query);
	}
	
	public function get_maquinas_averias_salon($id){
		$query = "SELECT * FROM `maquinas` WHERE salon = ".$id." AND modelo IN (120,121,122,131,182,191) order by salon,maquina ASC";
		return $this->db->query($query);
	}
	
	public function get_maquinas_averias_salon_2($id){
		$query = "SELECT * FROM maquinas WHERE salon = ".$id." AND modelo IN (select id from modelos_maquinas where tipo_maquina = 10) AND activo = 1 ORDER BY salon,maquina asc";
		return $this->db->query($query);
	}
	
	public function get_maquinas_averias_salon_identificador($salon,$id){
		$query = "SELECT * FROM maquinas WHERE salon = ".$salon." and id = '".$id."' AND activo = 1 ORDER BY salon,maquina asc";
		return $this->db->query($query);
	}
	
	public function get_maquinas_averias_salon_tipo($id,$tipo){
		$query = "SELECT * FROM maquinas WHERE salon = ".$id." and modelo IN (select id from modelos_maquinas where tipo_maquina = '".$tipo."') AND activo = 1 ORDER BY salon,maquina asc";
		return $this->db->query($query);
	}
	
	/* Listar maquinas/salon pag */
	public function get_maquinas_operador_salon_pag($id,$inicio,$tamanio){
		$query = "SELECT * FROM maquinas WHERE salon = ".$id." AND modelo IN (select id from modelos_maquinas where tipo_maquina != 10) AND activo = 1 ORDER BY salon,maquina asc limit ".$inicio.",".$tamanio."";
		return $this->db->query($query);
	}
	
	public function get_maquinas_operador_salon_tipo_pag($id,$tipo,$inicio,$tamanio){
		$query = "SELECT * FROM maquinas WHERE salon = ".$id." and modelo IN (select id from modelos_maquinas where tipo_maquina = '".$tipo."') AND activo = 1 ORDER BY salon,maquina asc limit ".$inicio.",".$tamanio."";
		return $this->db->query($query);
	}
	
	public function get_maquinas_averias_salon_pag($id,$inicio,$tamanio){
		$query = "SELECT * FROM `maquinas` WHERE salon = ".$id." AND modelo IN (120,121,122,131,182,191) order by salon,maquina ASC limit ".$inicio.",".$tamanio."";
		return $this->db->query($query);
	}
	
	public function get_maquinas_averias_salon_tipo_pag($id,$tipo,$inicio,$tamanio){
		$query = "SELECT * FROM maquinas WHERE salon = ".$id." and modelo IN (select id from modelos_maquinas where tipo_maquina = '".$tipo."') AND activo = 1 ORDER BY salon,maquina asc limit ".$inicio.",".$tamanio."";
		return $this->db->query($query);
	}
	
	public function get_maquinas_salon_contador($salon){
		$query = "SELECT * FROM maquinas WHERE salon = ".$salon." and modelo IN (select id from modelos_maquinas where (tipo_maquina = '1' OR tipo_maquina = '2' OR tipo_maquina = '3')) AND activo = 1 ORDER BY salon,maquina asc";
		return $this->db->query($query);
	}
	
	/* Get tipo maquina / puestos */
	public function get_puestos_modelo($id){
		$this->db->where('id', $id);
		$query = $this->db->get('modelos_maquinas');
		return $query->row();
	}
	
	/* Get Informes */
	public function get_informes_op($op){
		$this->db->where('situacion =', 6);
		$this->db->where('operadora', $op);
		$this->db->order_by('fecha_creacion','desc');
		return $this->db->get('tickets');
	}
	
	/* Obtener tickets por grupo */
	public function get_informe_group($group,$val,$sql){
		if(isset($sql) && $sql != ''){
			if($this->session->userdata('logged_in')['rol'] == 5){
				$query = 'SELECT * FROM tickets WHERE operadora = '.$this->session->userdata('logged_in')['acceso'].' AND situacion = 6 AND '.$group.' = "'.$val.'" '.$sql;
			}
		}else{
			if($this->session->userdata('logged_in')['rol'] == 5){
				$query = 'SELECT * FROM tickets WHERE operadora = '.$this->session->userdata('logged_in')['acceso'].' AND situacion = 6 AND '.$group.' = "'.$val.'"';
			}
		}
		$query .= ' ORDER BY fecha_creacion desc';
		return $this->db->query($query);
	}
	
	/* Agrupar informes */
	public function agrupar_informes($col,$sql){
		if(isset($sql) && $sql != ''){
			if($this->session->userdata('logged_in')['rol'] == 5){
				$query = 'SELECT * FROM tickets WHERE operadora = '.$this->session->userdata('logged_in')['acceso'].' '.$sql.' AND situacion = 6 group by '.$col;
			}
		}else{
			if($this->session->userdata('logged_in')['rol'] == 5){
				$query = 'SELECT * FROM tickets WHERE operadora = '.$this->session->userdata('logged_in')['acceso'].' AND situacion = 6 group by '.$col;
			}
		}
		$query .= ' ORDER BY fecha_creacion desc';
		return $this->db->query($query);	
	}
	
	/* Nuevo repostaje */
	public function nuevo_repostaje($u,$l,$k,$o,$m){
		$data = array(
			'usuario' => $u,
			'matricula' => $m,
			'repostaje' => $l,
			'kilometros' => $k,
			'operadora' => $o,
			'fecha' => date('Y-m-d')		
		);
		$this->db->insert('gasoil', $data);
		return true;
	}
	
	/* Get ultimos repostajes usuario */
	public function get_ultimos_respostajes($u){
		$this->db->where('usuario', $u);
		$this->db->order_by('id', 'desc');
		$this->db->limit(3);
		return $this->db->get('gasoil');
	}
	
	/* Get repostajes */
	public function get_repostajes($op,$u){
		if($u == 0){
			$sql = "SELECT * FROM gasoil WHERE operadora = '".$op."' order by id desc";
		}else{
			$sql = "SELECT * FROM gasoil WHERE operadora = '".$op."' AND usuario = '".$u."' order by id desc";
		}
		return $this->db->query($sql);
	}
	
	public function get_repostajes_fecha($op,$u,$i,$f){
		$fecha = '';
		if($i != ''){
			if (strpos($i, '/') !== false) {
				$i = explode('/', $i);
				$fi = $i[2]."-".$i[1]."-".$i[0];
			}else{
				$fi = $i;
			}
			$fecha .= " AND fecha >= '".$fi."'";
		}else{
			$fecha .= "";
		}
		if($f != ''){
			if (strpos($f, '/') !== false) {
				$f = explode('/', $f);
				$ff = $f[2]."-".$f[1]."-".$f[0];
			}else{
				$ff = $f;
			}
			$fecha .= " AND fecha <= '".$ff."'";
		}else{
			$fecha .= "";
		}
		if($u == 0){
			$sql = "SELECT * FROM gasoil WHERE operadora = '".$op."' ".$fecha." order by fecha desc";
		}else{
			$sql = "SELECT * FROM gasoil WHERE operadora = '".$op."' AND usuario = '".$u."' ".$fecha." order by fecha desc";
		}
		return $this->db->query($sql);
	}
	
	/* Get destino incidencia */
	public function get_destino_incidencia($op){
		$sql = "SELECT id FROM grupos WHERE operadora = '".$op."' AND nombre LIKE 'SAT'";
		$query = $this->db->query($sql);
		return $query->row();
	}

	/* Get empleados operadora */
	public function get_empleados_operadora($op){
		$sql = "SELECT * FROM usuarios WHERE (acceso = '".$op."' OR acceso IN (SELECT id FROM salones WHERE operadora = '".$op."')) AND rol != '1' AND activo = '1' AND id != '3' AND id != '73'";
		return $this->db->query($sql);
	}
	
	/* Get usuarios operadora */
	public function get_usuarios_operadora($op){
		$sql = "SELECT * FROM usuarios WHERE (acceso = '".$op."' OR acceso IN (SELECT id FROM salones WHERE operadora = '".$op."')) AND (rol = '1' OR rol = '2' OR rol = '4')";
		return $this->db->query($sql);
	}
	
	/* Get usuarios gasoil */
	public function get_usuarios_gasoil($op){
		$sql = "SELECT * FROM `usuarios` WHERE acceso = '".$op."' AND (rol = '4' || rol = '2' || rol = '6' || rol = '7') AND activo = 1";
		return $this->db->query($sql);
	}

	public function get_usuarios_registros_movimientos_locales($op){
		$sql = "SELECT * FROM `usuarios` WHERE acceso = '".$op."' AND (rol = '4' || rol = '2') AND activo = 1";
		return $this->db->query($sql);
	}
	
	/* vehiculos */
	public function get_vehiculos($op){
		$this->db->where('operadora', $op);
		return $this->db->get('vehiculos');
	}
	
	public function get_vehiculo($id){
		$this->db->where('id', $id);
		$query = $this->db->get('vehiculos');
		return $query->row();
	}
	
	/* Obtener TOTAL usuarios operadora - usuarios */
	public function get_usuarios_total($op){
		if($op == 24){
			$sql = "SELECT * FROM `usuarios` WHERE ((acceso = '".$op."' AND rol != 3) or acceso IN (select id from salones where operadora = '".$op."')) AND activo = 1 AND id != '3' AND id != '73' AND id != '1'";
		}else{
			$sql = "SELECT * FROM `usuarios` WHERE ((acceso = '".$op."' AND (rol = 2 OR rol = 4)) or acceso IN (select id from salones where operadora = '".$op."')) AND activo = 1";
		}
		return $this->db->query($sql);
	}
	
	/* Obtener usuarios operadora - usuarios */
	public function get_usuarios($op,$inicio,$tamanio){
		if($this->session->userdata('logged_in')['acceso'] == 2){
			$sql = "SELECT * FROM `usuarios` WHERE ((acceso = '".$op."' AND (rol = 2 OR rol = 4)) or acceso IN (select id from salones where operadora = '".$op."' AND id != '24')) AND id != '3' AND id != '73' AND id != '1' ORDER BY nombre ASC limit ".$inicio.",".$tamanio."";
		}else if($this->session->userdata('logged_in')['acceso'] == 24){
			$sql = "SELECT * FROM `usuarios` WHERE ((acceso = '".$op."' AND rol != 3) or acceso IN (select id from salones where operadora = '".$op."')) AND activo = 1 AND id != '73' AND id != '1' ORDER BY nombre ASC limit ".$inicio.",".$tamanio."";
		}else{
			$sql = "SELECT * FROM `usuarios` WHERE ((acceso = '".$op."' AND (rol = 2 OR rol = 4)) or acceso IN (select id from salones where operadora = '".$op."')) AND activo = 1 AND id != '73' AND id != '1' ORDER BY nombre ASC limit ".$inicio.",".$tamanio."";
		}
		return $this->db->query($sql);
	}

	/* Todos los usuario atc nueva incidencia nombre origen */
	public function get_usuarios_todos(){
		$this->db->where('activo', 1);
		$this->db->order_by('nombre', 'ASC');
		return $this->db->get('usuarios');
	}
	
	public function get_usuarios_salon($op,$r,$s,$inicio,$tamanio){
		if($op == 2){
			if($s == 24){
				if($r == '' || $r == 0){
					$sql = "SELECT * FROM `usuarios` WHERE acceso = '".$s."' AND id NOT IN (SELECT id FROM `usuarios` WHERE (acceso = 24 OR acceso = 41 OR acceso IN (SELECT id FROM salones WHERE (operadora = 24 OR operadora = 41))) AND (rol = 1 OR rol = 2 OR rol = 3 OR rol = 4 OR rol = '6' OR rol = '7')) limit ".$inicio.",".$tamanio."";
				}else{
					$sql = "SELECT * FROM `usuarios` WHERE acceso = '".$s."' AND id NOT IN (SELECT id FROM `usuarios` WHERE (acceso = 24 OR acceso = 41 OR acceso IN (SELECT id FROM salones WHERE (operadora = 24 OR operadora = 41))) AND (rol = ".$r.")) limit ".$inicio.",".$tamanio."";
				}				
			}else{
				if($s != '' && $s != 0){
					if($r == '' || $r == 0){
						$sql = "SELECT * FROM `usuarios` WHERE acceso = '".$s."' limit ".$inicio.",".$tamanio."";
					}else if($r == 3){
						$sql = "SELECT * FROM `usuarios` WHERE acceso = '".$s."' AND rol = ".$r." limit ".$inicio.",".$tamanio."";
					}else{
						$sql = "SELECT * FROM `usuarios` WHERE acceso = '".$s."' AND rol = ".$r." limit ".$inicio.",".$tamanio."";
					}
				}else{
					if($r == '' || $r == 0){
						$sql = "SELECT * FROM `usuarios` WHERE acceso = '".$op."' limit ".$inicio.",".$tamanio."";
					}else if($r == 3){
						$sql = "SELECT * FROM `usuarios` WHERE acceso IN (SELECT id FROM salones WHERE operadora = '".$op."') AND rol = ".$r." limit ".$inicio.",".$tamanio."";
					}else{
						$sql = "SELECT * FROM `usuarios` WHERE acceso = '".$op."' AND rol = ".$r." limit ".$inicio.",".$tamanio."";
					}
				}			
			}
		}else{
			if($s != '' && $s != 0){
				if($r == '' || $r == 0){
					$sql = "SELECT * FROM `usuarios` WHERE acceso = '".$s."' limit ".$inicio.",".$tamanio."";
				}else if($r == 3){
					$sql = "SELECT * FROM `usuarios` WHERE acceso = '".$s."' AND rol = ".$r." limit ".$inicio.",".$tamanio."";
				}else{
					$sql = "SELECT * FROM `usuarios` WHERE acceso = '".$s."' AND rol = ".$r." limit ".$inicio.",".$tamanio."";
				}
			}else{
				if($r == '' || $r == 0){
					$sql = "SELECT * FROM `usuarios` WHERE acceso = '".$op."' limit ".$inicio.",".$tamanio."";
				}else if($r == '3'){
					$sql = "SELECT * FROM `usuarios` WHERE acceso IN (SELECT id FROM salones WHERE operadora = '".$op."') AND rol = ".$r." limit ".$inicio.",".$tamanio."";
				}else{
					$sql = "SELECT * FROM `usuarios` WHERE acceso = '".$op."' AND rol = ".$r." limit ".$inicio.",".$tamanio."";
				}
			}			
		}
		return $this->db->query($sql);		
	}
	
	/* Obtener usuarios operadora - usuarios */
	public function get_usuario($id){
		$this->db->where('id', $id);
		$query = $this->db->get('usuarios');
		return $query->row();
	}

	public function get_usuario_nombre($usuario){
		$sql = "SELECT * FROM usuarios WHERE usuario LIKE '%".$usuario."%'";
		$query = $this->db->query($sql);
		return $query->row();
	}

	public function get_usuario_email($email){
		$sql = "SELECT * FROM usuarios WHERE email LIKE '%".$email."%' AND activo = 1 ORDER BY id ASC LIMIT 1";
		$query = $this->db->query($sql);
		return $query->row();
	}
	
	/* Obtener ultimo usuario - telegram */
	public function get_last_user(){
		$this->db->order_by('id', 'desc');
		$this->db->limit(1);
		$query = $this->db->get('usuarios');
		return $query->row();
	}
	
	/* Obtener acceso - nuevo usuario */
	public function get_acceso($acceso){
		$this->db->where('Activo', '1');
		$this->db->where('operadora', $acceso);
		return $this->db->get('salones');	
	}
	
	/* Crear usuario - nuevo usuario */
	public function crear_usuario($nombre,$email,$telefono,$usuario,$pass,$rol,$acceso,$jornada,$dias,$hora_inicio_jornada_mañana,$hora_fin_jornada_mañana,$hora_inicio_jornada_tarde,$hora_fin_jornada_tarde){
		// Comprobar duplicado
		$sql = $this->db->query("SELECT * FROM usuarios WHERE usuario LIKE '%".$usuario."%' or email LIKE '%".$usuario."%'");
		if($sql->num_rows() != 0){
			return false;
		}else{
			$contra = password_hash($pass, PASSWORD_DEFAULT);
			// Insertar usuario
			$data = array(
			   'nombre' => $nombre,
			   'email' => $email,
			   'telefono' => $telefono,
			   'usuario' => $usuario,
			   'pass' => $contra,
			   'rol' => $rol,
			   'acceso' => $acceso,
			   'jornada' => $jornada,
			   'hora_inicio_jornada_mañana' => $hora_inicio_jornada_mañana,
			   'hora_fin_jornada_mañana' => $hora_fin_jornada_mañana,
			   'hora_inicio_jornada_tarde' => $hora_inicio_jornada_tarde,
			   'hora_fin_jornada_tarde' => $hora_fin_jornada_tarde,
			   'jornada_dias' => $dias

			);
			$this->db->insert('usuarios', $data);

			if($rol == 2 || $rol == 4){
				$usuario = "tipster";
				$clave = "Fgwe3&38";

				try{	
				  $conn = new PDO('mysql:host=atc.averiasdemurcia.es;dbname=tipster; charset=utf8', $usuario, $clave);
				  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				}catch(PDOException $e){
				  echo "ERROR: " . $e->getMessage();
				}

				$sql = $conn->prepare("INSERT INTO usuarios (nombre, email, pass, dni, token_auth, fecha_alta, administradores_id, estado, ios, incidencias) VALUES ('".$nombre."', '".$email."', '".$contra."', '', '', '".date('Y-m-d H:i:s')."', NULL, 1, 0, 1)");
				$sql->execute();
			}
			return true;
		}
	}
	
	/* Crear departamento - nuevo departamento */
	public function crear_departamento($nombre,$email,$grupo,$acceso){
		$data = array(
			 'nombre' => $nombre,
		   'email' => $email,
		   'grupo' => $grupo,
		   'operadora' => $acceso
		);
		$this->db->insert('grupos', $data);
		return true;
	}
	
	/* Editar departamento - editar departamento */
	public function editar_departamento($id,$nombre,$email,$grupo,$acceso){
		$data = array(
			 'nombre' => $nombre,
		   'email' => $email,
		   'grupo' => $grupo,
		   'operadora' => $acceso
		);
		$this->db->where('id', $id);
		$this->db->update('grupos', $data);
		return true;
	}
	
	/* Editar usuario - editar usuario */
	public function editar_usuario($id,$nombre,$email,$telefono,$usuario,$pass,$rol,$acceso,$jornada,$dias,$hora_inicio_jornada_mañana,$hora_fin_jornada_mañana,$hora_inicio_jornada_tarde,$hora_fin_jornada_tarde,$activo){

		if($pass != ''){
			$contra = password_hash($pass, PASSWORD_DEFAULT);
		}
		
		if($rol == 2 || $rol == 4){
			$this->db->where('id', $id);
			$query = $this->db->get('usuarios');
			$user = $query->row();

			$db_usuario = "tipster";
			$db_clave = "Fgwe3&38";

			try{	
			  $conn = new PDO('mysql:host=atc.averiasdemurcia.es;dbname=tipster; charset=utf8', $db_usuario, $db_clave);
			  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			}catch(PDOException $e){
			  echo "ERROR: " . $e->getMessage();
			}

			$sql = $conn->prepare("SELECT * FROM usuarios WHERE email LIKE '%".$user->email."%'");
			$sql->execute();

			if($sql->rowCount() == 1){
				$user = $sql->fetch();
				if($pass == ''){
					$sql = $conn->prepare("UPDATE usuarios SET email = '".$email."' WHERE id = ".$user['id']."");
				}else{
					$sql = $conn->prepare("UPDATE usuarios SET email = '".$email."', pass = '".$contra."' WHERE id = ".$user['id']."");
				}
				$sql->execute();
			}			
		}

		if($pass == ''){
			$data = array(
			   'nombre' => $nombre,
			   'email' => $email,
			   'telefono' => $telefono,
			   'usuario' => $usuario,
			   'rol' => $rol,
			   'acceso' => $acceso,
			   'hora_inicio_jornada_mañana' => $hora_inicio_jornada_mañana,
			   'hora_fin_jornada_mañana' => $hora_fin_jornada_mañana,
			   'hora_inicio_jornada_tarde' => $hora_inicio_jornada_tarde,
			   'hora_fin_jornada_tarde' => $hora_fin_jornada_tarde,
			   'jornada_dias' => $dias,
			   'activo' => $activo
			);
		}else{
			$data = array(
				 'nombre' => $nombre,
			   'email' => $email,
			   'telefono' => $telefono,
			   'usuario' => $usuario,
			   'pass' => $contra,
			   'rol' => $rol,
			   'acceso' => $acceso,
			   'hora_inicio_jornada_mañana' => $hora_inicio_jornada_mañana,
			   'hora_fin_jornada_mañana' => $hora_fin_jornada_mañana,
			   'hora_inicio_jornada_tarde' => $hora_inicio_jornada_tarde,
			   'hora_fin_jornada_tarde' => $hora_fin_jornada_tarde,
			   'jornada_dias' => $dias
			);
		}
		$this->db->where('id', $id);
		$this->db->update('usuarios', $data);
		return true;				
	}
	
	public function actualizar_user_phone($id){
		$this->db->where('id', $id);
		$query = $this->db->get('usuarios');
		$user = $query->row();
		
		$data = array(
			'telefono' => '+34'.$user->telefono
		);
		$this->db->where('id', $id);
		$this->db->update('usuarios', $data);
		return true;
	}
	
	// Get salones cajeros
	public function get_salones_cajeros($op){
		if($this->session->userdata('logged_in')['acceso'] == 24 || $this->session->userdata('logged_in')['rol'] == 1){
			$sql = "SELECT * FROM salones WHERE Activo = '1' AND (operadora = '24' OR operadora = '41' OR id = '206') and salon not like '%BAR%' and id IN (SELECT salon FROM cajeros WHERE comprobacion_activa = '1')";
		}else{
			$sql = "SELECT * FROM salones WHERE Activo = '1' AND operadora = '".$op."' and salon not like '%BAR %' and id IN (SELECT salon FROM cajeros WHERE comprobacion_activa = '1')";
		}
		return $this->db->query($sql);
	}

	// Get salones cajeros
	public function get_salones_cajeros2($op){
		if($this->session->userdata('logged_in')['id'] == 352){
			$sql = "SELECT * FROM salones WHERE Activo = '1' AND (operadora = '7' OR operadora = '8' OR operadora = '9'  OR operadora = '11' OR operadora = '13' OR operadora = '14' OR operadora = '16' OR operadora = '18' OR operadora = '19' OR operadora = '20' OR operadora = '24') AND id IN (SELECT salon FROM cajeros)";
		}else if($this->session->userdata('logged_in')['acceso'] == 24 || $this->session->userdata('logged_in')['rol'] == 1){
			$sql = "SELECT * FROM salones WHERE Activo = '1' AND (operadora = '24' OR operadora = '41' OR id = '206') and salon not like '%BAR%' and id IN (SELECT salon FROM cajeros)";
		}else if($this->session->userdata('logged_in')['acceso'] == 6){
			// Excepcion temporal blazquez
			$sql = "SELECT * FROM salones WHERE Activo = '1' AND (operadora = '".$op."' OR operadora = '60') and salon not like '%BAR %' and id IN (SELECT salon FROM cajeros)";
		}else if($this->session->userdata('logged_in')['acceso'] == 41){
			$sql = "SELECT * FROM salones WHERE Activo = '1' AND empresa = 3 and salon not like '%BAR %' and id IN (SELECT salon FROM cajeros)";
		}else{
			$sql = "SELECT * FROM salones WHERE Activo = '1' AND operadora = '".$op."' and salon not like '%BAR %' and id IN (SELECT salon FROM cajeros)";
		}
		return $this->db->query($sql);
	}

	// Get salones credito
	public function get_salones_credito($op){
		if($op == 6){
			$sql = "SELECT * FROM salones WHERE Activo = '1' AND (operadora = ".$op." OR operadora = 60) AND id IN (SELECT salon FROM cajeros WHERE credito = 1)";
		}else{
			$sql = "SELECT * FROM salones WHERE Activo = '1' AND operadora = ".$op." AND id IN (SELECT salon FROM cajeros WHERE credito = 1)";
		}		
		return $this->db->query($sql);
	}

	/* Historial maquina errores */
	public function get_historial_maquina($id,$m){
		$hoy = strtotime("-6 months"); 
		$fecha = date("Y-m-d", $hoy);	
		$sql = "select * from tickets where maquina = '".$m."' and id != '".$id."' and fecha_creacion > '".$fecha."' order by id desc";
		return $this->db->query($sql);
	}
	
	public function get_historial_maquina_telegram($id,$m){
		$hoy = strtotime("-6 months"); 
		$fecha = date("Y-m-d", $hoy);	
		$sql = "select * from tickets where maquina = '".$m."' and id != '".$id."' order by id desc LIMIT 3";
		return $this->db->query($sql);
	}

	/* Historial cliente web */
	public function get_historial_cliente_web($id,$c){
		$c = preg_replace('/[^0-9]+/', '', $c);
		$sql = "select * from tickets where nombre LIKE '%".$c."%' and id != '".$id."' order by id desc LIMIT 3";
		return $this->db->query($sql);
	}
	
	/* Obtener gestion activa */
	public function get_gestion_activa($e){
		$this->db->where('id', $e);
		$activo = $this->db->get('empresas');
		return $activo->row();
	}
	
	/* Get tecnicos operadora */
	public function get_tecnicos_op($op){
		$this->db->where('acceso', $op);
		$this->db->where('rol', '4');
		$this->db->where('activo', '1');
		return $this->db->get('usuarios');
	}
	
	/* LOG */
	public function guardar_historial($u,$e){
		$fecha = date('d-m-Y H:i');
		if(isset($u) && $u != ''){
			$data = array(
				'usuario' => $u,
				'evento' => $e,
				'fecha' => $fecha
			);
			$this->db->insert('historial', $data);
		}			
	}

	/* Guardar historial perifericos */
	public function guardar_historial_perifericos($id,$a,$n){
		$fecha = date('Y-m-d H:i:s');
		$data = array(
				'ticket' => $id,
				'peri_ant' => $a,
				'peri_nue' => $n,
				'fecha' => $fecha
			);
		$this->db->insert('historial_perifericos', $data);
	}

	/* Obtener historial perifericos ticket */
	public function get_historial_periferico($id){
		$this->db->where('ticket', $id);
		$this->db->order_by('id', 'desc');
		$this->db->limit(1);
		$query = $this->db->get('historial_perifericos');
		return $query->row();
	}
	
	/* Obtener cajero */
	public function get_cajero($id,$maquina=NULL){
		if($id == 385){
			if(isset($maquina)){
				if($maquina == "CCM52"){
					$this->db->where('collect', '29');
				}else if($maquina == "CCM55"){
					$this->db->where('collect', '33');
				}
			}
		}
		$this->db->where('salon', $id);
		$query = $this->db->get('cajeros');
		return $query->row();
	}
	
	/* Editar cajero */
	public function editar_cajero($id,$cajero,$maquina,$ip,$puerto,$usuario,$clave,$collect,$limite_disponible,$limite_arqueo,$version,$limite_no_activo,$limite_multimoneda,$limite_hopper,$limite_reciclador1,$limite_reciclador2,$limite_reciclador3,$limite_reciclador4,$limite_reciclador5,$comprobar,$comprobar_credito,$ip_impresora,$puerto_impresora,$puerto_tpv,$digitos,$aux,$aux_num,$bloqueo,$comprobar_comision,$cantidad_comision,$limite_comision,$codigo_vip,$fecha_caducidad,$credito_espera,$tiempo_espera,$descripcion,$tipo_ticket){
		if($comprobar == 'on'){
	  		$activo = 1;
	  	}else{
	  		$activo = 0;
	  	}
	  	if($comprobar_credito == 'on'){
	  		$credito_activo = 1;
	  	}else{
	  		$credito_activo = 0;
	  	}
	  	if($aux == 'on'){
	  		$aux = 1;
	  	}else{
	  		$aux = 0;
	  	}
	  	if($bloqueo == 'on'){
	  		$bloqueo = 1;
	  		$bloqueo_camarero = 1;
	  	}else{
	  		$bloqueo = 0;
	  		$bloqueo_camarero = 0;
	  	}
	  	if($comprobar_comision == 'on'){
	  		$comprobar_comision = 1;
	  	}else{
	  		$comprobar_comision = 0;
	  		$cantidad_comision = NULL;
			$limite_comision = NULL;
			$codigo_vip = NULL;
			$fecha = NULL;
	  	}
	  	if(isset($fecha_caducidad) && $fecha_caducidad != ""){
	  		$fecha = explode("/", $fecha_caducidad);
	  		$fecha = $fecha[2]."-".$fecha[1]."-".$fecha[0];
	  	}else{
	  		$fecha = $fecha_caducidad;
	  	}
	  	if($credito_espera == 'on'){
	  		$credito_espera = 1;
	  	}else{
	  		$credito_espera = 0;
	  	}	  	
		$sql1 = "UPDATE maquinas SET maquina = '".$maquina."' WHERE id = '".$id."'";
		$this->db->query($sql1);
		$sql2 = "UPDATE cajeros SET servidor = '".$ip."', puerto = '".$puerto."', usuario = '".$usuario."', clave = '".$clave."', collect = '".$collect."', limite_disponible = '".$limite_disponible."', limite_arqueo = '".$limite_arqueo."', version = '".$version."', limite_no_activo = '".$limite_no_activo."', limite_multimoneda = '".$limite_multimoneda."', limite_hopper = '".$limite_hopper."', limite_reciclador_cassette1 = '".$limite_reciclador1."', limite_reciclador_cassette2 = '".$limite_reciclador2."', limite_reciclador_cassette3 = '".$limite_reciclador3."', limite_reciclador_cassette4 = '".$limite_reciclador4."', limite_reciclador_cassette5 = '".$limite_reciclador5."', comprobacion_activa = '".$activo."', credito = '".$credito_activo."', ip_impresora = '".$ip_impresora."', puerto_impresora = '".$puerto_impresora."', puerto_tpv = '".$puerto_tpv."', digitos = '".$digitos."', aux = '".$aux."', aux_num = '".$aux_num."', bloqueo = '".$bloqueo."', bloqueo_camarero = '".$bloqueo_camarero."', comision = '".$comprobar_comision."', cantidad_comision = '".$cantidad_comision."', limite_comision = '".$limite_comision."', codigo_vip = '".$codigo_vip."', fecha_caducidad_vip = '".$fecha."',tiempo_espera = '".$credito_espera."', duracion_espera = '".$tiempo_espera."', descripcion = '".$descripcion."', tipo_ticket = '".$tipo_ticket."' WHERE id = '".$cajero."'";
		$this->db->query($sql2);
		return true;
	}
	
	public function enviar_ticket_sat_adm($id){
		$this->db->set('situacion', 2);
		$this->db->set('asignado', 0);
		$this->db->set('destino', 4);
		$this->db->where('id', $id);
		$this->db->update('tickets');
		return true;
	}
	
	/* get IP servidor cajero */
	public function get_servidor_ticket($id){
		$this->db->where('id', $id);
		$query = $this->db->get('tickets');
		$ticket = $query->row();
		
		$this->db->where('salon', $ticket->salon);
		$query = $this->db->get('cajeros');
		if($query->num_rows() > 0){
			return $query->row();
		}else{
			return null;
		}
	}
	
	/* Get deposito operadora */
	public function get_deposito($op){
		$this->db->where('operadora', $op);
		return $this->db->get('deposito');
	}
	
	/* Get ultimo deposito operadora */
	public function get_ultimo_deposito($op){
		$this->db->where('operadora', $op);
		$this->db->order_by('id', 'desc');
		$this->db->limit(1);
		$sql = $this->db->get('deposito');
		return $sql->row();
	}
	
	/* Nuevo deposito gasoil */
	public function nuevo_deposito($litros,$fecha,$hora,$op){
		$fecha_db = explode("/", $fecha);
		$data = array(
			'deposito' => $litros,
			'operadora' => $op,
			'fecha' => $fecha_db[2]."-".$fecha_db[1]."-".$fecha_db[0],
			'hora' => $hora
		);
		$this->db->insert('deposito', $data);
		return true;
	}
	
	/* Get gasto deposito operadora */
	public function get_gasto_deposito($op){
		$sql = "SELECT * FROM gasoil WHERE usuario IN (SELECT id FROM usuarios WHERE acceso = '".$op."')";
		return $this->db->query($sql);
	}
	
	/* Get depositos */
	public function get_depositos($op){
		$sql = "SELECT * FROM deposito WHERE operadora = '".$op."' and id != 1 order by fecha desc";
		return $this->db->query($sql);
	}
	
	public function get_depositos_fecha($op,$i,$f){
		$fecha = '';
		if($i != ''){
			if (strpos($i, '/') !== false) {
				$i = explode('/', $i);
				$fi = $i[2]."-".$i[1]."-".$i[0];
			}else{
				$fi = $i;
			}
			$fecha .= " AND fecha >= '".$fi."'";
		}else{
			$fecha .= "";
		}
		if($f != ''){
			if (strpos($f, '/') !== false) {
				$f = explode('/', $f);
				$ff = $f[2]."-".$f[1]."-".$f[0];
			}else{
				$ff = $f;
			}
			$fecha .= " AND fecha <= '".$ff."'";
		}else{
			$fecha .= "";
		}
		$sql = "SELECT * FROM deposito WHERE operadora = '".$op."' and id != 1 ".$fecha." order by fecha desc";
		return $this->db->query($sql);
	}
	
	/* Get ruletas salones operadora */
	public function get_ruletas($op){
		$sql = "SELECT * FROM maquinas WHERE salon IN (select id from salones where operadora = '".$op."' and Activo = '1') AND modelo = '104' group by salon";
		return $this->db->query($sql);
	}
	
	/* Get servidor/ruleta */
	public function get_ruleta($id){
		$this->db->where('salon', $id);
		$query = $this->db->get('ruletas');
		if($query->num_rows() > 0){
			return $query->row();
		}else{
			return null;
		}
	}
	
	/* Get puestos ruleta */
	public function get_puestos_ruleta($id){		
		$sql = "SELECT COUNT(*) as puestos FROM `maquinas` WHERE salon = '".$id."' and modelo IN (SELECT id FROM modelos_maquinas WHERE tipo_maquina = '1')";
		$query = $this->db->query($sql);
		return $query->row();
	}
	
	/* Get limites ruleta */
	public function get_limites($id){
		$this->db->where('ruleta', $id);
		$query = $this->db->get('limites_ruletas');
		return $query->row();
	}
	
	/* zonas */
	public function get_zonas($op){
		$this->db->where('operadora', $op);
		return $this->db->get('zonas');
	}
	
	/* zonas */
	public function get_zona($id){
		$this->db->where('id', $id);
		$query = $this->db->get('zonas');
		return $query->row();
	}
	
	/* salones/zona */
	public function get_salones_zonas($zona){
		$sql = "SELECT * FROM salones_zonas WHERE zona = '".$zona."'";
		return $this->db->query($sql);
	}
	
	/* tecnicos/zona */
	public function get_tecnicos_zonas($zona){
		$sql = "SELECT * FROM tecnicos_zonas WHERE zona = '".$zona."'";
		return $this->db->query($sql);
	}
	
	/* nueva zona */
	public function nueva_zona($nombre,$op){
		$data = array(
			'zona' => $nombre,
			'operadora' => $op
		);
		$this->db->insert('zonas', $data);
		
		$this->db->order_by('id', 'desc');
		$this->db->limit(1);
		$query = $this->db->get('zonas');
		return $query->row();
	}
	
	/* Editar zona */
	public function editar_zona($id,$nombre,$op){
		$data = array(
			'zona' => $nombre
		);
		$this->db->where('id', $id);
		$this->db->update('zonas', $data);
		return true;
	}
	
	/* nuevo salon zona */
	public function nuevo_salon_zona($zona,$salon){
		$data = array(
			'zona' => $zona,
			'salon' => $salon
		);
		$this->db->insert('salones_zonas', $data);
	}
	
	/* nuevo tecnico zona */
	public function nuevo_tecnico_zona($zona,$tecnico){
		$data = array(
			'zona' => $zona,
			'tecnico' => $tecnico
		);
		$this->db->insert('tecnicos_zonas', $data);
	}
	
	/* Borrar salones zona */
	public function borrar_salones_zona($id){
		$this->db->where('zona', $id);
		return $this->db->delete('salones_zonas');
	}
	
	/* Borrar tecnicos zona */
	public function borrar_tecnicos_zona($id){
		$this->db->where('zona', $id);
		return $this->db->delete('tecnicos_zonas');
	}
	
	/* Eliminar zona */
	public function eliminar_zona($id){
		$this->db->where('zona', $id);
		$this->db->delete('tecnicos_zonas');
		
		$this->db->where('zona', $id);
		$this->db->delete('salones_zonas');
		
		$this->db->where('id', $id);
		return $this->db->delete('zonas');
	}

	/* Get zona de salon */
	public function get_zona_salon($s){
		$this->db->where('salon', $s);
		$query = $this->db->get('salones_zonas');
		return $query->row();
	}

	/* tecnico/zona */
	public function get_tecnico_zonas($z){
		$this->db->where('zona', $z);
		$query = $this->db->get('tecnicos_zonas');
		return $query->row();
	}
	
	/* Nuevo local */
	public function nuevo_local($direccion,$salon,$fecha,$poblacion,$telefono,$email,$horario,$ip_wan_euskaltel,$ip_lan_euskaltel,$ip_internet,$operadora,$activo){
		$data = array(
			'direccion' => $direccion,
			'salon' => $salon,
			'poblacion' => $poblacion,
			'telefono' => $telefono,
			'email' => $email,
			'horario' => $horario,
			'ip_lan_euskaltel' => $ip_lan_euskaltel,
			'ip_wan_euskaltel' => $ip_wan_euskaltel,
			'ip_internet' => $ip_internet,
			'operadora' => $operadora,
			'fecha_alta' => $fecha,
			'Activo' => $activo
		);
		$this->db->insert('salones', $data);
		return true;
	}
	
	/* Editar local */
	public function editar_local($id,$direccion,$fecha,$poblacion,$telefono,$email,$horario,$ip_wan_euskaltel,$ip_lan_euskaltel,$ip_internet,$operadora,$activo){
		$data = array(
			'direccion' => $direccion,
			'poblacion' => $poblacion,
			'telefono' => $telefono,
			'email' => $email,
			'horario' => $horario,
			'ip_lan_euskaltel' => $ip_lan_euskaltel,
			'ip_wan_euskaltel' => $ip_wan_euskaltel,
			'ip_internet' => $ip_internet,
			'operadora' => $operadora,
			'fecha_alta' => $fecha,
			'Activo' => $activo
		);
		$this->db->where('id', $id);
		$this->db->update('salones', $data);
		return true;
	}
	
	/* Activar/Desactivar local */
	public function activar_desactivar_local($a,$id){
		$this->db->set('Activo', $a);
		$this->db->where('id', $id);
		$this->db->update('salones');
		return true;
	}

	/* Get imagenes local incidencias */
	public function get_images_salon_ticket($id){
		$query = "SELECT * FROM tickets WHERE salon = '".$id."' AND ((imagen IS NOT NULL AND imagen != '') OR (imagen2 IS NOT NULL AND imagen2 != '')) ORDER BY id DESC";
		return $this->db->query($query);
	}
	
	/* Get imagenes local */
	public function get_images_salon($id){
		$this->db->where('salon', $id);
		return $this->db->get('salon_imagenes');
	}
	
	/* Get imagen */
	public function get_image($id){
		$this->db->where('id', $id);
		$img = $this->db->get('salon_imagenes');
		return $img->row();
	}
	
	/* Guardar imágen */
	public function save_image($salon,$img){
		
		for($i=0; $i < count($img); $i++){
		
			$data = array(
				'imagen' => $img[$i],
				'salon' => $salon
			);
			$this->db->insert('salon_imagenes', $data);
			
		}
	}
	
	/* Eliminar imágen */
	public function eliminar_imagen($id){
		$this->db->where('id', $id);
		$this->db->delete('salon_imagenes');
		return true;
	}
	
	/* Get personal locales */
	public function get_personal(){
		$query = "SELECT * FROM personal ORDER BY id DESC";
		return $this->db->query($query);
	}

	public function get_personal_test($t){
		$query = "SELECT * FROM personal WHERE test = '".$t."' ORDER BY id DESC";
		return $this->db->query($query);
	}

	/* Get personal locales */
	public function get_personal_activo($a){
		$query = "SELECT * FROM personal WHERE activo = '".$a."' ORDER BY id DESC";
		return $this->db->query($query);
	}

	public function get_personal_activo_test($a,$t){
		$query = "SELECT * FROM personal WHERE activo = '".$a."' AND test = '".$t."' ORDER BY id DESC";
		return $this->db->query($query);
	}

	/* Get personal locales */
	public function get_personal_curso($c){
		$query = "SELECT * FROM personal WHERE curso = '".$c."' ORDER BY id DESC";
		return $this->db->query($query);
	}

	public function get_personal_curso_test($c,$t){
		$query = "SELECT * FROM personal WHERE curso = '".$c."' AND test = '".$t."' ORDER BY id DESC";
		return $this->db->query($query);
	}

	/* Get personal locales */
	public function get_personal_activo_curso($a,$c){
		$query = "SELECT * FROM personal WHERE activo = '".$a."' AND curso = '".$c."' ORDER BY id DESC";
		return $this->db->query($query);
	}

	public function get_personal_activo_curso_test($a,$c,$t){
		$query = "SELECT * FROM personal WHERE activo = '".$a."' AND curso = '".$c."' AND test = '".$t."' ORDER BY id DESC";
		return $this->db->query($query);
	}

	/* Get personal locales por nobmre */
	public function get_personal_nombre(){
		$query = "SELECT * FROM personal ORDER BY nombre ASC";
		return $this->db->query($query);
	}
	
	public function buscar_personal($q){
		return $this->db->query($q);
	}

	public function filtrar_personal($n){
		return $this->db->query("SELECT * FROM personal WHERE nombre LIKE '%".$n."%' ORDER BY nombre");
	}

	public function filtrar_personal_ob($o){
		return $this->db->query("SELECT * FROM personal WHERE observaciones LIKE '%".$o."%' ORDER BY nombre");
	}

	public function filtrar_personal_test($n,$t){
		return $this->db->query("SELECT * FROM personal WHERE nombre LIKE '%".$n."%' AND test = '".$t."' ORDER BY nombre");
	}

	public function filtrar_personal_test_ob($o,$t){
		return $this->db->query("SELECT * FROM personal WHERE observaciones LIKE '%".$o."%' AND test = '".$t."' ORDER BY nombre");
	}

	public function filtrar_personal_curso($n,$c){
		return $this->db->query("SELECT * FROM personal WHERE nombre LIKE '%".$n."%' AND curso = '".$c."' ORDER BY nombre");
	}

	public function filtrar_personal_curso_ob($o,$c){
		return $this->db->query("SELECT * FROM personal WHERE observaciones LIKE '%".$o."%' AND curso = '".$c."' ORDER BY nombre");
	}

	public function filtrar_personal_curso_test($n,$c,$t){
		return $this->db->query("SELECT * FROM personal WHERE nombre LIKE '%".$n."%' AND curso = '".$c."' AND test = '".$t."' ORDER BY nombre");
	}

	public function filtrar_personal_curso_test_ob($o,$c,$t){
		return $this->db->query("SELECT * FROM personal WHERE observaciones LIKE '%".$o."%' AND curso = '".$c."' AND test = '".$t."' ORDER BY nombre");
	}

	public function filtrar_personal_activo($n,$a){
		return $this->db->query("SELECT * FROM personal WHERE nombre LIKE '%".$n."%' AND activo = '".$a."' ORDER BY nombre");
	}

	public function filtrar_personal_activo_ob($o,$a){
		return $this->db->query("SELECT * FROM personal WHERE observaciones LIKE '%".$o."%' AND activo = '".$a."' ORDER BY nombre");
	}

	public function filtrar_personal_activo_test($n,$a,$t){
		return $this->db->query("SELECT * FROM personal WHERE nombre LIKE '%".$n."%' AND activo = '".$a."' AND test = '".$t."' ORDER BY nombre");
	}

	public function filtrar_personal_activo_test_ob($o,$a,$t){
		return $this->db->query("SELECT * FROM personal WHERE observaciones LIKE '%".$o."%' AND activo = '".$a."' AND test = '".$t."' ORDER BY nombre");
	}

	public function filtrar_personal_activo_curso($n,$a,$c){
		return $this->db->query("SELECT * FROM personal WHERE nombre LIKE '%".$n."%' AND activo = '".$a."' AND curso = '".$c."' ORDER BY nombre");
	}

	public function filtrar_personal_activo_curso_ob($o,$a,$c){
		return $this->db->query("SELECT * FROM personal WHERE observaciones LIKE '%".$o."%' AND activo = '".$a."' AND curso = '".$c."' ORDER BY nombre");
	}

	public function filtrar_personal_activo_curso_test($n,$a,$c,$t){
		return $this->db->query("SELECT * FROM personal WHERE nombre LIKE '%".$n."%' AND activo = '".$a."' AND curso = '".$c."' AND test = '".$t."' ORDER BY nombre");
	}

	public function filtrar_personal_activo_curso_test_ob($o,$a,$c,$t){
		return $this->db->query("SELECT * FROM personal WHERE observaciones LIKE '%".$o."%' AND activo = '".$a."' AND curso = '".$c."' AND test = '".$t."' ORDER BY nombre");
	}

	/* Get personal nombre/operadora */
	public function get_personal_op_nombre($op,$n){
		return $this->db->query("SELECT * FROM personal WHERE operadora = '".$op."' AND nombre LIKE '%".$n."%' ORDER BY nombre");
	}

	public function get_personal_op_ob($op,$o){
		return $this->db->query("SELECT * FROM personal WHERE operadora = '".$op."' AND observaciones LIKE '%".$o."%' ORDER BY nombre");
	}

	public function get_personal_op_nombre_test($op,$n,$t){
		return $this->db->query("SELECT * FROM personal WHERE operadora = '".$op."' AND nombre LIKE '%".$n."%' AND test = '".$t."' ORDER BY nombre");
	}

	public function get_personal_op_ob_test($op,$o,$t){
		return $this->db->query("SELECT * FROM personal WHERE operadora = '".$op."' AND observaciones LIKE '%".$o."%' AND test = '".$t."' ORDER BY nombre");
	}

	/* Get personal nombre/operadora/curso */
	public function get_personal_op_nombre_curso($op,$n,$c){
		return $this->db->query("SELECT * FROM personal WHERE operadora = '".$op."' AND nombre LIKE '%".$n."%' AND curso = '".$c."' ORDER BY nombre");
	}

	public function get_personal_op_ob_curso($op,$o,$c){
		return $this->db->query("SELECT * FROM personal WHERE operadora = '".$op."' AND observaciones LIKE '%".$o."%' AND curso = '".$c."' ORDER BY nombre");
	}

	public function get_personal_op_nombre_curso_test($op,$n,$c,$t){
		return $this->db->query("SELECT * FROM personal WHERE operadora = '".$op."' AND nombre LIKE '%".$n."%' AND curso = '".$c."' AND test = '".$t."' ORDER BY nombre");
	}

	public function get_personal_op_ob_curso_test($op,$o,$c,$t){
		return $this->db->query("SELECT * FROM personal WHERE operadora = '".$op."' AND observaciones LIKE '%".$o."%' AND curso = '".$c."' AND test = '".$t."' ORDER BY nombre");
	}

	/* Get personal nombre/operadora/activo */
	public function get_personal_op_nombre_activo($op,$n,$a){
		return $this->db->query("SELECT * FROM personal WHERE operadora = '".$op."' AND nombre LIKE '%".$n."%' AND activo = '".$a."' ORDER BY nombre");
	}

	public function get_personal_op_ob_activo($op,$o,$a){
		return $this->db->query("SELECT * FROM personal WHERE operadora = '".$op."' AND observaciones LIKE '%".$o."%' AND activo = '".$a."' ORDER BY nombre");
	}

	public function get_personal_op_nombre_activo_test($op,$n,$a,$t){
		return $this->db->query("SELECT * FROM personal WHERE operadora = '".$op."' AND nombre LIKE '%".$n."%' AND activo = '".$a."' AND test = '".$t."' ORDER BY nombre");
	}

	public function get_personal_op_ob_activo_test($op,$o,$a,$t){
		return $this->db->query("SELECT * FROM personal WHERE operadora = '".$op."' AND observaciones LIKE '%".$o."%' AND activo = '".$a."' AND test = '".$t."' ORDER BY nombre");
	}

	/* Get personal nombre/operadora/activo/curso */
	public function get_personal_op_nombre_activo_curso($op,$n,$a,$c){
		return $this->db->query("SELECT * FROM personal WHERE operadora = '".$op."' AND nombre LIKE '%".$n."%' AND activo = '".$a."' AND curso = '".$c."' ORDER BY nombre");
	}

	public function get_personal_op_ob_activo_curso($op,$o,$a,$c){
		return $this->db->query("SELECT * FROM personal WHERE operadora = '".$op."' AND observaciones LIKE '%".$o."%' AND activo = '".$a."' AND curso = '".$c."' ORDER BY nombre");
	}

	public function get_personal_op_nombre_activo_curso_test($op,$n,$a,$c,$t){
		return $this->db->query("SELECT * FROM personal WHERE operadora = '".$op."' AND nombre LIKE '%".$n."%' AND activo = '".$a."' AND curso = '".$c."' AND test = '".$t."' ORDER BY nombre");
	}

	public function get_personal_op_ob_activo_curso_test($op,$o,$a,$c,$t){
		return $this->db->query("SELECT * FROM personal WHERE operadora = '".$op."' AND observaciones LIKE '%".$o."%' AND activo = '".$a."' AND curso = '".$c."' AND test = '".$t."' ORDER BY nombre");
	}
	
	/* Get personal/operadora */
	public function get_personal_op($op){
		$query = "SELECT * FROM personal WHERE operadora = '".$op."' ORDER BY nombre";
		return $this->db->query($query);
	}

	public function get_personal_salon_op($op,$s){
		if(isset($s) && $s != ''){
			$query = "SELECT * FROM personal WHERE salon = '".$s."' ORDER BY nombre";
		}else{
			$query = "SELECT * FROM personal WHERE operadora = '".$op."' ORDER BY nombre";
		}
		return $this->db->query($query);
	}

	public function get_personal_op_test($op,$t){
		$query = "SELECT * FROM personal WHERE operadora = '".$op."' AND test = '".$t."' ORDER BY nombre";
		return $this->db->query($query);
	}

	/* Get personal/operadora/curso */
	public function get_personal_op_curso($op,$c){
		$query = "SELECT * FROM personal WHERE operadora = '".$op."' AND curso = '".$c."' ORDER BY nombre";
		return $this->db->query($query);
	}

	public function get_personal_op_curso_test($op,$c,$t){
		$query = "SELECT * FROM personal WHERE operadora = '".$op."' AND curso = '".$c."' AND test = '".$t."' ORDER BY nombre";
		return $this->db->query($query);
	}

	/* Get personal/operadora/activo */
	public function get_personal_op_activo($op,$a){
		$query = "SELECT * FROM personal WHERE operadora = '".$op."' AND activo = '".$a."' ORDER BY nombre";
		return $this->db->query($query);
	}

	public function get_personal_op_activo_test($op,$a,$t){
		$query = "SELECT * FROM personal WHERE operadora = '".$op."' AND activo = '".$a."' AND test = '".$t."' ORDER BY nombre";
		return $this->db->query($query);
	}

	/* Get personal/operadora/activo/curso */
	public function get_personal_op_activo_curso($op,$a,$c){
		$query = "SELECT * FROM personal WHERE operadora = '".$op."' AND activo = '".$a."' AND curso = '".$c."' ORDER BY nombre";
		return $this->db->query($query);
	}

	public function get_personal_op_activo_curso_test($op,$a,$c,$t){
		$query = "SELECT * FROM personal WHERE operadora = '".$op."' AND activo = '".$a."' AND curso = '".$c."' AND test = '".$t."' ORDER BY nombre";
		return $this->db->query($query);
	}

	/* Get personal nombre/salon */
	public function get_personal_salon_nombre($salon,$n){
		return $this->db->query("SELECT * FROM personal WHERE salon = '".$salon."' AND nombre LIKE '%".$n."%' ORDER BY nombre");
	}

	public function get_personal_salon_ob($salon,$o){
		return $this->db->query("SELECT * FROM personal WHERE salon = '".$salon."' AND observaciones LIKE '%".$o."%' ORDER BY nombre");
	}

	public function get_personal_dni_nombre($dni,$n){
		return $this->db->query("SELECT * FROM personal WHERE dni LIKE '%".$dni."%' AND nombre LIKE '%".$n."%' ORDER BY nombre");
	}

	public function get_personal_dni_ob($dni,$o){
		return $this->db->query("SELECT * FROM personal WHERE dni LIKE '%".$dni."%' AND observaciones LIKE '%".$o."%' ORDER BY nombre");
	}

	public function get_personal_telefono_nombre($telefono,$n){
		return $this->db->query("SELECT * FROM personal WHERE telefono LIKE '%".$telefono."%' AND nombre LIKE '%".$n."%' ORDER BY nombre");
	}

	public function get_personal_telefono_ob($telefono,$o){
		return $this->db->query("SELECT * FROM personal WHERE telefono LIKE '%".$telefono."%' AND observaciones LIKE '%".$o."%' ORDER BY nombre");
	}

	public function get_personal_salon_nombre_test($salon,$n,$t){
		return $this->db->query("SELECT * FROM personal WHERE salon = '".$salon."' AND nombre LIKE '%".$n."%' AND test = '".$t."' ORDER BY nombre");
	}

	public function get_personal_salon_ob_test($salon,$o,$t){
		return $this->db->query("SELECT * FROM personal WHERE salon = '".$salon."' AND observaciones LIKE '%".$o."%' AND test = '".$t."' ORDER BY nombre");
	}

	public function get_personal_dni_nombre_test($dni,$n,$t){
		return $this->db->query("SELECT * FROM personal WHERE dni LIKE '%".$dni."%' AND nombre LIKE '%".$n."%' AND test = '".$t."' ORDER BY nombre");
	}

	public function get_personal_dni_ob_test($dni,$o,$t){
		return $this->db->query("SELECT * FROM personal WHERE dni LIKE '%".$dni."%' AND observaciones LIKE '%".$o."%' AND test = '".$t."' ORDER BY nombre");
	}

	public function get_personal_telefono_nombre_test($telefono,$n,$t){
		return $this->db->query("SELECT * FROM personal WHERE telefono LIKE '%".$telefono."%' AND nombre LIKE '%".$n."%' AND test = '".$t."' ORDER BY nombre");
	}

	public function get_personal_telefono_ob_test($telefono,$o,$t){
		return $this->db->query("SELECT * FROM personal WHERE telefono LIKE '%".$telefono."%' AND observaciones LIKE '%".$o."%' AND test = '".$t."' ORDER BY nombre");
	}

	/* Get personal nombre/salon/curso */
	public function get_personal_salon_nombre_curso($salon,$n,$c){
		return $this->db->query("SELECT * FROM personal WHERE salon = '".$salon."' AND nombre LIKE '%".$n."%' AND curso = '".$c."' ORDER BY nombre");
	}

	public function get_personal_salon_ob_curso($salon,$o,$c){
		return $this->db->query("SELECT * FROM personal WHERE salon = '".$salon."' AND observaciones LIKE '%".$o."%' AND curso = '".$c."' ORDER BY nombre");
	}

	public function get_personal_dni_nombre_curso($dni,$n,$c){
		return $this->db->query("SELECT * FROM personal WHERE dni LIKE '%".$dni."%' AND nombre LIKE '%".$n."%' AND curso = '".$c."' ORDER BY nombre");
	}

	public function get_personal_dni_ob_curso($dni,$o,$c){
		return $this->db->query("SELECT * FROM personal WHERE dni LIKE '%".$dni."%' AND observaciones LIKE '%".$o."%' AND curso = '".$c."' ORDER BY nombre");
	}

	public function get_personal_telefono_nombre_curso($telefono,$n,$c){
		return $this->db->query("SELECT * FROM personal WHERE telefono LIKE '%".$telefono."%' AND nombre LIKE '%".$n."%' AND curso = '".$c."' ORDER BY nombre");
	}

	public function get_personal_telefono_ob_curso($telefono,$o,$c){
		return $this->db->query("SELECT * FROM personal WHERE telefono LIKE '%".$telefono."%' AND observaciones LIKE '%".$o."%' AND curso = '".$c."' ORDER BY nombre");
	}

	public function get_personal_salon_nombre_curso_test($salon,$n,$c,$t){
		return $this->db->query("SELECT * FROM personal WHERE salon = '".$salon."' AND nombre LIKE '%".$n."%' AND curso = '".$c."' AND test = '".$t."' ORDER BY nombre");
	}

	public function get_personal_salon_ob_curso_test($salon,$o,$c,$t){
		return $this->db->query("SELECT * FROM personal WHERE salon = '".$salon."' AND observaciones LIKE '%".$o."%' AND curso = '".$c."' AND test = '".$t."' ORDER BY nombre");
	}

	public function get_personal_dni_nombre_curso_test($dni,$n,$c,$t){
		return $this->db->query("SELECT * FROM personal WHERE dni LIKE '%".$dni."%' AND nombre LIKE '%".$n."%' AND curso = '".$c."' AND test = '".$t."' ORDER BY nombre");
	}

	public function get_personal_dni_ob_curso_test($dni,$o,$c,$t){
		return $this->db->query("SELECT * FROM personal WHERE dni LIKE '%".$dni."%' AND observaciones LIKE '%".$o."%' AND curso = '".$c."' AND test = '".$t."' ORDER BY nombre");
	}

	public function get_personal_telefono_nombre_curso_test($telefono,$n,$c,$t){
		return $this->db->query("SELECT * FROM personal WHERE telefono LIKE '%".$telefono."%' AND nombre LIKE '%".$n."%' AND curso = '".$c."' AND test = '".$t."' ORDER BY nombre");
	}

	public function get_personal_telefono_ob_curso_test($telefono,$o,$c,$t){
		return $this->db->query("SELECT * FROM personal WHERE telefono LIKE '%".$telefono."%' AND observaciones LIKE '%".$o."%' AND curso = '".$c."' AND test = '".$t."' ORDER BY nombre");
	}

	/* Get personal nombre/salon/activo */
	public function get_personal_salon_nombre_activo($salon,$n,$a){
		return $this->db->query("SELECT * FROM personal WHERE salon = '".$salon."' AND nombre LIKE '%".$n."%' AND activo = '".$a."' ORDER BY nombre");
	}

	public function get_personal_salon_ob_activo($salon,$o,$a){
		return $this->db->query("SELECT * FROM personal WHERE salon = '".$salon."' AND observaciones LIKE '%".$o."%' AND activo = '".$a."' ORDER BY nombre");
	}

	public function get_personal_dni_nombre_activo($dni,$n,$a,$t){
		return $this->db->query("SELECT * FROM personal WHERE dni LIKE '%".$dni."%' AND nombre LIKE '%".$n."%' AND activo = '".$a."' AND test = '".$t."' ORDER BY nombre");
	}

	public function get_personal_dni_ob_activo($dni,$o,$a,$t){
		return $this->db->query("SELECT * FROM personal WHERE dni LIKE '%".$dni."%' AND observaciones LIKE '%".$o."%' AND activo = '".$a."' AND test = '".$t."' ORDER BY nombre");
	}

	public function get_personal_telefono_nombre_activo($telefono,$n,$a,$t){
		return $this->db->query("SELECT * FROM personal WHERE telefono LIKE '%".$telefono."%' AND nombre LIKE '%".$n."%' AND activo = '".$a."' AND test = '".$t."' ORDER BY nombre");
	}

	public function get_personal_telefono_ob_activo($telefono,$o,$a,$t){
		return $this->db->query("SELECT * FROM personal WHERE telefono LIKE '%".$telefono."%' AND observaciones LIKE '%".$o."%' AND activo = '".$a."' AND test = '".$t."' ORDER BY nombre");
	}

	public function get_personal_salon_nombre_activo_test($salon,$n,$a,$t){
		return $this->db->query("SELECT * FROM personal WHERE salon = '".$salon."' AND nombre LIKE '%".$n."%' AND activo = '".$a."' AND test = '".$t."' ORDER BY nombre");
	}

	public function get_personal_salon_ob_activo_test($salon,$o,$a,$t){
		return $this->db->query("SELECT * FROM personal WHERE salon = '".$salon."' AND observaciones LIKE '%".$o."%' AND activo = '".$a."' AND test = '".$t."' ORDER BY nombre");
	}

	public function get_personal_dni_nombre_activo_test($dni,$n,$a,$t){
		return $this->db->query("SELECT * FROM personal WHERE dni LIKE '%".$dni."%' AND nombre LIKE '%".$n."%' AND activo = '".$a."' AND test = '".$t."' ORDER BY nombre");
	}

	public function get_personal_dni_ob_activo_test($dni,$o,$a,$t){
		return $this->db->query("SELECT * FROM personal WHERE dni LIKE '%".$dni."%' AND observaciones LIKE '%".$o."%' AND activo = '".$a."' AND test = '".$t."' ORDER BY nombre");
	}

	public function get_personal_telefono_nombre_activo_test($telefono,$n,$a,$t){
		return $this->db->query("SELECT * FROM personal WHERE telefono LIKE '%".$telefono."%' AND nombre LIKE '%".$n."%' AND activo = '".$a."' AND test = '".$t."' ORDER BY nombre");
	}

	public function get_personal_telefono_ob_activo_test($telefono,$o,$a,$t){
		return $this->db->query("SELECT * FROM personal WHERE telefono LIKE '%".$telefono."%' AND observaciones LIKE '%".$o."%' AND activo = '".$a."' AND test = '".$t."' ORDER BY nombre");
	}

	/* Get personal nombre/salon/activo/curso */
	public function get_personal_salon_nombre_activo_curso($salon,$n,$a,$c){
		return $this->db->query("SELECT * FROM personal WHERE salon = '".$salon."' AND nombre LIKE '%".$n."%' AND activo = '".$a."' AND curso = '".$c."' ORDER BY nombre");
	}

	public function get_personal_salon_ob_activo_curso($salon,$o,$a,$c){
		return $this->db->query("SELECT * FROM personal WHERE salon = '".$salon."' AND observaciones LIKE '%".$o."%' AND activo = '".$a."' AND curso = '".$c."' ORDER BY nombre");
	}

	public function get_personal_dni_nombre_activo_curso($dni,$n,$a,$c){
		return $this->db->query("SELECT * FROM personal WHERE dni LIKE '%".$dni."%' AND nombre LIKE '%".$n."%' AND activo = '".$a."' AND curso = '".$c."' ORDER BY nombre");
	}

	public function get_personal_dni_ob_activo_curso($dni,$o,$a,$c){
		return $this->db->query("SELECT * FROM personal WHERE dni LIKE '%".$dni."%' AND observaciones LIKE '%".$o."%' AND activo = '".$a."' AND curso = '".$c."' ORDER BY nombre");
	}

	public function get_personal_telefono_nombre_activo_curso($telefono,$n,$a,$c){
		return $this->db->query("SELECT * FROM personal WHERE telefono LIKE '%".$telefono."%' AND nombre LIKE '%".$n."%' AND activo = '".$a."' AND curso = '".$c."' ORDER BY nombre");
	}

	public function get_personal_telefono_ob_activo_curso($telefono,$o,$a,$c){
		return $this->db->query("SELECT * FROM personal WHERE telefono LIKE '%".$telefono."%' AND observaciones LIKE '%".$o."%' AND activo = '".$a."' AND curso = '".$c."' ORDER BY nombre");
	}
	
	public function get_personal_salon_nombre_activo_curso_test($salon,$n,$a,$c,$t){
		return $this->db->query("SELECT * FROM personal WHERE salon = '".$salon."' AND nombre LIKE '%".$n."%' AND activo = '".$a."' AND curso = '".$c."' AND test = '".$t."' ORDER BY nombre");
	}

	public function get_personal_salon_ob_activo_curso_test($salon,$o,$a,$c,$t){
		return $this->db->query("SELECT * FROM personal WHERE salon = '".$salon."' AND observaciones LIKE '%".$o."%' AND activo = '".$a."' AND curso = '".$c."' AND test = '".$t."' ORDER BY nombre");
	}

	public function get_personal_dni_nombre_activo_curso_test($dni,$n,$a,$c,$t){
		return $this->db->query("SELECT * FROM personal WHERE dni LIKE '%".$dni."%' AND nombre LIKE '%".$n."%' AND activo = '".$a."' AND curso = '".$c."' AND test = '".$t."' ORDER BY nombre");
	}

	public function get_personal_dni_ob_activo_curso_test($dni,$o,$a,$c,$t){
		return $this->db->query("SELECT * FROM personal WHERE dni LIKE '%".$dni."%' AND observaciones LIKE '%".$o."%' AND activo = '".$a."' AND curso = '".$c."' AND test = '".$t."' ORDER BY nombre");
	}

	public function get_personal_telefono_nombre_activo_curso_test($telefono,$n,$a,$c,$t){
		return $this->db->query("SELECT * FROM personal WHERE telefono LIKE '%".$telefono."%' AND nombre LIKE '%".$n."%' AND activo = '".$a."' AND curso = '".$c."' AND test = '".$t."' ORDER BY nombre");
	}

	public function get_personal_telefono_ob_activo_curso_test($telefono,$o,$a,$c,$t){
		return $this->db->query("SELECT * FROM personal WHERE telefono LIKE '%".$telefono."%' AND observaciones LIKE '%".$o."%' AND activo = '".$a."' AND curso = '".$c."' AND test = '".$t."' ORDER BY nombre");
	}

	public function get_personal_supervisora_salon($sp,$s){
		return $this->db->query("SELECT * FROM personal WHERE salon = '".$s."' AND creador = '".$sp."' ORDER BY nombre");
	}

	public function get_personal_supervisora_op($sp,$o){
		return $this->db->query("SELECT * FROM personal WHERE operadora = '".$o."' AND creador = '".$sp."' ORDER BY nombre");
	}

	public function get_personal_supervisora($sp){
		return $this->db->query("SELECT * FROM personal WHERE creador = '".$sp."' ORDER BY nombre");
	}

	/* Get personal/salon */
	public function get_personal_salon($salon){
		$query = "SELECT * FROM personal WHERE salon = '".$salon."' ORDER BY nombre";
		return $this->db->query($query);
	}

	public function get_personal_dni($dni){
		$query = "SELECT * FROM personal WHERE dni LIKE '%".$dni."%' ORDER BY nombre";
		return $this->db->query($query);
	}

	public function get_personal_telefono($telefono){
		$query = "SELECT * FROM personal WHERE telefono LIKE '%".$telefono."%' ORDER BY nombre";
		return $this->db->query($query);
	}

	public function get_personal_salon_test($salon,$t){
		$query = "SELECT * FROM personal WHERE salon = '".$salon."' AND test = '".$t."' ORDER BY nombre";
		return $this->db->query($query);
	}

	public function get_personal_dni_test($dni,$t){
		$query = "SELECT * FROM personal WHERE dni LIKE '%".$dni."%' AND test = '".$t."' ORDER BY nombre";
		return $this->db->query($query);
	}

	public function get_personal_telefono_test($telefono,$t){
		$query = "SELECT * FROM personal WHERE telefono LIKE '%".$telefono."%' AND test = '".$t."' ORDER BY nombre";
		return $this->db->query($query);
	}

	/* Get personal/salon/curso */
	public function get_personal_salon_curso($salon,$c){
		$query = "SELECT * FROM personal WHERE salon = '".$salon."' AND curso = '".$c."' ORDER BY nombre";
		return $this->db->query($query);
	}

	public function get_personal_dni_curso($dni,$c){
		$query = "SELECT * FROM personal WHERE dni LIKE '%".$dni."%' AND curso = '".$c."' ORDER BY nombre";
		return $this->db->query($query);
	}

	public function get_personal_telefono_curso($telefono,$c){
		$query = "SELECT * FROM personal WHERE telefono LIKE '%".$telefono."%' AND curso = '".$c."' ORDER BY nombre";
		return $this->db->query($query);
	}

	public function get_personal_salon_curso_test($salon,$c,$t){
		$query = "SELECT * FROM personal WHERE salon = '".$salon."' AND curso = '".$c."' AND test = '".$t."' ORDER BY nombre";
		return $this->db->query($query);
	}

	public function get_personal_dni_curso_test($dni,$c,$t){
		$query = "SELECT * FROM personal WHERE dni LIKE '%".$dni."%' AND curso = '".$c."' AND test = '".$t."' ORDER BY nombre";
		return $this->db->query($query);
	}

	public function get_personal_telefono_curso_test($telefono,$c,$t){
		$query = "SELECT * FROM personal WHERE telefono LIKE '%".$telefono."%' AND curso = '".$c."' AND test = '".$t."' ORDER BY nombre";
		return $this->db->query($query);
	}

	/* Get personal/salon/activo */
	public function get_personal_salon_activo($salon,$a){
		$query = "SELECT * FROM personal WHERE salon = '".$salon."' AND activo = '".$a."' ORDER BY nombre";
		return $this->db->query($query);
	}

	public function get_personal_dni_activo($dni,$a){
		$query = "SELECT * FROM personal WHERE dni LIKE '%".$dni."%' AND activo = '".$a."' ORDER BY nombre";
		return $this->db->query($query);
	}

	public function get_personal_telefono_activo($telefono,$a){
		$query = "SELECT * FROM personal WHERE telefono LIKE '%".$telefono."%' AND activo = '".$a."' ORDER BY nombre";
		return $this->db->query($query);
	}

	public function get_personal_salon_activo_test($salon,$a,$t){
		$query = "SELECT * FROM personal WHERE salon = '".$salon."' AND activo = '".$a."' AND test = '".$t."' ORDER BY nombre";
		return $this->db->query($query);
	}

	public function get_personal_dni_activo_test($dni,$a,$t){
		$query = "SELECT * FROM personal WHERE dni LIKE '%".$dni."%' AND activo = '".$a."' AND test = '".$t."' ORDER BY nombre";
		return $this->db->query($query);
	}

	public function get_personal_telefono_activo_test($telefono,$a,$t){
		$query = "SELECT * FROM personal WHERE telefono LIKE '%".$telefono."%' AND activo = '".$a."' AND test = '".$t."' ORDER BY nombre";
		return $this->db->query($query);
	}

	/* Get personal/salon/activo/curso */
	public function get_personal_salon_activo_curso($salon,$a,$c){
		$query = "SELECT * FROM personal WHERE salon = '".$salon."' AND activo = '".$a."' AND curso = '".$c."' ORDER BY nombre";
		return $this->db->query($query);
	}

	public function get_personal_dni_activo_curso($dni,$a,$c){
		$query = "SELECT * FROM personal WHERE dni LIKE '%".$dni."%' AND activo = '".$a."' AND curso = '".$c."' ORDER BY nombre";
		return $this->db->query($query);
	}

	public function get_personal_telefono_activo_curso($telefono,$a,$c){
		$query = "SELECT * FROM personal WHERE telefono LIKE '%".$telefono."%' AND activo = '".$a."' AND curso = '".$c."' ORDER BY nombre";
		return $this->db->query($query);
	}

	public function get_personal_salon_activo_curso_test($salon,$a,$c,$t){
		$query = "SELECT * FROM personal WHERE salon = '".$salon."' AND activo = '".$a."' AND curso = '".$c."' AND test = '".$t."' ORDER BY nombre";
		return $this->db->query($query);
	}

	public function get_personal_dni_activo_curso_test($dni,$a,$c,$t){
		$query = "SELECT * FROM personal WHERE dni LIKE '%".$dni."%' AND activo = '".$a."' AND curso = '".$c."' AND test = '".$t."' ORDER BY nombre";
		return $this->db->query($query);
	}

	public function get_personal_telefono_activo_curso_test($telefono,$a,$c,$t){
		$query = "SELECT * FROM personal WHERE telefono LIKE '%".$telefono."%' AND activo = '".$a."' AND curso = '".$c."' AND test = '".$t."' ORDER BY nombre";
		return $this->db->query($query);
	}
	
	public function get_personal_pag($inicio,$tamanio){
		$query = "SELECT * FROM personal ORDER BY id DESC limit ".$inicio.",".$tamanio."";
		return $this->db->query($query);
	}
	
	public function get_personal_op_pag($op,$inicio,$tamanio){
		$query = "SELECT * FROM personal WHERE operadora = '".$op."' ORDER BY salon ASC limit ".$inicio.",".$tamanio."";
		return $this->db->query($query);
	}
	
	public function get_personal_salon_pag($salon,$inicio,$tamanio){
		$query = "SELECT * FROM personal WHERE salon = '".$salon."' ORDER BY id DESC limit ".$inicio.",".$tamanio."";
		return $this->db->query($query);
	}

	public function get_personal_registro($registro){
		$query = "SELECT * FROM personal WHERE CAST(registro as CHAR) LIKE '%".$registro."%' ORDER BY id DESC";
		return $this->db->query($query);
	}

	public function get_personal_registro_op($registro,$op){
		$query = "SELECT * FROM personal WHERE operadora = '".$op."' AND CAST(registro as CHAR) LIKE '%".$registro."%' ORDER BY id DESC";
		return $this->db->query($query);
	}

	public function get_personal_registro_salon($registro,$salon){
		$query = "SELECT * FROM personal WHERE salon = '".$salon."' AND CAST(registro as CHAR) LIKE '%".$registro."%' ORDER BY id DESC";
		return $this->db->query($query);
	}
	
	/* Get persona/id */
	public function get_persona($id){
		$query = "SELECT * FROM personal WHERE id = '".$id."'";
		$persona = $this->db->query($query);
		return $persona->row();
	}
	
	/* Crear personal */
	public function crear_personal($operador,$salon,$nombre,$dni,$telefono,$email,$curso,$carnet,$test,$activo,$fecha_carnet,$fecha_formacion,$nota,$registro,$texto,$imagen){

		// Check duplicados
		$sql = "SELECT * FROM personal WHERE dni LIKE '%".$dni."%'";
		$query = $this->db->query($sql);

		if($query->num_rows() > 0){
			$duplicado = $query->row();
			return $duplicado;
		}else{
			if(isset($fecha_carnet) && $fecha_carnet != ''){
				$fecha_c = explode("/", $fecha_carnet);
				$fecha1 = $fecha_c[2]."-".$fecha_c[1]."-".$fecha_c[0];
			}else{
				$fecha1 = '';
			}

			if(isset($fecha_formacion) && $fecha_formacion!= ''){
				$fecha_f = explode("/", $fecha_formacion);
				$fecha2 = $fecha_f[2]."-".$fecha_f[1]."-".$fecha_f[0];
			}else{
				$fecha2 = '';
			}

			$telefono = str_replace(" ", "", $telefono);

			$data = array(
				'operadora' => $operador,
				'salon' => $salon,
				'nombre' => $nombre,
				'dni' => $dni,
				'telefono' => $telefono,
				'email' => $email,
				'curso' => $curso,
				'carnet' => $carnet,
				'test' => $test,
				'activo' => $activo,
				'fecha_carnet' => $fecha1,
				'fecha_formacion' => $fecha2,
				'nota' => $nota,
				'registro' => $registro,
				'observaciones' => $texto,
				'fecha_alta' => date("Y-m-d"),
				'imagen' => $imagen,
				'creador' => $this->session->userdata('logged_in')['id']
			);
			$this->db->insert('personal', $data);

			$this->db->order_by('id', 'desc');
			$this->db->limit(1);
			$query = $this->db->get('personal');
			$personal = $query->row();

			$data = array(
				'usuario' => $personal->id,
				'salon' => $salon,
				'fecha' => date('Y-m-d'),
				'creador' => $this->session->userdata('logged_in')['id']
			);
			$this->db->insert('personal_salones_historial', $data);
			return true;
		}
	}
	
	/* Editar personal */	
	public function editar_personal($id,$operador,$salon,$nombre,$dni,$telefono,$email,$curso,$carnet,$test,$activo,$fecha_carnet,$fecha_formacion,$nota,$registro,$texto,$imagen){

		if(isset($fecha_carnet) && $fecha_carnet != ''){
			$fecha_c = explode("/", $fecha_carnet);
			$fecha1 = $fecha_c[2]."-".$fecha_c[1]."-".$fecha_c[0];
		}else{
			$fecha1 = '';
		}

		if(isset($fecha_formacion) && $fecha_formacion!= ''){
			$fecha_f = explode("/", $fecha_formacion);
			$fecha2 = $fecha_f[2]."-".$fecha_f[1]."-".$fecha_f[0];
		}else{
			$fecha2 = '';
		}

		/* Comprobar cambio de salon y ACTIVO/NO ACTIVO */
		$this->db->where('id', $id);
		$query = $this->db->get('personal');
		$personal = $query->row();

		if($personal->salon != $salon){
			$data = array(
				'usuario' => $id,
				'salon' => $salon,
				'fecha' => date('Y-m-d'),
				'creador' => $this->session->userdata('logged_in')['id']
			);
			$this->db->insert('personal_salones_historial', $data);
		}

		if($personal->activo != $activo){
			$data = array(
				'usuario' => $id,
				'activo' => $activo,
				'fecha' => date('Y-m-d'),
				'creador' => $this->session->userdata('logged_in')['id']
			);
			$this->db->insert('personal_activo_historial', $data);
		}
		/* --- */

		$telefono = str_replace(" ", "", $telefono);

		if($imagen != ''){
			$data = array(
				'operadora' => $operador,
				'salon' => $salon,
				'nombre' => $nombre,
				'dni' => $dni,
				'telefono' => $telefono,
			    'email' => $email,
			    'curso' => $curso,
			    'carnet' => $carnet,
			    'test' => $test,
			    'activo' => $activo,
			    'fecha_carnet' => $fecha1,
			    'fecha_formacion' => $fecha2,
			    'nota' => $nota,
			    'registro' => $registro,
			   	'observaciones' => $texto,
			   	'imagen' => $imagen
			);
		}else{
			$data = array(
				'operadora' => $operador,
				'salon' => $salon,
				'nombre' => $nombre,
				'dni' => $dni,
				'telefono' => $telefono,
			    'email' => $email,
			    'curso' => $curso,
			    'carnet' => $carnet,
			    'test' => $test,
			    'activo' => $activo,
			    'fecha_carnet' => $fecha1,
			    'fecha_formacion' => $fecha2,
			    'nota' => $nota,
			    'registro' => $registro,
			   	'observaciones' => $texto,
			);
		}
		$this->db->where('id', $id);
		$this->db->update('personal', $data);
		return true;
	}

	/* Actualizar personal */
	public function actualizar_personal($p){
		/* Comprobar cambio de salon y ACTIVO/NO ACTIVO */
		$this->db->where('id', $p['id']);
		$query = $this->db->get('personal');
		$personal = $query->row();

		if($personal->salon != $p['Salon']){
			$data = array(
				'usuario' => $p['id'],
				'salon' => $p['Salon'],
				'fecha' => date('Y-m-d'),
				'creador' => $this->session->userdata('logged_in')['id']
			);
			$this->db->insert('personal_salones_historial', $data);
		}

		if($personal->activo != $p['Activo']){
			$data = array(
				'usuario' => $p['id'],
				'activo' => $p['Activo'],
				'fecha' => date('Y-m-d'),
				'creador' => $this->session->userdata('logged_in')['id']
			);
			$this->db->insert('personal_activo_historial', $data);
		}
		/* --- */

		$this->db->query("UPDATE personal SET operadora = ".$p['Operadora'].", salon = ".$p['Salon'].", nombre = '".$p['Nombre']."', dni = '".$p['DNI']."', telefono = ".$p['Telefono'].", registro = '".$p['Registro']."', curso = ".$p['Curso'].", carnet = ".$p['Carnet'].", fecha_carnet = '".$p['FechaCar']."', fecha_formacion = '".$p['FechaForm']."', test = ".$p['Test'].", activo = ".$p['Activo']." WHERE id = ".$p['id']."");
	}

	public function nuevo_personal_comentario($id,$c){
		$data = array(
			'personal' => $id,
			'comentario' => $c,
			'fecha' => date('Y-m-d'),
			'creador' => $this->session->userdata('logged_in')['id']
		);
		$this->db->insert('personal_comentarios_historial', $data);
		return true;
	}

	public function get_comentarios_personal($id){
		$this->db->where('personal', $id);
		$this->db->order_by('id', 'DESC');
		return $this->db->get('personal_comentarios_historial');
	}

	public function get_cambios_activo_personal($id){
		$this->db->where('usuario', $id);
		$this->db->order_by('id', 'DESC');
		return $this->db->get('personal_activo_historial');
	}

	public function get_cambios_salon_personal($id){
		$this->db->where('usuario', $id);
		$this->db->order_by('id', 'DESC');
		return $this->db->get('personal_salones_historial');
	}
	
	/* Eliminar personal */
	public function eliminar_personal($id){
		$this->db->where('id', $id);
		$i = $this->db->get('personal');
		$personal = $i->row();
		unlink(APPPATH."../tickets/files/img/personal/".$personal->imagen."");
		$this->db->where('id', $id);
		return $this->db->delete('personal');
	}
	
	/* Get imagenes personal */
	public function get_images_persona($id){
		$this->db->where('personal', $id);
		return $this->db->get('personal_imagenes');
	}
	
	/* Get imagen */
	public function get_image_personal($id){
		$this->db->where('id', $id);
		$img = $this->db->get('personal_imagenes');
		return $img->row();
	}
	
	/* Eliminar imágen */
	public function eliminar_imagen_personal($id){
		$this->db->where('id', $id);
		$this->db->delete('personal_imagenes');
		return true;
	}
	
	/* Guardar imágen */
	public function save_image_personal($p,$img){
		
		for($i=0; $i < count($img); $i++){
		
			$data = array(
				'imagen' => $img[$i],
				'personal' => $p
			);
			$this->db->insert('personal_imagenes', $data);
			
		}
	}
	
	/* Get visitas locales */
	public function get_visitas(){
		$query = "SELECT * FROM visitas order by id DESC";
		return $this->db->query($query);
	}
	
	public function buscar_visitas($q){
		return $this->db->query($q);
	}

	public function filtrar_visitas($n){
		return $this->db->query("SELECT * FROM visitas WHERE personal1 = '".$n."' ORDER BY id DESC");
	}

	public function filtrar_visitas_supervisora($s){
		return $this->db->query("SELECT * FROM visitas WHERE creador = '".$s."' ORDER BY id DESC");
	}
	
	/* Get visitas/operadora */
	public function get_visitas_empresa($empresa,$su){
		if($empresa == 0){
			if(isset($su) && $su != ''){
				$query = "SELECT * FROM visitas WHERE creador = ".$su." order by operadora DESC, fecha DESC";
			}else{
				$query = "SELECT * FROM visitas order by operadora DESC, fecha DESC";
			}
		}else{
			if(isset($su) && $su != ''){
				$query = "SELECT * FROM visitas WHERE creador = ".$su." AND operadora IN (SELECT id FROM operadoras WHERE empresa = ".$empresa.") order by fecha DESC";
			}else{
				$query = "SELECT * FROM visitas WHERE operadora IN (SELECT id FROM operadoras WHERE empresa = ".$empresa.") order by fecha DESC";
			}
		}
		return $this->db->query($query);
	}

	public function get_visitas_ob($o){
		$query = "SELECT * FROM visitas WHERE observaciones LIKE '%".$o."%' order by id DESC";
		return $this->db->query($query);
	}
	
	/* Get visitas/operadora */
	public function get_visitas_op_nombre($op,$nombre){
		$query = "SELECT * FROM visitas WHERE operadora = '".$op."' AND personal1 = '".$nombre."' order by id DESC";
		return $this->db->query($query);
	}

	public function get_visitas_op_ob($op,$o){
		$query = "SELECT * FROM visitas WHERE operadora = '".$op."' AND observaciones LIKE '%".$o."%' order by id DESC";
		return $this->db->query($query);
	}

	/* Get visitas/operadora */
	public function get_visitas_op($op){
		$query = "SELECT * FROM visitas WHERE operadora = '".$op."' order by id DESC";
		return $this->db->query($query);
	}

	/* Get visitas/salon/nombre */
	public function get_visitas_salon_nombre($salon,$nombre){
		$query = "SELECT * FROM visitas WHERE salon = '".$salon."' AND personal1 = '".$nombre."' order by id DESC";
		return $this->db->query($query);
	}

	public function get_visitas_salon_ob($salon,$o){
		$query = "SELECT * FROM visitas WHERE salon = '".$salon."' AND observaciones LIKE '%".$o."%' order by id DESC";
		return $this->db->query($query);
	}

	/* Get visitas/supervisoras */
	public function get_visitas_salon_supervisora($salon,$s){
		$query = "SELECT * FROM visitas WHERE salon = '".$salon."' AND creador = '".$s."' order by id DESC";
		return $this->db->query($query);
	}

	/* Get visitas/supervisoras */
	public function get_visitas_op_supervisora($op,$s){
		$query = "SELECT * FROM visitas WHERE operadora = '".$op."' AND creador = '".$s."' order by id DESC";
		return $this->db->query($query);
	}
	
	/* Get visitas/salon */
	public function get_visitas_salon($salon){
		$query = "SELECT * FROM visitas WHERE salon = '".$salon."' order by id DESC";
		return $this->db->query($query);
	}
	
	public function get_visitas_pag($inicio,$tamanio){
		$query = "SELECT * FROM visitas ORDER BY id DESC limit ".$inicio.",".$tamanio."";
		return $this->db->query($query);
	}
	
	public function get_visitas_op_pag($op,$inicio,$tamanio){
		$query = "SELECT * FROM visitas WHERE operadora = '".$op."' order by id DESC limit ".$inicio.",".$tamanio."";
		return $this->db->query($query);
	}
	
	public function get_visitas_salon_pag($salon,$inicio,$tamanio){
		$query = "SELECT * FROM visitas WHERE salon = '".$salon."' order by id DESC limit ".$inicio.",".$tamanio."";
		return $this->db->query($query);
	}
	
	/* Get visita/id */
	public function get_visita($id){
		$query = "SELECT * FROM visitas WHERE id = '".$id."'";
		$persona = $this->db->query($query);
		return $persona->row();
	}

	public function get_visita_checklist($id){
		$this->db->where('id_visita', $id);
		$checklist = $this->db->get('visitas_checklist');
		return $checklist->row();
	}
	
	/* Crear visitas */	
	public function crear_visita($operador,$salon,$fecha,$personal1,$personal2,$texto,$checklist,$taburete,$mesa,$tablero,$imagen){

		if(!empty($fecha) && $fecha != ''){
			$fecha1 = explode(" ", $fecha);
			$fecha2 = explode("/", $fecha1[0]);
			$fecha_buena = $fecha2[2]."-".$fecha2[1]."-".$fecha2[0]." ".$fecha1[1];
		}else{
			$fecha_buena = '';
		}

		if(count($imagen) > 1){
			$data = array(
				'operadora' => $operador,
				'salon' => $salon,
			    'fecha' => $fecha_buena,
			    'personal1' => $personal1,
			    'personal2' => $personal2,
			    'observaciones' => $texto,
			    'imagen' => $imagen[1],
			    'taburete' => $taburete,
			    'mesa' => $mesa,
			    'tablero' => $tablero,
			    'creador' => $this->session->userdata('logged_in')['id']
			);
			$this->db->insert('visitas', $data);
		}else{
			$files = array();
			if($handle = opendir(APPPATH."../tickets/files/img/visitas/")){
			    while (false !== ($file = readdir($handle))) {
			        if ($file != "." && $file != "..") {
			           $files[] = $file;
			        }
			    }
			    closedir($handle);
			}
			ksort($files);
	    	$last = end($files);
			$lastdate = date("d-m-Y h:i:s", filemtime(APPPATH."../tickets/files/img/visitas/".$last));

			foreach($files as $file) {
		        $filedate = date("Y-m-d h:i:s", filemtime(APPPATH."../tickets/files/img/visitas/".$file));
		        if(strtotime($filedate) > strtotime($lastdate)){
		        	$last = $file;
		        }	  
			}

			$sql = "SELECT * FROM visitas WHERE imagen LIKE '%".$last."%'";
			$query = $this->db->query($sql);
			if($query->num_rows() == 0){
				$data = array(
					'operadora' => $operador,
					'salon' => $salon,
				    'fecha' => $fecha_buena,
				    'personal1' => $personal1,
				    'personal2' => $personal2,
				    'observaciones' => $texto,
				    'imagen' => $last,
				    'taburete' => $taburete,
				    'mesa' => $mesa,
				    'tablero' => $tablero,
				    'creador' => $this->session->userdata('logged_in')['id']
				);
				$this->db->insert('visitas', $data);
			}else{
				$data = array(
					'operadora' => $operador,
					'salon' => $salon,
				    'fecha' => $fecha_buena,
				    'personal1' => $personal1,
				    'personal2' => $personal2,
				    'observaciones' => $texto,
				    'taburete' => $taburete,
				    'mesa' => $mesa,
				    'tablero' => $tablero,
				    'creador' => $this->session->userdata('logged_in')['id']
				);
				$this->db->insert('visitas', $data);
			}
		}

		$this->db->order_by('id', 'desc');
		$this->db->limit(1);
		$query = $this->db->get('visitas');
		$visita = $query->row();

		$totalImg = count($imagen);
		for($i=1; $i<$totalImg; $i++){
			$data = array(
				'visita' => $visita->id,
				'imagen' => $imagen[$i] 
			);
			$this->db->insert('visitas_imagenes', $data);
		}
		
		if($checklist){
			$data = array();
			if(!empty($checklist)){
			    foreach($checklist as $check){
			    	$data[$check] = 1;
			    }
			}
			$data['id_visita'] = $visita->id;
			$this->db->insert('visitas_checklist', $data);
		}
		return true;
	}
	
	/* Editar visitas */	
	public function editar_visita($id,$operador,$salon,$fecha,$personal1,$personal2,$texto,$checklist,$taburete,$mesa,$tablero,$imagen){

		if((!empty($fecha) && $fecha != '')){
			$fecha1 = explode(" ", $fecha);
			$fecha2 = explode("/", $fecha1[0]);
			$fecha_buena = $fecha2[2]."-".$fecha2[1]."-".$fecha2[0]." ".$fecha1[1];
		}else{
			$fecha_buena = '';
		}

		if(count($imagen) > 1){
			$data = array(
				'operadora' => $operador,
				'salon' => $salon,
			    'fecha' => $fecha_buena,
			    'personal1' => $personal1,
			    'personal2' => $personal2,
			    'observaciones' => $texto,
			    'taburete' => $taburete,
			    'mesa' => $mesa,
			    'tablero' => $tablero,
			    'imagen' => $imagen[1]
			);
		}else{
			$files = array();
			if($handle = opendir(APPPATH."../tickets/files/img/visitas/")){
			    while (false !== ($file = readdir($handle))) {
			        if ($file != "." && $file != "..") {
			           $files[] = $file;
			        }
			    }
			    closedir($handle);
			}
			ksort($files);
	    	$last = end($files);
			$lastdate = date("d-m-Y h:i:s", filemtime(APPPATH."../tickets/files/img/visitas/".$last));

			foreach($files as $file) {
		        $filedate = date("Y-m-d h:i:s", filemtime(APPPATH."../tickets/files/img/visitas/".$file));
		        if(strtotime($filedate) > strtotime($lastdate)){
		        	$last = $file;
		        }	  
			}

			$sql = "SELECT * FROM visitas WHERE imagen LIKE '%".$last."%'";
			$query = $this->db->query($sql);
			if($query->num_rows() == 0){
				$data = array(
					'operadora' => $operador,
					'salon' => $salon,
				    'fecha' => $fecha_buena,
				    'personal1' => $personal1,
				    'personal2' => $personal2,
				    'observaciones' => $texto,
				    'taburete' => $taburete,
				    'mesa' => $mesa,
				    'tablero' => $tablero,
				    'imagen' => $last
				);
			}else{
				$data = array(
					'operadora' => $operador,
					'salon' => $salon,
				    'fecha' => $fecha_buena,
				    'personal1' => $personal1,
				    'personal2' => $personal2,
				    'observaciones' => $texto,
				    'taburete' => $taburete,
				    'mesa' => $mesa,
				    'tablero' => $tablero
				);
			}
		}
		
		$this->db->where('id', $id);
		$this->db->update('visitas', $data);

		$totalImg = count($imagen);
		for($i=1; $i<$totalImg; $i++){
			$data = array(
				'visita' => $id,
				'imagen' => $imagen[$i] 
			);
			$this->db->insert('visitas_imagenes', $data);
		}

		$this->db->where('id_visita', $id);
		$this->db->delete('visitas_checklist');

		if($checklist){
			$data = array();
			if(!empty($checklist)){
			    foreach($checklist as $check){
			    	$data[$check] = 1;
			    }
			}
			$data['id_visita'] = $id;
			$this->db->insert('visitas_checklist', $data);
		}
		$data = array(
			'visita' => $id,
			'fecha' => date('Y-m-d'),
			'creador' => $this->session->userdata('logged_in')['id']
		);
		$this->db->insert('visitas_ediciones_historial', $data);			
		return true;
	}

	/* Actualizar visita */
	public function actualizar_visita($v){
		$this->db->query("UPDATE visitas SET operadora = ".$v['Operadora'].", salon = ".$v['Salon'].", fecha = '".$v['Fecha']."', personal1 = '".$v['Personal1']."', personal2 = '".$v['Personal2']."', observaciones = '".$v['Observaciones']."' WHERE id = ".$v['id']."");
	}

	/* Múltiples imágenes visita */
	public function get_imagenes_visita($id){
		return $this->db->query("SELECT * FROM visitas_imagenes WHERE visita = ".$id."");
	}

	public function nueva_visita_comentario($id,$c){
		$data = array(
			'visita' => $id,
			'comentario' => $c,
			'fecha' => date('Y-m-d'),
			'creador' => $this->session->userdata('logged_in')['id']
		);
		$this->db->insert('visitas_comentarios_historial', $data);
		return true;
	}

	public function get_comentarios_visita($id){
		$this->db->where('visita', $id);
		$this->db->order_by('id', 'DESC');
		return $this->db->get('visitas_comentarios_historial');
	}

	public function get_ediciones_visita($id){
		$this->db->where('visita', $id);
		$this->db->order_by('id', 'DESC');
		return $this->db->get('visitas_ediciones_historial');
	}
	
	/* Eliminar visitas */
	public function eliminar_visita($id){
		$this->db->where('id', $id);
		$i = $this->db->get('visitas');
		$visita = $i->row();
		unlink(APPPATH."../tickets/files/img/visitas/".$visita->imagen."");
		$sql = "SELECT * FROM `visitas_imagenes` WHERE visita = '".$id."'";
		$query = $this->db->query($sql);
		foreach($query->result() as $q){
			unlink(APPPATH."../tickets/files/img/visitas/".$q->imagen."");
		}
		$this->db->where('visita', $id);
		$this->db->delete('visitas_imagenes');
		$this->db->where('id', $id);
		return $this->db->delete('visitas');
	}
	
	/* Informes visitas */
	public function get_informes_visitas(){
		$this->db->order_by('id', 'desc');
		return $this->db->get('informes_visitas');
	}

	public function get_informes_visitas_ajax($e,$s){
		if($e != 0) $this->db->where('empresa', $e);
		if($s != 0) $this->db->where('usuario', $s);
		$this->db->order_by('fecha', 'desc');
		return $this->db->get('informes_visitas');
	}
	
	public function get_informe_visita($id){
		$this->db->where('id', $id);
		$i = $this->db->get('informes_visitas');
		return $i->row();
	}
	
	/* Eliminar informes visitas */
	public function eliminar_informe_visita($id){
		$this->db->where('id', $id);
		$this->db->delete('informes_visitas');
		return true;
	}

	/* Eliminar comentario visita */
	public function borrar_comentario_visita($id){
		$this->db->where('id', $id);
		$this->db->delete('visitas_comentarios_historial');
		return true;
	}

	/* PROMOS */
	public function get_promos($sql){
		return $this->db->query($sql);
	}
	/* ------ */
	
	/* Guardias técnicos */
	public function get_guardias_tecnico($tecnico,$mes,$anio){
		$sql = "SELECT * FROM tickets WHERE situacion = '6' AND soluciona = '".$tecnico."'";
		$anio = $anio;
		
		switch($mes){
			case "1":
				if($anio == 2018){
					$sql .= " AND (fecha_solucion = '2018-01-01' OR fecha_solucion = '2018-01-06' OR fecha_solucion = '2018-01-07' OR fecha_solucion = '2018-01-13' OR fecha_solucion = '2018-01-14' OR fecha_solucion = '2018-01-20' OR fecha_solucion = '2018-01-21' OR fecha_solucion = '2018-01-27' OR fecha_solucion = '2018-01-28')";
					break;
				}else if($anio == 2019){
					$sql .= " AND (fecha_solucion = '2019-01-01' OR fecha_solucion = '2019-01-05' OR fecha_solucion = '2019-01-06' OR fecha_solucion = '2019-01-07' OR fecha_solucion = '2019-01-12' OR fecha_solucion = '2019-01-13' OR fecha_solucion = '2019-01-19' OR fecha_solucion = '2019-01-20' OR fecha_solucion = '2019-01-26' OR fecha_solucion = '2019-01-27')";
					break;
				}else if($anio == 2020){
					$sql .= " AND (fecha_solucion = '2020-01-01' OR fecha_solucion = '2020-01-04' OR fecha_solucion = '2020-01-05' OR fecha_solucion = '2020-01-06' OR fecha_solucion = '2020-01-11' OR fecha_solucion = '2020-01-12' OR fecha_solucion = '2020-01-18' OR fecha_solucion = '2020-01-19' OR fecha_solucion = '2020-01-25' OR fecha_solucion = '2020-01-26')";
					break;
				}else if($anio == 2021){
					$sql .= " AND (fecha_solucion = '2021-01-01' OR fecha_solucion = '2021-01-02' OR fecha_solucion = '2021-01-03' OR fecha_solucion = '2021-01-06' OR fecha_solucion = '2021-01-09' OR fecha_solucion = '2021-01-10' OR fecha_solucion = '2021-01-16' OR fecha_solucion = '2021-01-17' OR fecha_solucion = '2021-01-23' OR fecha_solucion = '2021-01-24' OR fecha_solucion = '2021-01-30' OR fecha_solucion = '2021-01-31')";
					break;
				}			
			case "2":
				if($anio == 2018){
					$sql .= " AND (fecha_solucion = '2018-02-03' OR fecha_solucion = '2018-02-04' OR fecha_solucion = '2018-02-10' OR fecha_solucion = '2018-02-11' OR fecha_solucion = '2018-02-17' OR fecha_solucion = '2018-02-18' OR fecha_solucion = '2018-02-24' OR fecha_solucion = '2018-02-25')";
					break;
				}else if($anio == 2019){
					$sql .= " AND (fecha_solucion = '2019-02-02' OR fecha_solucion = '2019-02-03' OR fecha_solucion = '2019-02-09' OR fecha_solucion = '2019-02-10' OR fecha_solucion = '2019-02-16' OR fecha_solucion = '2019-02-17' OR fecha_solucion = '2019-02-23' OR fecha_solucion = '2019-02-24')";
					break;
				}else if($anio == 2020){
					$sql .= " AND (fecha_solucion = '2020-02-01' OR fecha_solucion = '2020-02-02' OR fecha_solucion = '2020-02-08' OR fecha_solucion = '2020-02-09' OR fecha_solucion = '2020-02-15' OR fecha_solucion = '2020-02-16' OR fecha_solucion = '2020-02-22' OR fecha_solucion = '2020-02-23' OR fecha_solucion = '2020-02-29')";
					break;
				}else if($anio == 2021){
					$sql .= " AND (fecha_solucion = '2021-02-06' OR fecha_solucion = '2021-02-07' OR fecha_solucion = '2021-02-13' OR fecha_solucion = '2021-02-14' OR fecha_solucion = '2021-02-20' OR fecha_solucion = '2021-02-21' OR fecha_solucion = '2021-02-27' OR fecha_solucion = '2021-02-28')";
					break;
				}			
			case "3":
				if($anio == 2018){
					$sql .= " AND (fecha_solucion = '2018-03-03' OR fecha_solucion = '2018-03-04' OR fecha_solucion = '2018-03-10' OR fecha_solucion = '2018-03-11' OR fecha_solucion = '2018-03-17' OR fecha_solucion = '2018-03-18' OR fecha_solucion = '2018-03-24' OR fecha_solucion = '2018-03-25' OR fecha_solucion = '2018-03-29' OR fecha_solucion = '2018-03-30' OR fecha_solucion = '2018-03-31')";
					break;
				}else if($anio == 2019){
					$sql .= " AND (fecha_solucion = '2019-03-02' OR fecha_solucion = '2019-03-03' OR fecha_solucion = '2019-03-09' OR fecha_solucion = '2019-03-10' OR fecha_solucion = '2019-03-16' OR fecha_solucion = '2019-03-17' OR fecha_solucion = '2019-03-23' OR fecha_solucion = '2019-03-24' OR fecha_solucion = '2019-03-30' OR fecha_solucion = '2019-03-31')";
					break;
				}else if($anio == 2020){
					$sql .= " AND (fecha_solucion = '2020-03-01' OR fecha_solucion = '2020-03-07' OR fecha_solucion = '2020-03-08' OR fecha_solucion = '2020-03-14' OR fecha_solucion = '2020-03-15' OR fecha_solucion = '2020-03-19' OR fecha_solucion = '2020-03-21' OR fecha_solucion = '2020-03-22' OR fecha_solucion = '2020-03-28' OR fecha_solucion = '2020-03-29')";
					break;
				}else if($anio == 2021){
					$sql .= " AND (fecha_solucion = '2021-03-06' OR fecha_solucion = '2021-03-07' OR fecha_solucion = '2021-03-13' OR fecha_solucion = '2021-03-14' OR fecha_solucion = '2021-03-19' OR fecha_solucion = '2021-03-20' OR fecha_solucion = '2021-03-21' OR fecha_solucion = '2021-03-27' OR fecha_solucion = '2021-03-28')";
					break;
				}			
			case "4":
				if($anio == 2018){
					$sql .= " AND (fecha_solucion = '2018-04-01' OR fecha_solucion = '2018-04-03' OR fecha_solucion = '2018-04-07' OR fecha_solucion = '2018-01-08' OR fecha_solucion = '2018-04-14' OR fecha_solucion = '2018-04-15' OR fecha_solucion = '2018-04-21' OR fecha_solucion = '2018-04-22' OR fecha_solucion = '2018-04-28' OR fecha_solucion = '2018-04-29')";
					break;
				}else if($anio == 2019){
					$sql .= " AND (fecha_solucion = '2019-04-06' OR fecha_solucion = '2019-04-07' OR fecha_solucion = '2019-04-13' OR fecha_solucion = '2019-04-14' OR fecha_solucion = '2019-04-18' OR fecha_solucion = '2019-04-19' OR fecha_solucion = '2019-04-20' OR fecha_solucion = '2019-04-21' OR fecha_solucion = '2019-04-23' OR fecha_solucion = '2019-04-27' OR fecha_solucion = '2019-04-28')";
					break;
				}else if($anio == 2020){
					$sql .= " AND (fecha_solucion = '2020-04-04' OR fecha_solucion = '2020-04-05' OR fecha_solucion = '2020-04-09' OR fecha_solucion = '2020-04-10' OR fecha_solucion = '2020-04-11' OR fecha_solucion = '2020-04-12' OR fecha_solucion = '2020-04-14' OR fecha_solucion = '2020-04-18' OR fecha_solucion = '2020-04-19' OR fecha_solucion = '2020-04-25' OR fecha_solucion = '2020-04-26')";
					break;
				}else if($anio == 2021){
					$sql .= " AND (fecha_solucion = '2021-04-01' OR fecha_solucion = '2021-04-02' OR fecha_solucion = '2021-04-03' OR fecha_solucion = '2021-04-04' OR fecha_solucion = '2021-04-06' OR fecha_solucion = '2021-04-10' OR fecha_solucion = '2021-04-11' OR fecha_solucion = '2021-04-17' OR fecha_solucion = '2021-04-18' OR fecha_solucion = '2021-04-24' OR fecha_solucion = '2021-04-25')";
					break;
				}			
			case "5":
				if($anio == 2018){
					$sql .= " AND (fecha_solucion = '2018-05-01' OR fecha_solucion = '2018-05-05' OR fecha_solucion = '2018-05-06' OR fecha_solucion = '2018-05-12' OR fecha_solucion = '2018-05-13' OR fecha_solucion = '2018-05-19' OR fecha_solucion = '2018-05-20' OR fecha_solucion = '2018-05-26' OR fecha_solucion = '2018-05-27')";
					break;
				}else if($anio == 2019){
					$sql .= " AND (fecha_solucion = '2019-05-01' OR fecha_solucion = '2019-05-04' OR fecha_solucion = '2019-05-05' OR fecha_solucion = '2019-05-11' OR fecha_solucion = '2019-05-12' OR fecha_solucion = '2019-05-18' OR fecha_solucion = '2019-05-19' OR fecha_solucion = '2019-05-25' OR fecha_solucion = '2019-05-26')";
					break;
				}else if($anio == 2020){
					$sql .= " AND (fecha_solucion = '2020-05-01' OR fecha_solucion = '2020-05-02' OR fecha_solucion = '2020-05-03' OR fecha_solucion = '2020-05-09' OR fecha_solucion = '2020-05-10' OR fecha_solucion = '2020-05-16' OR fecha_solucion = '2020-05-17' OR fecha_solucion = '2020-05-23' OR fecha_solucion = '2020-05-24' OR fecha_solucion = '2020-05-30' OR fecha_solucion = '2020-05-31')";
					break;
				}else if($anio == 2021){
					$sql .= " AND (fecha_solucion = '2021-05-01' OR fecha_solucion = '2021-05-02' OR fecha_solucion = '2021-05-08' OR fecha_solucion = '2021-05-09' OR fecha_solucion = '2021-05-15' OR fecha_solucion = '2021-05-16' OR fecha_solucion = '2021-05-22' OR fecha_solucion = '2021-05-23' OR fecha_solucion = '2021-05-29' OR fecha_solucion = '2021-05-30')";
					break;
				}			
			case "6":
				if($anio == 2018){
					$sql .= " AND (fecha_solucion = '2018-06-02' OR fecha_solucion = '2018-06-03' OR fecha_solucion = '2018-06-09' OR fecha_solucion = '2018-06-10' OR fecha_solucion = '2018-06-16' OR fecha_solucion = '2018-06-17' OR fecha_solucion = '2018-06-23' OR fecha_solucion = '2018-06-24' OR fecha_solucion = '2018-06-30')";
					break;
				}else if($anio == 2019){
					$sql .= " AND (fecha_solucion = '2019-06-01' OR fecha_solucion = '2019-06-02' OR fecha_solucion = '2019-06-08' OR fecha_solucion = '2019-06-09' OR fecha_solucion = '2019-06-10' OR fecha_solucion = '2019-06-15' OR fecha_solucion = '2019-06-16' OR fecha_solucion = '2019-06-22' OR fecha_solucion = '2019-06-23' OR fecha_solucion = '2019-06-29' OR fecha_solucion = '2019-06-30')";
					break;
				}else if($anio == 2020){
					$sql .= " AND (fecha_solucion = '2020-06-06' OR fecha_solucion = '2020-06-07' OR fecha_solucion = '2020-06-09' OR fecha_solucion = '2020-06-13' OR fecha_solucion = '2020-06-14' OR fecha_solucion = '2020-06-20' OR fecha_solucion = '2020-06-21' OR fecha_solucion = '2020-06-27' OR fecha_solucion = '2020-06-28')";
					break;
				}else if($anio == 2021){
					$sql .= " AND (fecha_solucion = '2021-06-05' OR fecha_solucion = '2021-06-06' OR fecha_solucion = '2021-06-09' OR fecha_solucion = '2021-06-12' OR fecha_solucion = '2021-06-13' OR fecha_solucion = '2021-06-19' OR fecha_solucion = '2021-06-20' OR fecha_solucion = '2021-06-26' OR fecha_solucion = '2021-06-27')";
					break;
				}			
			case "7":
				if($anio == 2018){
					$sql .= " AND (fecha_solucion = '2018-07-01' OR fecha_solucion = '2018-07-07' OR fecha_solucion = '2018-07-08' OR fecha_solucion = '2018-07-14' OR fecha_solucion = '2018-07-15' OR fecha_solucion = '2018-07-21' OR fecha_solucion = '2018-07-22' OR fecha_solucion = '2018-07-28' OR fecha_solucion = '2018-07-29')";
					break;
				}else if($anio == 2019){
					$sql .= " AND (fecha_solucion = '2019-07-06' OR fecha_solucion = '2019-07-07' OR fecha_solucion = '2019-07-13' OR fecha_solucion = '2019-07-14' OR fecha_solucion = '2019-07-20' OR fecha_solucion = '2019-07-21' OR fecha_solucion = '2019-07-27' OR fecha_solucion = '2019-07-28')";
					break;
				}else if($anio == 2020){
					$sql .= " AND (fecha_solucion = '2020-07-04' OR fecha_solucion = '2020-07-05' OR fecha_solucion = '2020-07-11' OR fecha_solucion = '2020-07-12' OR fecha_solucion = '2020-07-18' OR fecha_solucion = '2020-07-19' OR fecha_solucion = '2020-07-25' OR fecha_solucion = '2020-07-26')";
					break;
				}else if($anio == 2021){
					$sql .= " AND (fecha_solucion = '2021-07-03' OR fecha_solucion = '2021-07-04' OR fecha_solucion = '2021-07-10' OR fecha_solucion = '2021-07-11' OR fecha_solucion = '2021-07-17' OR fecha_solucion = '2021-07-18' OR fecha_solucion = '2021-07-24' OR fecha_solucion = '2021-07-25' OR fecha_solucion = '2021-07-31')";
					break;
				}			
			case "8":
				if($anio == 2018){
					$sql .= " AND (fecha_solucion = '2018-08-04' OR fecha_solucion = '2018-08-05' OR fecha_solucion = '2018-08-11' OR fecha_solucion = '2018-08-12' OR fecha_solucion = '2018-08-15' OR fecha_solucion = '2018-08-18' OR fecha_solucion = '2018-08-19' OR fecha_solucion = '2018-08-25' OR fecha_solucion = '2018-08-26')";
					break;
				}else if($anio == 2019){
					$sql .= " AND (fecha_solucion = '2019-08-03' OR fecha_solucion = '2019-08-04' OR fecha_solucion = '2019-08-10' OR fecha_solucion = '2019-08-11' OR fecha_solucion = '2019-08-15' OR fecha_solucion = '2019-08-17' OR fecha_solucion = '2019-08-18' OR fecha_solucion = '2019-08-24' OR fecha_solucion = '2019-08-25' OR fecha_solucion = '2019-08-31')";
					break;
				}else if($anio == 2020){
					$sql .= " AND (fecha_solucion = '2020-08-01' OR fecha_solucion = '2020-08-02' OR fecha_solucion = '2020-08-08' OR fecha_solucion = '2020-08-09' OR fecha_solucion = '2020-08-15' OR fecha_solucion = '2020-08-16' OR fecha_solucion = '2020-08-22' OR fecha_solucion = '2020-08-23' OR fecha_solucion = '2020-08-29' OR fecha_solucion = '2020-08-30')";
					break;
				}else if($anio == 2021){
					$sql .= " AND (fecha_solucion = '2021-08-01' OR fecha_solucion = '2021-08-07' OR fecha_solucion = '2021-08-08' OR fecha_solucion = '2021-08-14' OR fecha_solucion = '2021-08-15' OR fecha_solucion = '2021-08-21' OR fecha_solucion = '2021-08-22' OR fecha_solucion = '2021-08-28' OR fecha_solucion = '2021-08-29')";
					break;
				}			
			case "9":
				if($anio == 2018){
					$sql .= " AND (fecha_solucion = '2018-09-01' OR fecha_solucion = '2018-09-02' OR fecha_solucion = '2018-09-08' OR fecha_solucion = '2018-09-09' OR fecha_solucion = '2018-09-11' OR fecha_solucion = '2018-09-15' OR fecha_solucion = '2018-09-16' OR fecha_solucion = '2018-09-22' OR fecha_solucion = '2018-09-23' OR fecha_solucion = '2018-09-29' OR fecha_solucion = '2018-09-30')";
					break;
				}else if($anio == 2019){
					$sql .= " AND (fecha_solucion = '2019-09-01' OR fecha_solucion = '2019-09-07' OR fecha_solucion = '2019-09-08' OR fecha_solucion = '2019-09-17' OR fecha_solucion = '2019-09-14' OR fecha_solucion = '2019-09-15' OR fecha_solucion = '2019-09-21' OR fecha_solucion = '2019-09-22' OR fecha_solucion = '2019-09-28' OR fecha_solucion = '2019-09-29')";
					break;
				}else if($anio == 2020){
					$sql .= " AND (fecha_solucion = '2020-09-05' OR fecha_solucion = '2020-09-06' OR fecha_solucion = '2020-09-12' OR fecha_solucion = '2020-09-13' OR fecha_solucion = '2020-09-15' OR fecha_solucion = '2020-09-19' OR fecha_solucion = '2020-09-20' OR fecha_solucion = '2020-09-26' OR fecha_solucion = '2020-09-27')";
					break;
				}else if($anio == 2021){
					$sql .= " AND (fecha_solucion = '2021-09-04' OR fecha_solucion = '2021-09-05' OR fecha_solucion = '2021-09-11' OR fecha_solucion = '2021-09-12' OR fecha_solucion = '2021-09-14' OR fecha_solucion = '2021-09-18' OR fecha_solucion = '2021-09-19' OR fecha_solucion = '2021-09-25' OR fecha_solucion = '2021-09-26')";
					break;
				}			
			case "10":
				if($anio == 2018){
					$sql .= " AND (fecha_solucion = '2018-10-06' OR fecha_solucion = '2018-10-07' OR fecha_solucion = '2018-10-12' OR fecha_solucion = '2018-10-13' OR fecha_solucion = '2018-10-14' OR fecha_solucion = '2018-10-20' OR fecha_solucion = '2018-10-21' OR fecha_solucion = '2018-10-27' OR fecha_solucion = '2018-10-28')";
					break;
				}else if($anio == 2019){
					$sql .= " AND (fecha_solucion = '2019-10-05' OR fecha_solucion = '2019-10-06' OR fecha_solucion = '2019-10-12' OR fecha_solucion = '2019-10-13' OR fecha_solucion = '2019-10-19' OR fecha_solucion = '2019-10-20' OR fecha_solucion = '2019-10-26' OR fecha_solucion = '2019-10-27')";
					break;
				}else if($anio == 2020){
					$sql .= " AND (fecha_solucion = '2020-10-03' OR fecha_solucion = '2020-10-04' OR fecha_solucion = '2020-10-10' OR fecha_solucion = '2020-10-11' OR fecha_solucion = '2020-10-12' OR fecha_solucion = '2020-10-17' OR fecha_solucion = '2020-10-18' OR fecha_solucion = '2020-10-24' OR fecha_solucion = '2020-10-25' OR fecha_solucion = '2020-10-31')";
					break;
				}else if($anio == 2021){
					$sql .= " AND (fecha_solucion = '2021-10-02' OR fecha_solucion = '2021-10-03' OR fecha_solucion = '2021-10-09' OR fecha_solucion = '2021-10-10' OR fecha_solucion = '2021-10-12' OR fecha_solucion = '2021-10-16' OR fecha_solucion = '2021-10-17' OR fecha_solucion = '2021-10-23' OR fecha_solucion = '2021-10-24' OR fecha_solucion = '2021-10-30' OR fecha_solucion = '2021-10-31')";
					break;
				}			
			case "11":
				if($anio == 2018){
					$sql .= " AND (fecha_solucion = '2018-11-01' OR fecha_solucion = '2018-11-03' OR fecha_solucion = '2018-11-04' OR fecha_solucion = '2018-11-10' OR fecha_solucion = '2018-11-11' OR fecha_solucion = '2018-11-17' OR fecha_solucion = '2018-11-18' OR fecha_solucion = '2018-11-24' OR fecha_solucion = '2018-11-25')";
					break;
				}else if($anio == 2019){
					$sql .= " AND (fecha_solucion = '2019-11-01' OR fecha_solucion = '2019-11-02' OR fecha_solucion = '2019-11-03' OR fecha_solucion = '2019-11-09' OR fecha_solucion = '2019-11-10' OR fecha_solucion = '2019-11-16' OR fecha_solucion = '2019-11-17' OR fecha_solucion = '2019-11-23' OR fecha_solucion = '2019-11-24' OR fecha_solucion = '2019-11-30')";
					break;
				}else if($anio == 2020){
					$sql .= " AND (fecha_solucion = '2020-11-01' OR fecha_solucion = '2020-11-07' OR fecha_solucion = '2020-11-08' OR fecha_solucion = '2020-11-14' OR fecha_solucion = '2020-11-15' OR fecha_solucion = '2020-11-21' OR fecha_solucion = '2020-11-22' OR fecha_solucion = '2020-11-28' OR fecha_solucion = '2020-11-29')";
					break;
				}else if($anio == 2021){
					$sql .= " AND (fecha_solucion = '2021-11-01' OR fecha_solucion = '2021-11-06' OR fecha_solucion = '2021-11-07' OR fecha_solucion = '2021-11-13' OR fecha_solucion = '2021-11-14' OR fecha_solucion = '2021-11-20' OR fecha_solucion = '2021-11-21' OR fecha_solucion = '2021-11-27' OR fecha_solucion = '2021-11-28')";
					break;
				}			
			case "12":
				if($anio == 2018){
					$sql .= " AND (fecha_solucion = '2018-12-01' OR fecha_solucion = '2018-12-02' OR fecha_solucion = '2018-12-06' OR fecha_solucion = '2018-12-08' OR fecha_solucion = '2018-12-09' OR fecha_solucion = '2018-12-15' OR fecha_solucion = '2018-12-16' OR fecha_solucion = '2018-12-22' OR fecha_solucion = '2018-12-23' OR fecha_solucion = '2018-12-25' OR fecha_solucion = '2018-12-29' OR fecha_solucion = '2018-12-30')";
					break;
				}else if($anio == 2019){
					$sql .= " AND (fecha_solucion = '2019-12-01' OR fecha_solucion = '2019-12-06' OR fecha_solucion = '2019-12-07' OR fecha_solucion = '2019-12-08' OR fecha_solucion = '2019-12-14' OR fecha_solucion = '2019-12-15' OR fecha_solucion = '2019-12-21' OR fecha_solucion = '2019-12-22' OR fecha_solucion = '2019-12-25' OR fecha_solucion = '2019-12-28' OR fecha_solucion = '2019-12-29')";
					break;
				}else if($anio == 2020){
					$sql .= " AND (fecha_solucion = '2020-12-05' OR fecha_solucion = '2020-12-06' OR fecha_solucion = '2020-12-07' OR fecha_solucion = '2020-12-08' OR fecha_solucion = '2020-12-12' OR fecha_solucion = '2020-12-13' OR fecha_solucion = '2020-12-19' OR fecha_solucion = '2020-12-20' OR fecha_solucion = '2020-12-25' OR fecha_solucion = '2020-12-26' OR fecha_solucion = '2020-12-27')";
					break;
				}else if($anio == 2021){
					$sql .= " AND (fecha_solucion = '2021-12-04' OR fecha_solucion = '2021-12-05' OR fecha_solucion = '2021-12-06' OR fecha_solucion = '2021-12-08' OR fecha_solucion = '2021-12-11' OR fecha_solucion = '2021-12-12' OR fecha_solucion = '2021-12-18' OR fecha_solucion = '2021-12-19' OR fecha_solucion = '2021-12-25' OR fecha_solucion = '2021-12-26')";
					break;
				}
		}
		return $this->db->query($sql);
	}
	
	public function get_tickets_fecha($fecha,$tipo){
		if($tipo == 0){
			$sql = "SELECT * FROM tickets WHERE fecha_creacion = '".$fecha."' AND creador IN (SELECT id FROM usuarios WHERE acceso = '24') ORDER BY hora_creacion ASC";
		}else if($tipo == 1){
			$sql = "SELECT * FROM tickets WHERE fecha_creacion = '".$fecha."' AND (salon = 413 OR salon = 580 OR tipo_error = 137 OR detalle_error = 630) AND creador IN (SELECT id FROM usuarios WHERE acceso = '24') ORDER BY hora_creacion ASC";
		}else if($tipo == 2){
			$sql = "SELECT * FROM tickets WHERE fecha_creacion = '".$fecha."' AND salon != 413 AND salon != 580 AND cliente != 1 AND cliente != 5 AND tipo_error != 137 AND detalle_error != 630 AND creador IN (SELECT id FROM usuarios WHERE acceso = '24') ORDER BY hora_creacion ASC";
		}
		return $this->db->query($sql);
	}

	// Informes turnos

	public function get_empleado_turno_finde($fecha){
		$sql = "SELECT creador FROM tickets WHERE fecha_creacion LIKE '%".$fecha."%' AND creador IN (SELECT id FROM usuarios WHERE rol = 1 and activo = 1) and creador != 1 group by creador";
		return $this->db->query($sql);
	}

	public function get_incidencias_turno_finde($fecha, $creador){
		$sql = "SELECT * FROM ediciones WHERE fecha_edicion LIKE '%".$fecha."%' AND creador = ".$creador." AND edicion_inicial = 'SI'";
		return $this->db->query($sql);
	}

	public function get_incidencias_sat_turno_finde($fecha, $creador){
		$sql = "SELECT * FROM ediciones WHERE fecha_edicion LIKE '%".$fecha."%' AND creador = ".$creador." AND edicion_inicial = 'SI' AND situacion = 2";
		return $this->db->query($sql);
	}

	public function get_incidencias_op_turno_finde($fecha, $creador){
		$sql = "SELECT * FROM ediciones WHERE fecha_edicion LIKE '%".$fecha."%' AND creador = ".$creador." AND edicion_inicial = 'SI' AND situacion = 13";
		return $this->db->query($sql);
	}

	public function get_incidencias_cad_turno_finde($fecha, $creador){
		$sql = "SELECT * FROM ediciones WHERE fecha_edicion LIKE '%".$fecha."%' AND creador = ".$creador." AND edicion_inicial = 'SI' AND situacion = 5";
		return $this->db->query($sql);
	}

	public function get_incidencias_rev_turno_finde($fecha, $creador){
		$sql = "SELECT * FROM ediciones WHERE fecha_edicion LIKE '%".$fecha."%' AND creador = ".$creador." AND edicion_inicial = 'SI' AND situacion = 1";
		return $this->db->query($sql);
	}

	public function get_incidencias_trata_turno_finde($fecha, $creador){
		$sql = "SELECT * FROM ediciones WHERE fecha_edicion LIKE '%".$fecha."%' AND creador = ".$creador." AND edicion_inicial = 'SI' AND situacion = 4";
		return $this->db->query($sql);
	}

	public function get_incidencias_eusk_turno_finde($fecha, $creador){
		$sql = "SELECT * FROM ediciones WHERE fecha_edicion LIKE '%".$fecha."%' AND creador = ".$creador." AND edicion_inicial = 'SI' AND situacion = 3";
		return $this->db->query($sql);
	}

	public function get_incidencias_kirol_turno_finde($fecha, $creador){
		$sql = "SELECT * FROM ediciones WHERE fecha_edicion LIKE '%".$fecha."%' AND creador = ".$creador." AND edicion_inicial = 'SI' AND situacion = 8";
		return $this->db->query($sql);
	}

	public function get_incidencias_lla_turno_finde($fecha, $creador){
		$sql = "SELECT * FROM ediciones WHERE fecha_edicion LIKE '%".$fecha."%' AND creador = ".$creador." AND edicion_inicial = 'SI' AND situacion = 11";
		return $this->db->query($sql);
	}

	public function get_incidencias_resp_turno_finde($fecha, $creador){
		$sql = "SELECT * FROM ediciones WHERE fecha_edicion LIKE '%".$fecha."%' AND creador = ".$creador." AND edicion_inicial = 'SI' AND situacion = 16";
		return $this->db->query($sql);
	}

	public function get_incidencias_paq_turno_finde($fecha, $creador){
		$sql = "SELECT * FROM ediciones WHERE fecha_edicion LIKE '%".$fecha."%' AND creador = ".$creador." AND edicion_inicial = 'SI' AND situacion = 18";
		return $this->db->query($sql);
	}

	public function get_empleado_turno_mañana($fecha){
		$sql = "SELECT creador FROM tickets WHERE fecha_creacion LIKE '%".$fecha."%' AND hora_creacion < '15:00:00' AND creador IN (SELECT id FROM usuarios WHERE rol = 1 and activo = 1) and creador != 1 group by creador";
		return $this->db->query($sql);
	}

	public function get_incidencias_turno_mañana($fecha, $creador){
		$sql = "SELECT * FROM ediciones WHERE fecha_edicion LIKE '%".$fecha."%' AND hora_edicion < '15:00:00' AND creador = ".$creador." AND edicion_inicial = 'SI'";
		return $this->db->query($sql);
	}

	public function get_incidencias_sat_turno_mañana($fecha, $creador){
		$sql = "SELECT * FROM ediciones WHERE fecha_edicion LIKE '%".$fecha."%' AND hora_edicion < '15:00:00' AND creador = ".$creador." AND edicion_inicial = 'SI' AND situacion = 2";
		return $this->db->query($sql);
	}

	public function get_incidencias_op_turno_mañana($fecha, $creador){
		$sql = "SELECT * FROM ediciones WHERE fecha_edicion LIKE '%".$fecha."%' AND hora_edicion < '15:00:00' AND creador = ".$creador." AND edicion_inicial = 'SI' AND situacion = 13";
		return $this->db->query($sql);
	}

	public function get_incidencias_cad_turno_mañana($fecha, $creador){
		$sql = "SELECT * FROM ediciones WHERE fecha_edicion LIKE '%".$fecha."%' AND hora_edicion < '15:00:00' AND creador = ".$creador." AND edicion_inicial = 'SI' AND situacion = 5";
		return $this->db->query($sql);
	}

	public function get_incidencias_rev_turno_mañana($fecha, $creador){
		$sql = "SELECT * FROM ediciones WHERE fecha_edicion LIKE '%".$fecha."%' AND hora_edicion < '15:00:00' AND creador = ".$creador." AND edicion_inicial = 'SI' AND situacion = 1";
		return $this->db->query($sql);
	}

	public function get_incidencias_trata_turno_mañana($fecha, $creador){
		$sql = "SELECT * FROM ediciones WHERE fecha_edicion LIKE '%".$fecha."%' AND hora_edicion < '15:00:00' AND creador = ".$creador." AND edicion_inicial = 'SI' AND situacion = 4";
		return $this->db->query($sql);
	}

	public function get_incidencias_eusk_turno_mañana($fecha, $creador){
		$sql = "SELECT * FROM ediciones WHERE fecha_edicion LIKE '%".$fecha."%' AND hora_edicion < '15:00:00' AND creador = ".$creador." AND edicion_inicial = 'SI' AND situacion = 3";
		return $this->db->query($sql);
	}

	public function get_incidencias_kirol_turno_mañana($fecha, $creador){
		$sql = "SELECT * FROM ediciones WHERE fecha_edicion LIKE '%".$fecha."%' AND hora_edicion < '15:00:00' AND creador = ".$creador." AND edicion_inicial = 'SI' AND situacion = 8";
		return $this->db->query($sql);
	}

	public function get_incidencias_lla_turno_mañana($fecha, $creador){
		$sql = "SELECT * FROM ediciones WHERE fecha_edicion LIKE '%".$fecha."%' AND hora_edicion < '15:00:00' AND creador = ".$creador." AND edicion_inicial = 'SI' AND situacion = 11";
		return $this->db->query($sql);
	}

	public function get_incidencias_resp_turno_mañana($fecha, $creador){
		$sql = "SELECT * FROM ediciones WHERE fecha_edicion LIKE '%".$fecha."%' AND hora_edicion < '15:00:00' AND creador = ".$creador." AND edicion_inicial = 'SI' AND situacion = 16";
		return $this->db->query($sql);
	}

	public function get_incidencias_paq_turno_mañana($fecha, $creador){
		$sql = "SELECT * FROM ediciones WHERE fecha_edicion LIKE '%".$fecha."%' AND hora_edicion < '15:00:00' AND creador = ".$creador." AND edicion_inicial = 'SI' AND situacion = 18";
		return $this->db->query($sql);
	}

	public function get_empleado_turno_tarde($fecha){
		$sql = "SELECT creador FROM tickets WHERE fecha_creacion LIKE '%".$fecha."%' AND hora_creacion > '15:00:00' AND creador IN (SELECT id FROM usuarios WHERE rol = 1 and activo = 1) and creador != 1 group by creador";
		return $this->db->query($sql);
	}

	public function get_incidencias_turno_tarde($fecha, $creador){
		$sql = "SELECT * FROM ediciones WHERE fecha_edicion LIKE '%".$fecha."%' AND hora_edicion > '15:00:00' AND creador = ".$creador." AND edicion_inicial = 'SI'";
		return $this->db->query($sql);
	}

	public function get_incidencias_sat_turno_tarde($fecha, $creador){
		$sql = "SELECT * FROM ediciones WHERE fecha_edicion LIKE '%".$fecha."%' AND hora_edicion > '15:00:00' AND creador = ".$creador." AND edicion_inicial = 'SI' AND situacion = 2";
		return $this->db->query($sql);
	}

	public function get_incidencias_op_turno_tarde($fecha, $creador){
		$sql = "SELECT * FROM ediciones WHERE fecha_edicion LIKE '%".$fecha."%' AND hora_edicion > '15:00:00' AND creador = ".$creador." AND edicion_inicial = 'SI' AND situacion = 13";
		return $this->db->query($sql);
	}

	public function get_incidencias_cad_turno_tarde($fecha, $creador){
		$sql = "SELECT * FROM ediciones WHERE fecha_edicion LIKE '%".$fecha."%' AND hora_edicion > '15:00:00' AND creador = ".$creador." AND edicion_inicial = 'SI' AND situacion = 5";
		return $this->db->query($sql);
	}

	public function get_incidencias_rev_turno_tarde($fecha, $creador){
		$sql = "SELECT * FROM ediciones WHERE fecha_edicion LIKE '%".$fecha."%' AND hora_edicion > '15:00:00' AND creador = ".$creador." AND edicion_inicial = 'SI' AND situacion = 1";
		return $this->db->query($sql);
	}

	public function get_incidencias_trata_turno_tarde($fecha, $creador){
		$sql = "SELECT * FROM ediciones WHERE fecha_edicion LIKE '%".$fecha."%' AND hora_edicion > '15:00:00' AND creador = ".$creador." AND edicion_inicial = 'SI' AND situacion = 4";
		return $this->db->query($sql);
	}

	public function get_incidencias_eusk_turno_tarde($fecha, $creador){
		$sql = "SELECT * FROM ediciones WHERE fecha_edicion LIKE '%".$fecha."%' AND hora_edicion > '15:00:00' AND creador = ".$creador." AND edicion_inicial = 'SI' AND situacion = 3";
		return $this->db->query($sql);
	}

	public function get_incidencias_kirol_turno_tarde($fecha, $creador){
		$sql = "SELECT * FROM ediciones WHERE fecha_edicion LIKE '%".$fecha."%' AND hora_edicion > '15:00:00' AND creador = ".$creador." AND edicion_inicial = 'SI' AND situacion = 8";
		return $this->db->query($sql);
	}

	public function get_incidencias_lla_turno_tarde($fecha, $creador){
		$sql = "SELECT * FROM ediciones WHERE fecha_edicion LIKE '%".$fecha."%' AND hora_edicion > '15:00:00' AND creador = ".$creador." AND edicion_inicial = 'SI' AND situacion = 11";
		return $this->db->query($sql);
	}

	public function get_incidencias_resp_turno_tarde($fecha, $creador){
		$sql = "SELECT * FROM ediciones WHERE fecha_edicion LIKE '%".$fecha."%' AND hora_edicion > '15:00:00' AND creador = ".$creador." AND edicion_inicial = 'SI' AND situacion = 16";
		return $this->db->query($sql);
	}

	public function get_incidencias_paq_turno_tarde($fecha, $creador){
		$sql = "SELECT * FROM ediciones WHERE fecha_edicion LIKE '%".$fecha."%' AND hora_edicion > '15:00:00' AND creador = ".$creador." AND edicion_inicial = 'SI' AND situacion = 18";
		return $this->db->query($sql);
	}

	public function get_tickets_salones($fechaI,$fechaF){
		$sql = "SELECT * FROM tickets WHERE fecha_creacion >= '".$fechaI."' AND fecha_creacion <= '".$fechaF."' AND creador IN (SELECT id FROM usuarios WHERE acceso = '24') ORDER BY operadora ASC, salon ASC, fecha_creacion desc, hora_creacion desc";
		return $this->db->query($sql);
	}

	public function get_tickets_fecha_agrupados_creador($fecha){
		$sql = "SELECT creador, count(*) as total FROM tickets WHERE fecha_creacion = '".$fecha."' AND creador IN (SELECT id FROM usuarios WHERE acceso = '24') AND salon IN (SELECT salon FROM maquinas WHERE modelo IN (SELECT id FROM modelos_maquinas WHERE tipo_maquina = 10)) GROUP BY creador";
		return $this->db->query($sql);
	}
	
	public function get_tickets_fecha_agrupados_salon($fecha){
		$sql = "SELECT salon, count(*) as total FROM tickets WHERE fecha_creacion = '".$fecha."' AND salon IN (SELECT salon FROM maquinas WHERE modelo IN (SELECT id FROM modelos_maquinas WHERE tipo_maquina = 10)) AND salon NOT IN (385,413) GROUP BY salon";
		return $this->db->query($sql);
	}

	public function get_tickets_fecha_agrupados_salon_maquina($fecha,$salon){
		$sql = "SELECT * FROM tickets WHERE fecha_creacion = '".$fecha."' AND salon = '".$salon."' AND maquina IN (SELECT id FROM maquinas WHERE modelo IN (SELECT id FROM modelos_maquinas WHERE tipo_maquina = 10)) GROUP BY maquina";
		return $this->db->query($sql);
	}

	/* Seccion recaudacion */
	public function get_recaudaciones(){
		$this->db->order_by('id', 'desc');
		return $this->db->get('recaudaciones');
	}
	
	public function get_recaudaciones_salones_informes(){
		$this->db->group_by('salon');
		return $this->db->get('recaudaciones_salones');
	}
	
	public function get_recaudaciones_salones(){
		$this->db->order_by('id', 'desc');
		return $this->db->get('recaudaciones_salones');
	}
	
	public function get_ultima_recaudacion_salon($salon){
		$sql = "SELECT * FROM recaudaciones_salones WHERE salon = '".$salon."' ORDER BY id DESC LIMIT 1";
		$reca = $this->db->query($sql);
		return $reca->row();
	}
	
	public function get_parcial_recaudacion_salon($salon,$reca){
		$sql = "SELECT * FROM recaudaciones_salones WHERE id != '".$reca."' AND salon = '".$salon."' ORDER BY id DESC LIMIT 1";
		$reca = $this->db->query($sql);
		return $reca->row();
	}
	
	public function get_maquinas_anterior_recaudacion($maquina,$reca){
		$sql = "SELECT * FROM recaudaciones_maquinas_salon_contador WHERE maquina = '".$maquina."' AND recaudacion != '".$reca."' ORDER BY id DESC LIMIT 1";
		$reca = $this->db->query($sql);
		return $reca->row();
	}
	
	public function get_recaudaciones_maquina($id){
		$this->db->where('maquina', $id);
		return $this->db->get('recaudaciones_maquinas');
	}
	
	public function get_recaudaciones_maquina_salon_contador($id){
		$this->db->where('maquina', $id);
		return $this->db->get('recaudaciones_maquinas_salon_contador');
	}
	
	public function get_recaudacion_maquina_id($id){
		$this->db->where('id', $id);
		$reca = $this->db->get('recaudaciones_maquinas');
		return $reca->row();
	}
	
	public function get_recaudacion_maquina_salon_contador_id($id){
		$this->db->where('id', $id);
		$reca = $this->db->get('recaudaciones_maquinas_salon_contador');
		return $reca->row();
	}
	
	/* Seccion recaudacion */
	public function get_recaudacion_fecha($fecha,$salon){
		$sql = "SELECT * FROM recaudaciones WHERE fecha = '".$fecha."' AND salon = '".$salon."'";
		return $this->db->query($sql);
	}
	
	/* Seccion recaudacion */
	public function get_recaudacion_id($r){
		$sql = "SELECT * FROM recaudaciones WHERE id = '".$r."'";
		$query = $this->db->query($sql);
		return $query->row();
	}

	/* Seccion recaudacion */
	public function get_recaudacion_salon_id($r){
		$sql = "SELECT * FROM recaudaciones_salones WHERE id = '".$r."'";
		$query = $this->db->query($sql);
		return $query->row();
	}
	
	public function get_ultima_recaudacion($salon){
		$sql = "SELECT * FROM recaudaciones WHERE salon = '".$salon."' ORDER BY id DESC LIMIT 1";
		$query = $this->db->query($sql);
		return $query->row();
	}

	public function get_ultima_recaudacion_maquina($maquina){
		$sql = "SELECT * FROM recaudaciones_maquinas_salon_contador WHERE maquina = '".$maquina."' AND recaudacion IS NOT NULL ORDER BY id DESC LIMIT 1";
		$query = $this->db->query($sql);
		return $query->row();
	}
	
	/* Seccion recaudacion */
	public function crear_recaudacion_maquina($maquina, $salon, $t_h_u_1, $t_h_t_1, $t_h_u_2, $t_h_t_2, $t_h_t, $t_b_u_5, $t_b_t_5, $t_b_u_10, $t_b_t_10, $t_b_u_20, $t_b_t_20, $t_b_u_50, $t_b_t_50, $t_b_t, $t_c_u_1, $t_c_t_1, $t_c_u_2, $t_c_t_2, $t_c_t, $t_r_u_20, $t_r_t_20, $t_r_t, $t_reca_t, $r_h_u_1, $r_h_t_1, $r_h_u_2, $r_h_t_2, $r_h_t, $r_b_u_5, $r_b_t_5, $r_b_u_10, $r_b_t_10, $r_b_u_20, $r_b_t_20, $r_b_u_50, $r_b_t_50, $r_b_t, $r_c_u_1, $r_c_t_1, $r_c_u_2, $r_c_t_2, $r_c_t, $r_r_u_20, $r_r_t_20, $r_r_t, $r_reca_t, $carga, $neto){
		$fecha = date("Y-m-d");
		$sql = "INSERT INTO recaudaciones_maquinas (maquina,salon,recaudacion,fecha,t_h_u_1,t_h_t_1,t_h_u_2,t_h_t_2,t_h_t,t_b_u_5,t_b_t_5,t_b_u_10,t_b_t_10,t_b_u_20,t_b_t_20,t_b_u_50,t_b_t_50,t_b_t,t_c_u_1,t_c_t_1,t_c_u_2,t_c_t_2,t_c_t,t_r_u_20,t_r_t_20,t_r_t,t_reca_t,r_h_u_1,r_h_t_1,r_h_u_2,r_h_t_2,r_h_t,r_b_u_5,r_b_t_5,r_b_u_10,r_b_t_10,r_b_u_20,r_b_t_20,r_b_u_50,r_b_t_50,r_b_t,r_c_u_1,r_c_t_1,r_c_u_2,r_c_t_2,r_c_t,r_r_u_20,r_r_t_20,r_r_t,r_reca_t,carga,neto) VALUES ('".$maquina."','".$salon."',NULL,'".$fecha."','".$t_h_u_1."','".$t_h_t_1."','".$t_h_u_2."','".$t_h_t_2."','".$t_h_t."','".$t_b_u_5."','".$t_b_t_5."','".$t_b_u_10."','".$t_b_t_10."','".$t_b_u_20."','".$t_b_t_20."','".$t_b_u_50."','".$t_b_t_50."','".$t_b_t."','".$t_c_u_1."','".$t_c_t_1."','".$t_c_u_2."','".$t_c_t_2."','".$t_c_t."','".$t_r_u_20."','".$t_r_t_20."','".$t_r_t."','".$t_reca_t."','".$r_h_u_1."','".$r_h_t_1."','".$r_h_u_2."','".$r_h_t_2."','".$r_h_t."','".$r_b_u_5."','".$r_b_t_5."','".$r_b_u_10."','".$r_b_t_10."','".$r_b_u_20."','".$r_b_t_20."','".$r_b_u_50."','".$r_b_t_50."','".$r_b_t."','".$r_c_u_1."','".$r_c_t_1."','".$r_c_u_2."','".$r_c_t_2."','".$r_c_t."','".$r_r_u_20."','".$r_r_t_20."','".$r_r_t."','".$r_reca_t."','".$carga."','".$neto."')";
		$this->db->query($sql);
		return true;
	}
	
	public function crear_recaudacion_maquina_salon_contador($maquina, $salon, $entrada_total_pasos, $entrada_total_euros, $entrada_parcial_pasos, $entrada_parcial_euros, $salida_total_pasos, $salida_total_euros, $salida_parcial_pasos, $salida_parcial_euros, $total_pasos, $total_euros, $parcial_pasos, $parcial_euros, $neto_total_pasos, $neto_total_euros, $neto_parcial_pasos, $neto_parcial_euros){
		$fecha = date("Y-m-d");
		$sql = "INSERT INTO recaudaciones_maquinas_salon_contador (maquina, salon, recaudacion, fecha, entrada_total_pasos, entrada_total_euros, entrada_parcial_pasos, entrada_parcial_euros, salida_total_pasos, salida_total_euros, salida_parcial_pasos, salida_parcial_euros, total_pasos, total_euros, parcial_pasos, parcial_euros, neto_total_pasos, neto_total_euros, neto_parcial_pasos, neto_parcial_euros) VALUES ('".$maquina."', '".$salon."', NULL, '".$fecha."', '".$entrada_total_pasos."', '".$entrada_total_euros."', '".$entrada_parcial_pasos."', '".$entrada_parcial_euros."', '".$salida_total_pasos."', '".$salida_total_euros."', '".$salida_parcial_pasos."', '".$salida_parcial_euros."', '".$total_pasos."', '".$total_euros."', '".$parcial_pasos."', '".$parcial_euros."', '".$neto_total_pasos."', '".$neto_total_euros."', '".$neto_parcial_pasos."', '".$neto_parcial_euros."')";
		$this->db->query($sql);
		return true;
	}
	
	public function editar_recaudacion_maquina($recaudacion, $t_h_u_1, $t_h_t_1, $t_h_u_2, $t_h_t_2, $t_h_t, $t_b_u_5, $t_b_t_5, $t_b_u_10, $t_b_t_10, $t_b_u_20, $t_b_t_20, $t_b_u_50, $t_b_t_50, $t_b_t, $t_c_u_1, $t_c_t_1, $t_c_u_2, $t_c_t_2, $t_c_t, $t_r_u_20, $t_r_t_20, $t_r_t, $t_reca_t, $r_h_u_1, $r_h_t_1, $r_h_u_2, $r_h_t_2, $r_h_t, $r_b_u_5, $r_b_t_5, $r_b_u_10, $r_b_t_10, $r_b_u_20, $r_b_t_20, $r_b_u_50, $r_b_t_50, $r_b_t, $r_c_u_1, $r_c_t_1, $r_c_u_2, $r_c_t_2, $r_c_t, $r_r_u_20, $r_r_t_20, $r_r_t, $r_reca_t, $carga, $neto){
		$fecha = date("Y-m-d");
		$sql = "UPDATE recaudaciones_maquinas SET fecha = '".$fecha."', t_h_u_1 = '".$t_h_u_1."', t_h_t_1 = '".$t_h_t_1."', t_h_u_2 = '".$t_h_u_2."', t_h_t_2 = '".$t_h_t_2."', t_h_t = '".$t_h_t."', t_b_u_5 = '".$t_b_u_5."', t_b_t_5 = '".$t_b_t_5."', t_b_u_10 = '".$t_b_u_10."', t_b_t_10 = '".$t_b_t_10."', t_b_u_20 = '".$t_b_u_20."', t_b_t_20 = '".$t_b_t_20."', t_b_u_50 = '".$t_b_u_50."', t_b_t_50 = '".$t_b_t_50."', t_b_t = '".$t_b_t."', t_c_u_1 = '".$t_c_u_1."', t_c_t_1 = '".$t_c_t_1."', t_c_u_2 = '".$t_c_u_2."', t_c_t_2 = '".$t_c_t_2."', t_c_t = '".$t_c_t."', t_r_u_20 = '".$t_r_u_20."', t_r_t_20 = '".$t_r_t_20."', t_r_t = '".$t_r_t."', t_reca_t = '".$t_reca_t."', r_h_u_1 = '".$r_h_u_1."', r_h_t_1 = '".$r_h_t_1."', r_h_u_2 = '".$r_h_u_2."', r_h_t_2 = '".$r_h_t_2."', r_h_t = '".$r_h_t."', r_b_u_5 = '".$r_b_u_5."', r_b_t_5 = '".$r_b_t_5."', r_b_u_10 = '".$r_b_u_10."', r_b_t_10 = '".$r_b_t_10."' ,r_b_u_20 = '".$r_b_u_20."', r_b_t_20 = '".$r_b_t_20."', r_b_u_50 = '".$r_b_u_50."', r_b_t_50 = '".$r_b_t_50."', r_b_t = '".$r_b_t."', r_c_u_1 = '".$r_c_u_1."', r_c_t_1 = '".$r_c_t_1."', r_c_u_2 = '".$r_c_u_2."', r_c_t_2 = '".$r_c_t_2."', r_c_t = '".$r_c_t."', r_r_u_20 = '".$r_r_u_20."', r_r_t_20 = '".$r_r_t_20."',r_r_t = '".$r_r_t."', r_reca_t = '".$r_reca_t."', carga = '".$carga."', neto = '".$neto."' WHERE id = '".$recaudacion."'";
		$this->db->query($sql);
		return true;
	}
	
	public function editar_recaudacion_maquina_salon_contador($recaudacion, $entrada_total_pasos, $entrada_total_euros, $entrada_parcial_pasos, $entrada_parcial_euros, $salida_total_pasos, $salida_total_euros, $salida_parcial_pasos, $salida_parcial_euros, $total_pasos, $total_euros, $parcial_pasos, $parcial_euros, $neto_total_pasos, $neto_total_euros, $neto_parcial_pasos, $neto_parcial_euros){
		$fecha = date("Y-m-d");
		$sql = "UPDATE recaudaciones_maquinas_salon_contador SET fecha = '".$fecha."', entrada_total_pasos = '".$entrada_total_pasos."', entrada_total_euros = '".$entrada_total_euros."', entrada_parcial_pasos = '".$entrada_parcial_pasos."', entrada_parcial_euros = '".$entrada_parcial_euros."', salida_total_pasos = '".$salida_total_pasos."', salida_total_euros = '".$salida_total_euros."', salida_parcial_pasos = '".$salida_parcial_pasos."', salida_parcial_euros = '".$salida_parcial_euros."', total_pasos = '".$total_pasos."', total_euros = '".$total_euros."', parcial_pasos = '".$parcial_pasos."', parcial_euros = '".$parcial_euros."', neto_total_pasos = '".$neto_total_pasos."', neto_total_euros = '".$neto_total_euros."', neto_parcial_pasos = '".$neto_parcial_pasos."', neto_parcial_euros = '".$neto_parcial_euros."' WHERE id = '".$recaudacion."'";
		$this->db->query($sql);
		return true;
	}
	
	public function get_maquinas_recaudadas_salon_contador($salon){
		$sql = "SELECT * FROM `recaudaciones_maquinas_salon_contador` WHERE salon = '".$salon."' AND recaudacion IS NULL";
		return $this->db->query($sql);
	}
	
	public function get_maquinas_ultima_recaudacion($salon,$reca){
		$sql = "SELECT * FROM `recaudaciones_maquinas_salon_contador` WHERE salon = '".$salon."' AND recaudacion = '".$reca."'";
		return $this->db->query($sql);
	}
	
	public function get_ultima_recaudacion_salon_contador($salon){
		$sql = "SELECT * FROM recaudaciones_salones WHERE salon = '".$salon."' ORDER BY id DESC LIMIT 1";
		$query = $this->db->query($sql);
		return $query->row();
	}

	public function get_pagos_cajero_jackpot($salon,$fecha){
		$this->db->where('salon', $salon);
		$query = $this->db->get('cajeros');
		$servidor = $query->row();
		$usuario = "ccm";
		$clave = "ccm10";
		$database = "ticketserver";
		try{	
		  $conn = new PDO('mysql:host='.$servidor->servidor.';dbname='.$database.'; charset=utf8', $usuario, $clave);
		  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}catch(PDOException $e){
		    echo "ERROR: " . $e->getMessage();
		}

		$sql = $conn->prepare('SELECT * FROM tickets WHERE CAST(DateTime AS DATE) >= "'.$fecha.'" AND Command = "CLOSE" AND (COMMENT LIKE "%JACKPOTS%" OR TYPE LIKE "%JACKPOTS%" OR TYPE LIKE "%PJP%")');
		$sql->execute();
		if($sql->rowCount() > 0){
			return $sql->fetchAll();
		}else{
			return false;
		}
	}

	public function get_pagos_cajero_manual($salon,$fecha){
		$this->db->where('salon', $salon);
		$query = $this->db->get('cajeros');
		$servidor = $query->row();
		$usuario = "ccm";
		$clave = "ccm10";
		$database = "ticketserver";
		try{	
		  $conn = new PDO('mysql:host='.$servidor->servidor.';dbname='.$database.'; charset=utf8', $usuario, $clave);
		  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}catch(PDOException $e){
		    echo "ERROR: " . $e->getMessage();
		}
		//$sql = $conn->prepare('SELECT * FROM tickets WHERE CAST(DateTime AS DATE) >= "'.$fecha.'" AND (COMMENT LIKE "%MANUAL%" OR TYPE LIKE "%MANUAL%" OR TYPE LIKE "%MAN%" OR (user LIKE "%pc%" AND (TYPE LIKE "%APOLLO_1%" OR TYPE LIKE "%APOLLO_2%" OR TYPE LIKE "%APOLLO_3%")))');
		$sql = $conn->prepare("SELECT sum(value) AS total FROM tickets WHERE Command = 'CLOSE' AND LastCommandChangeDateTime >= '".$fecha."' AND Type != 'CCM KIROLSOFT' AND Type != 'APOSTIUM TPV' AND Type NOT LIKE '%PJP%'");
		$sql->execute();
		if($sql->rowCount() > 0){
			return $sql->fetch();
		}else{
			return false;
		}
	}

	public function get_pagos_cajero_mnr($salon,$fecha){
		$this->db->where('salon', $salon);
		$query = $this->db->get('cajeros');
		$servidor = $query->row();
		$usuario = "ccm";
		$clave = "ccm10";
		$database = "ticketserver";
		try{	
		  $conn = new PDO('mysql:host='.$servidor->servidor.';dbname='.$database.'; charset=utf8', $usuario, $clave);
		  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}catch(PDOException $e){
		    echo "ERROR: " . $e->getMessage();
		}
		$sql = $conn->prepare('SELECT * FROM tickets WHERE CAST(DateTime AS DATE) >= "'.$fecha.'" AND (COMMENT LIKE "%REGISTRADO%" OR TYPE LIKE "%REGISTRADO%" OR TYPE LIKE "%PNR%")');
		$sql->execute();
		if($sql->rowCount() > 0){
			return $sql->fetchAll();
		}else{
			return false;
		}
	}
	
	public function get_pagos_cajero_factura($salon,$fecha){
		$this->db->where('salon', $salon);
		$query = $this->db->get('cajeros');
		$servidor = $query->row();
		$usuario = "ccm";
		$clave = "ccm10";
		$database = "ticketserver";
		try{	
		  $conn = new PDO('mysql:host='.$servidor->servidor.';dbname='.$database.'; charset=utf8', $usuario, $clave);
		  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}catch(PDOException $e){
		    echo "ERROR: " . $e->getMessage();
		}
		$sql = $conn->prepare('SELECT * FROM tickets WHERE DATETIME >= "'.$fecha.'" AND (COMMENT LIKE "%FACTURA%" OR TYPE LIKE "%FACTURA%")');
		$sql->execute();
		if($sql->rowCount() > 0){
			return $sql->fetchAll();
		}else{
			return false;
		}
	}

	public function get_pagos_cajero_incidencia($salon,$fecha){
		$this->db->where('salon', $salon);
		$query = $this->db->get('cajeros');
		$servidor = $query->row();
		$usuario = "ccm";
		$clave = "ccm10";
		$database = "ticketserver";
		try{	
		  $conn = new PDO('mysql:host='.$servidor->servidor.';dbname='.$database.'; charset=utf8', $usuario, $clave);
		  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}catch(PDOException $e){
		    echo "ERROR: " . $e->getMessage();
		}
		$sql = $conn->prepare('SELECT * FROM tickets WHERE DATETIME >= "'.$fecha.'" AND (COMMENT LIKE "%INCIDENCIA%" OR TYPE LIKE "%INCIDENCIA%")');
		$sql->execute();
		if($sql->rowCount() > 0){
			return $sql->fetchAll();
		}else{
			return false;
		}
	}

	public function get_pagos_cajero_datafono($salon,$fecha){
		$this->db->where('salon', $salon);
		$query = $this->db->get('cajeros');
		$servidor = $query->row();
		$usuario = "ccm";
		$clave = "ccm10";
		$database = "ticketserver";
		try{	
		  $conn = new PDO('mysql:host='.$servidor->servidor.';dbname='.$database.'; charset=utf8', $usuario, $clave);
		  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}catch(PDOException $e){
		    echo "ERROR: " . $e->getMessage();
		}
		$sql = $conn->prepare('SELECT * FROM tickets WHERE Command = "CLOSE" AND CAST(DateTime AS DATE) >= "'.$fecha.'" AND (COMMENT LIKE "%APOSTIUM TPV%" OR TYPE LIKE "%PAGO DATAFONO%")');
		$sql->execute();
		if($sql->rowCount() > 0){
			return $sql->fetchAll();
		}else{
			return false;
		}
	}

	/* Seccion recaudacion */
	public function get_maquinas_recaudadas($salon){
		$sql = "SELECT * FROM `recaudaciones_maquinas` WHERE salon = '".$salon."' AND recaudacion IS NULL";
		return $this->db->query($sql);
	}
	
	public function crear_recaudacion_salon_contador($salon,$bruto,$pagos,$datafono,$neto,$coment){
		$fecha = date("Y-m-d");
		$sql = "INSERT INTO recaudaciones_salones (salon,recaudador,fecha,bruto,pagos,datafono,neto,comentarios) VALUES ('".$salon."','".$this->session->userdata('logged_in')['id']."','".$fecha."','".$bruto."','".$pagos."','".$datafono."','".$neto."','".$coment."')";
		$this->db->query($sql);
		
		$sql = "SELECT * FROM recaudaciones_salones ORDER BY id DESC LIMIT 1";
		$query = $this->db->query($sql);
		$reca = $query->row();
		
		$sql = "SELECT * FROM `recaudaciones_maquinas_salon_contador` WHERE salon = '".$salon."' AND recaudacion IS NULL";
		$query = $this->db->query($sql);
		foreach($query->result() as $q){
			$sql = "UPDATE recaudaciones_maquinas_salon_contador SET recaudacion = '".$reca->id."' WHERE id = '".$q->id."'";
			$this->db->query($sql);
		}
		return $reca;
	}
	
	/* Seccion recaudacion */
	public function crear_recaudacion_salon($salon,$reca_ant,$pag_ant,$bal_ant,$pag_caj,$bal,$total,$reca_total,$pagos,$pagos_1,$pagos_2,$pagos_5,$pagos_10,$pagos_20,$pagos_50,$neto,$coment){
		$fecha = date("Y-m-d");
		$sql = "INSERT INTO recaudaciones (salon,recaudador,fecha,reca_ant,pag_ant,bal_ant,pag_caj,bal,total,reca_total,pagos,pagos_1,pagos_2,pagos_5,pagos_10,pagos_20,pagos_50,neto,comentarios) VALUES ('".$salon."','".$this->session->userdata('logged_in')['id']."','".$fecha."','".$reca_ant."','".$pag_ant."','".$bal_ant."','".$pag_caj."','".$bal."','".$total."','".$reca_total."','".$pagos."','".$pagos_1."','".$pagos_2."','".$pagos_5."','".$pagos_10."','".$pagos_20."','".$pagos_50."','".$neto."','".$coment."')";
		$this->db->query($sql);
		
		$sql = "SELECT * FROM recaudaciones ORDER BY id DESC LIMIT 1";
		$query = $this->db->query($sql);
		$reca = $query->row();
		return $reca;
	}
	
	/* Seccion recaudacion */
	public function guardar_firma_recaudacion($r,$f1,$f2,$salon){
		$sql = "UPDATE recaudaciones SET firma_recaudador = '".$f1."', firma_responsable = '".$f2."' WHERE id = '".$r."'";
		$this->db->query($sql);

		$sql = "SELECT * FROM `recaudaciones_maquinas` WHERE salon = '".$salon."' AND recaudacion IS NULL";
		$query = $this->db->query($sql);
		foreach($query->result() as $q){
			$sql = "UPDATE recaudaciones_maquinas SET recaudacion = '".$r."' WHERE id = '".$q->id."'";
			$this->db->query($sql);
		}
		return true;
	}
	
	/* Get prioridades */
	public function get_prioridad(){
		$this->db->order_by('id', 'asc');
		return $this->db->get('prioridad');
	}
	
	public function get_prioridad_id($id){
		$this->db->where('id', $id);
		$query = $this->db->get('prioridad');
		return $query->row();
	}
	
	/* Get conceptos */
	public function get_conceptos(){
		$this->db->order_by('concepto');
		return $this->db->get('conceptos');
	}
	
	public function get_concepto($id){
		$this->db->where('id', $id);
		$query = $this->db->get('conceptos');
		return $query->row();
	}
	
	/* GET vehiculos renting */
	public function get_vehiculos_renting(){
		$this->db->where('renting', 1);
		$this->db->order_by('vehiculo');
		return $this->db->get('vehiculos');
	}
	
	public function get_repostajes_limite($id,$i,$f){
		$query = "SELECT * FROM gasoil WHERE matricula = ".$id." AND fecha >= '".$i."' AND fecha < '".$f."'";
		return $this->db->query($query);
	}

	public function get_usuarios_salones_adm(){
		$usuarios = array();
		$sql = $this->db->query("SELECT id FROM `usuarios` WHERE activo = 1 AND rol = 3 AND acceso IN (SELECT id FROM salones WHERE (operadora = 24 OR operadora = 41))");
		foreach($sql->result() as $user){
			$usuarios[] = $user->id;
		}
		return $usuarios;	
	}
	
	public function get_usuarios_adm(){
		$usuarios = array();
		$sql = $this->db->query("SELECT id FROM `usuarios` WHERE (((acceso = 24 OR acceso = 41) AND rol != 3) OR acceso IN (SELECT id FROM salones WHERE (operadora = 24 OR operadora = 41))) AND (rol = 1 OR rol = 2 OR rol = 3 OR rol = 4 OR rol = 6 OR rol = 7 OR rol = 8 OR rol = 9)");
		foreach($sql->result() as $user){
			$usuarios[] = $user->id;
		}
		return $usuarios;
	}

	public function switch_mes($m){
		switch ($m) {
			case '1':
				return "Enero";
				break;
			case '2':
				return "Febrero";
				break;
			case '3':
				return "Marzo";
				break;
			case '4':
				return "Abril";
				break;
			case '5':
				return "Mayo";
				break;
			case '6':
				return "Junio";
				break;
			case '7':
				return "Julio";
				break;
			case '8':
				return "Agosto";
				break;
			case '9':
				return "Septiembre";
				break;
			case '10':
				return "Ocubre";
				break;
			case '11':
				return "Noviembre";
				break;
			case '12':
				return "Diciembre";
				break;
		}
	}

	/* SUPERVISORAS ADM */
	public function get_supervisoras(){
		$sql = "SELECT * FROM `usuarios` WHERE rol = 6 AND acceso = 24 AND activo = 1 ORDER BY nombre ASC";
		return $this->db->query($sql);
	}

	/* ACTUALIZAR PROHIBIDOS */
	public function actualizar_prohibidos($db){
		$sql = "INSERT INTO prohibidos (usuario, operadora, db, fecha) VALUES ('".$this->session->userdata('logged_in')['id']."', '".$this->session->userdata('logged_in')['acceso']."', '".$db."', '".date("Y-m-d H:i:s")."')";
		return $this->db->query($sql);
	}

	public function get_prohibidos(){
		$this->db->order_by('fecha', 'DESC');
		return $this->db->get('prohibidos');
	}

	/* CENTRALITA */
	public function get_centralita_telefono_llamada($id){
		$ticket = $this->db->query("SELECT * FROM tickets WHERE id = ".$id."");
		if($ticket->num_rows() != 0){
			$telefono = $ticket->row();
			return $telefono->telefono;
		}else{
			return false;
		}
	}

	public function get_centralita_cliente_llamada($tlf){
		$usuario = $this->db->query("SELECT * FROM usuarios WHERE telefono LIKE '%".$tlf."%' LIMIT 1");
		if($usuario->num_rows() != 0){
			$cliente = $usuario->row();
			if($cliente->rol == 3){
				$salon = $this->db->query("SELECT * FROM salones WHERE id = ".$cliente->acceso."");
				$s = $salon->row();
				$operadora = $this->db->query("SELECT * FROM operadoras WHERE id = ".$s->operadora."");
				$op = $operadora->row();
				$salon = $s->salon;
				$id_salon = $s->id;
			}else{
				$operadora = $this->db->query("SELECT * FROM operadoras WHERE id = ".$cliente->acceso."");
				$op = $operadora->row();
				$salon = NULL;
				$id_salon = NULL;
			}
			$llamada = array(
				'id_op' => $op->id,
				'id_salon' => $id_salon,
				'operadora' => $op->operadora,
				'salon' => $salon,
				'cliente' => $cliente->nombre,
				'telefono' => $tlf
			);
			return $llamada;
		}else{
			$salon = $this->db->query("SELECT * FROM salones WHERE telefono LIKE '%".$tlf."%' LIMIT 1");
			if($salon->num_rows() != 0){
				$cliente = $salon->row();
				$operadora = $this->db->query("SELECT * FROM operadoras WHERE id = ".$cliente->operadora."");
				$op = $operadora->row();
				$llamada = array(
					'id_op' => $op->id,
					'id_salon' => $cliente->id,
					'operadora' => $op->operadora,
					'salon' => $cliente->salon,
					'telefono' => $tlf,
					'cliente' => NULL
				);
				return $llamada;
			}else{
				return false;
			}
		}
	}

	public function get_telefono_incidencia($id){
		$this->db->where('id', $id);
		$query = $this->db->get('tickets');
		$ticket = $query->row();
		if($ticket->telefono != '' && $ticket->telefono != "968272869"){
			return $ticket->telefono;
		}else{
			$this->db->where('id', $ticket->salon);
			$query = $this->db->get('salones');
			$salon = $query->row();
			if($salon->telefono != '' && $salon->telefono != "968272869"){
				return $salon->telefono;
			}else{
				return false;
			}
		}
	}

	/* PROMO AZAFATAS */
	public function get_promos_azafatas(){
		$this->db->order_by('id', 'ASC');
		return $this->db->get('promo_salones');
	}

	/* Camarero activar datafono */
	public function activar_datafono($salon,$dni){
		$this->db->set('bloqueo_camarero', 0);
		$this->db->where('salon', $salon);
		$this->db->update('cajeros');

		$data = array(
			'salon' => $salon,
			'dni' => $dni,
			'fecha' => date('Y-m-d H:i:s')
		);
		$this->db->insert('aupabettpv_dni', $data);

		return true;
	}

	/* Obtener datos datafono */
	public function get_datafono($salon){
		$this->db->where('salon', $salon);
		$query = $this->db->get('datafonos');
		return $query->row();
	}

	public function get_ultimos_tickets_cajero($salon){
		$sql = $this->db->query("SELECT * FROM credito_tickets WHERE salon = ".$salon." AND fecha >= '".date("Y-m-d",strtotime(date("Y-m-d")."- 7 days"))."' ORDER BY id DESC");
		if($sql->num_rows() != 0){
			return $sql;
		}else{
			return false;
		}
	}

	public function get_ultimos_dni_cajero($salon){
		$sql = $this->db->query("SELECT * FROM aupabettpv_dni WHERE salon = ".$salon." ORDER BY id DESC");
		if($sql->num_rows() != 0){
			return $sql;
		}else{
			return false;
		}
	}

	//GPS APK LOCATION
	public function save_location($user,$location){
		$sql = $this->db->query("SELECT * FROM usuarios WHERE email LIKE '%".$user."%'");
		if($sql->num_rows() != 0){
			$usuario = $sql->row();
		}
		$data = array(
			'usuario' => $user,
			'ubicacion' => $location,
			'acceso' => $usuario->acceso,
			'fecha' => date('Y-m-d H:i:s')
		);
		$this->db->insert('ubicaciones', $data);
		return true;
	}

	//GET UBICACIONES USUSARIOS
	public function get_ubicaciones_usuarios($acceso){
		$sql = $this->db->query("SELECT DISTINCT(usuario) FROM ubicaciones WHERE acceso = ".$acceso." ORDER BY id DESC");
		if($sql->num_rows() != 0){
			return $sql;
		}else{
			return false;
		}
	}

	//GET ULTIMA UBICACION USUARIO
	public function get_ultima_ubicacion($usuario){
		$sql = $this->db->query("SELECT * FROM ubicaciones WHERE usuario LIKE '".$usuario."' ORDER BY id DESC LIMIT 1");
		if($sql->num_rows() != 0){
			$user = $sql->row();
			return $user;
		}else{
			return false;
		}
	}

	public function nuevo_ticketserver($maquina,$importe,$texto){
		try{
	        $conn = new PDO('mysql:host=212.170.94.0;dbname=ticketserver; charset=utf8', 'ccm', 'ccm10', array(PDO::ATTR_TIMEOUT => 10));
	        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	    }catch(PDOException $e){
	        return false;
	        die();
	    }

	    function generar_numero(){
	    	try{
		        $conn = new PDO('mysql:host=212.170.94.0;dbname=ticketserver; charset=utf8', 'ccm', 'ccm10', array(PDO::ATTR_TIMEOUT => 10));
		        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		    }catch(PDOException $e){
		        return false;
		        die();
		    }
	    	$ticketNumber = "99480".rand(100,999);
			$sql = $conn->prepare("SELECT * FROM tickets WHERE TicketNumber LIKE '%".$ticketNumber."%'");
		    if($sql->execute()){           
		        if($sql->rowCount() != 0){
		            $ticketNumber = generar_numero();
		        }else{
		        	return $ticketNumber;
		        }
		    }

	    }

		$ticketNumber = generar_numero();

		$sql = $conn->prepare("INSERT INTO tickets (Command, TicketNumber, Mode, DateTime, LastCommandChangeDateTime, LastIP, LastUser, Value, Residual, IP, User, Comment, Type, TypeIsBets, TypeIsAux, AuxConcept, HideOnTC, Used, UsedFromIP, UsedAmount, UsedDateTime, MergedFromId, Status, ExpirationDate, TITOTitle, TITOTicketType, TITOStreet, TITOPlace, TITOCity, TITOPostalCode, TITODescription, TITOExpirationType) VALUES ('OPEN', '".$ticketNumber."', 'pdaPost', '".date('Y-m-d H:i:s')."', '0000-00-00 00:00:00', '".$_SERVER['REMOTE_ADDR']."', 'ccm', ".$importe.", '0.00', '".$_SERVER['REMOTE_ADDR']."', 'ccm', '".$texto."', '".$maquina."', 0, '', '', 1, 0, '', 0.00, '0000-00-00 00:00:00', NULL, '', '0000-00-00 00:00:00', '', '', '', '', '', '', '', 0)");
		
		if($sql->execute()){
            $sql = $conn->prepare("SELECT * FROM tickets ORDER BY Id DESC LIMIT 1");
            if($sql->execute()){            
                if($sql->rowCount() == 1){
                    $row = $sql->fetch();
                    return $row["Id"];
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }else{
            return false;
        }
	}

	public function get_movimientos(){
		$this->db->order_by('movimiento', 'ASC');
		return $this->db->get('movimientos');
	}

	public function get_movimiento($id){
		$this->db->where('id', $id);
		$query = $this->db->get('movimientos');
		return $query->row();
	}

	public function guardar_registro($local,$maquina,$movimiento,$importe,$saldo,$firma,$fecha){
		$sql = "INSERT INTO registro_movimientos_locales (usuario,local,maquina,movimiento,importe,saldo,firma,fecha) VALUES ('".$this->session->userdata('logged_in')['id']."','".$local."','".$maquina."','".$movimiento."','".$importe."','".$saldo."','".$firma."','".$fecha."')";		
		if($this->db->query($sql)){
			$sql = $this->db->query("SELECT * FROM registro_movimientos_locales WHERE usuario LIKE '".$this->session->userdata('logged_in')['id']."' ORDER BY id DESC LIMIT 1");
			if($sql->num_rows() != 0){
				$row = $sql->row();
				return $row;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}

	public function get_ultimos_registros_locales($op){
		$sql = "SELECT * FROM registro_movimientos_locales WHERE usuario IN (SELECT id FROM usuarios WHERE (rol = 2 OR rol = 4) AND acceso = ".$op.") ORDER BY id DESC LIMIT 10";
		return $this->db->query($sql);
	}

	public function get_registros_locales($fechaI,$fechaF,$usuario,$salon,$movimiento){
		$sql = "SELECT * FROM registro_movimientos_locales WHERE 1";

		if($fechaI != 0){
			$fechaI = explode("/", $fechaI);
			$fechaI = $fechaI[2]."-".$fechaI[1]."-".$fechaI[0];
			$sql .= " AND fecha >= '".$fechaI." 00:00:00'";
		}

		if($fechaF != 0){
			$fechaF = explode("/", $fechaF);
			$fechaF = $fechaF[2]."-".$fechaF[1]."-".$fechaF[0];
			$sql .= " AND fecha <= '".$fechaF." 23:59:59'";
		}

		if($usuario != 0){
			$sql .= " AND usuario = '".$usuario."'";
		}

		if($salon != 0){
			$sql .= " AND local = '".$salon."'";
		}

		if($movimiento != 0){
			$sql .= " AND movimiento = '".$movimiento."'";
		}

		$sql .= " ORDER BY id DESC";

		return $this->db->query($sql);
	}

	/* Seccion registrar visitas informes salones operadoras */
	public function crear_informe_operadora_salon($salon,$fecha,$texto,$checklist){
		$data = array(
			'salon' => $salon,
		    'fecha' => $fecha,
		    'observaciones' => $texto,
		    'creador' => $this->session->userdata('logged_in')['id']
		);
		$this->db->insert('informes_salones_operadora', $data);

		$this->db->order_by('id', 'desc');
		$this->db->limit(1);
		$query = $this->db->get('informes_salones_operadora');
		$visita = $query->row();
		
		if($checklist){
			$data = array();
			if(!empty($checklist)){
			    foreach($checklist as $check){
			    	$data[$check] = 1;
			    }
			}
			$data['id_visita'] = $visita->id;
			$this->db->insert('informes_salones_operadora_checklist', $data);
		}
		return $visita->id;
	}

	public function get_informe_operadora_salon($id){
		$this->db->where('id', $id);
		$query = $this->db->get('informes_salones_operadora');
		return $query->row();
	}

	public function get_informes_operadora($op,$rol){
		if($rol == 3){
			return $this->db->query("SELECT * FROM informes_salones_operadora WHERE salon = '".$op."' ORDER BY id DESC");
		}else{
			return $this->db->query("SELECT * FROM informes_salones_operadora WHERE salon IN (SELECT id FROM salones WHERE operadora = '".$op."') ORDER BY id DESC");
		}
	}

	public function get_informe_visita_operadora($id){
		$this->db->where('id', $id);
		$i = $this->db->get('informes_salones_operadora');
		return $i->row();
	}

	public function eliminar_informe_visita_operadora($id){
		$this->db->where('id', $id);
		$this->db->delete('informes_salones_operadora');

		$this->db->where('id_visita', $id);
		$this->db->delete('informes_salones_operadora_checklist');
		return true;
	}
}

?>