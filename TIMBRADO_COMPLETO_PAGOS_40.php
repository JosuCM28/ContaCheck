<?php

//*** Servicio de Timbrado COMPLETO (Generaci�n de XML + Sellado + TFD) ***

	//Declaramos la librer�a de servicios SOAP
		require_once('lib/nusoap.php');

	// URL del Servicio Web que vamos a consumir PARA CFDI 4.0
		$url = "http://www.facturafiel.com/websrv/servicio_timbrado_40.php?wsdl";

	//Creamos el cliente para conectarse
		$client = new nusoap_client($url);

	//Mostramos si hay alg�n error local
		$err = $client->getError();
		if ($err) {
			echo '<p><b>Error: ' . $err . '</b></p>';
			exit(0);
		}

	//Asignamos los datos que se enviar�n

        //El R.F.C. propietario (emisor) del Comprobante (de 12 � de 13 caracteres)
        	$RFC_Emisor = "SUL010720JN8";

        //La API Key que se utilizar� para la conexi�n (16 caracteres)
        	$API_Key = "1234567890ABCDEF";

        //Llenamos el cuerpo del comprobante
        	$Datos = Datos_del_Comprobante();

	//Hacemos la llamada al servicio de timbrado y leemos la respuesta
	//La Cadena que vamos a enviar debe ser de esta forma: RFC_Emisor ~ API_Key ~ Datos
		$Cadena_Enviada = $RFC_Emisor."~".$API_Key."~".$Datos;

	//Hacemos la llamada al servicio
		$args = array('datos_enviados' => $Cadena_Enviada);
		$Respuesta = $client->call('servicio_timbrado', $args);
		$Respuesta = trim($Respuesta);

	//Leemos la respuesta

			if ( strtoupper(substr($Respuesta,0,5)) != "ERROR" ){
				//Mostramos el XML Timbrado

					//Por �ltimo, si deseamos guardar el XML Timbrado a un archivo, entonces es muy IMPORTANTE hacerlo con Codificaci�n UTF-8
					$Nombre_Archivo = "Mi_Archivo_Timbrado.xml";

					$fp = fopen($Nombre_Archivo, 'w');
					fwrite($fp, utf8_encode($Respuesta));
					fclose($fp);

					header("Content-type: text/xml");
					echo utf8_encode($Respuesta);
			}
			else{
				echo '<html>';
				echo '<head>';
				echo '<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">';
				echo '<title>ERROR</title>';
				echo '</head>';
				echo '<body>';
					echo $Respuesta;
				echo '</body>';
				echo '</html>';
			}



function Datos_del_Comprobante(){


    // +++ IMPORTANTE:

            // ++++++++ GUIA LLENADO 4.0 ++++++++
    //http://omawww.sat.gob.mx/tramitesyservicios/Paginas/documentos/Guiallenadopagos311221.pdf


    //NOTA IMPORTANTE: Todos los campos deben ser terminados con un retorno de carro + avance de l�nea
    //Debido al standard, NO se admiten caracteres PIPE  "|", tampoco debe utilizar el caracter "=" en el valor del campo, ya que se destina para delimitar los campos

		$Cadena = "";
	//INFORMACI�N GENERAL DEL COMPROBANTE:

    $Cadena = $Cadena."AmbienteDePruebas=SI"."\n";

    $Cadena = $Cadena."TipoDeComprobante=I";
        $Cadena = $Cadena."Pago"."\n";


    $Cadena = $Cadena."TipoDeFormato=";
        $Cadena = $Cadena."Pago"."\n";

    $Cadena = $Cadena."Serie=";
        //Campo OPCIONAL. Puede ser vac�o, no puede contener n�meros, s�lo letras. (Si se omite ser� vac�o).
        $Cadena = $Cadena."PA"."\n";

    $Cadena = $Cadena."Folio=";
        //Campo OBLIGATORIO. Debe ser Num�rico. (Si se omite se usar� en autom�tico el �ltimo folio usado + 1).
        $Cadena = $Cadena."31"."\n";


        // NUEVO CAMPO / ATRIBUTO PARA CFDI 4.0
    $Cadena = $Cadena."Exportacion=";
        //Campo OBLIGATORIO. Debe corresponder a la clave utilizada en el Cat�logo c_Exportaci�n que el SAT proporcion�   "01", "02", "03", "04" � los que el SAT publique en el Excel  "catCFDI_V_4.xls"
        $Cadena = $Cadena."01"."\n";



    $Cadena = $Cadena."LugarExpedicion=";
        //Campo OBLIGATORIO. (Si se omite regresar� ERROR). Debe corresponder a un C�DIGO POSTAL, N�MERICO
        $Cadena = $Cadena."06140"."\n";

    $Cadena = $Cadena."SubTotal=";
        //Campo OBLIGATORIO. (Si se omite regresar� ERROR).
        $Cadena = $Cadena."0"."\n";


    $Cadena = $Cadena."Total=";
        //Campo OBLIGATORIO. (Si se omite regresar� ERROR).
        $Cadena = $Cadena."0"."\n";

    $Cadena = $Cadena."Moneda=";
        //Campo OBLIGATORIO. (Si se omite se usar� en autom�tico "MXN").
        $Cadena = $Cadena."XXX"."\n";


    //$Cadena = $Cadena."TipoRelacion=";
        //Campo OPCIONAL.
        //Se debe registrar la clave "04" (Sustituci�n de los CFDI previos) de la relaci�n que existe entre �ste comprobante que se est� generando con el CFDI que se sustituye
        //$Cadena = $Cadena."04"."\n";

    //$Cadena = $Cadena."UUIDS_Relacionados=";
        //Campo OPCIONAL.
        //Se debe registrar el folio fiscal (UUID) de 1 CFDI con complemento para recepci�n de pagos relacionado que se sustituye con el presente comprobante.
        //Deben ir todos los Folios Fiscales (UUIDs) relacionados con este comprobante, separados por comas
        //$Cadena = $Cadena."D1D8A485-E2A5-48D9-811E-B15E75D5F54A"."\n";



    //LAS OBSERVACIONES EXTRA DEL DOCUMENTO NO FORMAN PARTE DEL XML, PERO PUEDE UTILIZARLAS PARA IMPRIMIR EN EL PDF MUCHA INFORMACI�N EXTRA,
        //Por ejemplo, datos no fiscales, pero que s� sirven para efectos operativos o comerciales, tales como: N�meros de Gu�a, N�meros de Placa, N�meros de Ordenes de Pedido, N�meros de Contrato, Nombres de los Bancos y cualquier otro dato que necesite, ya que son campos abiertos a su discreci�n.
        //La API permite enviar hasta 20 Observaciones Extra ("Observaciones_1" a "Observaciones_20"), y el limite para cada campo es de 1,000 caracteres
    $Cadena = $Cadena."Observaciones_1=";
        //Campo OPCIONAL. Observaciones EXTRA del Documento.
        $Cadena = $Cadena."N�mero de Gu�a Mensajer�a AF7512141282"."\n";

    $Cadena = $Cadena."Observaciones_2=";
        //Campo OPCIONAL. Observaciones EXTRA del Documento.
        $Cadena = $Cadena."Esta factura proviene del pedido #2569"."\n";

    $Cadena = $Cadena."Observaciones_3=";
        //Campo OPCIONAL. Observaciones EXTRA del Documento.
        $Cadena = $Cadena."No. de Contrato 346"."\n";



    $Cadena = $Cadena."RegimenEmisor=";
        //Campo OBLIGATORIO. Debe corresponder a la clave utilizada en el Cat�logo c_RegimenFiscal que el SAT proporcion�
        $Cadena = $Cadena."601"."\n";


    $Cadena = $Cadena."SucursalQueExpide=";
        //Campo OPCIONAL. Aqu� se utiliza el "Nombre de la Sucursal" deseada, la cual se di� de alta dentro del Cat�logo de Sucursales de la cuenta en FacturaFiel.Com. (Si se omite no se manejar� informaci�n para "ExpedidoEn").
        $Cadena = $Cadena."Matriz"."\n";


//DATOS DEL CLIENTE / PROVEEDOR QUE RECIBE EL COMPROBANTE:


    // NUEVO CAMPO / ATRIBUTO PARA CFDI 4.0
    $Cadena = $Cadena."RegimenFiscalReceptor=";
        //Campo OBLIGATORIO. Debe corresponder a la clave utilizada en el Cat�logo c_RegimenFiscal que el SAT proporcion�
        $Cadena = $Cadena."616"."\n";

    // NUEVO CAMPO / ATRIBUTO PARA CFDI 4.0
    $Cadena = $Cadena."DomicilioFiscalReceptor=";
        //Campo OBLIGATORIO. Debe corresponder al C�digo Postal del Domicilio del Receptor que tiene dado de alta el Receptor en el SAT
        $Cadena = $Cadena."11520"."\n";



    $Cadena = $Cadena."UsoCFDI=";
        //Campo OBLIGATORIO. Se debe registrar la clave "01" (Por definir)
        $Cadena = $Cadena."CP01"."\n";

    $Cadena = $Cadena."RFCReceptor=";
        //Campo OBLIGATORIO. 13 caracteres para persona f�sica, 12 caracteres para persona moral. (Si se omite regresar� ERROR).
        $Cadena = $Cadena."RACJ801124KN8"."\n";

    $Cadena = $Cadena."NombreReceptor=";
        //Campo OBLIGATORIO. (Si se omite regresar� ERROR).
        $Cadena = $Cadena."JORGE RAMIREZ CAMPOS"."\n";

    $Cadena = $Cadena."Pais=";
        //Campo OBLIGATORIO. (Si se omite se usar� en autom�tico "M�xico").
        $Cadena = $Cadena."M�xico"."\n";

    $Cadena = $Cadena."Estado=";
        //Campo OBLIGATORIO. (Si se omite regresar� ERROR).
        $Cadena = $Cadena."Oaxaca"."\n";

    $Cadena = $Cadena."Localidad=";
        //Campo OBLIGATORIO. Aqu� va la Localidad � Ciudad (Si se omite regresar� ERROR).
        $Cadena = $Cadena."Villa Sola de Vega"."\n";

    $Cadena = $Cadena."Municipio=";
        //Campo OBLIGATORIO. Aqu� va el Municipio � Delegaci�n (Si se omite regresar� ERROR).
        $Cadena = $Cadena."Villa Sola de Vega"."\n";

    $Cadena = $Cadena."Colonia=";
        //Campo OBLIGATORIO. (Si se omite regresar� ERROR).
        $Cadena = $Cadena."Campo de la Primavera"."\n";

    $Cadena = $Cadena."Calle=";
        //Campo OBLIGATORIO. (Si se omite regresar� ERROR).
        $Cadena = $Cadena."Avenida Siempre Viva"."\n";

    $Cadena = $Cadena."NoExterior=";
        //Campo OBLIGATORIO. (Si se omite regresar� ERROR).
        $Cadena = $Cadena."742"."\n";

    $Cadena = $Cadena."NoInterior=";
        //Campo OPCIONAL.
        $Cadena = $Cadena."2"."\n";

    $Cadena = $Cadena."CodigoPostal=";
        //Campo OBLIGATORIO. (Si se omite regresar� ERROR).
        $Cadena = $Cadena."71427"."\n";

    $Cadena = $Cadena."Referencia=";
        //Campo OPCIONAL.
        $Cadena = $Cadena."Junto a Rancher�a El Olvido"."\n";

    $Cadena = $Cadena."Telefono=";
        //Campo OPCIONAL.
        $Cadena = $Cadena."5553225588"."\n";

    $Cadena = $Cadena."Email=";
        //Campo OPCIONAL.
        $Cadena = $Cadena."clientesa@cliente.com"."\n";



// NUEVO CAMPO / ATRIBUTO PARA CFDI 4.0
    $Cadena = $Cadena."TotalRetencionesIEPS=";
        $Cadena = $Cadena."0"."\n";

// NUEVO CAMPO / ATRIBUTO PARA CFDI 4.0
    $Cadena = $Cadena."TotalRetencionesISR=";
        $Cadena = $Cadena."28"."\n";

// NUEVO CAMPO / ATRIBUTO PARA CFDI 4.0
    $Cadena = $Cadena."TotalRetencionesIVA=";
        $Cadena = $Cadena."12.80"."\n";

// NUEVO CAMPO / ATRIBUTO PARA CFDI 4.0
    $Cadena = $Cadena."TotalTrasladosBaseIVA0=";
        $Cadena = $Cadena."0"."\n";

// NUEVO CAMPO / ATRIBUTO PARA CFDI 4.0
    $Cadena = $Cadena."TotalTrasladosBaseIVA8=";
        $Cadena = $Cadena."0"."\n";

// NUEVO CAMPO / ATRIBUTO PARA CFDI 4.0
    $Cadena = $Cadena."TotalTrasladosBaseIVA16=";
        $Cadena = $Cadena."80"."\n";

// NUEVO CAMPO / ATRIBUTO PARA CFDI 4.0
    $Cadena = $Cadena."TotalTrasladosBaseIVAExento=";
        $Cadena = $Cadena."0"."\n";

// NUEVO CAMPO / ATRIBUTO PARA CFDI 4.0
    $Cadena = $Cadena."TotalTrasladosImpuestoIVA0=";
        $Cadena = $Cadena."0"."\n";

// NUEVO CAMPO / ATRIBUTO PARA CFDI 4.0
    $Cadena = $Cadena."TotalTrasladosImpuestoIVA16=";
        $Cadena = $Cadena."12.80"."\n";

// NUEVO CAMPO / ATRIBUTO PARA CFDI 4.0
    $Cadena = $Cadena."TotalTrasladosImpuestoIVA8=";
        $Cadena = $Cadena."0"."\n";

// NUEVO CAMPO / ATRIBUTO PARA CFDI 4.0
    $Cadena = $Cadena."MontoTotalPagos=";
        $Cadena = $Cadena."280"."\n";




//PARTIDAS / CONCEPTOS DEL COMPROBANTE:

    $Cadena = $Cadena."NumeroDePartidas=";
        $Cadena = $Cadena."1"."\n";

    $Cadena = $Cadena."Concepto_1_Cantidad=";
        $Cadena = $Cadena."1"."\n";

    $Cadena = $Cadena."Concepto_1_Unidad=";
        $Cadena = $Cadena."Servicio"."\n";

        //Campo OBLIGATORIO. Debe corresponder a la clave utilizada en el Cat�logo c_ClaveUnidad que el SAT proporcion�
    $Cadena = $Cadena."Concepto_1_UnidadSAT=";
        $Cadena = $Cadena."ACT"."\n";

        //Campo OBLIGATORIO. Descripci�n/Nombre que corresponde a la clave "UnidadSAT" del campo anterior
    $Cadena = $Cadena."Concepto_1_UnidadSATD=";
        $Cadena = $Cadena."Actividad"."\n";

        //Campo OBLIGATORIO. Debe corresponder a la clave utilizada en el Cat�logo c_ClaveProdServ que el SAT proporcion�
    $Cadena = $Cadena."Concepto_1_ClaveSAT=";
        $Cadena = $Cadena."84111506"."\n";

        //Campo OBLIGATORIO. Descripci�n/Nombre que corresponde a la clave "ClaveSAT" del campo anterior
    $Cadena = $Cadena."Concepto_1_ClaveSATD=";
        $Cadena = $Cadena."Servicios de facturaci�n"."\n";

    $Cadena = $Cadena."Concepto_1_Descripcion=";
        $Cadena = $Cadena."Pago"."\n";

    $Cadena = $Cadena."Concepto_1_ValorUnitario=";
        $Cadena = $Cadena."0"."\n";

    $Cadena = $Cadena."Concepto_1_Importe=";
        $Cadena = $Cadena."0"."\n";


    // NUEVO CAMPO / ATRIBUTO PARA CFDI 4.0
    //Campo OBLIGATORIO. Debe corresponder a la clave utilizada en el Cat�logo c_ObjetoImp que el SAT proporcion�  "01", "02" o "03"
    $Cadena = $Cadena."Concepto_1_ObjetoImp=";
        $Cadena = $Cadena."01"."\n";



    $Cadena = $Cadena."Concepto_1_Num_Impuestos_Tras=0"."\n";
    $Cadena = $Cadena."Concepto_1_Num_Impuestos_Ret=0"."\n";



    //COMPLEMENTO DE PAGOS

        $Cadena = $Cadena."NumeroDePagos=";
            //Valor del Impuesto Retenido.
            $Cadena = $Cadena."2"."\n";

                //DATOS DEL PAGO # 1
                    $Cadena = $Cadena."Pago_1_FechaPago=";
                        //Valor del Impuesto Retenido.
                        $Cadena = $Cadena."2022-05-20T12:52:03"."\n";

                    $Cadena = $Cadena."Pago_1_FormaDePagoP=";
                        //Valor del Impuesto Retenido.
                        $Cadena = $Cadena."02"."\n";

                    $Cadena = $Cadena."Pago_1_MonedaP=";
                        //Valor del Impuesto Retenido.
                        $Cadena = $Cadena."MXN"."\n";

                    $Cadena = $Cadena."Pago_1_TipoCambioP=";
                        //Valor del Impuesto Retenido.
                        $Cadena = $Cadena."1"."\n";

                    $Cadena = $Cadena."Pago_1_Monto=";
                        //Valor del Impuesto Retenido.
                        $Cadena = $Cadena."80"."\n";

		/*
                     $Cadena = $Cadena."Pago_1_NumOperacion=";
                         //Valor del Impuesto Retenido.
                         $Cadena = $Cadena."0006529"."\n";

                     $Cadena = $Cadena."Pago_1_RfcEmisorCtaOrd=";
                         //Valor del Impuesto Retenido.
                         $Cadena = $Cadena."XEXX01010100"."\n";

                     $Cadena = $Cadena."Pago_1_NomBancoOrdExt=";
                         //Valor del Impuesto Retenido.
                         $Cadena = $Cadena."BANK OF TOKYO"."\n";

                     $Cadena = $Cadena."Pago_1_CtaOrdenante=";
                         //Valor del Impuesto Retenido.
                         $Cadena = $Cadena."1234567890"."\n";

                     $Cadena = $Cadena."Pago_1_RfcEmisorCtaBen=";
                         //Valor del Impuesto Retenido.
                         $Cadena = $Cadena."MES120224LK4"."\n";

                     $Cadena = $Cadena."Pago_1_CtaBeneficiario=";
                         //Valor del Impuesto Retenido.
                         $Cadena = $Cadena."5621234567"."\n";

                     $Cadena = $Cadena."Pago_1_TipoCadPago=";
                         //Valor del Impuesto Retenido.
                         $Cadena = $Cadena."01"."\n";

                     $Cadena = $Cadena."Pago_1_CertPago=";
                         //Valor del Impuesto Retenido.
                         $Cadena = $Cadena."02"."\n";

                     $Cadena = $Cadena."Pago_1_CadPago=";
                         //Valor del Impuesto Retenido.
                         $Cadena = $Cadena."CadPago=&#124;&#124;Pago&#124;Banco&#124;300.00&#124;&#124;"."\n";

                     $Cadena = $Cadena."Pago_1_SelloPago=";
                         //Valor del Impuesto Retenido.
                         $Cadena = $Cadena."02"."\n";
                */



        // NUEVO CAMPO / ATRIBUTO PARA CFDI 4.0
            //N�mero de Impuestos TRASLADADOS que tiene este PAGO # 1
                        $Cadena = $Cadena."Pago_1_Num_ImpuestosP_TrasladosP=";
                            //Valor del Campo
                            $Cadena = $Cadena."2"."\n";

        // NUEVO CAMPO / ATRIBUTO PARA CFDI 4.0
            //N�mero de Impuestos RETENIDOS que tiene este PAGO # 1
                        $Cadena = $Cadena."Pago_1_Num_ImpuestosP_RetencionesP=";
                            //Valor del Campo
                            $Cadena = $Cadena."2"."\n";

                // NUEVOS CAMPOS / ATRIBUTOS PARA CFDI 4.0
                            //DATOS DEL PAGO # 1 IMPUESTO TRASLADADO # 1
                                                    $Cadena = $Cadena."Pago_1_ImpuestosP_TrasladosP1_Impuesto=";
                                                        $Cadena = $Cadena."002"."\n";
                                                    $Cadena = $Cadena."Pago_1_ImpuestosP_TrasladosP1_Base=";
                                                        $Cadena = $Cadena."80"."\n";
                                                    $Cadena = $Cadena."Pago_1_ImpuestosP_TrasladosP1_TipoFactor=";
                                                        $Cadena = $Cadena."Tasa"."\n";
                                                    $Cadena = $Cadena."Pago_1_ImpuestosP_TrasladosP1_Importe=";
                                                        $Cadena = $Cadena."12.80"."\n";
                                                    $Cadena = $Cadena."Pago_1_ImpuestosP_TrasladosP1_TasaOCuota=";
                                                        $Cadena = $Cadena."0.160000"."\n";

                            //DATOS DEL PAGO # 1 IMPUESTO TRASLADADO # 2
                                                    $Cadena = $Cadena."Pago_1_ImpuestosP_TrasladosP2_Impuesto=";
                                                        $Cadena = $Cadena."003"."\n";
                                                    $Cadena = $Cadena."Pago_1_ImpuestosP_TrasladosP2_Base=";
                                                        $Cadena = $Cadena."80"."\n";
                                                    $Cadena = $Cadena."Pago_1_ImpuestosP_TrasladosP2_TipoFactor=";
                                                        $Cadena = $Cadena."Tasa"."\n";
                                                    $Cadena = $Cadena."Pago_1_ImpuestosP_TrasladosP2_Importe=";
                                                        $Cadena = $Cadena."2.40"."\n";
                                                    $Cadena = $Cadena."Pago_1_ImpuestosP_TrasladosP2_TasaOCuota=";
                                                        $Cadena = $Cadena."0.030000"."\n";

                // NUEVOS CAMPOS / ATRIBUTOS PARA CFDI 4.0
                            //DATOS DEL PAGO # 1 IMPUESTO RETENIDO # 1
                                                    $Cadena = $Cadena."Pago_1_ImpuestosP_RetencionesP1_Impuesto=";
                                                        $Cadena = $Cadena."001"."\n";
                                                    $Cadena = $Cadena."Pago_1_ImpuestosP_RetencionesP1_Importe=";
                                                        $Cadena = $Cadena."28"."\n";

                            //DATOS DEL PAGO # 1 IMPUESTO RETENIDO # 2
                                                    $Cadena = $Cadena."Pago_1_ImpuestosP_RetencionesP2_Impuesto=";
                                                        $Cadena = $Cadena."002"."\n";
                                                    $Cadena = $Cadena."Pago_1_ImpuestosP_RetencionesP2_Importe=";
                                                        $Cadena = $Cadena."12.80"."\n";




                            $Cadena = $Cadena."Pago_1_Num_PagosDoctoRelacionados=";
                                //Valor del Impuesto Retenido.
                                $Cadena = $Cadena."2"."\n";

                                //DATOS DEL PAGO # 1 DOCUMENTO RELACIONADO # 1

                                    $Cadena = $Cadena."Pago_1_Docto_1_IdDocumento=";
                                        //Valor del Impuesto Retenido.
                                        $Cadena = $Cadena."D1D8A485-E2A5-48D9-811E-B15E75D5F54A"."\n";

                                    $Cadena = $Cadena."Pago_1_Docto_1_Serie=";
                                        //Valor del Impuesto Retenido.
                                        $Cadena = $Cadena."F"."\n";

                                    $Cadena = $Cadena."Pago_1_Docto_1_Folio=";
                                        //Valor del Impuesto Retenido.
                                        $Cadena = $Cadena."5621"."\n";

                                    $Cadena = $Cadena."Pago_1_Docto_1_MonedaDR=";
                                        //Valor del Impuesto Retenido.
                                        $Cadena = $Cadena."MXN"."\n";

                                    $Cadena = $Cadena."Pago_1_Docto_1_TipoCambioDR=";
                                        //Valor del Impuesto Retenido.
                                        $Cadena = $Cadena."1"."\n";

                                    $Cadena = $Cadena."Pago_1_Docto_1_MetodoDePagoDR=";
                                        //Valor del Impuesto Retenido.
                                        $Cadena = $Cadena."PPD"."\n";

                                    $Cadena = $Cadena."Pago_1_Docto_1_NumParcialidad=";
                                        //Valor del Impuesto Retenido.
                                        $Cadena = $Cadena."1"."\n";

                                    $Cadena = $Cadena."Pago_1_Docto_1_ImpSaldoAnt=";
                                        //Valor del Impuesto Retenido.
                                        $Cadena = $Cadena."100"."\n";

                                    $Cadena = $Cadena."Pago_1_Docto_1_ImpPagado=";
                                        //Valor del Impuesto Retenido.
                                        $Cadena = $Cadena."40"."\n";

                                    $Cadena = $Cadena."Pago_1_Docto_1_ImpSaldoInsoluto=";
                                        //Valor del Impuesto Retenido.
                                        $Cadena = $Cadena."60"."\n";




									// NUEVO CAMPO / ATRIBUTO PARA CFDI 4.0
									$Cadena = $Cadena."Pago_1_Docto_1_EquivalenciaDR=";
										//Valor del Campo
										$Cadena = $Cadena."1"."\n";

									// NUEVO CAMPO / ATRIBUTO PARA CFDI 4.0
									$Cadena = $Cadena."Pago_1_Docto_1_ObjetoImpDR=";
										//Valor del Campo
										$Cadena = $Cadena."02"."\n";

									// NUEVO CAMPO / ATRIBUTO PARA CFDI 4.0
									//N�MERO de Impuestos TRASLADADOS de este Documento Relacionado 1
									$Cadena = $Cadena."Pago_1_Docto_1_Num_ImpuestosDR_TrasladosDR=";
										//Valor del Campo
										$Cadena = $Cadena."2"."\n";

									// NUEVO CAMPO / ATRIBUTO PARA CFDI 4.0
									$Cadena = $Cadena."Pago_1_Docto_1_Num_ImpuestosDR_RetencionesDR=";
										//Valor del Campo
										$Cadena = $Cadena."2"."\n";

									// NUEVOS CAMPOS / ATRIBUTOS PARA CFDI 4.0
												//DATOS DEL PAGO # 1  -  DOCUMENTO RELACIONADO # 1  -  IMPUESTO TRASLADADO # 1
																		$Cadena = $Cadena."Pago_1_Docto_1_ImpuestosDR_TrasladosDR1_Impuesto=";
																			$Cadena = $Cadena."002"."\n";
																		$Cadena = $Cadena."Pago_1_Docto_1_ImpuestosDR_TrasladosDR1_Base=";
																			$Cadena = $Cadena."40"."\n";
																		$Cadena = $Cadena."Pago_1_Docto_1_ImpuestosDR_TrasladosDR1_TipoFactor=";
																			$Cadena = $Cadena."Tasa"."\n";
																		$Cadena = $Cadena."Pago_1_Docto_1_ImpuestosDR_TrasladosDR1_Importe=";
																			$Cadena = $Cadena."6.400000"."\n";
																		$Cadena = $Cadena."Pago_1_Docto_1_ImpuestosDR_TrasladosDR1_TasaOCuota=";
																			$Cadena = $Cadena."0.160000"."\n";

												//DATOS DEL PAGO # 1  -  DOCUMENTO RELACIONADO # 1  -  IMPUESTO TRASLADADO # 2
																		$Cadena = $Cadena."Pago_1_Docto_1_ImpuestosDR_TrasladosDR2_Impuesto=";
																			$Cadena = $Cadena."003"."\n";
																		$Cadena = $Cadena."Pago_1_Docto_1_ImpuestosDR_TrasladosDR2_Base=";
																			$Cadena = $Cadena."40"."\n";
																		$Cadena = $Cadena."Pago_1_Docto_1_ImpuestosDR_TrasladosDR2_TipoFactor=";
																			$Cadena = $Cadena."Tasa"."\n";
																		$Cadena = $Cadena."Pago_1_Docto_1_ImpuestosDR_TrasladosDR2_Importe=";
																			$Cadena = $Cadena."1.200000"."\n";
																		$Cadena = $Cadena."Pago_1_Docto_1_ImpuestosDR_TrasladosDR2_TasaOCuota=";
																			$Cadena = $Cadena."0.030000"."\n";

									// NUEVOS CAMPOS / ATRIBUTOS PARA CFDI 4.0
												//DATOS DEL PAGO # 1  -  DOCUMENTO RELACIONADO # 1  -  IMPUESTO RETENIDO # 1
																		$Cadena = $Cadena."Pago_1_Docto_1_ImpuestosDR_RetencionesDR1_Impuesto=";
																			$Cadena = $Cadena."001"."\n";
																		$Cadena = $Cadena."Pago_1_Docto_1_ImpuestosDR_RetencionesDR1_Base=";
																			$Cadena = $Cadena."40"."\n";
																		$Cadena = $Cadena."Pago_1_Docto_1_ImpuestosDR_RetencionesDR1_TipoFactor=";
																			$Cadena = $Cadena."Tasa"."\n";
																		$Cadena = $Cadena."Pago_1_Docto_1_ImpuestosDR_RetencionesDR1_Importe=";
																			$Cadena = $Cadena."14"."\n";
																		$Cadena = $Cadena."Pago_1_Docto_1_ImpuestosDR_RetencionesDR1_TasaOCuota=";
																			$Cadena = $Cadena."0.350000"."\n";

												//DATOS DEL PAGO # 1  -  DOCUMENTO RELACIONADO # 1  -  IMPUESTO RETENIDO # 2
																		$Cadena = $Cadena."Pago_1_Docto_1_ImpuestosDR_RetencionesDR2_Impuesto=";
																			$Cadena = $Cadena."002"."\n";
																		$Cadena = $Cadena."Pago_1_Docto_1_ImpuestosDR_RetencionesDR2_Base=";
																			$Cadena = $Cadena."40"."\n";
																		$Cadena = $Cadena."Pago_1_Docto_1_ImpuestosDR_RetencionesDR2_TipoFactor=";
																			$Cadena = $Cadena."Tasa"."\n";
																		$Cadena = $Cadena."Pago_1_Docto_1_ImpuestosDR_RetencionesDR2_Importe=";
																			$Cadena = $Cadena."6.400000"."\n";
																		$Cadena = $Cadena."Pago_1_Docto_1_ImpuestosDR_RetencionesDR2_TasaOCuota=";
																			$Cadena = $Cadena."0.160000"."\n";





                                //DATOS DEL PAGO # 1 DOCUMENTO RELACIONADO # 2

                                    $Cadena = $Cadena."Pago_1_Docto_2_IdDocumento=";
                                        //Valor del Impuesto Retenido.
                                        $Cadena = $Cadena."7C6555A9-4531-416F-8C03-34D87A51E88B"."\n";

                                    $Cadena = $Cadena."Pago_1_Docto_2_Serie=";
                                        //Valor del Impuesto Retenido.
                                        $Cadena = $Cadena."F"."\n";

                                    $Cadena = $Cadena."Pago_1_Docto_2_Folio=";
                                        //Valor del Impuesto Retenido.
                                        $Cadena = $Cadena."5824"."\n";

                                    $Cadena = $Cadena."Pago_1_Docto_2_MonedaDR=";
                                        //Valor del Impuesto Retenido.
                                        $Cadena = $Cadena."MXN"."\n";

                                    $Cadena = $Cadena."Pago_1_Docto_2_TipoCambioDR=";
                                        //Valor del Impuesto Retenido.
                                        $Cadena = $Cadena."1"."\n";

                                    $Cadena = $Cadena."Pago_1_Docto_2_MetodoDePagoDR=";
                                        //Valor del Impuesto Retenido.
                                        $Cadena = $Cadena."PPD"."\n";

                                    $Cadena = $Cadena."Pago_1_Docto_2_NumParcialidad=";
                                        //Valor del Impuesto Retenido.
                                        $Cadena = $Cadena."4"."\n";

                                    $Cadena = $Cadena."Pago_1_Docto_2_ImpSaldoAnt=";
                                        //Valor del Impuesto Retenido.
                                        $Cadena = $Cadena."100"."\n";

                                    $Cadena = $Cadena."Pago_1_Docto_2_ImpPagado=";
                                        //Valor del Impuesto Retenido.
                                        $Cadena = $Cadena."40"."\n";

                                    $Cadena = $Cadena."Pago_1_Docto_2_ImpSaldoInsoluto=";
                                        //Valor del Impuesto Retenido.
                                        $Cadena = $Cadena."60"."\n";



                                    // NUEVO CAMPO / ATRIBUTO PARA CFDI 4.0
                                    $Cadena = $Cadena."Pago_1_Docto_2_EquivalenciaDR=";
                                        //Valor del Campo
                                        $Cadena = $Cadena."1"."\n";

                                    // NUEVO CAMPO / ATRIBUTO PARA CFDI 4.0
                                    $Cadena = $Cadena."Pago_1_Docto_2_ObjetoImpDR=";
                                        //Valor del Campo
                                        $Cadena = $Cadena."02"."\n";

                                    // NUEVO CAMPO / ATRIBUTO PARA CFDI 4.0
                                    $Cadena = $Cadena."Pago_1_Docto_2_Num_ImpuestosDR_TrasladosDR=";
                                        //Valor del Campo
                                        $Cadena = $Cadena."2"."\n";

                                    // NUEVO CAMPO / ATRIBUTO PARA CFDI 4.0
                                    $Cadena = $Cadena."Pago_1_Docto_2_Num_ImpuestosDR_RetencionesDR=";
                                        //Valor del Campo
                                        $Cadena = $Cadena."2"."\n";


									// NUEVOS CAMPOS / ATRIBUTOS PARA CFDI 4.0
												//DATOS DEL PAGO # 1  -  DOCUMENTO RELACIONADO # 2  -  IMPUESTO TRASLADADO # 1
																		$Cadena = $Cadena."Pago_1_Docto_2_ImpuestosDR_TrasladosDR1_Impuesto=";
																			$Cadena = $Cadena."002"."\n";
																		$Cadena = $Cadena."Pago_1_Docto_2_ImpuestosDR_TrasladosDR1_Base=";
																			$Cadena = $Cadena."40"."\n";
																		$Cadena = $Cadena."Pago_1_Docto_2_ImpuestosDR_TrasladosDR1_TipoFactor=";
																			$Cadena = $Cadena."Tasa"."\n";
																		$Cadena = $Cadena."Pago_1_Docto_2_ImpuestosDR_TrasladosDR1_Importe=";
																			$Cadena = $Cadena."6.400000"."\n";
																		$Cadena = $Cadena."Pago_1_Docto_2_ImpuestosDR_TrasladosDR1_TasaOCuota=";
																			$Cadena = $Cadena."0.160000"."\n";

												//DATOS DEL PAGO # 1  -  DOCUMENTO RELACIONADO # 2  -  IMPUESTO TRASLADADO # 2
																		$Cadena = $Cadena."Pago_1_Docto_2_ImpuestosDR_TrasladosDR2_Impuesto=";
																			$Cadena = $Cadena."003"."\n";
																		$Cadena = $Cadena."Pago_1_Docto_2_ImpuestosDR_TrasladosDR2_Base=";
																			$Cadena = $Cadena."40"."\n";
																		$Cadena = $Cadena."Pago_1_Docto_2_ImpuestosDR_TrasladosDR2_TipoFactor=";
																			$Cadena = $Cadena."Tasa"."\n";
																		$Cadena = $Cadena."Pago_1_Docto_2_ImpuestosDR_TrasladosDR2_Importe=";
																			$Cadena = $Cadena."1.200000"."\n";
																		$Cadena = $Cadena."Pago_1_Docto_2_ImpuestosDR_TrasladosDR2_TasaOCuota=";
																			$Cadena = $Cadena."0.030000"."\n";

									// NUEVOS CAMPOS / ATRIBUTOS PARA CFDI 4.0
												//DATOS DEL PAGO # 1  -  DOCUMENTO RELACIONADO # 2  -  IMPUESTO RETENIDO # 1
																		$Cadena = $Cadena."Pago_1_Docto_2_ImpuestosDR_RetencionesDR1_Impuesto=";
																			$Cadena = $Cadena."001"."\n";
																		$Cadena = $Cadena."Pago_1_Docto_2_ImpuestosDR_RetencionesDR1_Base=";
																			$Cadena = $Cadena."40"."\n";
																		$Cadena = $Cadena."Pago_1_Docto_2_ImpuestosDR_RetencionesDR1_TipoFactor=";
																			$Cadena = $Cadena."Tasa"."\n";
																		$Cadena = $Cadena."Pago_1_Docto_2_ImpuestosDR_RetencionesDR1_Importe=";
																			$Cadena = $Cadena."14"."\n";
																		$Cadena = $Cadena."Pago_1_Docto_2_ImpuestosDR_RetencionesDR1_TasaOCuota=";
																			$Cadena = $Cadena."0.350000"."\n";

												//DATOS DEL PAGO # 1  -  DOCUMENTO RELACIONADO # 2  -  IMPUESTO RETENIDO # 2
																		$Cadena = $Cadena."Pago_1_Docto_2_ImpuestosDR_RetencionesDR2_Impuesto=";
																			$Cadena = $Cadena."002"."\n";
																		$Cadena = $Cadena."Pago_1_Docto_2_ImpuestosDR_RetencionesDR2_Base=";
																			$Cadena = $Cadena."40"."\n";
																		$Cadena = $Cadena."Pago_1_Docto_2_ImpuestosDR_RetencionesDR2_TipoFactor=";
																			$Cadena = $Cadena."Tasa"."\n";
																		$Cadena = $Cadena."Pago_1_Docto_2_ImpuestosDR_RetencionesDR2_Importe=";
																			$Cadena = $Cadena."6.400000"."\n";
																		$Cadena = $Cadena."Pago_1_Docto_2_ImpuestosDR_RetencionesDR2_TasaOCuota=";
																			$Cadena = $Cadena."0.160000"."\n";




                //DATOS DEL PAGO # 2
                    $Cadena = $Cadena."Pago_2_FechaPago=";
                        //Valor del Impuesto Retenido.
                        $Cadena = $Cadena."2022-05-20T10:24:03"."\n";

                    $Cadena = $Cadena."Pago_2_FormaDePagoP=";
                        //Valor del Impuesto Retenido.
                        $Cadena = $Cadena."02"."\n";

                    $Cadena = $Cadena."Pago_2_MonedaP=";
                        //Valor del Impuesto Retenido.
                        $Cadena = $Cadena."MXN"."\n";

                    $Cadena = $Cadena."Pago_2_TipoCambioP=";
                        //Valor del Impuesto Retenido.
                        $Cadena = $Cadena."1"."\n";

                    $Cadena = $Cadena."Pago_2_Monto=";
                        //Valor del Impuesto Retenido.
                        $Cadena = $Cadena."200.00"."\n";

                /*
                     $Cadena = $Cadena."Pago_2_NumOperacion=";
                         //Valor del Impuesto Retenido.
                         $Cadena = $Cadena."62585"."\n";

                     $Cadena = $Cadena."Pago_2_RfcEmisorCtaOrd=";
                         //Valor del Impuesto Retenido.
                         $Cadena = $Cadena."XEXX01010100"."\n";

                     $Cadena = $Cadena."Pago_2_NomBancoOrdExt=";
                         //Valor del Impuesto Retenido.
                         $Cadena = $Cadena."BANK OF AMERICA"."\n";

                     $Cadena = $Cadena."Pago_2_CtaOrdenante=";
                         //Valor del Impuesto Retenido.
                         $Cadena = $Cadena."1234567890"."\n";

                     $Cadena = $Cadena."Pago_2_RfcEmisorCtaBen=";
                         //Valor del Impuesto Retenido.
                         $Cadena = $Cadena."MES120224LK4"."\n";

                     $Cadena = $Cadena."Pago_2_CtaBeneficiario=";
                         //Valor del Impuesto Retenido.
                         $Cadena = $Cadena."5621234567"."\n";

                     $Cadena = $Cadena."Pago_2_TipoCadPago=";
                         //Valor del Impuesto Retenido.
                         $Cadena = $Cadena."01"."\n";

                     $Cadena = $Cadena."Pago_2_CertPago=";
                         //Valor del Impuesto Retenido.
                         $Cadena = $Cadena."02"."\n";

                     $Cadena = $Cadena."Pago_2_CadPago=";
                         //Valor del Impuesto Retenido.
                         $Cadena = $Cadena."CadPago=&#124;&#124;Pago&#124;Banco&#124;300.00&#124;&#124;"."\n";

                     $Cadena = $Cadena."Pago_2_SelloPago=";
                         //Valor del Impuesto Retenido.
                         $Cadena = $Cadena."02"."\n";
                */



        // NUEVO CAMPO / ATRIBUTO PARA CFDI 4.0
            //N�mero de Impuestos Trasladados que tiene este Pago # 2
                        $Cadena = $Cadena."Pago_2_Num_ImpuestosP_TrasladosP=";
                            //Valor del Campo
                            $Cadena = $Cadena."0"."\n";

        // NUEVO CAMPO / ATRIBUTO PARA CFDI 4.0
            //N�mero de Impuestos Trasladados que tiene este Pago # 2
                        $Cadena = $Cadena."Pago_2_Num_ImpuestosP_TrasladosP=";
                            //Valor del Campo
                            $Cadena = $Cadena."0"."\n";




					$Cadena = $Cadena."Pago_2_Num_PagosDoctoRelacionados=";
						//Valor del Impuesto Retenido.
						$Cadena = $Cadena."1"."\n";


					//DATOS DEL PAGO # 2 DOCUMENTO RELACIONADO # 1

						$Cadena = $Cadena."Pago_2_Docto_1_IdDocumento=";
							//Valor del Campo
							$Cadena = $Cadena."D1D8A485-E2A5-48D9-811E-B15E75D5F54A"."\n";

						$Cadena = $Cadena."Pago_2_Docto_1_Serie=";
							//Valor del Campo
							$Cadena = $Cadena."F"."\n";

						$Cadena = $Cadena."Pago_2_Docto_1_Folio=";
							//Valor del Campo
							$Cadena = $Cadena."5621"."\n";

						$Cadena = $Cadena."Pago_2_Docto_1_MonedaDR=";
							//Valor del Campo
							$Cadena = $Cadena."MXN"."\n";

						$Cadena = $Cadena."Pago_2_Docto_1_TipoCambioDR=";
							//Valor del Campo
							$Cadena = $Cadena."1"."\n";

						$Cadena = $Cadena."Pago_2_Docto_1_MetodoDePagoDR=";
							//Valor del Campo
							$Cadena = $Cadena."PPD"."\n";

						$Cadena = $Cadena."Pago_2_Docto_1_NumParcialidad=";
							//Valor del Campo
							$Cadena = $Cadena."1"."\n";

						$Cadena = $Cadena."Pago_2_Docto_1_ImpSaldoAnt=";
							//Valor del Campo
							$Cadena = $Cadena."200"."\n";

						$Cadena = $Cadena."Pago_2_Docto_1_ImpPagado=";
							//Valor del Campo
							$Cadena = $Cadena."200"."\n";

						$Cadena = $Cadena."Pago_2_Docto_1_ImpSaldoInsoluto=";
							//Valor del Campo
							$Cadena = $Cadena."0"."\n";

						// NUEVO CAMPO / ATRIBUTO PARA CFDI 4.0
						$Cadena = $Cadena."Pago_2_Docto_1_EquivalenciaDR=";
							//Valor del Campo
							$Cadena = $Cadena."1"."\n";

						// NUEVO CAMPO / ATRIBUTO PARA CFDI 4.0
						$Cadena = $Cadena."Pago_2_Docto_1_ObjetoImpDR=";
							//Valor del Campo
							$Cadena = $Cadena."01"."\n";

						// NUEVO CAMPO / ATRIBUTO PARA CFDI 4.0
						//N�MERO de Impuestos TRASLADADOS de este Documento Relacionado 1
						$Cadena = $Cadena."Pago_2_Docto_1_Num_ImpuestosDR_TrasladosDR=";
							//Valor del Campo
							$Cadena = $Cadena."0"."\n";

						// NUEVO CAMPO / ATRIBUTO PARA CFDI 4.0
						$Cadena = $Cadena."Pago_2_Docto_1_Num_ImpuestosDR_RetencionesDR=";
							//Valor del Campo
							$Cadena = $Cadena."0"."\n";


    //FIN DE COMPLEMENTO DE PAGOS




	return $Cadena;

}


?>