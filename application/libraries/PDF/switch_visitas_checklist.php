<?php

function switch_visitas_checklist($check){
	
	switch($check){
		case "car_obl1":
			return "Carteles Obligatorios";
			break;
		case "piz_ext":
			return "Pizarra exterior";
			break;
		case "fac_vin":
			return "Referenciar ADM en fachada";
			break;
		case "car_obl2":
			return "Carteles interiores obligatorios";
			break;
		case "fol_lud":
			return "Folletos ludopatia";
			break;
		case "fol_pub":
			return "Disponer folletos publicidad ADM";
			break;
		case "piz_int":
			return "Adquisición y uso de pizarra";
			break;
		case "pan_tac":
			return "Mantener pantalla tactil activa";
			break;
		case "tvs_cor":
			return "TVs emitiendo canales deportivos";
			break;
		case "ver_act":
			return "Vertical actualizada";
			break;
		case "cor_est":
			return "Corner en buen estado";
			break;
		case "ter_inc":
			return "Terminales en buen estado";
			break;
		case "com_pro":
			return "Comprobacion prohibidos activo";
			break;
		case "blo_ter":
			return "Bloqueo de terminales activo";
			break;
		case "cor_adm":
			return "Correo ADM revisado";
			break;
		case "per_for":
			return "Personal de apuestas formado";
			break;
		case "nec_rec":
			return "Sin necesidad de reciclaje de personal";
			break;
		case "per_tes":
			return "Test de personal aprobado";
			break;
		case "per_uni":
			return "Personal uniformado";
			break;
		case "hil_mus":
			return "Contratar y emitir hilo musical ADM";
			break;
		case "pan_car":
			return "Panel de carteleria";
			break;
		case "car_pro":
			return "Imprimir cartelera deportiva";
			break;
		case "tar_adm":
			return "Disponder de tarjetas ADM";
			break;
		case "mob_apu":
			return "Mobiliario ADM en mal estado";
			break;
		case "inc_apu":
			return "Terminales sin incidencias";
			break;
		case "lim_loc":
			return "Revisar limpieza local";
			break;
		case "vin_est":
			return "Vinilo buen estado";
			break;
		case "bol_pla":
			return "Boletines / Placa terminales";
			break;
		case "san_cab":
			return "Saneamiento cableado basic";
			break;
		case "dis_may":
			return "Distintivo +18";
			break;
		case "tpv_inc":
			return "TPV sin incidencias";
			break;
		case "lec_tar":
			return "Lector tarjetas TPV";
			break;
		case "ban_fac":
			return "Banderola fachada";
			break;
		case "aio_est":
			return "AIO buen estado";
			break;
		case "ent_mer":
			return "Entrega merchand";
			break;
		case "señ_gal":
			return "Señal Galgos";
			break;
		case "señ_lot":
			return "Señal Lottos";
			break;
		case "señ_dep":
			return "Señal Deportes";
			break;
		case "señ_otr":
			return "Otra señal TV";
			break;
		default:
			return "Checklist";
			break;
	}
}
?>