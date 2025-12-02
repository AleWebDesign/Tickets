<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <script type="text/javascript">
            function imprimir() {
                if (window.print) {
                    window.print();
                } else {
                    alert("La función de impresion no esta soportada por su navegador.");
                }
            }
        </script>
        <style type="text/css">
            td{
                width: 100px;
                padding: 10px;
            }   
        </style>
    </head>
    <body onload="imprimir()">
        <h3>Salidas SAT</h3>
        <div style="width: 100%">
            <div style="width: 30%; float: left; margin-right: 2%;">
                <table style="border: 1px solid #000; font-weight: bold;">
                    <tbody>
                        <tr>
                            <td style="border-right: 1px solid #000; border-bottom: 1px solid #000;">Técnico</td>
                            <td style="border-right: 1px solid #000; border-bottom: 1px solid #000;"><?php echo $tecnico; ?></td>
                        </tr>
                        <tr>
                            <td style="border-right: 1px solid #000; border-bottom: 1px solid #000;">Mes</td>
                            <td style="border-bottom: 1px solid #000;"><?php echo $mes; ?></td>
                        </tr>
                        <tr>
                            <td style="border-right: 1px solid #000; border-bottom: 1px solid #000;">Año</td>
                            <td style="border-right: 1px solid #000; border-bottom: 1px solid #000;"><?php echo $anio; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div style="width: 30%; float: left; margin-left: 2%;">
                <table style="border: 1px solid #000; font-weight: bold;">
                    <tbody>
                        <tr>
                            <td style="border-right: 1px solid #000; border-bottom: 1px solid #000;">Kilómetros</td>
                            <td style="border-right: 1px solid #000; border-bottom: 1px solid #000;"><?php echo $km; ?> €</td>
                        </tr>
                        <tr>
                            <td style="border-right: 1px solid #000; border-bottom: 1px solid #000;">Dietas</td>
                            <td style="border-bottom: 1px solid #000;"><?php echo $dietas; ?> €</td>
                        </tr>
                        <tr>
                            <td style="border-right: 1px solid #000;">Total</td>
                            <td><?php echo $total; ?> €</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div style="width: 100%; margin-top: 5%; float: left">
            <table style="border: 1px solid #000; font-weight: bold;">
                <tbody>
                    <tr>
                        <td style="border-bottom: 1px solid #000;">Detalle</td>
                    </tr>
                    <tr>
                        <td style="border-right: 1px solid #000; border-bottom: 1px solid #000;">Destino</td>
                        <td style="border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000;">Cantidad</td>
                        <td style="border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000;">Direccion</td>
                        <td style="border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000;">Fecha</td>
                    </tr>
                    <?php $contador = 0; ?>
                    <?php if(isset($vilanueva) && $vilanueva != 0  && $vilanueva != ''){ ?>
                    <tr>
                        <td style="border-right: 1px solid #000; border-bottom: 1px solid #000;">Villanueva de Córdoba</td>
                        <td style="border-right: 1px solid #000; border-bottom: 1px solid #000;"><?php echo $vilanueva; ?></td>
                        <td style="border-right: 1px solid #000; border-bottom: 1px solid #000;">Av. de Cardeña, 15 14440</td>
                        <td style="border-right: 1px solid #000; border-bottom: 1px solid #000;"><?php $fecha = explode("-", $fechas[$contador]); echo $fecha[2]."-".$fecha[1]."-".$fecha[0]; ?></td>
                    </tr>
                    <tr>
                        <td colspan="4" style="border-bottom: 1px solid #000;"><?php echo $tratas[$contador]; ?></td>
                    </tr>
                    <?php $contador++; ?>
                    <?php } ?>
                    <?php if(isset($solana) && $solana != 0  && $solana != ''){ ?>
                    <tr>
                        <td style="border-right: 1px solid #000; border-bottom: 1px solid #000;">La Solana</td>
                        <td style="border-right: 1px solid #000; border-bottom: 1px solid #000;"><?php echo $solana; ?></td>
                        <td style="border-right: 1px solid #000; border-bottom: 1px solid #000;">Calle Convento 22 13240</td>
                        <td style="border-right: 1px solid #000; border-bottom: 1px solid #000;"><?php $fecha = explode("-", $fechas[$contador]); echo $fecha[2]."-".$fecha[1]."-".$fecha[0]; ?></td>
                    </tr>
                    <tr>
                        <td colspan="4" style="border-bottom: 1px solid #000;"><?php echo $tratas[$contador]; ?></td>
                    </tr>
                    <?php $contador++; ?>
                    <?php } ?>
                    <?php if(isset($granada) && $granada != 0  && $granada != ''){ ?>
                    <tr>
                        <td style="border-right: 1px solid #000; border-bottom: 1px solid #000;">Granada</td>
                        <td style="border-right: 1px solid #000; border-bottom: 1px solid #000;"><?php echo $granada; ?></td>
                        <td style="border-right: 1px solid #000; border-bottom: 1px solid #000;">Calle Melchor Almagro, 4 18002</td>
                        <td style="border-right: 1px solid #000; border-bottom: 1px solid #000;"><?php $fecha = explode("-", $fechas[$contador]); echo $fecha[2]."-".$fecha[1]."-".$fecha[0]; ?></td>
                    </tr>
                    <tr>
                        <td colspan="4" style="border-bottom: 1px solid #000;"><?php echo $tratas[$contador]; ?></td>
                    </tr>
                    <?php $contador++; ?>
                    <?php } ?>
                    <?php if(isset($almeria) && $almeria != 0  && $almeria != ''){ ?>
                    <tr>
                        <td style="border-right: 1px solid #000; border-bottom: 1px solid #000;">Almería</td>
                        <td style="border-right: 1px solid #000; border-bottom: 1px solid #000;"><?php echo $almeria; ?></td>
                        <td style="border-right: 1px solid #000; border-bottom: 1px solid #000;">Calle San José Obrero, 84, 04009</td>
                        <td style="border-right: 1px solid #000; border-bottom: 1px solid #000;"><?php $fecha = explode("-", $fechas[$contador]); echo $fecha[2]."-".$fecha[1]."-".$fecha[0]; ?></td>
                    </tr>
                    <tr>
                        <td colspan="4" style="border-bottom: 1px solid #000;"><?php echo $tratas[$contador]; ?></td>
                    </tr>
                    <?php $contador++; ?>
                    <?php } ?>
                    <?php if(isset($ejido) && $ejido != 0  && $ejido != ''){ ?>
                    <tr>
                        <td style="border-right: 1px solid #000; border-bottom: 1px solid #000;">El Ejido</td>
                        <td style="border-right: 1px solid #000; border-bottom: 1px solid #000;"><?php echo $ejido; ?></td>
                        <td style="border-right: 1px solid #000; border-bottom: 1px solid #000;">Paseo alcalde García Acien, 04700</td>
                        <td style="border-right: 1px solid #000; border-bottom: 1px solid #000;"><?php $fecha = explode("-", $fechas[$contador]); echo $fecha[2]."-".$fecha[1]."-".$fecha[0]; ?></td>
                    </tr>
                    <tr>
                        <td colspan="4" style="border-bottom: 1px solid #000;"><?php echo $tratas[$contador]; ?></td>
                    </tr>
                    <?php $contador++; ?>
                    <?php } ?>                    
                </tbody>
            </table>
        </div>
        <br><br>
        <div style="width: 100%; float: left; margin-top: 5%;">
            <p style="font-weight: bold">
                <?php echo "A ".date("d/m/Y"); ?>
            </p>
            <p style="font-weight: bold">
                <span style="float: left">Firma Dpto. Administración</span>
                <span style="float: right">Firma Trabajador</span>
            </p>
        </div>
    </body>
</html>